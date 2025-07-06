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

namespace ScssPhp\ScssPhp\Evaluation;

use ScssPhp\ScssPhp\Ast\Sass\Statement\Stylesheet;
use ScssPhp\ScssPhp\Importer\Importer;

/**
 * The result of loading a stylesheet via {@see EvaluateVisitor::loadStylesheet}.
 *
 * @internal
 */
final class LoadedStylesheet
{
    /**
     * The stylesheet itself.
     */
    private readonly Stylesheet $stylesheet;

    private readonly Importer $importer;

    /**
     * Whether this load counts as a dependency.
     *
     * That is, whether this was (transitively) loaded through a load path or
     * importer rather than relative to the entrypoint.
     */
    private readonly bool $dependency;

    public function __construct(Stylesheet $stylesheet, Importer $importer, bool $dependency)
    {
        $this->stylesheet = $stylesheet;
        $this->importer = $importer;
        $this->dependency = $dependency;
    }

    public function getStylesheet(): Stylesheet
    {
        return $this->stylesheet;
    }

    public function getImporter(): Importer
    {
        return $this->importer;
    }

    public function isDependency(): bool
    {
        return $this->dependency;
    }
}
