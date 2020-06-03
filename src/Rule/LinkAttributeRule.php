<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Rule;

use DOMAttr;

final class LinkAttributeRule extends AttributeRule
{

	/** @var string[] */
	private array $schemes = ['http', 'https'];

	/**
	 * @param string[] $schemes
	 */
	public function setSchemes(array $schemes): self
	{
		$this->schemes = $schemes;

		return $this;
	}

	public function apply(DOMAttr $attribute): bool
	{
		if (!$attribute->value) {
			return false;
		}

		if (!$this->validateLink($attribute->value)) {
			return true;
		}

		return parent::apply($attribute);
	}

	private function validateLink(string $value): bool
	{
		$pos = strpos($value, ':');
		if ($pos === false) {
			return true;
		}

		$scheme = substr($value, 0, $pos);

		return in_array($scheme, $this->schemes);
	}

}
