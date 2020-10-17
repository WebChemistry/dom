<?php declare(strict_types = 1);

namespace WebChemistry\Dom\DI;

use Nette\DI\CompilerExtension;
use WebChemistry\Dom\Parser\Html5DocumentParser;
use WebChemistry\Dom\Parser\HtmlDocumentParserInterface;
use WebChemistry\Dom\Purifier;

final class DomExtension extends CompilerExtension
{

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('domParser'))
			->setType(HtmlDocumentParserInterface::class)
			->setFactory(Html5DocumentParser::class);

		$builder->addDefinition($this->prefix('purifier'))
			->setFactory(Purifier::class);
	}

}
