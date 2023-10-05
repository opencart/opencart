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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends Command
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

        $this
            ->setName('update')
            ->setDescription('Parses then renders a project')
            ->setHelp(
                <<<EOF
The <info>%command.name%</info> command parses and renders a project:

    <info>php %command.full_name% config/symfony.php</info>

This command is the same as running the parse command followed
by the render command.
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
        return $this->update($this->doctum->getProject());
    }

}
