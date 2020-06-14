<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Parser;

use DOMDocument;
use WebChemistry\Dom\Renderer\DomRendererInterface;

interface DomDocumentParserInterface
{

	public function parseHtml(string $html, array $options = []): DOMDocument;

	public function parseHtmlReturnRenderer(string $html, array $options = []): DomRendererInterface;

}
