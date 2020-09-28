<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Tests;

use PHPUnit\Framework\TestCase;
use Wikibase\LocalMedia\WikibaseLocalMedia;

class SmokeTest extends TestCase {

	public function testGetValueValidators() {
		WikibaseLocalMedia::getGlobalInstance()->getValueValidators();
		$this->assertTrue( true );
	}

	public function testGetFormatterBuilder() {
		WikibaseLocalMedia::getGlobalInstance()->getFormatterBuilder();
		$this->assertTrue( true );
	}

	public function testGetRdfBuilder() {
		WikibaseLocalMedia::getGlobalInstance()->getRdfBuilder();
		$this->assertTrue( true );
	}

}
