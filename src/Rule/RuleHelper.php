<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Rule;

use DOMAttr;

final class RuleHelper
{

	public static function matchClasses(array $classes): callable
	{
		return function (DOMAttr $attribute) use ($classes): void {
			$rows = array_filter(explode(' ', $attribute->textContent));

			$attribute->textContent = implode(' ', array_intersect($rows, $classes));
		};
	}

	public static function matchRegexClasses(array $patterns): callable
	{
		return function (DOMAttr $attribute) use ($patterns): void {
			$rows = array_filter(explode(' ', $attribute->nodeValue));

			$final = [];
			foreach ($rows as $row) {
				foreach ($patterns as $pattern) {
					if (preg_match($pattern, $row)) {
						$final[] = $row;

						continue 2;
					}
				}
			}

			$attribute->nodeValue = implode(' ', $final);
		};
	}

}
