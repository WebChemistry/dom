<?php declare(strict_types = 1);

namespace WebChemistry\Dom\Parser;

use InvalidArgumentException;
use Masterminds\HTML5;
use WebChemistry\Dom\Renderer\DocumentInterface;
use WebChemistry\Dom\Renderer\DocumentObjectInterface;
use WebChemistry\Dom\Renderer\Html5Document;

final class Html5DocumentParser implements HtmlDocumentParserInterface
{

	private HTML5 $parser;

	public function __construct()
	{
		$this->parser = new HTML5(['disable_html_ns' => true]);
	}

	/**
	 * @param string|DocumentInterface $document
	 * @param mixed[] $options
	 */
	public function parseHtml($document, array $options = []): DocumentObjectInterface
	{
		if ($document instanceof DocumentObjectInterface) {
			return $document;
		} elseif ($document instanceof DocumentInterface) {
			$string = $document->toString();
		} elseif (is_string($document)) {
			$string = $document;
		} else {
			throw new InvalidArgumentException(sprintf('Argument must be instance of %s or string', DocumentInterface::class));
		}

		return new Html5Document($this->parser, $this->parser->loadHTML($string, $options));
	}

}
