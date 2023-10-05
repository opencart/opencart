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

namespace Doctum\Reflection;

class HintReflection
{
    protected $name;
    protected $array;

    public function __construct($name, $array)
    {
        $this->name  = $name;
        $this->array = $array;
    }

    public function __toString()
    {
        // We're casting name to string, as it can be eg. `ClassReflection` object.
        return (string) $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function isClass()
    {
        return $this->name instanceof ClassReflection;
    }

    public function isArray()
    {
        return $this->array;
    }

    public function setArray($boolean)
    {
        $this->array = (bool) $boolean;
    }

}
