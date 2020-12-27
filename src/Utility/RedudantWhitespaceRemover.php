<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Utility;

use DOMText;
use WebChemistry\Dom\Renderer\DocumentObjectInterface;

final class RedudantWhitespaceRemover
{

	private const NBSP_VALUE = "\xC2\xA0";

	public static function remove(DocumentObjectInterface $documentObject): void
	{
		$document = $documentObject->getDocument();

		// from last
		while ($node = $document->lastChild) {
			if ((iterator_count($node->childNodes) === 1) && ($text = $node->firstChild) instanceof DOMText) {
				$trimmed = preg_replace('#(\s|\xC2\xA0)#', '', $node->textContent);
				if (!$trimmed) {
					$node->parentNode->removeChild($node);
					continue;
				}
			}

			break;
		}

		// from first
		while ($node = $document->firstChild) {
			if ((iterator_count($node->childNodes) === 1) && ($text = $node->firstChild) instanceof DOMText) {
				$trimmed = preg_replace('#(\s|\xC2\xA0)#', '', $node->textContent);
				if (!$trimmed) {
					$node->parentNode->removeChild($node);
					continue;
				}
			}

			break;
		}

		// from last child remove end spaces
		$lastChild = $document->lastChild;

		if ($node = $lastChild->lastChild) {
			if ($node instanceof DOMText) {
				$node->textContent = preg_replace('#(\s|\xC2\xA0)#', '', $node->textContent) ?? $node->textContent;
				if (!$node->textContent) {
					$node->parentNode->removeChild($node);
				}
			}
		}
	}

}
