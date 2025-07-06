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
     * Creates a new {@see Interpolation} by concatenating a sequence of strings,
     * {@see Expression}s, or nested {@see Interpolation}s.
     *
     * @param array<string|Expression|Interpolation> $contents
     */
    public static function concat(array $contents, FileSpan $span): Interpolation
    {
        $buffer = new InterpolationBuffer();

        foreach ($contents as $element) {
            if (\is_string($element)) {
                $buffer->write($element);
            } elseif ($element instanceof Expression) {
                $buffer->add($element);
            } elseif ($element instanceof Interpolation) {
                $buffer->addInterpolation($element);
            } else {
                throw new \InvalidArgumentException(sprintf('The elements in $contents may only contains strings, Expressions, or Interpolations, "%s" given.', get_debug_type($element)));
            }
        }

        return $buffer->buildInterpolation($span);
    }

    /**
     * @param list<string|Expression> $contents
     */
    public function __construct(array $contents, FileSpan $span)
    {
        for ($i = 0; $i < \count($contents); $i++) {
            if (!\is_string($contents[$i]) && !$contents[$i] instanceof Expression) {
                throw new \TypeError('The contents of an Interpolation may only contain strings or Expression instances.');
            }

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
