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

class ClassReflection extends Reflection
{
    private const CATEGORY_CLASS     = 1;
    private const CATEGORY_INTERFACE = 2;
    private const CATEGORY_TRAIT     = 3;

    /** @var Project */
    protected $project;

    protected $hash;
    /** @var string|null */
    protected $namespace;
    /** @var PropertyReflection[] */
    protected $properties = [];
    /** @var MethodReflection[] */
    protected $methods    = [];
    protected $interfaces = [];
    protected $constants  = [];
    protected $traits     = [];
    protected $parent;
    protected $file;
    protected $relativeFilePath;
    protected $category     = self::CATEGORY_CLASS;
    protected $projectClass = true;
    protected $aliases      = [];
    protected $fromCache    = false;

    public function __toString()
    {
        return $this->name;
    }

    public function getClass()
    {
        return $this;
    }

    public function getCategoryId(): int
    {
        return $this->category;
    }

    public function isProjectClass(): bool
    {
        return $this->projectClass;
    }

    public function isPhpClass(): bool
    {
        return isset(Project::$phpInternalClasses[strtolower($this->name)]);
    }

    public function setName(string $name): void
    {
        parent::setName(ltrim($name, '\\'));
    }

    public function getShortName(): string
    {
        $pos = strrpos($this->name, '\\');
        if ($pos !== false) {
            return substr($this->name, $pos + 1);
        }

        return $this->name;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function setRelativeFilePath($relativeFilePath)
    {
        $this->relativeFilePath = $relativeFilePath;
    }

    public function getRelativeFilePath()
    {
        return $this->relativeFilePath;
    }

    public function getSourcePath($line = null)
    {
        if (null === $this->relativeFilePath) {
            return '';
        }

        return $this->project->getViewSourceUrl($this->relativeFilePath, $line);
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return void
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = ltrim($namespace, '\\');
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function addProperty(PropertyReflection $property)
    {
        $this->properties[$property->getName()] = $property;
        $property->setClass($this);
    }

    public function getProperties($deep = false)
    {
        if (false === $deep) {
            return $this->properties;
        }

        $properties = [];
        if ($this->getParent()) {
            foreach ($this->getParent()->getProperties(true) as $name => $property) {
                $properties[$name] = $property;
            }
        }

        foreach ($this->getTraits(true) as $trait) {
            foreach ($trait->getProperties(true) as $name => $property) {
                $properties[$name] = $property;
            }
        }

        foreach ($this->properties as $name => $property) {
            $properties[$name] = $property;
        }

        return $properties;
    }

    /**
     * Can be any iterator (so that we can lazy-load the properties)
     *
     * @param PropertyReflection[] $properties
     * @return void
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return void
     */
    public function addConstant(ConstantReflection $constant)
    {
        $this->constants[$constant->getName()] = $constant;
        $constant->setClass($this);
    }

    public function getConstants($deep = false)
    {
        if (false === $deep) {
            return $this->constants;
        }

        $constants = [];
        if ($this->getParent()) {
            foreach ($this->getParent()->getConstants(true) as $name => $constant) {
                $constants[$name] = $constant;
            }
        }

        foreach ($this->constants as $name => $constant) {
            $constants[$name] = $constant;
        }

        return $constants;
    }

    public function setConstants($constants)
    {
        $this->constants = $constants;
    }

    /**
     * @return void
     */
    public function addMethod(MethodReflection $method)
    {
        $this->methods[$method->getName()] = $method;
        $method->setClass($this);
    }

    /**
     * @return MethodReflection|false False if not found
     */
    public function getMethod($name)
    {
        return $this->methods[$name] ?? false;
    }

    public function getParentMethod($name)
    {
        if ($this->getParent()) {
            foreach ($this->getParent()->getMethods(true) as $n => $method) {
                if ($name == $n) {
                    return $method;
                }
            }
        }

        foreach ($this->getInterfaces(true) as $interface) {
            foreach ($interface->getMethods(true) as $n => $method) {
                if ($name == $n) {
                    return $method;
                }
            }
        }
    }

    public function getMethods($deep = false)
    {
        if (false === $deep) {
            return $this->methods;
        }

        $methods = [];
        if ($this->isInterface()) {
            foreach ($this->getInterfaces(true) as $interface) {
                foreach ($interface->getMethods(true) as $name => $method) {
                    $methods[$name] = $method;
                }
            }
        }

        if ($this->getParent()) {
            foreach ($this->getParent()->getMethods(true) as $name => $method) {
                $methods[$name] = $method;
            }
        }

        foreach ($this->getTraits(true) as $trait) {
            foreach ($trait->getMethods(true) as $name => $method) {
                $methods[$name] = $method;
            }
        }

        foreach ($this->methods as $name => $method) {
            $methods[$name] = $method;
        }

        return $methods;
    }

    /**
     * @return void
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;
    }

    /**
     * @param \PhpParser\Node\Stmt\Interface_ $interface
     * @return void
     */
    public function addInterface($interface)
    {
        $this->interfaces[$interface] = $interface;
    }

    /**
     * @return ClassReflection[]
     */
    public function getInterfaces($deep = false)
    {
        $interfaces = [];
        foreach ($this->interfaces as $interface) {
            $interfaces[] = $this->project->getClass($interface);
        }

        if (false === $deep) {
            return $interfaces;
        }

        $allInterfaces = $interfaces;
        foreach ($interfaces as $interface) {
            $allInterfaces = array_merge($allInterfaces, $interface->getInterfaces(true));
        }

        if ($parent = $this->getParent()) {
            $allInterfaces = array_merge($allInterfaces, $parent->getInterfaces(true));
        }

        return $allInterfaces;
    }

    /**
     * @param \PhpParser\Node\Stmt\Trait_ $trait
     * @return void
     */
    public function addTrait($trait)
    {
        $this->traits[$trait] = $trait;
    }

    /**
     * @return ClassReflection[]
     */
    public function getTraits($deep = false)
    {
        $traits = [];
        foreach ($this->traits as $trait) {
            $traits[] = $this->project->getClass($trait);
        }

        if (false === $deep) {
            return $traits;
        }

        $allTraits = $traits;
        foreach ($traits as $trait) {
            $allTraits = array_merge($allTraits, $trait->getTraits(true));
        }

        if ($parent = $this->getParent()) {
            $allTraits = array_merge($allTraits, $parent->getTraits(true));
        }

        return $allTraits;
    }

    /**
     * @return void
     */
    public function setTraits($traits)
    {
        $this->traits = $traits;
    }

    /**
     * @return void
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent($deep = false)
    {
        if (!$this->parent) {
            return $deep ? [] : null;
        }

        $parent = $this->project->getClass($this->parent);

        if (false === $deep) {
            return $parent;
        }

        return array_merge([$parent], $parent->getParent(true));
    }

    /**
     * @return void
     */
    public function setInterface($boolean)
    {
        $this->category = $boolean ? self::CATEGORY_INTERFACE : self::CATEGORY_CLASS;
    }

    /**
     * @return bool
     */
    public function isInterface()
    {
        return self::CATEGORY_INTERFACE === $this->category;
    }

    /**
     * @return void
     */
    public function setTrait($boolean)
    {
        $this->category = $boolean ? self::CATEGORY_TRAIT : self::CATEGORY_CLASS;
    }

    /**
     * @return bool
     */
    public function isTrait()
    {
        return self::CATEGORY_TRAIT === $this->category;
    }

    /**
     * @return void
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function hasMixins(): bool
    {
        return ! empty($this->getTags('mixin'));
    }

    /**
     * @return array<int,array<string,ClassReflection>>
     */
    public function getMixins(): array
    {
        $mixins = [];
        foreach ($this->getTags('mixin') as $mixin) {
            $mixins[] = [
                'class' => new ClassReflection($mixin[0], -1),
            ];
        }
        return $mixins;
    }

    /**
     * @return bool
     */
    public function isException()
    {
        $parent = $this;
        while ($parent = $parent->getParent()) {
            if ('Exception' == $parent->getName()) {
                return true;
            }
        }

        return false;
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @return void
     */
    public function setAliases($aliases)
    {
        $this->aliases = $aliases;
    }

    public function isFromCache()
    {
        return $this->fromCache;
    }

    /**
     * @return void
     */
    public function notFromCache()
    {
        $this->fromCache = false;
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
            'tags' => $this->tags,
            'namespace' => $this->namespace,
            'file' => $this->file,
            'relative_file' => $this->relativeFilePath,
            'hash' => $this->hash,
            'parent' => $this->parent,
            'modifiers' => $this->modifiers,
            'is_trait' => $this->isTrait(),
            'is_interface' => $this->isInterface(),
            'is_read_only' => $this->isReadOnly(),
            'aliases' => $this->aliases,
            'errors' => $this->errors,
            'interfaces' => $this->interfaces,
            'traits' => $this->traits,
            'properties' => array_map(
                static function ($property) {
                    return $property->toArray();
                },
                $this->properties
            ),
            'methods' => array_map(
                static function ($method) {
                    return $method->toArray();
                },
                $this->methods
            ),
            'constants' => array_map(
                static function ($constant) {
                    return $constant->toArray();
                },
                $this->constants
            ),
        ];
    }

    /**
     * @return self
     */
    public static function fromArray(Project $project, array $array)
    {
        $class                   = new self($array['name'], $array['line']);
        $class->shortDesc        = $array['short_desc'];
        $class->longDesc         = $array['long_desc'];
        $class->hint             = $array['hint'];
        $class->tags             = $array['tags'];
        $class->namespace        = $array['namespace'];
        $class->hash             = $array['hash'];
        $class->file             = $array['file'];
        $class->relativeFilePath = $array['relative_file'];
        $class->modifiers        = $array['modifiers'];
        $class->fromCache        = true;

        if (isset($array['is_read_only'])) {// New in 5.4.0
            $class->setReadOnly($array['is_read_only']);
        }

        if ($array['is_interface']) {
            $class->setInterface(true);
        }
        if ($array['is_trait']) {
            $class->setTrait(true);
        }
        $class->aliases    = $array['aliases'];
        $class->errors     = $array['errors'];
        $class->parent     = $array['parent'];
        $class->interfaces = $array['interfaces'];
        $class->constants  = $array['constants'];
        $class->traits     = $array['traits'];

        $class->setProject($project);

        foreach ($array['methods'] as $method) {
            $class->addMethod(MethodReflection::fromArray($project, $method));
        }

        foreach ($array['properties'] as $property) {
            $class->addProperty(PropertyReflection::fromArray($project, $property));
        }

        foreach ($array['constants'] as $constant) {
            $class->addConstant(ConstantReflection::fromArray($project, $constant));
        }

        return $class;
    }

    /**
     * @return void
     */
    public function sortInterfaces($sort)
    {
        if (is_callable($sort)) {
            uksort($this->interfaces, $sort);
        } else {
            ksort($this->interfaces);
        }
    }

}
