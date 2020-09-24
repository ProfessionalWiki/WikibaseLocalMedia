<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use Title;
use Wikibase\Lib\Formatters\ImageLinker;

class LocalImageLinker implements ImageLinker {

	public function buildUrl( Title $title ): string {
		return $title->getFullURL();
	}

}

