<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use DataValues\DataValue;
use TitleFactory;
use Wikibase\Repo\Rdf\Values\ObjectUriRdfBuilder;

/**
 * MediaWiki 1.35+
 */
class LocalMediaRdfBuilder35 extends ObjectUriRdfBuilder {

	private $titleFactory;

	public function __construct( TitleFactory $titleFactory ) {
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @param DataValue $value
	 *
	 * @return string the object URI
	 */
	protected function getValueUri( DataValue $value ) {
		return $this->titleFactory->newFromText( $value->getValue(), NS_FILE )->getFullURL();
	}

}
