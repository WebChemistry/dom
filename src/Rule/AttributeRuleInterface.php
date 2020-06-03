<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Rule;

use DOMAttr;

interface AttributeRuleInterface
{

	public function getId(): string;

	/**
	 * Remove the attribute?
	 */
	public function apply(DOMAttr $attribute): bool;

}
