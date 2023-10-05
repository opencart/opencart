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

class Version
{

    /**
     * @var bool
     */
    protected $isFrozen;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $longname;

    public function __construct(string $name, ?string $longname = null)
    {
        $this->name     = $name;
        $this->longname = null === $longname ? $name : $longname;
        $this->isFrozen = false;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLongName(): ?string
    {
        return $this->longname;
    }

    public function setFrozen(bool $isFrozen): void
    {
        $this->isFrozen = $isFrozen;
    }

    public function isFrozen(): bool
    {
        return $this->isFrozen;
    }

}
