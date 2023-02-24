<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Tests\Unit;

use DataValues\StringValue;
use MediaWiki\MediaWikiServices;
use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\NumericPropertyId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\LocalMedia\Services\LocalMediaRdfBuilder35;
use Wikibase\LocalMedia\WikibaseLocalMedia;
use Wikimedia\Purtle\NTriplesRdfWriter;

/**
 * @covers \Wikibase\LocalMedia\Services\LocalMediaRdfBuilder35
 * @group Wikibase
 * @group WikibaseRdf
 * @license GPL-2.0-or-later
 */
class LocalMediaRdfBuilderTest extends TestCase {

	public function testAddValue() {
		if ( !method_exists( MediaWikiServices::getInstance(), 'getTitleFactory' ) ) {
			$this->markTestSkipped( 'Need MW 1.35+' );
		}

		$builder = new LocalMediaRdfBuilder35( MediaWikiServices::getInstance()->getTitleFactory() );

		$writer = new NTriplesRdfWriter();
		$writer->prefix( 'www', "http://www/" );
		$writer->prefix( 'acme', "http://acme/" );

		$writer->start();
		$writer->about( 'www', 'Q42' );

		$snak = new PropertyValueSnak(
			// Wikibase 1.37+
			class_exists( NumericPropertyId::class ) ?
				new NumericPropertyId( 'P1' ) :
				new PropertyId( 'P1' ),
			new StringValue( 'Bunny.jpg' )
		);

		$builder->addValue( $writer, 'acme', 'testing', 'DUMMY', '', $snak );
		$rdf = $writer->drain();

		$this->assertStringContainsString( 'File:Bunny.jpg', $rdf );
		$this->assertStringContainsString( $GLOBALS['wgServer'], $rdf );
	}

	public function testGetRdfBuilder() {
		WikibaseLocalMedia::getGlobalInstance()->getRdfBuilder();
		$this->assertTrue( true );
	}

}
