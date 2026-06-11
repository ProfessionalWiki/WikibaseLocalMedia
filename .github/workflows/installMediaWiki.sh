#! /bin/bash

MW_BRANCH=$1
EXTENSION_NAME=$2

wget "https://github.com/wikimedia/mediawiki/archive/refs/heads/$MW_BRANCH.tar.gz" -nv

tar -zxf $MW_BRANCH.tar.gz
mv mediawiki-$MW_BRANCH mediawiki

cd mediawiki

# Composer 2.10+ refuses to install dependency versions flagged by security advisories.
# Older MediaWiki branches pin such versions; allow them here as this is a throwaway CI install.
php -r '$f = "composer.json"; $c = json_decode( file_get_contents( $f ), true ); $c["config"]["policy"]["advisories"]["block"] = false; file_put_contents( $f, json_encode( $c, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "\n" );'

# Wikibase's REL1_45 and later branches pull in beta/dev stability dependencies.
# prefer-stable keeps everything that has a stable release on its stable release.
composer config minimum-stability dev
composer config prefer-stable true

composer install
php maintenance/install.php --dbtype sqlite --dbuser root --dbname mw --dbpath $(pwd) --pass AdminPassword WikiName AdminUser

echo 'error_reporting(E_ALL| E_STRICT);' >> LocalSettings.php
echo 'ini_set("display_errors", 1);' >> LocalSettings.php
echo '$wgShowExceptionDetails = true;' >> LocalSettings.php
echo '$wgShowDBErrorBacktrace = true;' >> LocalSettings.php
echo '$wgDevelopmentWarnings = true;' >> LocalSettings.php
echo '$wgServer = "http://localhost";' >> LocalSettings.php
echo '$wgDeprecationReleaseLimit = "1.33";' >> LocalSettings.php

echo '$wgEnableWikibaseRepo = true;' >> LocalSettings.php
echo '$wgEnableWikibaseClient = false;' >> LocalSettings.php

if [ "$MW_BRANCH" == "REL1_34" ] || [ "$MW_BRANCH" == "REL1_35" ]; then
  echo 'require_once __DIR__ . "/extensions/Wikibase/repo/Wikibase.php";' >> LocalSettings.php
else
  echo 'wfLoadExtension( "WikibaseRepository", __DIR__ . "/extensions/Wikibase/extension-repo.json" );' >> LocalSettings.php
fi

echo 'require_once __DIR__ . "/extensions/Wikibase/repo/ExampleSettings.php";' >> LocalSettings.php

echo 'wfLoadExtension( "'$EXTENSION_NAME'" );' >> LocalSettings.php

cat <<EOT >> composer.local.json
{
  "require": {
    "wikibase/wikibase": "dev-$MW_BRANCH"
  },
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
