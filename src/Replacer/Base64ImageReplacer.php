<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Replacer;

use DOMDocument;
use DOMElement;
use finfo;
use WebChemistry\Dom\Replacer\Entity\FileInfo;

final class Base64ImageReplacer
{

	/** @var callable */
	private $callback;

	/** @var string[] */
	private array $mimeTypes = ['image/jpeg', 'image/gif', 'image/webp', 'image/png'];

	public function __construct(callable $callback)
	{
		$this->callback = $callback;
	}

	public function replace(DOMDocument $document): void
	{
		/** @var DOMElement $image */
		foreach ($document->getElementsByTagName('img') as $image) {
			if (!$image->hasAttribute('src')) {
				continue;
			}

			$attr = $image->getAttributeNode('src');

			$pivot = $this->extractPivot($attr->value);
			if (!$pivot) {
				continue;
			}

			$binary = base64_decode(substr($attr->value, $pivot));
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			$mimeType = $finfo->buffer($binary);
			if ($mimeType === false) {
				continue;
			}

			if (!in_array($mimeType, $this->mimeTypes)) {
				continue;
			}

			$attr->value = ($this->callback)(new FileInfo($mimeType, $binary));
		}
	}

	private function extractPivot(string $value): ?int
	{
		if (substr($value, 0, 5) !== 'data:') {
			return null;
		}

		$pos = strpos($value, ',');
		if ($pos === false) {
			return null;
		}

		$firstPart = substr($value, 5, $pos - 5);
		$parameters = explode(';', $firstPart);

		if (!in_array('base64', $parameters)) {
			return null;
		}

		return $pos + 1;
	}

}
