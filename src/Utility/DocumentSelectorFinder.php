<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Utility;

use DOMDocument;
use DOMNode;
use DOMXPath;
use InvalidArgumentException;
use Symfony\Component\CssSelector\CssSelectorConverter;

final class DocumentSelectorFinder
{

	private DOMDocument $document;

	private CssSelectorConverter $selector;

	private DOMNode $node;

	public function __construct(DOMNode $node)
	{
		$document = $node instanceof DOMDocument ? $node : $node->ownerDocument;

		if (!$document) {
			throw new InvalidArgumentException('Invalid DOMNode');
		}

		$this->document = $document;
		$this->selector = new CssSelectorConverter();
		$this->node = $node;
	}

	/**
	 * @return DOMNode[]
	 */
	public function find(string $selector): array
	{
		$xpath = new DOMXPath($this->document);
		$expression = $this->selector->toXPath($selector);

		return iterator_to_array($xpath->evaluate($expression, $this->node));
	}

}
