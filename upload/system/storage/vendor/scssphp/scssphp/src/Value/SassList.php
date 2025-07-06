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

use JiriPudil\SealedClasses\Sealed;
use ScssPhp\ScssPhp\Visitor\ValueVisitor;

/**
 * A SassScript list.
 */
#[Sealed(permits: [SassArgumentList::class])]
class SassList extends Value
{
    /**
     * @var list<Value>
     */
    private readonly array $contents;

    private readonly ListSeparator $separator;

    private readonly bool $brackets;

    public static function createEmpty(ListSeparator $separator = ListSeparator::UNDECIDED, bool $brackets = false): SassList
    {
        return new self(array(), $separator, $brackets);
    }

    /**
     * @param list<Value> $contents
     */
    public function __construct(array $contents, ListSeparator $separator, bool $brackets = false)
    {
        if ($separator === ListSeparator::UNDECIDED && count($contents) > 1) {
            throw new \InvalidArgumentException('A list with more than one element must have an explicit separator.');
        }

        $this->contents = $contents;
        $this->separator = $separator;
        $this->brackets = $brackets;
    }

    public function getSeparator(): ListSeparator
    {
        return $this->separator;
    }

    public function hasBrackets(): bool
    {
        return $this->brackets;
    }

    public function isBlank(): bool
    {
        if ($this->brackets) {
            return false;
        }

        foreach ($this->contents as $element) {
            if (!$element->isBlank()) {
                return false;
            }
        }

        return true;
    }

    public function asList(): array
    {
        return $this->contents;
    }

    protected function getLengthAsList(): int
    {
        return \count($this->contents);
    }

    public function accept(ValueVisitor $visitor)
    {
        return $visitor->visitList($this);
    }

    public function assertMap(?string $name = null): SassMap
    {
        if (\count($this->contents) === 0) {
            return SassMap::createEmpty();
        }

        return parent::assertMap($name);
    }

    public function tryMap(): ?SassMap
    {
        if (\count($this->contents) === 0) {
            return SassMap::createEmpty();
        }

        return null;
    }

    public function equals(object $other): bool
    {
        if ($other instanceof SassMap) {
            return \count($this->contents) === 0 && \count($other->asList()) === 0;
        }

        if (!$other instanceof SassList) {
            return false;
        }

        if ($this->separator !== $other->separator || $this->brackets !== $other->brackets) {
            return false;
        }

        $otherContent = $other->contents;
        $length = \count($this->contents);

        if ($length !== \count($otherContent)) {
            return false;
        }

        for ($i = 0; $i < $length; ++$i) {
            if (!$this->contents[$i]->equals($otherContent[$i])) {
                return false;
            }
        }

        return true;
    }
}
