<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Tests;

use DataValues\StringValue;
use MediaWiki\MediaWikiServices;
use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\LocalMedia\Services\LocalMediaRdfBuilder;
use Wikimedia\Purtle\NTriplesRdfWriter;

/**
 * @covers \Wikibase\LocalMedia\Services\LocalMediaRdfBuilder
 * @group Wikibase
 * @group WikibaseRdf
 * @license GPL-2.0-or-later
 */
class LocalMediaRdfBuilderTest extends TestCase {

	public function testAddValue() {
		$builder = new LocalMediaRdfBuilder( MediaWikiServices::getInstance()->getTitleFactory() );

		$writer = new NTriplesRdfWriter();
		$writer->prefix( 'www', "http://www/" );
		$writer->prefix( 'acme', "http://acme/" );

		$writer->start();
		$writer->about( 'www', 'Q42' );

		$snak = new PropertyValueSnak(
			new PropertyId( 'P1' ),
			new StringValue( 'Bunny.jpg' )
		);

		$builder->addValue( $writer, 'acme', 'testing', 'DUMMY', '', $snak );
		$rdf = $writer->drain();

		$this->assertStringContainsString( 'File:Bunny.jpg', $rdf );
		$this->assertStringContainsString( $GLOBALS['wgServer'], $rdf );
	}

}
