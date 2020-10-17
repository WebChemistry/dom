<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Finder;

use DOMCharacterData;
use DOMDocument;
use DOMDocumentType;
use DOMNode;

final class TextNodeFinder
{

	/** @var callable|null */
	private $validator;

	private DOMDocument $document;

	/** @var DOMCharacterData[] */
	private array $texts = [];

	private string $regex;

	private int $regexGroup;

	public function setValidator(callable $validator): self
	{
		$this->validator = $validator;

		return $this;
	}

	public function setRegex(string $regex, int $group = 0): self
	{
		$this->regex = $regex;
		$this->regexGroup = $group;

		return $this;
	}

	/**
	 * @return mixed[]
	 */
	public function find(DOMDocument $document): array
	{
		$this->document = $document;
		$this->texts = [];

		$this->iterateChildNodes($document);

		$occurences = [];
		foreach ($this->texts as $text) {
			$this->processTextNode($text, $occurences);
		}

		return $occurences;
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

	/**
	 * @param mixed[] $occurences
	 */
	private function processTextNode(DOMCharacterData $node, array &$occurences): void
	{
		if ($this->validator && !($this->validator)($node)) {
			return;
		}

		if ($this->regex) {
			if (preg_match_all($this->regex, $node->textContent, $matches)) {
				foreach ($matches[$this->regexGroup] as $match) {
					$occurences[] = $match;
				}
			}
		}
	}

}
