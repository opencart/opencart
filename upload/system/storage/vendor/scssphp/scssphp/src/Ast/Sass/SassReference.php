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

namespace ScssPhp\ScssPhp\Ast\Sass;

use SourceSpan\FileSpan;

/**
 * A common interface for any node that references a Sass member.
 *
 * @internal
 */
interface SassReference extends SassNode
{
    /**
     * The namespace of the member being referenced, or `null` if it's referenced
     * without a namespace.
     */
    public function getNamespace(): ?string;

    /**
     * The name of the member being referenced, with underscores converted to
     * hyphens.
     *
     * This does not include the `$` for variables.
     */
    public function getName(): string;

    /**
     * The span containing this reference's name.
     *
     * For variables, this should include the `$`.
     */
    public function getNameSpan(): FileSpan;

    /**
     * The span containing this reference's namespace, null if {@see getNamespace} is
     * null.
     */
    public function getNamespaceSpan(): ?FileSpan;
}
