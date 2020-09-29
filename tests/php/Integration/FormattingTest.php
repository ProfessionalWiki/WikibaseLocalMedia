<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Tests\Integration;

use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use ValueFormatters\FormatterOptions;
use Wikibase\Lib\Formatters\SnakFormatter;
use Wikibase\LocalMedia\WikibaseLocalMedia;

class FormattingTest extends TestCase {

	/**
	 * @dataProvider formattingProvider
	 */
	public function testFormatting( string $format, string $expected ) {
		$formatter = WikibaseLocalMedia::getGlobalInstance()->getFormatterBuilder()->newFormatter(
			$format,
			new FormatterOptions()
		);

		$this->assertEquals(
			$expected,
			$formatter->format( new StringValue( 'Jonas-revenge.png' ) )
		);
	}

	public function formattingProvider() {
		yield [ SnakFormatter::FORMAT_WIKI, '[[File:Jonas-revenge.png|frameless]]' ];
		yield [ SnakFormatter::FORMAT_PLAIN, 'Jonas-revenge.png' ];
	}

}
