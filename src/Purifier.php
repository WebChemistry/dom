<?php declare(strict_types = 1);

namespace WebChemistry\Dom;

use DOMCharacterData;
use DOMNode;
use LogicException;
use Masterminds\HTML5;
use WebChemistry\Dom\Parser\DomDocumentParserInterface;
use WebChemistry\Dom\Parser\Html5DocumentParser;
use WebChemistry\Dom\Renderer\DomRendererInterface;
use WebChemistry\Dom\Rule\ElementRuleInterface;

final class Purifier
{

	/** @var ElementRuleInterface[] */
	private array $rules = [];

	private DomDocumentParserInterface $parser;

	public function __construct(?DomDocumentParserInterface $parser = null)
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

	public function purify(string $html): DomRendererInterface
	{
		$renderer = $this->parser->parseHtmlReturnRenderer($html);

		$this->iterateChildren($renderer->getDocument());

		return $renderer;
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
