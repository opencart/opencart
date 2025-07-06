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

namespace ScssPhp\ScssPhp;

final class CompilationResult
{
    private string $css;

    private ?string $sourceMap;

    /**
     * @var list<string>
     */
    private array $includedFiles;

    /**
     * @param list<string> $includedFiles
     */
    public function __construct(string $css, ?string $sourceMap, array $includedFiles)
    {
        $this->css = $css;
        $this->sourceMap = $sourceMap;
        $this->includedFiles = $includedFiles;
    }

    public function getCss(): string
    {
        return $this->css;
    }

    /**
     * @return list<string>
     */
    public function getIncludedFiles(): array
    {
        return $this->includedFiles;
    }

    /**
     * The sourceMap content, if it was generated
     */
    public function getSourceMap(): ?string
    {
        return $this->sourceMap;
    }
}
