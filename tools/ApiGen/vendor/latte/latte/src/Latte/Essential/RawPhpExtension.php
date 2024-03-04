<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;


/**
 * Raw PHP in {php ...}
 */
final class RawPhpExtension extends Latte\Extension
{
	use Latte\Strict;

	public function getTags(): array
	{
		return [
			'php' => [Nodes\RawPhpNode::class, 'create'],
		];
	}
}
