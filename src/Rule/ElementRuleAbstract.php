<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Rule;

use DOMAttr;
use DOMElement;
use DOMNode;

abstract class ElementRuleAbstract implements ElementRuleInterface
{

	/** @var AttributeRuleInterface[] */
	private array $attributeRules = [];

	private string $id;

	public function __construct(string $id)
	{
		$this->id = $id;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function addAttributeRule(AttributeRuleInterface $rule): self
	{
		$this->attributeRules[$rule->getId()] = $rule;

		return $this;
	}

	/**
	 * @return static
	 */
	public function addAttributeRules(AttributeRuleInterface ...$rules): self
	{
		foreach ($rules as $rule) {
			$this->addAttributeRule($rule);
		}

		return $this;
	}

	public function apply(DOMNode $node): ?DOMNode
	{
		if ($node instanceof DOMElement) {
			$remove = [];
			/** @var DOMAttr $attribute */
			foreach ($node->attributes as $attribute) {
				if (isset($this->attributeRules[$attribute->name])) {
					if (($this->attributeRules[$attribute->name])->apply($attribute)) {
						$remove[] = $attribute->name;
					}
				} else {
					$remove[] = $attribute->name;
				}
			}

			foreach ($remove as $name) {
				$node->removeAttribute($name);
			}
		}

		return $node;
	}

}
