<?php declare(strict_types = 1);

namespace unit;

use Codeception\PHPUnit\TestCase;
use DOMAttr;
use WebChemistry\Dom\Rule\RuleHelper;

final class RuleHelperTest extends TestCase
{

	public function testClasses(): void
	{
		$attr = new DOMAttr('class', 'value  foo bar');
		$callback = RuleHelper::matchClasses(['foo']);

		$callback($attr);

		$this->assertSame('', $attr->nodeValue);
	}

	public function testRegexClasses(): void
	{
		$attr = new DOMAttr('class', 'value  foo foo2 foo21 foo2a bar');
		$callback = RuleHelper::matchRegexClasses(['#^foo[0-9]$#']);

		$callback($attr);

		$this->assertSame('foo2', $attr->nodeValue);
	}

}
