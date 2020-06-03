<?php declare(strict_types = 1);

namespace unit;

use Codeception\PHPUnit\TestCase;
use DOMDocument;
use DOMNode;
use WebChemistry\Dom\Replacer\Base64ImageReplacer;
use WebChemistry\Dom\Replacer\Entity\FileInfo;
use WebChemistry\Dom\Replacer\TextNodeReplacer;

final class ReplacerTest extends TestCase
{

	public function testBase64Image(): void
	{
		$replacer = new Base64ImageReplacer(function (FileInfo $fileInfo): string {
			$this->assertSame('image/jpeg', $fileInfo->getMimeType());

			return 'image';
		});

		$base64 = file_get_contents(__DIR__ . '/assets/base64Image.txt');

		$replacer->replace($document = $this->createHtml(sprintf('<img src="%s">', $base64)));

		$this->assertSame('<img src="image">', $document->saveHTML($document->documentElement));
	}

	public function testTextNodeReplacer(): void
	{
		$replacer = new TextNodeReplacer();
		$replacer->setSplitCallback('@(?<=^|\s)(#\w{1,120})@u', function (string $match, DOMDocument $document) {
			$link = $document->createElement('a', $match);
			$link->setAttribute('href', $match);

			return $link;
		});
		$replacer->setValidator(function (DOMNode $node): bool {
			while ($node = $node->parentNode) {
				if ($node->nodeName === 'a') {
					return false;
				}
			}

			return true;
		});

		$replacer->replace($document = $this->createHtml('start #hash <a href="#">#hash2</a> end'));

		$this->assertSame('<p>start <a href="#hash">#hash</a> <a href="#">#hash2</a> end</p>', trim($document->saveHTML($document)));
	}

	private function createHtml(string $html): DOMDocument
	{
		$document = new DOMDocument('1.0', 'UTF-8');
		$document->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

		return $document;
	}

}
