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

namespace Doctum\Parser\Filter;

use Doctum\Reflection\MethodReflection;
use Doctum\Reflection\PropertyReflection;

class DefaultFilter extends TrueFilter
{

    public function acceptMethod(MethodReflection $method)
    {
        return !$method->isPrivate();
    }

    public function acceptProperty(PropertyReflection $property)
    {
        return !$property->isPrivate();
    }

}
