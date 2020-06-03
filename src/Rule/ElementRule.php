<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Rule;

use DOMNode;

class ElementRule extends ElementRuleAbstract
{

	private bool $discard = false;

	final public function __construct(string $id)
	{
		parent::__construct($id);
	}

	final public static function create(string $id): self
	{
		return new static($id);
	}

	public function discard(): self
	{
		$this->discard = true;

		return $this;
	}

	public function apply(DOMNode $node): ?DOMNode
	{
		if ($this->discard) {
			return null;
		}

		return parent::apply($node);
	}

}
