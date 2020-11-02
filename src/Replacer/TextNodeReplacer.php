<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Replacer;

use DOMCharacterData;
use DOMDocument;
use DOMDocumentType;
use DOMNode;

final class TextNodeReplacer
{

	/** @var callable|null */
	private $validator;

	private DOMDocument $document;

	/** @var DOMCharacterData[] */
	private array $texts = [];

	/** @var callable|null */
	private $splitCallback = null;

	private string $splitRegex;

	public function setValidator(callable $validator): self
	{
		$this->validator = $validator;

		return $this;
	}

	public function setSplitCallback(string $text, callable $callback): self
	{
		$this->splitRegex = sprintf('#(?<=^|\s)(%s)(?=$|\s)#', preg_quote($text, '#'));
		$this->splitCallback = $callback;

		return $this;
	}

	public function setSplitRegexCallback(string $regex, callable $callback): self
	{
		$this->splitCallback = $callback;
		$this->splitRegex = $regex;

		return $this;
	}

	public function replace(DOMDocument $document): void
	{
		$this->document = $document;
		$this->texts = [];

		$this->iterateChildNodes($document);

		foreach ($this->texts as $text) {
			$this->processTextNode($text);
		}
	}

	private function iterateChildNodes(DOMNode $node): void
	{
		$child = $node->firstChild;

		while ($child) {
			if ($child instanceof DOMDocumentType) {
				$child = $child->nextSibling;

				continue;
			}

			if ($child instanceof DOMCharacterData) {
				$this->texts[] = $child;
			} elseif ($child->hasChildNodes()) {
				$this->iterateChildNodes($child);
			}

			$child = $child->nextSibling;
		}
	}

	private function processTextNode(DOMCharacterData $node): void
	{
		if ($this->validator && !($this->validator)($node)) {
			return;
		}

		if ($this->splitCallback) {
			$splits = preg_split($this->splitRegex, $node->textContent, -1, PREG_SPLIT_DELIM_CAPTURE);

			if ($splits === false) {
				return;
			}

			$parent = $node->parentNode;
			if ($parent === null) {
				return;
			}

			if (count($splits) <= 1) {
				return;
			}

			$replaceWith = [];
			$replace = false;
			foreach ($splits as $index => $value) {
				if ($index % 2 === 1) {
					$el = ($this->splitCallback)($value, $this->document);
					if (!$el) {
						if ($el === null) {
							$replace = true;
						}

						continue;
					}

					$replaceWith[] = $el;
					$replace = true;
				} else {
					$replaceWith[] = $this->document->createTextNode($value);
				}
			}

			if ($replace) {
				foreach ($replaceWith as $item) {
					$parent->insertBefore($item, $node);
				}

				$parent->removeChild($node);
			}

			return;
		}
	}

}
