# Wikibase Local Media

MediaWiki extension that adds support for local media files to [Wikibase] via a new data type.

Wikibase Local Media was created by [Professional.Wiki] with Funding from [Rhizome].
[Professional.Wiki] provides commercial [Wikibase hosting], [MediaWiki development] and support.

## Platform requirements

* [PHP] 7.3 or later
* [MediaWiki] 1.34.x
* [Wikibase Repository] branch: REL1_34

## Installation

First install MediaWiki and Wikibase Repository.

The recommended way to install Wikibase Local Media is using [Composer](https://getcomposer.org) with
[MediaWiki's built-in support for Composer](https://professional.wiki/en/articles/installing-mediawiki-extensions-with-composer).

On the commandline, go to your wikis root directory. Then run these two commands:

```shell script
COMPOSER=composer.local.json composer require --no-update professional-wiki/wikibase-local-media:*
composer update professional-wiki/wikibase-local-media --no-dev -o
```

Then enable the extension by adding the following to the bottom of your wikis `LocalSettings.php` file:

```php
wfLoadExtension( 'WikibaseLocalMedia' );
```

You can verify the extension was enabled successfully by opening your wikis Special:Version page in your browser.


## Configuration

You can configure Wikibase Local Media via [LocalSettings.php].

## Release notes

### Version 0.1

Under development

* Initial release

[Professional.Wiki]: https://professional.wiki
[Wikibase]: https://wikiba.se
[Rhizome]: https://rhizome.org/
[MediaWiki]: https://www.mediawiki.org
[PHP]: https://www.php.net
[Wikibase Repository]: https://www.mediawiki.org/wiki/Extension:Wikibase_Repository
[LocalSettings.php]: https://www.mediawiki.org/wiki/Manual:LocalSettings.php
[MediaWiki development]: https://professional.wiki/en/mediawiki-development
[Wikibase hosting]: https://professional.wiki/en/hosting/wikibase
