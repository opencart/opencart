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

use Doctum\Project;

class ConstantReflection extends Reflection
{
    protected $class;

    public function __toString()
    {
        return $this->class . '::' . $this->name;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass(ClassReflection $class)
    {
        $this->class = $class;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'line' => $this->line,
            'short_desc' => $this->shortDesc,
            'long_desc' => $this->longDesc,
            'modifiers' => $this->modifiers,
            'tags' => $this->tags,
        ];
    }

    /**
     * @return self
     */
    public static function fromArray(Project $project, array $array)
    {
        $constant            = new self($array['name'], $array['line']);
        $constant->shortDesc = $array['short_desc'];
        $constant->longDesc  = $array['long_desc'];
        $constant->modifiers = $array['modifiers'] ?? 0;// New in 5.4.0
        $constant->tags      = $array['tags'] ?? [];// New in 5.4.0

        return $constant;
    }

}
