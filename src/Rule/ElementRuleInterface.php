<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Rule;

use DOMNode;

interface ElementRuleInterface
{

	public function getId(): string;

	public function apply(DOMNode $node): ?DOMNode;

}
