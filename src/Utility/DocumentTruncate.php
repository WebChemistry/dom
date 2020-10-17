<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Utility;

use DOMNode;
use DOMText;
use Nette\Utils\Strings;

class DocumentTruncate
{

	private DOMNode $root;

	private int $length;

	private bool $truncate = false;

	public function __construct(DOMNode $root, int $length)
	{
		$this->root = $root;
		$this->length = $length;
	}

	public static function truncate(DOMNode $element, int $length): void
	{
		(new self($element, $length))();
	}

	public function __invoke(): void
	{
		$this->walkSiblings($this->root, function (DOMNode $node): void {
			$this->recursiveNode($node);
		});
	}

	private function recursiveNode(DOMNode $node): void
	{
		/** @var DOMNode $child */
		foreach (iterator_to_array($node->childNodes) as $child) {
			if ($this->truncate) {
				$node->removeChild($child);

			} elseif ($child->nodeType === XML_TEXT_NODE) {
				$this->calculateTruncate($child);

			} elseif ($child->hasChildNodes()) {
				$this->recursiveNode($child);
			}
		}
	}

	private function calculateTruncate(DOMText $text): void
	{
		if ($this->length <= 0) {
			$this->truncate = true;
			$text->parentNode->removeChild($text);

			return;
		}

		$length = strlen($text->nodeValue);

		if ($length >= $this->length) {
			$this->truncate = true;
			$text->nodeValue = Strings::truncate($text->nodeValue, $this->length);
		}

		$this->length -= $length;
	}

	private function walkSiblings(DOMNode $root, callable $function): void
	{
		$node = $root;
		do {
			$function($node);
		} while ($node = $root->nextSibling);
	}

}
