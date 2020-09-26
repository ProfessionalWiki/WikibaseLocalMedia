<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use DataValues\DataValue;
use Wikibase\Rdf\Values\ObjectUriRdfBuilder;

/**
 * MediaWiki 1.34.x
 */
class LocalMediaRdfBuilder34 extends ObjectUriRdfBuilder {

	/**
	 * @param DataValue $value
	 *
	 * @return string the object URI
	 */
	protected function getValueUri( DataValue $value ) {
		return \Title::newFromText( $value->getValue(), NS_FILE )->getFullURL();
	}

}
