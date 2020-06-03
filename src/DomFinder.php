<?php declare(strict_types = 1);

namespace WebChemistry\Dom;

use DOMDocument;
use DOMNode;
use DOMXPath;
use InvalidArgumentException;
use Symfony\Component\CssSelector\CssSelectorConverter;

final class DomFinder
{

	private DOMDocument $document;
	private CssSelectorConverter $selector;
	private DOMNode $node;

	public function __construct(DOMNode $node)
	{
		if ($node instanceof DOMDocument) {
			$document = $node;
		} else {
			$document = $node->ownerDocument;
		}

		if (!$document) {
			throw new InvalidArgumentException('Invalid DOMNode');
		}

		$this->document = $document;
		$this->selector = new CssSelectorConverter();
		$this->node = $node;
	}

	/**
	 * @param string $selector
	 * @return DOMNode[]
	 */
	public function find(string $selector): array
	{
		$xpath = new DOMXPath($this->document);
		$expression = $this->selector->toXPath($selector);

		return iterator_to_array($xpath->evaluate($expression, $this->node));
	}

}
