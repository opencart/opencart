<?php

declare(strict_types = 1);

/*
 * This file is part of the Doctum utility.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doctum;

use JsonSerializable;

/**
 * @internal Represents a Tree node
 */
class TreeNode implements JsonSerializable
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $path;

    /** @var int */
    protected $level;

    /** @var TreeNode[]|null */
    protected $children;

    /**
     * @param TreeNode[] $children
     */
    public function __construct(
        int $level,
        string $name,
        string $path,
        ?array $children
    ) {
        $this->level    = $level;
        $this->name     = $name;
        $this->path     = $path;
        $this->children = $children;
    }

    /**
     * @return array<string,mixed>
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        if ($this->children === null || $this->children === []) {
            return [
                'l' => $this->level,
                'n' => $this->name,
                'p' => str_replace('\\', '/', $this->path),
            ];
        }
        return [
            'l' => $this->level,
            'n' => $this->name,
            'p' => str_replace('\\', '/', $this->path),
            'c' => $this->children,
        ];
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function hasChildren(): bool
    {
        return $this->children !== null;
    }

    /**
     * @return TreeNode[]|null
     */
    public function getChildren(): ?array
    {
        return $this->children;
    }

}
