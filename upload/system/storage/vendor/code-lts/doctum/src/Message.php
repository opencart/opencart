<?php

declare(strict_types = 1);

/*
 * This file is part of the Doctum utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doctum;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Message
{
    public const PARSE_ERROR             = 1;
    public const PARSE_CLASS             = 2;
    public const PARSE_VERSION_FINISHED  = 3;
    public const RENDER_PROGRESS         = 4;
    public const RENDER_VERSION_FINISHED = 5;
    public const SWITCH_VERSION          = 6;
}
