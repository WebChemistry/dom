<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Renderer;

use DOMCharacterData;
use DOMDocument;
use DOMNamedNodeMap;
use DOMNode;
use Masterminds\HTML5;

final class Html5DomRenderer implements DomRendererInterface
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

	public function render(): string
	{
		return $this->parser->saveHTML($this->document);
	}

	public function renderWithoutDocType(): string
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
		return $this->renderWithoutDocType();
	}

}
