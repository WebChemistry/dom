<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Utility;

use DOMNode;
use DOMText;
use WebChemistry\Dom\Renderer\DocumentObjectInterface;

final class RedudantWhitespaceRemover
{

	private const NBSP_VALUE = "\xC2\xA0";

	public static function remove(DocumentObjectInterface $documentObject): void
	{
		$document = $documentObject->getDocument();

		// from last
		self::removeEmptyNodes(fn () => $document->lastChild);

		// from first
		self::removeEmptyNodes(fn () => $document->firstChild);

		// from last child remove end spaces
		self::trimEndSpaces($document->lastChild?->lastChild);

		// from first child remove start spaces
		self::trimStartSpaces($document->firstChild?->firstChild);
	}

	private static function trimEndSpaces(?DOMNode $node): void
	{
		if (!$node) {
			return;
		}

		if ($node instanceof DOMText) {
			$node->textContent = preg_replace('#(\s|\xC2\xA0)+$#', '', $node->textContent) ?? $node->textContent;
			if (!$node->textContent) {
				$node->parentNode->removeChild($node);
			}
		}
	}

	private static function trimStartSpaces(?DOMNode $node): void
	{
		if (!$node) {
			return;
		}

		if ($node instanceof DOMText) {
			$node->textContent = preg_replace('#^(\s|\xC2\xA0)+#', '', $node->textContent) ?? $node->textContent;
			if (!$node->textContent) {
				$node->parentNode->removeChild($node);
			}
		}
	}

	private static function removeEmptyNodes(callable $next): void
	{
		while ($node = $next()) {
			if ((iterator_count($node->childNodes) === 1) && ($text = $node->firstChild) instanceof DOMText) {
				$trimmed = preg_replace('#(\s|\xC2\xA0)#', '', $node->textContent);
				if (!$trimmed) {
					$node->parentNode->removeChild($node);

					continue;
				}
			}

			break;
		}
	}

}
