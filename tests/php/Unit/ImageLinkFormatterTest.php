<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Tests\Unit;

use DataValues\StringValue;
use Wikibase\LocalMedia\Services\ImageLinkFormatter;
use Wikibase\LocalMedia\Services\LocalImageLinker;

/**
 * @covers \Wikibase\LocalMedia\Services\ImageLinkFormatter
 */
class ImageLinkFormatterTest extends \MediaWikiTestCase {

	public function testCssClass() {
		$formatter = new ImageLinkFormatter( new LocalImageLinker(), 'kittens' );

		if ( method_exists( $this, 'assertStringContainsString' ) ) {
			$this->assertStringContainsString(
				'class="kittens"',
				$formatter->format( new StringValue( 'MyImage.png' ) )
			);
		}
		else {
			$this->assertContains(
				'class="kittens"',
				$formatter->format( new StringValue( 'MyImage.png' ) )
			);
		}
	}

}
