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

class PropertyReflection extends Reflection
{
    protected $class;
    protected $default;
    /** @var bool */
    protected $isWriteOnly = false;

    public function __toString()
    {
        return $this->class . '::$' . $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setModifiers(int $modifiers): void
    {
        // if no modifiers, property is public
        if (0 === ($modifiers & self::VISIBILITY_MODIFER_MASK)) {
            $modifiers |= self::MODIFIER_PUBLIC;
        }

        $this->modifiers = $modifiers;
    }

    public function setWriteOnly(bool $isWriteOnly): void
    {
        $this->isWriteOnly = $isWriteOnly;
    }

    public function isWriteOnly(): bool
    {
        return $this->isWriteOnly;
    }

    public function setDefault($default): void
    {
        $this->default = $default;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass(ClassReflection $class): void
    {
        $this->class = $class;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'line' => $this->line,
            'short_desc' => $this->shortDesc,
            'long_desc' => $this->longDesc,
            'hint' => $this->hint,
            'hint_desc' => $this->hintDesc,
            'tags' => $this->tags,
            'modifiers' => $this->modifiers,
            'default' => $this->default,
            'errors' => $this->errors,
            'is_read_only' => $this->isReadOnly(),
            'is_write_only' => $this->isWriteOnly(),
        ];
    }

    /**
     * @return self
     */
    public static function fromArray(Project $project, array $array)
    {
        $property            = new self($array['name'], $array['line']);
        $property->shortDesc = $array['short_desc'];
        $property->longDesc  = $array['long_desc'];
        $property->hint      = $array['hint'];
        $property->hintDesc  = $array['hint_desc'];
        $property->tags      = $array['tags'];
        $property->modifiers = $array['modifiers'];
        $property->default   = $array['default'];
        $property->errors    = $array['errors'];

        if (isset($array['is_read_only'])) {// New in 5.4.0
            $property->setReadOnly($array['is_read_only']);
        }

        if (isset($array['is_write_only'])) {// New in 5.4.0
            $property->setWriteOnly($array['is_write_only']);
        }

        return $property;
    }

}
