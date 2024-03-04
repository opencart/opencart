<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Attributes;

use Attribute;


#[Attribute(Attribute::TARGET_METHOD)]
class TemplateFunction
{
}


#[Attribute(Attribute::TARGET_METHOD)]
class TemplateFilter
{
}
