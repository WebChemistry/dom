<?php declare(strict_types = 1);

namespace unit;

use Codeception\PHPUnit\TestCase;
use DOMDocument;
use WebChemistry\Dom\Parser\Html5DocumentParser;
use WebChemistry\Dom\Utility\DocumentToTextUtility;

final class DocumentToTextUtilityTest extends TestCase
{

	public function testDocumentToText(): void
	{
		$parser = new Html5DocumentParser();
		$document = $parser->parseHtml(file_get_contents(__DIR__ . '/assets/example.html'));

		$string = DocumentToTextUtility::documentToText($document->getDocument());

		$this->assertSame(3393, strlen($string));
	}

	private function createHtml(string $html): DOMDocument
	{
		$document = new DOMDocument('1.0', 'UTF-8');
		$document->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

		return $document;
	}

}
