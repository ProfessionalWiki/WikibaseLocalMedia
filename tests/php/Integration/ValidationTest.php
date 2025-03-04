<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Tests\Integration;

use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use Wikibase\LocalMedia\WikibaseLocalMedia;

/**
 * @covers \Wikibase\LocalMedia\WikibaseLocalMedia
 */
class ValidationTest extends TestCase {

	public function testValidationSucceeds() {
		$validators = WikibaseLocalMedia::getGlobalInstance()->getValueValidators();

		$this->assertNotEmpty( $validators );

		foreach ( $validators as $validator ) {
			$this->assertCount( 0, $validator->validate( new StringValue( 'Valid-value.png' ) )->getErrors() );
		}
	}

	public function testValidationFails() {
		$validators = WikibaseLocalMedia::getGlobalInstance()->getValueValidators();

		$this->assertNotEmpty( $validators );

		$errors = [];

		foreach ( $validators as $validator ) {
			$errors = array_merge( $errors, $validator->validate( new StringValue( 'Invalid-value' ) )->getErrors() );
		}

		$this->assertNotEmpty( $errors );
	}

}
