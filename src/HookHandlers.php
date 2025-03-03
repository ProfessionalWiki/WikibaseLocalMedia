<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia;

use MediaWiki\MediaWikiServices;
use ValueFormatters\FormatterOptions;

final class HookHandlers {

	public static function onWikibaseRepoDataTypes( array &$dataTypeDefinitions ): void {
		$dataTypeDefinitions['PT:localMedia'] = [
			'value-type' => 'string',
			'expert-module' => 'jquery.valueview.experts.LocalMediaType',
			'validator-factory-callback' => static function () {
				return WikibaseLocalMedia::getGlobalInstance()->getValueValidators();
			},
			'formatter-factory-callback' => static function ( $format, FormatterOptions $options ) {
				return WikibaseLocalMedia::getGlobalInstance()
					->getFormatterBuilder()->newFormatter( $format, $options );
			},
			'rdf-builder-factory-callback' => static function () {
				return WikibaseLocalMedia::getGlobalInstance()->getRdfBuilder();
			},
			'rdf-data-type' => static function () {
				if ( class_exists( 'Wikibase\Repo\Rdf\PropertySpecificComponentsRdfBuilder' ) ) {
					return \Wikibase\Repo\Rdf\PropertySpecificComponentsRdfBuilder::OBJECT_PROPERTY;
				}

				if ( class_exists( 'Wikibase\Rdf\PropertyRdfBuilder' ) ) {
					return \Wikibase\Rdf\PropertyRdfBuilder::OBJECT_PROPERTY;
				}

				return \Wikibase\Repo\Rdf\PropertyRdfBuilder::OBJECT_PROPERTY;
			},
		];
	}

	public static function onWikibaseClientDataTypes( array &$dataTypeDefinitions ): void {
		$dataTypeDefinitions['PT:localMedia'] = [
			'value-type' => 'string',
			'formatter-factory-callback' => static function ( $format, FormatterOptions $options ) {
				return WikibaseLocalMedia::getGlobalInstance()
					->getFormatterBuilder()->newFormatter( $format, $options );
			},
		];
	}

	public static function onResourceLoaderGetConfigVars( array &$vars ): void {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$vars['wgWikibaseLocalMediaRemoteApiUrl'] = $config->get( 'WikibaseLocalMediaRemoteApiUrl' );
	}
}
