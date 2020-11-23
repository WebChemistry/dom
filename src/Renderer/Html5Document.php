<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Renderer;

use DOMDocument;
use DOMElement;
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

	public function toString(bool $raw = false): string
	{
		if ($raw) {
			return $this->parser->saveHTML($this->document);
		}

		// content inside <html>
		$elements = $this->document->getElementsByTagName('html');
		if ($elements->count() === 1) {
			/** @var DOMElement $element */
			$element = $elements[0];

			if ($element->hasChildNodes()) {
				$html = '';
				foreach ($element->childNodes as $node) {
					$html .= $this->parser->saveHTML($node);
				}

				return $html;
			}
		}

		// or remove DOCTYPE
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
