<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Parser\Entity;

final class InlineStyleCollection
{

	/** @var InlineStyle[] */
	private array $styles;

	/**
	 * @param InlineStyle[] $styles
	 */
	public function __construct(array $styles)
	{
		$this->styles = $styles;
	}

	public function has(string $name): bool
	{
		return isset($this->styles[$name]);
	}

	public function get(string $name): InlineStyle
	{
		return $this->styles[$name];
	}

	public function remove(string $name): void
	{
		unset($this->styles[$name]);
	}

	/**
	 * @param string[] $names
	 */
	public function createSubCollectionBy(array $names): self
	{
		$styles = [];
		foreach ($names as $name) {
			if (isset($this->styles[$name])) {
				$styles[$name] = $this->styles[$name];
			}
		}

		return new self($styles);
	}

	public function toString(): string
	{
		return implode(';', $this->styles);
	}

	public function __toString(): string
	{
		return $this->toString();
	}

}
