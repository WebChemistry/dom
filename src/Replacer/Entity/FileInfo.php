<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Replacer\Entity;

use LogicException;

final class FileInfo
{
	
	private const SUFFIX_LOOKUP_TABLE = [
		'image/jpeg' => '.jpg',
		'image/png' => '.png',
		'image/webp' => '.webp',
		'image/gif' => '.gif',
	];

	private string $mimeType;

	private string $binary;

	public function __construct(string $mimeType, string $binary)
	{
		$this->mimeType = $mimeType;
		$this->binary = $binary;
	}

	public function getMimeType(): string
	{
		return $this->mimeType;
	}

	public function getBinary(): string
	{
		return $this->binary;
	}

	public function getSuffix(): string
	{
		if (!isset(self::SUFFIX_LOOKUP_TABLE[$this->mimeType])) {
			throw new LogicException(sprintf('MimeType %s not found in lookup table', $this->mimeType));
		}
		
		return self::SUFFIX_LOOKUP_TABLE[$this->mimeType];
	}

}
