<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Utility;

use DOMDocument;
use DOMText;
use DOMXPath;

final class DocumentToTextUtility
{

	public static function documentToText(DOMDocument $document): string
	{
		$xpath = new DOMXPath($document);
		$nodes = $xpath->query('//text()');

		$string = '';
		/** @var DOMText $node */
		foreach ($nodes as $node) {
			$string .= $node->nodeValue;
		}

		return $string;
	}

}
