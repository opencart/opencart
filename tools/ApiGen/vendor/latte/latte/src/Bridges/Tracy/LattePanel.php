<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Bridges\Tracy;

use Latte\Engine;
use Latte\Extension;
use Latte\Runtime\Template;
use Tracy;


/**
 * Bar panel for Tracy 2.x
 */
class LattePanel implements Tracy\IBarPanel
{
	public bool $dumpParameters = true;

	/** @var Template[] */
	private array $templates = [];
	private array $list;
	private ?string $name = null;


	/** @deprecated use TracyExtension */
	public static function initialize(Engine $latte, ?string $name = null, ?Tracy\Bar $bar = null): void
	{
		$bar ??= Tracy\Debugger::getBar();
		$bar->addPanel(new self($latte, $name));
	}


	/** @deprecated use TracyExtension */
	public function __construct(?Engine $latte = null, ?string $name = null)
	{
		$this->name = $name;
		if ($latte) {
			$latte->addExtension(
				new class ($this->templates) extends Extension {
					public function __construct(
						private array &$templates,
					) {
					}


					public function beforeRender(Template $template): void
					{
						$this->templates[] = $template;
					}
				},
			);
		}
	}


	public function addTemplate(Template $template): void
	{
		$this->templates[] = $template;
	}


	/**
	 * Renders tab.
	 */
	public function getTab(): ?string
	{
		if (!$this->templates) {
			return null;
		}

		return Tracy\Helpers::capture(function () {
			$name = $this->name ?? basename(reset($this->templates)->getName());
			require __DIR__ . '/templates/LattePanel.tab.phtml';
		});
	}


	/**
	 * Renders panel.
	 */
	public function getPanel(): string
	{
		$this->list = [];
		$this->buildList($this->templates[0]);

		return Tracy\Helpers::capture(function () {
			$list = $this->list;
			$dumpParameters = $this->dumpParameters;
			require __DIR__ . '/templates/LattePanel.panel.phtml';
		});
	}


	private function buildList(Template $template, int $depth = 0, int $count = 1): void
	{
		$this->list[] = (object) [
			'template' => $template,
			'depth' => $depth,
			'count' => $count,
			'phpFile' => (new \ReflectionObject($template))->getFileName(),
		];

		$children = $counter = [];
		foreach ($this->templates as $t) {
			if ($t->getReferringTemplate() === $template) {
				$children[$t->getName()] = $t;
				@$counter[$t->getName()]++;
			}
		}

		foreach ($children as $name => $t) {
			$this->buildList($t, $depth + 1, $counter[$name]);
		}
	}
}
