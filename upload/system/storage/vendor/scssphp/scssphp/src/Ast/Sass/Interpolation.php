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

use ScssPhp\ScssPhp\Parser\InterpolationBuffer;
use SourceSpan\FileSpan;

/**
 * Plain text interpolated with Sass expressions.
 *
 * @internal
 */
final class Interpolation implements SassNode
{
    /**
     * @var list<string|Expression>
     */
    private readonly array $contents;

    private readonly FileSpan $span;

    /**
     * @param list<string|Expression> $contents
     */
    public function __construct(array $contents, FileSpan $span)
    {
        for ($i = 0; $i < \count($contents); $i++) {
            // Dart-sass has a validation on the type of elements here. This is useless for us because phpstan supports union types, unlike the Dart type system

            if ($i != 0 && \is_string($contents[$i]) && \is_string($contents[$i - 1])) {
                throw new \InvalidArgumentException('The contents of an Interpolation may not contain adjacent strings.');
            }
        }

        $this->contents = $contents;
        $this->span = $span;
    }

    /**
     * @return list<string|Expression>
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    /**
     * Returns whether this contains no interpolated expressions.
     */
    public function isPlain(): bool
    {
        return $this->getAsPlain() !== null;
    }

    /**
     * If this contains no interpolated expressions, returns its text contents.
     *
     * Otherwise, returns `null`.
     *
     * @psalm-mutation-free
     */
    public function getAsPlain(): ?string
    {
        if (\count($this->contents) === 0) {
            return '';
        }

        if (\count($this->contents) > 1) {
            return null;
        }

        if (\is_string($this->contents[0])) {
            return $this->contents[0];
        }

        return null;
    }

    /**
     * Returns the plain text before the interpolation, or the empty string.
     */
    public function getInitialPlain(): string
    {
        $first = $this->contents[0] ?? null;

        if (\is_string($first)) {
            return $first;
        }

        return '';
    }

    public function __toString(): string
    {
        return implode('', array_map(fn($value) => \is_string($value) ? $value : '#{' . $value . '}', $this->contents));
    }
}
