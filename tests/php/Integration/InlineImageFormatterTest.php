<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Tests\Integration;

use DataValues\StringValue;
use File;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use ParserOptions;
use RepoGroup;
use Wikibase\LocalMedia\Services\InlineImageFormatter;
use Wikibase\LocalMedia\Services\LocalImageLinker;

/**
 * @covers \Wikibase\LocalMedia\Services\InlineImageFormatter
 */
class InlineImageFormatterTest extends MediaWikiIntegrationTestCase {

	public function testFileMetadataIncludesDimensions(): void {
		$this->overrideConfigValue( MainConfigNames::ResponsiveImages, false );
		$this->setService( 'RepoGroup', $this->newRepoGroupFindingFile() );

		$html = $this->newFormatter()->format( new StringValue( 'Jonas-revenge.png' ) );

		$this->assertStringContainsString( '800 × 600 pixels', $html );
	}

	private function newRepoGroupFindingFile(): RepoGroup {
		$file = $this->createStub( File::class );
		$file->method( 'transform' )->willReturn( $this->newThumbnail() );
		$file->method( 'getDimensionsString' )->willReturn( '800 × 600 pixels' );
		$file->method( 'getSize' )->willReturn( 12345 );

		$repoGroup = $this->createStub( RepoGroup::class );
		$repoGroup->method( 'findFile' )->willReturn( $file );

		return $repoGroup;
	}

	private function newThumbnail(): object {
		return new class {
			public function toHtml(): string {
				return '<img src="thumbnail.png">';
			}
		};
	}

	private function newFormatter(): InlineImageFormatter {
		return new InlineImageFormatter(
			ParserOptions::newFromAnon(),
			[],
			'en',
			new LocalImageLinker(),
			'commons-media-caption'
		);
	}

}
