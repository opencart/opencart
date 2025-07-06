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

namespace ScssPhp\ScssPhp\Ast\Selector;

use ScssPhp\ScssPhp\Extend\ExtendUtil;
use ScssPhp\ScssPhp\Visitor\SelectorVisitor;
use SourceSpan\FileSpan;

/**
 * Matches any element in the given namespace.
 *
 * @internal
 */
final class UniversalSelector extends SimpleSelector
{
    /**
     * The selector namespace.
     *
     * If this is `null`, this matches all elements in the default namespace. If
     * it's the empty string, this matches all elements that aren't in any
     * namespace. If it's `*`, this matches all elements in any namespace.
     * Otherwise, it matches all elements in the given namespace.
     */
    private readonly ?string $namespace;

    public function __construct(FileSpan $span, ?string $namespace = null)
    {
        $this->namespace = $namespace;
        parent::__construct($span);
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getSpecificity(): int
    {
        return 0;
    }

    public function accept(SelectorVisitor $visitor)
    {
        return $visitor->visitUniversalSelector($this);
    }

    public function unify(array $compound): ?array
    {
        $first = $compound[0] ?? null;

        if ($first instanceof UniversalSelector || $first instanceof TypeSelector) {
            $unified = ExtendUtil::unifyUniversalAndElement($this, $first);

            if ($unified === null) {
                return null;
            }

            $compound[0] = $unified;

            return $compound;
        }

        if (\count($compound) === 1 && $first instanceof PseudoSelector && ($first->isHost() || $first->isHostContext())) {
            return null;
        }

        if ($this->namespace !== null && $this->namespace !== '*') {
            return array_merge([$this], $compound);
        }

        // Not-empty compound list
        if ($first !== null) {
            return $compound;
        }

        return [$this];
    }

    public function isSuperselector(SimpleSelector $other): bool
    {
        if ($this->namespace === '*') {
            return true;
        }

        if ($other instanceof TypeSelector) {
            return $this->namespace === $other->getName()->getNamespace();
        }

        if ($other instanceof UniversalSelector) {
            return $this->namespace === $other->namespace;
        }

        return $this->namespace === null || parent::isSuperselector($other);
    }

    public function equals(object $other): bool
    {
        return $other instanceof UniversalSelector && $other->namespace === $this->namespace;
    }
}
