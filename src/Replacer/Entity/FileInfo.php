<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Replacer\Entity;

final class FileInfo
{

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

}
