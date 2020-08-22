<?php declare(strict_types = 1);

namespace WebChemistry\Dom;

use DOMNode;
use DOMNodeList;
use DOMText;
use Nette\Utils\Strings;

class HtmlTruncate {

	private DOMNode $root;

	private int $length;

	/** @var DOMText[] */
	private array $textNodes = [];

	public function __construct(DOMNode $root, int $length)
	{
		$this->root = $root;
		$this->length = $length;
	}

	public static function truncate(DOMNode $element, int $length)
	{
		return (new self($element, $length))();
	}

	private function checkTextNode(DOMNode $node): void
	{
		if ($node->nodeType === XML_TEXT_NODE) {
			$this->textNodes[] = $node;
		}
	}

	private function walk(DOMNodeList $list): void
	{
		/** @var DOMNode $node */
		foreach ($list as $node) {
			$this->checkTextNode($node);
			if ($node->hasChildNodes()) {
				$this->walk($node->childNodes);
			}
		}
	}

	public function __invoke()
	{
		$sibling = $this->root;

		do {
			$this->checkTextNode($sibling);

			$this->walk($sibling->childNodes);
		} while ($sibling = $this->root->nextSibling);

		$this->truncateTexts();
	}

	private function truncateTexts(): void
	{
		foreach ($this->textNodes as $text) {
			if ($this->length <= 0) {
				$this->removeText($text);

				continue;
			}

			$length = strlen($text->nodeValue);

			if ($length >= $this->length) {
				$text->nodeValue = Strings::truncate($text->nodeValue, $this->length);
			}

			$this->length -= $length;
		}
	}

	private function removeText(DOMText $child): void
	{
		while ($parent = $child->parentNode) {
			$parent->removeChild($child);

			if ($parent->hasChildNodes()) {
				break;
			}

			$child = $parent;
		}
	}

}
