<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Value;

use ScssPhp\ScssPhp\Collection\Map;
use ScssPhp\ScssPhp\Visitor\ValueVisitor;

/**
 * A SassScript map.
 */
final class SassMap extends Value
{
    /**
     * @var Map<Value>
     */
    private readonly Map $contents;

    /**
     * @param Map<Value> $contents
     */
    private function __construct(Map $contents)
    {
        $this->contents = Map::unmodifiable($contents);
    }

    public static function createEmpty(): SassMap
    {
        return new self(new Map());
    }

    /**
     * @param Map<Value> $contents
     */
    public static function create(Map $contents): SassMap
    {
        return new self($contents);
    }

    /**
     * The returned Map is unmodifiable.
     *
     * @return Map<Value>
     */
    public function getContents(): Map
    {
        return $this->contents;
    }

    public function getSeparator(): ListSeparator
    {
        return \count($this->contents) === 0 ? ListSeparator::UNDECIDED : ListSeparator::COMMA;
    }

    public function asList(): array
    {
        $result = [];

        foreach ($this->contents as $key => $value) {
            $result[] = new SassList([$key, $value], ListSeparator::SPACE);
        }

        return $result;
    }

    protected function getLengthAsList(): int
    {
        return \count($this->contents);
    }

    public function accept(ValueVisitor $visitor)
    {
        return $visitor->visitMap($this);
    }

    public function assertMap(?string $name = null): SassMap
    {
        return $this;
    }

    public function tryMap(): ?SassMap
    {
        return $this;
    }

    public function equals(object $other): bool
    {
        if ($other instanceof SassList) {
            return \count($this->contents) === 0 && \count($other->asList()) === 0;
        }

        if (!$other instanceof SassMap) {
            return false;
        }

        if ($this->contents === $other->contents) {
            return true;
        }

        if (\count($this->contents) !== \count($other->contents)) {
            return false;
        }

        foreach ($this->contents as $key => $value) {
            $otherValue = $other->contents->get($key);

            if ($otherValue === null) {
                return false;
            }

            if (!$value->equals($otherValue)) {
                return false;
            }
        }

        return true;
    }
}
