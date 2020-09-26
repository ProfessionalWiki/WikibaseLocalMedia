<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use DataValues\StringValue;
use Html;
use InvalidArgumentException;
use Title;
use ValueFormatters\ValueFormatter;

class ImageLinkFormatter implements ValueFormatter {

	private $imageLinker;
	private $cssClass;

	public function __construct( ImageLinker $imageLinker, string $cssClass ) {
		$this->imageLinker = $imageLinker;
		$this->cssClass = $cssClass;
	}

	/**
	 * @see ValueFormatter::format
	 *
	 * Formats the given commons file name as an HTML link
	 *
	 * @param StringValue $value The commons file name to turn into a link
	 *
	 * @throws InvalidArgumentException
	 * @return string HTML
	 */
	public function format( $value ) {
		if ( !( $value instanceof StringValue ) ) {
			throw new InvalidArgumentException( 'Data value type mismatch. Expected a StringValue.' );
		}

		$fileName = $value->getValue();
		$title = Title::makeTitleSafe( NS_MAIN, $fileName );

		if ( $title === null ) {
			return htmlspecialchars( $fileName );
		}

		return Html::element(
			'a',
			[
				'class' => $this->cssClass,
				'href' => $this->imageLinker->buildUrl( $title )
			],
			$fileName
		);
	}

}
