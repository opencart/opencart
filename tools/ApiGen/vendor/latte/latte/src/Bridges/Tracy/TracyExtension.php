<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Bridges\Tracy;

use Latte\Extension;
use Latte\Runtime\Template;
use Tracy;


/**
 * Extension for Tracy 2.x
 */
class TracyExtension extends Extension
{
	private LattePanel $panel;


	public function __construct(?string $name = null)
	{
		BlueScreenPanel::initialize();
		$this->panel = new LattePanel(name: $name);
		Tracy\Debugger::getBar()->addPanel($this->panel);
	}


	public function beforeRender(Template $template): void
	{
		$this->panel->addTemplate($template);
	}
}
