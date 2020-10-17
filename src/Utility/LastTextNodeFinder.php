<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Utility;

use DOMCharacterData;
use DOMNode;

final class LastTextNodeFinder
{

	public static function getLastTextNode(DOMNode $node): ?DOMCharacterData
	{
		return self::reverseIterateChildren($node);
	}

	private static function reverseIterateChildren(DOMNode $node): ?DOMCharacterData
	{
		$child = $node->lastChild;
		if (!$child) {
			return null;
		}

		do {
			if ($child instanceof DOMCharacterData) {
				return $child;
			}

			if ($child->hasChildNodes()) {
				return self::reverseIterateChildren($child);
			}
		} while ($child = $child->previousSibling);

		return null;
	}

}
