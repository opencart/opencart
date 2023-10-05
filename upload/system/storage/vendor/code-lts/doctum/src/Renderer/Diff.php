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

namespace Doctum\Renderer;

use Doctum\Project;

class Diff
{
    /** @var Project */
    protected $project;
    /** @var Index */
    protected $current;
    /** @var string[] */
    protected $versions;
    /** @var string */
    protected $filename;
    /** @var bool */
    protected $alreadyRendered;

    protected $previousNamespaces;
    protected $currentNamespaces;

    /** @var Index */
    private $previous;

    public function __construct(Project $project, string $filename)
    {
        $this->project  = $project;
        $this->current  = new Index($project);
        $this->filename = $filename;

        if (file_exists($filename)) {
            $this->alreadyRendered = true;
            $previous              = $this->readSerializedFile($filename);
            if (null === $previous) {
                $this->alreadyRendered = false;
                $this->previous        = new Index();
            } else {
                $this->previous = $previous;
            }
        } else {
            $this->alreadyRendered = false;
            $this->previous        = new Index();
        }

        $this->previousNamespaces = $this->previous->getNamespaces();
        $this->currentNamespaces  = $this->current->getNamespaces();
    }

    protected function readSerializedFile(string $filename): ?Index
    {
        $contents = file_get_contents($filename);
        if ($contents === false) {
            return null;
        }
        $contents = @unserialize($contents);
        return $contents === false ? null : $contents;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->areVersionsModified() && (0 == count($this->getModifiedClasses()) + count($this->getRemovedClasses()));
    }

    /**
     * @return void
     */
    public function save()
    {
        file_put_contents($this->filename, serialize($this->current));
    }

    /**
     * @return bool
     */
    public function isAlreadyRendered()
    {
        return $this->alreadyRendered;
    }

    /**
     * @return bool
     */
    public function areVersionsModified()
    {
        $versions = [];
        foreach ($this->project->getVersions() as $version) {
            $versions[] = (string) $version;
        }

        return $versions != $this->previous->getVersions();
    }

    public function getModifiedNamespaces()
    {
        return array_diff($this->currentNamespaces, $this->previousNamespaces);
    }

    public function getRemovedNamespaces()
    {
        return array_diff($this->previousNamespaces, $this->currentNamespaces);
    }

    /**
     * @return \Doctum\Reflection\ClassReflection[]
     */
    public function getModifiedClasses()
    {
        $classes = [];
        foreach ($this->current->getClasses() as $class => $hash) {
            if ($hash !== $this->previous->getHash($class)) {
                $classes[] = $this->project->getClass($class);
            }
        }

        return $classes;
    }

    public function getRemovedClasses()
    {
        return array_diff(array_keys($this->previous->getClasses()), array_keys($this->current->getClasses()));
    }

}
