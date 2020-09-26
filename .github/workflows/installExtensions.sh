#! /bin/bash

MW_BRANCH=$1
EXTENSION_NAME=$2

cd mediawiki

cat <<EOT >> composer.local.json
{
	"extra": {
		"merge-plugin": {
			"merge-dev": true,
			"include": [
				"extensions/$EXTENSION_NAME/composer.json"
			]
		}
	}
}
EOT

composer require --no-update wikibase/wikibase:dev-$MW_BRANCH

echo '$wgEnableWikibaseRepo = true;' >> LocalSettings.php
echo '$wgEnableWikibaseClient = false;' >> LocalSettings.php
echo 'require_once __DIR__ . "/extensions/Wikibase/repo/Wikibase.php";' >> LocalSettings.php
echo 'require_once __DIR__ . "/extensions/Wikibase/repo/ExampleSettings.php";' >> LocalSettings.php

echo 'wfLoadExtension( "'$EXTENSION_NAME'" );' >> LocalSettings.php
