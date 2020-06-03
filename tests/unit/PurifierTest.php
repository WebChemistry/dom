<?php declare(strict_types = 1);

namespace unit;

use Codeception\PHPUnit\TestCase;
use WebChemistry\Dom\Purifier;
use WebChemistry\Dom\Rule\AttributeRule;
use WebChemistry\Dom\Rule\ElementRule;
use WebChemistry\Dom\Rule\LinkAttributeRule;

final class PurifierTest extends TestCase
{

	public function testBasic(): void
	{
		$this->assertSame('discard', $this->purify('<div>discard</div>'));
	}

	public function testDiscard(): void
	{
		$this->assertSame('', $this->purify('<div>discard</div>', ElementRule::create('div')->discard()));
	}

	public function testAttributes(): void
	{
		$this->assertSame(
			'<div style="color: red;">discard</div>',
			$this->purify(
				'<div style="color: red;" onclick="alert()">discard</div>',
				ElementRule::create('div')->addAttributeRule(
					AttributeRule::create('style')
				)
			)
		);
	}

	public function testAttributeValueTrue(): void
	{
		$this->assertSame(
			'<div style="color: red;">discard</div>',
			$this->purify(
				'<div style="color: red;" onclick="alert()">discard</div>',
				ElementRule::create('div')->addAttributeRule(
					AttributeRule::create('style')
						->callback(function (string $value): bool {
							$this->assertSame('color: red;', $value);

							return true;
						})
				)
			)
		);
	}

	public function testAttributeValueFalse(): void
	{
		$this->assertSame(
			'<div>discard</div>',
			$this->purify(
				'<div style="color: red;" onclick="alert()">discard</div>',
				ElementRule::create('div')->addAttributeRule(
					AttributeRule::create('style')
						->callback(function (string $value): bool {
							$this->assertSame('color: red;', $value);

							return false;
						})
				)
			)
		);
	}

	public function testPurify(): void
	{
		$purifier = new Purifier();
		$purifier->addRules(
			ElementRule::create('script')
				->discard(),
			ElementRule::create('span')
				->addAttributeRule(AttributeRule::create('style')),
			ElementRule::create('img')
				->addAttributeRule(AttributeRule::create('src')),
			ElementRule::create('a')
				->addAttributeRule(LinkAttributeRule::create('href')),
		);

		$html = '<div>
Lorem ipsum <strong class="italic h2">is simply</strong>
<script>alert("dummy text!")</script>
<a href="javascript:tester">tester</a>
<span style="color: #FFF;font-weight: bold" onclick="alert(\'xxx\')">white</span> and <span style="color: red">red</span>
<br>
#tester xxx <a href="asd">#tester2</a> www
</div>';

		$document = $purifier->purify($html);
		$this->assertSame('Lorem ipsum is simply

<a>tester</a>
<span style="color: #FFF;font-weight: bold">white</span> and <span style="color: red">red</span>

#tester xxx <a href="asd">#tester2</a> www', trim($document->renderWithoutDocType()));
	}

	private function purify(string $html, ...$rules): string
	{
		$purifier = new Purifier();
		$purifier->addRules(...$rules);

		$document = $purifier->purify($html);

		return trim($document->renderWithoutDocType());
	}

}
