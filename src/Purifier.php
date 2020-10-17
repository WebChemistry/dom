<?php declare(strict_types = 1);

namespace WebChemistry\Dom;

use DOMCharacterData;
use DOMNode;
use InvalidArgumentException;
use WebChemistry\Dom\Parser\Html5DocumentParser;
use WebChemistry\Dom\Parser\HtmlDocumentParserInterface;
use WebChemistry\Dom\Renderer\DocumentInterface;
use WebChemistry\Dom\Renderer\DocumentObjectInterface;
use WebChemistry\Dom\Rule\ElementRuleInterface;

final class Purifier
{

	/** @var ElementRuleInterface[] */
	private array $rules = [];

	private HtmlDocumentParserInterface $parser;

	public function __construct(?HtmlDocumentParserInterface $parser = null)
	{
		$this->parser = $parser ?? new Html5DocumentParser();
	}

	public function addRule(ElementRuleInterface $rule): self
	{
		$this->rules[$rule->getId()] = $rule;

		return $this;
	}

	/**
	 * @return static
	 */
	public function addRules(ElementRuleInterface ...$rules): self
	{
		foreach ($rules as $rule) {
			$this->addRule($rule);
		}

		return $this;
	}

	/**
	 * @param string|DocumentInterface $document
	 */
	public function purify($document): DocumentObjectInterface
	{
		if ($document instanceof DocumentObjectInterface) {
			return $document;
		} elseif ($document instanceof DocumentInterface) {
			$string = $document->toString();
		} elseif (is_string($document)) {
			$string = $document;
		} else {
			throw new InvalidArgumentException(sprintf('Argument must be instance of %s or string', DocumentInterface::class));
		}

		$document = $this->parser->parseHtml($string);

		$this->iterateChildren($document->getDocument());

		return $document;
	}

	private function iterateChildren(DOMNode $parent): void
	{
		$child = $parent->firstChild;
		while ($child) {
			if (!$child instanceof DOMCharacterData) {
				$rule = $this->rules[$child->nodeName] ?? null;
				if (!$rule) {
					$child = DomManipulator::removeNodeKeepChildren($child);
				} else {
					$result = $rule->apply($child);

					if ($result === null) {
						$next = $child->nextSibling;
						$parent->removeChild($child);

						$child = $next;

						continue;
					}
				}

				$this->iterateChildren($child);
			}

			$child = $child->nextSibling;
		}
	}

}
