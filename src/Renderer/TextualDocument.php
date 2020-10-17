<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Renderer;

use LogicException;

final class TextualDocument implements DocumentInterface
{

	private string $document;

	public function __construct(string $document)
	{
		$this->document = $document;
	}

	public function getDocument(): DOMDocument
	{
		throw new LogicException('TextualDocument have not DOMDocument');
	}

	public function isObject(): bool
	{
		return false;
	}

	public function toString(): string
	{
		return $this->document;
	}

	public function __toString(): string
	{
		return $this->document;
	}

}
