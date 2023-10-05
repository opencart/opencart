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

namespace Doctum\Version;

use Doctum\Project;

/**
 * @implements \Iterator<Version>
 */
abstract class VersionCollection implements \Iterator, \Countable
{
    /**
     * @var Version[]|mixed
     */
    protected $versions = [];

    /**
     * @var int
     */
    protected $indice;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @phpstan-param array|Version $versions
     */
    public function __construct($versions)
    {
        $this->add($versions);
    }

    /**
     * @phpstan-return void
     */
    abstract protected function switchVersion(Version $version);

    /**
     * @phpstan-return mixed
     */
    public static function create()
    {
        $r = new \ReflectionClass(get_called_class());

        return $r->newInstanceArgs(func_get_args());
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @param string[]|string|Version $version
     * @param string               $longname
     * @phpstan-return self
     */
    public function add($version, $longname = null)
    {
        if (is_array($version)) {
            foreach ($version as $v) {
                $this->add($v);
            }
        } else {
            if (!$version instanceof Version) {
                $version = new Version($version, $longname);
            }

            $this->versions[] = $version;
        }

        return $this;
    }

    /**
     * @return Version[]
     */
    public function getVersions(): array
    {
        return $this->versions;
    }

    public function key(): int
    {
        return $this->indice;
    }

    public function current(): Version
    {
        return $this->versions[$this->indice];
    }

    public function next(): void
    {
        ++$this->indice;
    }

    public function rewind(): void
    {
        $this->indice = 0;
    }

    public function valid(): bool
    {
        if ($this->indice < count($this->versions)) {
            $this->switchVersion($this->current());

            return true;
        }

        return false;
    }

    public function count(): int
    {
        return count($this->versions);
    }

}
