<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Renderer;

use DOMDocument;
use Masterminds\HTML5;

final class Html5Document implements DocumentObjectInterface
{

	private HTML5 $parser;

	private DOMDocument $document;

	public function __construct(HTML5 $parser, DOMDocument $document)
	{
		$this->parser = $parser;
		$this->document = $document;
	}

	public function getDocument(): DOMDocument
	{
		return $this->document;
	}

	public function isObject(): bool
	{
		return true;
	}

	public function toString(): string
	{
		$node = $this->document->firstChild;
		if ($node === null) {
			return '';
		}

		$html = '';
		do {
			$html .= $this->parser->saveHTML($node);
		} while ($node = $node->nextSibling);

		return $html;
	}

	public function __toString(): string
	{
		return $this->toString();
	}

}
