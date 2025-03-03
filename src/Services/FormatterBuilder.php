<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use RequestContext;
use ValueFormatters\FormatterOptions;
use ValueFormatters\ValueFormatter;
use Wikibase\Lib\Formatters\CommonsThumbnailFormatter;
use Wikibase\Lib\Formatters\SnakFormat;
use Wikibase\Lib\Formatters\SnakFormatter;
use Wikibase\Lib\Formatters\WikibaseValueFormatterBuilders;

class FormatterBuilder {

	private WikibaseValueFormatterBuilders $formatterBuilders;
	private array $thumbLimits;

	public function __construct( WikibaseValueFormatterBuilders $formatterBuilders, array $thumbLimits ) {
		$this->formatterBuilders = $formatterBuilders;
		$this->thumbLimits = $thumbLimits;
	}

	public function newFormatter( string $format, FormatterOptions $options ): ValueFormatter {
		$snakFormat = new SnakFormat();

		if ( $snakFormat->isPossibleFormat( SnakFormatter::FORMAT_HTML_VERBOSE, $format ) ) {
			return new InlineImageFormatter(
				RequestContext::getMain()->getOutput()->parserOptions(),
				$this->thumbLimits,
				$options->getOption( ValueFormatter::OPT_LANG ),
				new LocalImageLinker(),
				'commons-media-caption'
			);
		}

		switch ( $snakFormat->getBaseFormat( $format ) ) {
			case SnakFormatter::FORMAT_HTML:
				return new ImageLinkFormatter( new LocalImageLinker(), '' );
			case SnakFormatter::FORMAT_WIKI:
				return new CommonsThumbnailFormatter();
			default:
				return $this->formatterBuilders->newStringFormatter( $format );
		}
	}

}
