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

use ScssPhp\ScssPhp\Util\Equatable;

/**
 * A [qualified name][].
 *
 * [qualified name]: https://www.w3.org/TR/css3-namespace/#css-qnames
 *
 * @internal
 */
final class QualifiedName implements Equatable
{
    /**
     * The identifier name.
     */
    private readonly string $name;

    /**
     * The namespace name.
     *
     * If this is `null`, {@see name} belongs to the default namespace. If it's the
     * empty string, {@see name} belongs to no namespace. If it's `*`, {@see name} belongs
     * to any namespace. Otherwise, {@see name} belongs to the given namespace.
     */
    private readonly ?string $namespace;

    public function __construct(string $name, ?string $namespace = null)
    {
        $this->name = $name;
        $this->namespace = $namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function __toString(): string
    {
        return $this->namespace === null ? $this->name : $this->namespace . '|' . $this->name;
    }

    public function equals(object $other): bool
    {
        return $other instanceof QualifiedName && $other->name === $this->name && $other->namespace === $this->namespace;
    }
}
