<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Parser;

use WebChemistry\Dom\Renderer\DocumentInterface;
use WebChemistry\Dom\Renderer\DocumentObjectInterface;

interface HtmlDocumentParserInterface
{

	/**
	 * @param string|DocumentInterface $document
	 * @param mixed[] $options
	 */
	public function parseHtml($document, array $options = []): DocumentObjectInterface;

}
