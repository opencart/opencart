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

use Doctum\Parser\ClassVisitorInterface;
use Doctum\Reflection\ClassReflection;

class InheritdocClassVisitor implements ClassVisitorInterface
{

    public function visit(ClassReflection $class)
    {
        $modified = false;
        foreach ($class->getMethods() as $name => $method) {
            if (!$parentMethod = $class->getParentMethod($name)) {
                continue;
            }

            foreach ($method->getParameters() as $name => $parameter) {
                if (!$parentParameter = $parentMethod->getParameter($name)) {
                    continue;
                }

                if ($parameter->getShortDesc() != $parentParameter->getShortDesc()) {
                    $parameter->setShortDesc($parentParameter->getShortDesc());
                    $modified = true;
                }

                if ($parameter->getHint() != $parentParameter->getRawHint()) {
                    // FIXME: should test for a raw hint from tags, not the one from PHP itself
                    $parameter->setHint($parentParameter->getRawHint());
                    $modified = true;
                }
            }

            if ($method->getHint() != $parentMethod->getRawHint()) {
                $method->setHint($parentMethod->getRawHint());
                $modified = true;
            }

            if ($method->getHintDesc() != $parentMethod->getHintDesc()) {
                $method->setHintDesc($parentMethod->getHintDesc());
                $modified = true;
            }

            $shortDesc = $method->getShortDesc() ?? '';
            if ('{@inheritdoc}' === strtolower(trim($shortDesc)) || !$method->getDocComment()) {
                if ($shortDesc != $parentMethod->getShortDesc()) {
                    $method->setShortDesc($parentMethod->getShortDesc());
                    $modified = true;
                }

                if ($method->getLongDesc() != $parentMethod->getLongDesc()) {
                    $method->setLongDesc($parentMethod->getLongDesc());
                    $modified = true;
                }

                if ($method->getExceptions() != $parentMethod->getRawExceptions()) {
                    $method->setExceptions($parentMethod->getRawExceptions());
                    $modified = true;
                }
            }
        }

        return $modified;
    }

}
