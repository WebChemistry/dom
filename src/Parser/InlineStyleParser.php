<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Parser;

use WebChemistry\Dom\Parser\Entity\InlineStyle;
use WebChemistry\Dom\Parser\Entity\InlineStyleCollection;

class InlineStyleParser
{

	public static function parse(string $code): InlineStyleCollection
	{
		if (preg_match_all('#\s*(.+?)\s*:\s*(.+?)\s*(?:;+|$)\s*#m', $code, $matches)) {
			$return = [];

			foreach ($matches[1] as $key => $name) {
				$style = new InlineStyle($name, $matches[2][$key]);

				$return[$style->getName()] = $style;
			}

			return new InlineStyleCollection($return);
		}

		return new InlineStyleCollection([]);
	}

}
