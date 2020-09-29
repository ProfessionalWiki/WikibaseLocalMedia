<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia;

use ValueFormatters\FormatterOptions;

final class HookHandlers {

	public static function onExtensionRegistration(): void {

	}

	public static function onWikibaseRepoDataTypes( array &$dataTypeDefinitions ): void {
		$dataTypeDefinitions['PT:localMedia'] = [
			'value-type' => 'string',
			'expert-module' => 'jquery.valueview.experts.LocalMediaType',
			'validator-factory-callback' => function() {
				return WikibaseLocalMedia::getGlobalInstance()->getValueValidators();
			},
			'formatter-factory-callback' => function( $format, FormatterOptions $options ) {
				return WikibaseLocalMedia::getGlobalInstance()->getFormatterBuilder()->newFormatter( $format, $options );
			},
			'rdf-builder-factory-callback' => function () {
				return WikibaseLocalMedia::getGlobalInstance()->getRdfBuilder();
			},
			'rdf-data-type' => function() {
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
			'formatter-factory-callback' => function( $format, FormatterOptions $options ) {
				return WikibaseLocalMedia::getGlobalInstance()->getFormatterBuilder()->newFormatter( $format, $options );
			},
		];
	}

}
