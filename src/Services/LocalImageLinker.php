<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use MediaWiki\Title\Title;

class LocalImageLinker implements ImageLinker {

	public function buildUrl( Title $title ): string {
		return $title->getFullURL();
	}

}
