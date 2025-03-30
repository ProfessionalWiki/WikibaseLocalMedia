<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Tests\Unit;

use MediaWiki\Title\Title;
use PHPUnit\Framework\TestCase;
use Wikibase\LocalMedia\Services\LocalImageLinker;

/**
 * @covers \Wikibase\LocalMedia\Services\LocalImageLinker
 * @group Wikibase
 * @license GPL-2.0-or-later
 */
class LocalImageLinkerTest extends TestCase {

	public function testBuildUrlReturnsFullUrl() {
		$title = Title::newFromText( 'MyPage' );

		$this->assertSame(
			$title->getFullURL(),
			( new LocalImageLinker() )->buildUrl( $title )
		);
	}

}
