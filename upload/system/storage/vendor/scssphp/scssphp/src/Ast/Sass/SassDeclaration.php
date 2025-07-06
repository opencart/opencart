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
 * A common interface for any node that declares a Sass member.
 *
 * @internal
 */
interface SassDeclaration extends SassNode
{
    /**
     * The name of the declaration, with underscores converted to hyphens.
     *
     * This does not include the `$` for variables.
     */
    public function getName(): string;

    /**
     * The span containing this declaration's name.
     *
     * This includes the `$` for variables.
     */
    public function getNameSpan(): FileSpan;
}
