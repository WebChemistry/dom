<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Rule;

use DOMAttr;

class AttributeRule implements AttributeRuleInterface
{

	/** @var callable|null */
	private $callback = null;

	/** @var callable|null */
	private $normalizer = null;

	private string $id;

	final public function __construct(string $id)
	{
		$this->id = $id;
	}

	public function getId(): string
	{
		return $this->id;
	}

	final public static function create(string $id): self
	{
		return new static($id);
	}

	public function normalize(callable $callback): self
	{
		$this->normalizer = $callback;
		return $this;
	}

	public function callback(callable $callback): self
	{
		$this->callback = $callback;

		return $this;
	}

	public function apply(DOMAttr $attribute): bool
	{
		if (!$attribute->value) {
			return false;
		}

		if ($this->normalizer) {
			($this->normalizer)($attribute);
		}

		if ($this->callback) {
			if (!($this->callback)($attribute->value)) {
				return true;
			}
		}

		return false;
	}

}
