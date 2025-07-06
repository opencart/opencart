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

namespace ScssPhp\ScssPhp\Parser;

use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Interpolation;
use SourceSpan\FileSpan;

/**
 * A buffer that iteratively builds up an {@see Interpolation}.
 *
 * @internal
 */
final class InterpolationBuffer
{
    private string $text = '';

    /**
     * @var list<string|Expression>
     */
    private array $contents = [];

    /**
     * Returns the substring of the buffer string after the last interpolation.
     */
    public function getTrailingString(): string
    {
        return $this->text;
    }

    public function isEmpty(): bool
    {
        return $this->text === '' && \count($this->contents) === 0;
    }

    public function write(string $string): void
    {
        $this->text .= $string;
    }

    public function add(Expression $expression): void
    {
        $this->flushText();
        $this->contents[] = $expression;
    }

    public function addInterpolation(Interpolation $interpolation): void
    {
        $contents = $interpolation->getContents();

        if (empty($contents)) {
            return;
        }

        if (is_string($contents[0])) {
            $this->text .= $contents[0];

            array_shift($contents);
        }

        $this->flushText();

        foreach ($contents as $content) {
            $this->contents[] = $content;
        }

        if (\is_string($this->contents[\count($this->contents) - 1])) {
            $this->text = $this->contents[\count($this->contents) - 1];
            array_pop($this->contents);
        }
    }

    public function buildInterpolation(FileSpan $span): Interpolation
    {
        $contents = $this->contents;

        if ($this->text !== '') {
            $contents[] = $this->text;
        }

        return new Interpolation($contents, $span);
    }

    /**
     * Flushes {@see self::$text} to {@see self::$contents} if necessary.
     */
    private function flushText(): void
    {
        if ($this->text === '') {
            return;
        }

        $this->contents[] = $this->text;
        $this->text = '';
    }
}
