<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use DataValues\DataValue;
use MediaWiki\Title\TitleFactory;
use Wikibase\Repo\Rdf\Values\ObjectUriRdfBuilder;

class LocalMediaRdfBuilder extends ObjectUriRdfBuilder {

	private TitleFactory $titleFactory;

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
