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

namespace Doctum\Parser;

use Doctum\Project;
use Doctum\Reflection\ClassReflection;

class Transaction
{
    protected $hashes;
    protected $classes;
    protected $visited;
    protected $modified;

    public function __construct(Project $project)
    {
        $this->hashes  = [];
        $this->classes = [];

        foreach ($project->getProjectClasses() as $class) {
            $this->addClass($class);
        }

        $this->visited  = [];
        $this->modified = [];
    }

    public function hasHash($hash)
    {
        if (!array_key_exists($hash, $this->hashes)) {
            return false;
        }

        $this->visited[$hash] = true;

        return true;
    }

    public function getModifiedClasses()
    {
        return $this->modified;
    }

    public function getRemovedClasses()
    {
        $classes = [];
        foreach ($this->hashes as $hash => $c) {
            if (!isset($this->visited[$hash])) {
                $classes = array_merge($classes, $c);
            }
        }

        return array_keys($classes);
    }

    public function addClass(ClassReflection $class)
    {
        $name = $class->getName();
        $hash = $class->getHash();

        if (isset($this->classes[$name])) {
            unset($this->hashes[$this->classes[$name]][$name]);
            if (!$this->hashes[$this->classes[$name]]) {
                unset($this->hashes[$this->classes[$name]]);
            }
        }

        $this->hashes[$hash][$name] = true;
        $this->classes[$name]       = $hash;
        $this->modified[]           = $name;
        $this->visited[$hash]       = true;
    }

}
