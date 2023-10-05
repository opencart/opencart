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

namespace Doctum\Console;

use Doctum\Console\Command\ParseCommand;
use Doctum\Console\Command\RenderCommand;
use Doctum\Console\Command\UpdateCommand;
use Doctum\Console\Command\VersionCommand;
use Doctum\ErrorHandler;
use Doctum\Doctum;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{

    public function __construct()
    {
        error_reporting(-1);
        ErrorHandler::register();

        parent::__construct('Doctum', Doctum::VERSION);

        $this->add(new VersionCommand());
        $this->add(new UpdateCommand());
        $this->add(new ParseCommand());
        $this->add(new RenderCommand());
    }

    public function getLongVersion()
    {
        return parent::getLongVersion() . ' by <comment>Fabien Potencier and William Desportes</comment>';
    }

}
