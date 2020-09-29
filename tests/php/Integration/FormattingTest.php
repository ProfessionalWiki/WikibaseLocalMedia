<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Tests\Integration;

use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use ValueFormatters\FormatterOptions;
use Wikibase\Lib\Formatters\SnakFormatter;
use Wikibase\LocalMedia\WikibaseLocalMedia;

/**
 * @covers \Wikibase\LocalMedia\WikibaseLocalMedia
 * @covers \Wikibase\LocalMedia\Services\FormatterBuilder
 */
class FormattingTest extends TestCase {

	/**
	 * @dataProvider formattingProvider
	 */
	public function testFormatting( string $format, string $expected ) {
		$formatter = WikibaseLocalMedia::getGlobalInstance()->getFormatterBuilder()->newFormatter(
			$format,
			$this->newOptions()
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

	private function newOptions(): FormatterOptions {
		return new FormatterOptions( [ 'lang' => 'en' ] );
	}

	public function testHtmlFormat() {
		if ( !method_exists( $this, 'assertStringContainsString' ) ) {
			$this->markTestSkipped();
		}

		$formatter = WikibaseLocalMedia::getGlobalInstance()->getFormatterBuilder()->newFormatter(
			SnakFormatter::FORMAT_HTML_VERBOSE,
			$this->newOptions()
		);

		$html = $formatter->format( new StringValue( 'ValidImageThatDoesNotExist.png' ) );

		$this->assertStringContainsString( '<div class="commons-media-caption">', $html );
		$this->assertStringContainsString( 'href="//', $html );
		$this->assertStringContainsString( 'ValidImageThatDoesNotExist.png', $html );
	}

}
