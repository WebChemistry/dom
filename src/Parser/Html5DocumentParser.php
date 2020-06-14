<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Parser;

use DOMDocument;
use Masterminds\HTML5;
use WebChemistry\Dom\Renderer\DomRendererInterface;
use WebChemistry\Dom\Renderer\Html5DomRenderer;

final class Html5DocumentParser implements DomDocumentParserInterface
{

	private HTML5 $parser;

	public function __construct()
	{
		$this->parser = new HTML5(['disable_html_ns' => true]);
	}

	public function parseHtml(string $html, array $options = []): DOMDocument
	{
		return $this->parser->loadHTML($html, $options);
	}

	public function parseHtmlReturnRenderer(string $html, array $options = []): DomRendererInterface
	{
		return new Html5DomRenderer($this->parser, $this->parseHtml($html, $options));
	}

}
