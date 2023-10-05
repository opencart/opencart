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

namespace Doctum\Parser\FunctionVisitor;

use Doctum\Reflection\FunctionReflection;
use Doctum\Parser\FunctionVisitorInterface;
use Doctum\RemoteRepository\AbstractRemoteRepository;

class ViewSourceFunctionVisitor implements FunctionVisitorInterface
{
    /** @var AbstractRemoteRepository */
    protected $remoteRepository;

    public function __construct(AbstractRemoteRepository $remoteRepository)
    {
        $this->remoteRepository = $remoteRepository;
    }

    public function visit(FunctionReflection $class): bool
    {
        $filePath = $this->remoteRepository->getRelativePath($class->getFile() ?? '');

        if ($class->getRelativeFilePath() !== $filePath) {
            $class->setRelativeFilePath($filePath);

            return true;
        }

        return false;
    }

}
