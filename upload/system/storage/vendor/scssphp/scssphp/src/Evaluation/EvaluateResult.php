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

use ScssPhp\ScssPhp\Ast\Css\CssStylesheet;

/**
 * The result of compiling a Sass document to a CSS tree, along with metadata
 * about the compilation process.
 *
 * @internal
 */
final class EvaluateResult
{
    private readonly CssStylesheet $stylesheet;

    /**
     * @var list<string>
     */
    private readonly array $loadedUrls;

    /**
     * @param list<string> $loadedUrls
     */
    public function __construct(CssStylesheet $stylesheet, array $loadedUrls)
    {
        $this->stylesheet = $stylesheet;
        $this->loadedUrls = $loadedUrls;
    }

    public function getStylesheet(): CssStylesheet
    {
        return $this->stylesheet;
    }

    /**
     * @return list<string>
     */
    public function getLoadedUrls(): array
    {
        return $this->loadedUrls;
    }
}
