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

class LazyClassReflection extends ClassReflection
{
    protected $loaded = false;

    public function __construct($name)
    {
        parent::__construct($name, -1);
    }

    public function isProjectClass(): bool
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::isProjectClass();
    }

    public function getShortDesc()
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getShortDesc();
    }

    public function setShortDesc($shortDesc)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function getLongDesc()
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getLongDesc();
    }

    public function setLongDesc($longDesc)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function getHint()
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getHint();
    }

    public function setHint($hint)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function isAbstract(): bool
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::isAbstract();
    }

    public function isFinal(): bool
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::isFinal();
    }

    public function getFile()
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getFile();
    }

    public function setFile($file)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    /**
     * {@inheritDoc}
     */
    public function setModifiers(int $modifiers): void
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function addProperty(PropertyReflection $property)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function getProperties($deep = false)
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getProperties($deep);
    }

    public function setProperties($properties)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function addMethod(MethodReflection $method)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function getParentMethod($name)
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getParentMethod($name);
    }

    public function getMethods($deep = false)
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getMethods($deep);
    }

    public function setMethods($methods)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function addInterface($interface)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function getInterfaces($deep = false)
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getInterfaces($deep);
    }

    public function setParent($parent)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function getParent($deep = false)
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getParent($deep);
    }

    public function setInterface($boolean)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    public function isInterface()
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::isInterface();
    }

    public function isException()
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::isException();
    }

    public function getAliases()
    {
        if (false === $this->loaded) {
            $this->load();
        }

        return parent::getAliases();
    }

    public function setAliases($aliases)
    {
        throw new \LogicException('A LazyClassReflection instance is read-only.');
    }

    protected function load()
    {
        $class = $this->project->loadClass($this->name);

        if (null === $class) {
            $this->projectClass = false;
        } else {
            foreach (array_keys(get_class_vars(ClassReflection::class)) as $property) {
                $this->$property = $class->$property;
            }
        }

        $this->loaded = true;
    }

}
