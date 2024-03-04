<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/*enum*/ final class ContentType
{
	public const
		Text = 'text',
		Html = 'html',
		Xml = 'xml',
		JavaScript = 'js',
		Css = 'css',
		ICal = 'ical';
}
