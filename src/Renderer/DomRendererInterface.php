<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Renderer;

use DOMDocument;

interface DomRendererInterface
{

	public function getDocument(): DOMDocument;

	public function render(): string;

	public function renderWithoutDocType(): string;

	public function __toString(): string;

}
