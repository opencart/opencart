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

namespace Doctum\Parser\ClassVisitor;

use Doctum\Reflection\ClassReflection;
use Doctum\Parser\ClassVisitorInterface;
use Doctum\RemoteRepository\AbstractRemoteRepository;

class ViewSourceClassVisitor implements ClassVisitorInterface
{
    /** @var AbstractRemoteRepository */
    protected $remoteRepository;

    public function __construct(AbstractRemoteRepository $remoteRepository)
    {
        $this->remoteRepository = $remoteRepository;
    }

    public function visit(ClassReflection $class)
    {
        $filePath = $this->remoteRepository->getRelativePath($class->getFile());

        if ($class->getRelativeFilePath() != $filePath) {
            $class->setRelativeFilePath($filePath);

            return true;
        }

        return false;
    }

}
