<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia;

use MediaWiki\MediaWikiServices;
use ValueValidators\ValueValidator;
use Wikibase\LocalMedia\Services\FormatterBuilder;
use Wikibase\LocalMedia\Services\LocalMediaRdfBuilder34;
use Wikibase\LocalMedia\Services\LocalMediaRdfBuilder35;
use Wikibase\Repo\WikibaseRepo;

class WikibaseLocalMedia {

	protected static /* ?self */ $instance;

	public static function getGlobalInstance(): self {
		if ( !isset( self::$instance ) ) {
			self::$instance = self::newDefault();
		}

		return self::$instance;
	}

	protected static function newDefault(): self {
		return new static();
	}

	protected final function __construct() {
	}

	/**
	 * @return ValueValidator[]
	 */
	public function getValueValidators(): array {
		return WikibaseRepo::getDefaultValidatorBuilders()->buildMediaValidators( 'doNotCheckExistence' );
	}

	public function getFormatterBuilder(): FormatterBuilder {
		return new FormatterBuilder(
			WikibaseRepo::getDefaultInstance()->getDefaultValueFormatterBuilders(),
			$GLOBALS['wgThumbLimits']
		);
	}

	public function getRdfBuilder() {
		if ( method_exists( MediaWikiServices::getInstance(), 'getTitleFactory' ) ) {
			return new LocalMediaRdfBuilder35( MediaWikiServices::getInstance()->getTitleFactory() );
		}

		return new LocalMediaRdfBuilder34();
	}

}
