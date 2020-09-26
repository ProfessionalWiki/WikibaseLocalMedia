<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use DataValues\StringValue;
use File;
use Html;
use InvalidArgumentException;
use Language;
use Linker;
use ParserOptions;
use RepoGroup;
use Title;
use ValueFormatters\ValueFormatter;

/**
 * @license GPL-2.0-or-later
 * @author Adrian Heine
 * @author Marius Hoch
 */
class InlineImageFormatter implements ValueFormatter {

	const FALLBACK_THUMBNAIL_WIDTH = 320; // 320 the was default hardcoded value. Removed in T224189

	/**
	 * @var Language
	 */
	private $language;

	/**
	 * @var RepoGroup
	 */
	private $repoGroup;

	/**
	 * @var ParserOptions
	 */
	private $parserOptions;

	/**
	 * @var array
	 */
	private $thumbLimits;

	/**
	 * @var ImageLinker
	 */
	private $imageLinker;

	/**
	 * @var string
	 */
	private $captionCssClass;

	/**
	 * @param ParserOptions $parserOptions Options for thumbnail size
	 * @param array $thumbLimits Mapping of thumb number to the limit like [ 0 => 120, 1 => 240, ...]
	 * @param string $languageCode
	 * @param ImageLinker $imageLinker
	 * @param string $captionCssClass
	 * @param RepoGroup|null $repoGroup
	 * @throws \MWException
	 */
	public function __construct(
		ParserOptions $parserOptions,
		array $thumbLimits,
		string $languageCode,
		ImageLinker $imageLinker,
		string $captionCssClass,
		RepoGroup $repoGroup = null
	) {
		$this->language = Language::factory( $languageCode );
		$this->repoGroup = $repoGroup ?: RepoGroup::singleton();
		$this->parserOptions = $parserOptions;
		$this->thumbLimits = $thumbLimits;
		$this->imageLinker = $imageLinker;
		$this->captionCssClass = $captionCssClass;
	}

	/**
	 * @see ValueFormatter::format
	 *
	 * Formats the given commons file name as an HTML image gallery.
	 *
	 * @param StringValue $value The commons file name
	 *
	 * @throws InvalidArgumentException
	 * @return string HTML
	 */
	public function format( $value ) {
		if ( !( $value instanceof StringValue ) ) {
			throw new InvalidArgumentException( 'Data value type mismatch. Expected a StringValue.' );
		}

		$fileName = $value->getValue();
		// We cannot use makeTitle because it does not secureAndSplit()
		$title = Title::makeTitleSafe( NS_FILE, $fileName );
		if ( $title === null ) {
			return htmlspecialchars( $fileName );
		}

		$transformOptions = [
			'width' => $this->getThumbWidth( $this->parserOptions->getThumbSize() ),
			'height' => 1000
		];

		$file = $this->repoGroup->findFile( $fileName );
		if ( !$file instanceof File ) {
			return $this->getCaptionHtml( $title );
		}
		$thumb = $file->transform( $transformOptions );
		if ( !$thumb ) {
			return $this->getCaptionHtml( $title );
		}

		Linker::processResponsiveImages( $file, $thumb, $transformOptions );

		return $this->wrapThumb( $title, $thumb->toHtml() ) . $this->getCaptionHtml( $title, $file );
	}

	private function getThumbWidth( $thumbSize ) {
		return $this->thumbLimits[$thumbSize] ?? self::FALLBACK_THUMBNAIL_WIDTH;
	}

	/**
	 * @param Title $title
	 * @param string $thumbHtml
	 * @return string HTML
	 */
	private function wrapThumb( Title $title, $thumbHtml ) {
		$attributes = [
			'class' => 'image',
			'href' => $this->imageLinker->buildUrl( $title )
		];

		return Html::rawElement(
			'div',
			[ 'class' => 'thumb' ],
			Html::rawElement( 'a', $attributes, $thumbHtml )
		);
	}

	/**
	 * @param Title $title
	 * @param File|null $file
	 * @return string HTML
	 */
	private function getCaptionHtml( Title $title, $file = null ) {
		$attributes = [
			'href' => $this->imageLinker->buildUrl( $title )
		];
		$innerHtml = Html::element( 'a', $attributes, $title->getText() );

		if ( $file ) {
			$innerHtml .= Html::element( 'br' ) . $this->getFileMetaHtml( $file );
		}

		return Html::rawElement(
			'div',
			[ 'class' => $this->captionCssClass ],
			$innerHtml
		);
	}

	/**
	 * @param File $file
	 * @return string HTML
	 */
	private function getFileMetaHtml( File $file ) {
		return $this->language->semicolonList( [
			$file->getDimensionsString(),
			htmlspecialchars( $this->language->formatSize( $file->getSize() ) )
		] );
	}

}
