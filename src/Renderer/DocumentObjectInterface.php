<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Renderer;

use DOMDocument;

interface DocumentObjectInterface extends DocumentInterface
{

	public function getDocument(): DOMDocument;

}
