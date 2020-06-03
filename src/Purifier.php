<?php declare(strict_types = 1);

namespace WebChemistry\Dom;

use DOMCharacterData;
use DOMNode;
use LogicException;
use Masterminds\HTML5;
use WebChemistry\Dom\Renderer\DomRenderer;
use WebChemistry\Dom\Rule\ElementRuleInterface;

final class Purifier
{

	/** @var ElementRuleInterface[] */
	private array $rules = [];

	private HTML5 $parser;

	public function __construct(?HTML5 $parser = null)
	{
		$this->parser = $parser ?? new HTML5(['disable_html_ns' => true]);
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

	public function purify(string $html): DomRenderer
	{
		$document = $this->parser->loadHTML($html);

		$this->iterateChildren($document);

		return new DomRenderer($this->parser, $document);
	}

	private function iterateChildren(DOMNode $parent): void
	{
		$child = $parent->firstChild;
		while ($child) {
			if (!$child instanceof DOMCharacterData) {
				$rule = $this->rules[$child->nodeName] ?? null;
				if (!$rule) {
					$child = $this->removeNodeKeepChildren($child);
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

	private function removeNodeKeepChildren(DOMNode $node): DOMNode
	{
		$parent = $node->parentNode;
		if ($parent === null) {
			return $node;
		}

		while ($node->hasChildNodes()) {
			if (!$node->lastChild) {
				throw new LogicException('Invalid last child');
			}

			if ($node->nextSibling) {
				$parent->insertBefore($node->lastChild, $node->nextSibling);
			} else {
				$parent->insertBefore($node->lastChild);
			}
		}

		$parent->removeChild($node);

		return $parent;
	}

}
