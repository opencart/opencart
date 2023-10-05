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

namespace Doctum\Console\Command;

use Doctum\Doctum;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RenderCommand extends Command
{

    /**
     * @see Command
     * @phpstan-return void
     */
    protected function configure()
    {
        parent::configure();

        $this->addForceOption();
        $this->addOutputFormatOption();
        $this->addNoProgressOption();
        $this->addIgnoreParseErrors();
        $this->addPrintFrozenErrors();

        $defaultVersionName = Doctum::$defaultVersionName;
        $this
            ->setName('render')
            ->setDescription('Renders a project')
            ->setHelp(
                <<<EOF
The <info>%command.name%</info> command renders a project as a static set of HTML files:

    <info>php %command.full_name% render config/doctum.php</info>

The <comment>--force</comment> option forces a rebuild (it disables the
incremental rendering algorithm):

    <info>php %command.full_name% render config/doctum.php --force</info>

The <comment>--version</comment> option overrides the version specified
in the configuration:

    <info>php %command.full_name% render config/doctum.php --version=$defaultVersionName</info>
EOF
            );
    }

    /**
     * @see Command
     *
     * @throws \InvalidArgumentException When the target directory does not exist
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->render($this->doctum->getProject());
    }

}
