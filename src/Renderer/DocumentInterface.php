<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Renderer;

interface DocumentInterface
{

	public function toString(): string;

	public function __toString(): string;

}
