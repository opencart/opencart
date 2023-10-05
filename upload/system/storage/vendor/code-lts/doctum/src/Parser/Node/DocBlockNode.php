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

namespace Doctum\Parser\Node;

class DocBlockNode
{
    /** @var string */
    protected $shortDesc;
    /** @var string */
    protected $longDesc;
    /** @var array<string,array> */
    protected $tags = [];
    /** @var string[] */
    protected $errors = [];

    /**
     * Add a tag
     *
     * @param string  $key
     * @param array[] $value
     */
    public function addTag($key, $value): void
    {
        $this->tags[$key][] = $value;
    }

    /**
     * @return array<string,array>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return array<string,array>
     */
    public function getOtherTags(): array
    {
        $tags = $this->tags;
        unset($tags['param'], $tags['return'], $tags['var'], $tags['throws']);

        foreach ($tags as $name => $values) {
            foreach ($values as $i => $value) {
                // For 'see' tag we try to maintain backwards compatibility
                // by returning only a part of the value.
                if ($name === 'see') {
                    $value = $value[0];
                }

                $tags[$name][$i] = is_string($value) ? explode(' ', $value) : $value;
            }
        }

        return $tags;
    }

    /**
     * @param string $key
     * @return mixed[]
     */
    public function getTag($key): array
    {
        return $this->tags[$key] ?? [];
    }

    /**
     * @return string
     */
    public function getShortDesc()
    {
        return $this->shortDesc;
    }

    /**
     * @return string
     */
    public function getLongDesc()
    {
        return $this->longDesc;
    }

    /**
     * @param string $shortDesc
     */
    public function setShortDesc($shortDesc): void
    {
        $this->shortDesc = $shortDesc;
    }

    /**
     * @param string $longDesc
     */
    public function setLongDesc($longDesc): void
    {
        $this->longDesc = $longDesc;
    }

    public function getDesc(): string
    {
        return $this->shortDesc . "\n\n" . $this->longDesc;
    }

    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

}
