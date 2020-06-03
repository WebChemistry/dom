<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Parser\Entity;

final class InlineStyle
{

	private string $name;
	private string $value;

	public function __construct(string $name, string $value)
	{
		$this->name = strtolower(trim($name));
		$this->value = trim($value);
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function toString(): string
	{
		return sprintf('%s:%s', $this->name, $this->value);
	}

	public function __toString(): string
	{
		return $this->toString();
	}

}
