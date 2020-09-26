<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use DataValues\DataValue;
//use TitleFactory;
use Wikibase\Rdf\Values\ObjectUriRdfBuilder;

/**
 * RDF mapping for local media values.
 *
 * @license GPL-2.0-or-later
 */
class LocalMediaRdfBuilder extends ObjectUriRdfBuilder {

//	private $titleFactory;
//
//	public function __construct( TitleFactory $titleFactory ) {
//		$this->titleFactory = $titleFactory;
//	}

	/**
	 * @param DataValue $value
	 *
	 * @return string the object URI
	 */
	protected function getValueUri( DataValue $value ) {
		return \Title::newFromText( $value->getValue(), NS_FILE )->getFullURL();
	}

}
