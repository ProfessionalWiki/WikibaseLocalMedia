<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia;

use ValueFormatters\FormatterOptions;
use Wikibase\Repo\Rdf\PropertyRdfBuilder;

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
				return PropertyRdfBuilder::OBJECT_PROPERTY;
			},
		];
	}

}
