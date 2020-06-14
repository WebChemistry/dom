<?php declare(strict_types = 1);

namespace WebChemistry\Dom;

use DOMNode;

final class DomManipulator
{

	public static function removeNodeKeepChildren(DOMNode $node): DOMNode
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
