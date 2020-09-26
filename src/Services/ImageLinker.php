<?php

declare( strict_types = 1 );

namespace Wikibase\LocalMedia\Services;

use Title;

/**
 * @license GPL-2.0-or-later
 */
interface ImageLinker {

	public function buildUrl( Title $title ): string;

}
