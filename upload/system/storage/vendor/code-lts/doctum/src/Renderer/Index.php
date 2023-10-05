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

class Index implements \Serializable
{
    protected $classes;
    /** @var string[] */
    protected $versions;
    protected $namespaces;

    public function __construct(?Project $project = null)
    {
        $this->classes = [];
        if (null !== $project) {
            foreach ($project->getProjectClasses() as $class) {
                $this->classes[$class->getName()] = $class->getHash();
            }
        }

        $this->versions = [];
        if (null !== $project) {
            foreach ($project->getVersions() as $version) {
                $this->versions[] = (string) $version;
            }
        }

        $this->namespaces = [];
        if (null !== $project) {
            $this->namespaces = $project->getNamespaces();
        }
    }

    /**
     * @return string[]
     */
    public function getVersions()
    {
        return $this->versions;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function getNamespaces()
    {
        return $this->namespaces;
    }

    public function getHash($class)
    {
        return $this->classes[$class] ?? false;
    }

    /**
     * @return array[]
     */
    public function __serialize(): array
    {
        return [$this->classes, $this->versions, $this->namespaces];
    }

    /**
     * @param array[] $data
     */
    public function __unserialize(array $data): void
    {
        [$this->classes, $this->versions, $this->namespaces] = $data;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([$this->classes, $this->versions, $this->namespaces]);
    }

    /**
     * @param string $data
     *
     * @return void
     */
    public function unserialize($data)
    {
        [$this->classes, $this->versions, $this->namespaces] = unserialize($data);
    }

}
