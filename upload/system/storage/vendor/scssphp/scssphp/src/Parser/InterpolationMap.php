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
use ScssPhp\ScssPhp\Util\Character;
use ScssPhp\ScssPhp\Util\IterableUtil;
use SourceSpan\FileLocation;
use SourceSpan\FileSpan;
use SourceSpan\SourceLocation;

/**
 * A class that can map locations in a string generated from an {@see Interpolation}
 * to the original source code in the interpolation.
 *
 * @internal
 */
final class InterpolationMap
{
    private readonly Interpolation $interpolation;

    /**
     * Locations in the generated string.
     *
     * Each of these indicates the location in the generated string that
     * corresponds to the end of the component at the same index of
     * {@see $interpolation->getContents()}. Its length is always one less than
     * {@see $interpolation->getContents()} because the last element always ends the string.
     *
     * @var list<SourceLocation>
     */
    private readonly array $targetLocations;

    /**
     * @param list<SourceLocation> $targetLocations
     */
    public function __construct(Interpolation $interpolation, array $targetLocations)
    {
        $this->interpolation = $interpolation;
        $this->targetLocations = $targetLocations;

        $expectedLocations = max(0, \count($interpolation->getContents()) - 1);
        if (\count($targetLocations) !== $expectedLocations) {
            $interpolationParts = \count($interpolation->getContents());
            throw new \InvalidArgumentException("InterpolationMap must have $expectedLocations targetLocations if the interpolation has $interpolationParts components.");
        }
    }

    public function mapException(FormatException $error): FormatException
    {
        $source = $this->mapSpan($error->getSpan());
        $startIndex = $this->indexInContents($source->getStart());
        $endIndex = $this->indexInContents($source->getEnd());

        if (!IterableUtil::any(array_slice($this->interpolation->getContents(), $startIndex, $endIndex - $startIndex + 1), fn ($content) => $content instanceof Expression)) {
            return new FormatException($error->getMessage(), $source, $error);
        }

        return new MultiSourceFormatException($error->getMessage(), $source, '', ['error in interpolated output' => $error->getSpan()], $error);
    }

    public function mapSpan(FileSpan $target): FileSpan
    {
        $start = $this->mapLocation($target->getStart());
        $end  = $this->mapLocation($target->getEnd());

        if ($start instanceof FileSpan) {
            if ($end instanceof FileSpan) {
                return $start->expand($end);
            }

            return $this->interpolation->getSpan()->getFile()->span($this->expandInterpolationSpanLeft($start->getStart()), $end->getOffset());
        }

        if ($end instanceof FileSpan) {
            return $this->interpolation->getSpan()->getFile()->span($start->getOffset(), $this->expandInterpolationSpanRight($end->getEnd()));
        }

        return $this->interpolation->getSpan()->getFile()->span($start->getOffset(), $end->getOffset());
    }

    /**
     * @return FileSpan|FileLocation
     */
    private function mapLocation(SourceLocation $target): object
    {
        $index = $this->indexInContents($target);

        $components = $this->interpolation->getContents();

        if ($components[$index] instanceof Expression) {
            return $components[$index]->getSpan();
        }

        if ($index === 0) {
            $previousLocation = $this->interpolation->getSpan()->getStart();
        } else {
            $previousComponent = $components[$index - 1];
            \assert($previousComponent instanceof Expression);
            $previousLocation = $this->interpolation->getSpan()->getFile()->location($this->expandInterpolationSpanRight($previousComponent->getSpan()->getEnd()));
        }

        $offsetInString = $target->getOffset() - ($index === 0 ? 0 : $this->targetLocations[$index - 1]->getOffset());

        return $previousLocation->getFile()->location($previousLocation->getOffset() + $offsetInString);
    }

    private function indexInContents(SourceLocation $target): int
    {
        foreach ($this->targetLocations as $i => $location) {
            if ($target->getOffset() < $location->getOffset()) {
                return $i;
            }
        }

        return \count($this->interpolation->getContents()) - 1;
    }

    /**
     * Given the start of a {@see FileSpan} covering an interpolated expression, returns
     * the offset of the interpolation's opening `#`.
     *
     * Note that this can be tricked by a `#{` that appears within a single-line
     * comment before the expression, but since it's only used for error
     * reporting that's probably fine.
     */
    private function expandInterpolationSpanLeft(FileLocation $start): int
    {
        $source = $start->getFile()->getString();
        $i = $start->getOffset() - 1;

        while ($i >= 0) {
            $prev = $source[$i--];

            if ($prev === '{') {
                if ($source[$i] === '#') {
                    break;
                }
            } elseif ($prev === '/') {
                $second = $source[$i--];

                if ($second === '*') {
                    while ($i >= 0) {
                        $char = $source[$i--];

                        if ($char !== '*') {
                            continue;
                        }

                        do {
                            $char = $source[$i--];
                        } while ($char === '*' && $i >= 0);

                        if ($char === '/') {
                            break;
                        }
                    }
                }
            }
        }

        return $i;
    }

    /**
     * Given the end of a {@see FileSpan} covering an interpolated expression, returns
     * the offset of the interpolation's closing `}`.
     */
    private function expandInterpolationSpanRight(FileLocation $end): int
    {
        $source = $end->getFile()->getString();
        $i = $end->getOffset();

        while ($i < \strlen($source)) {
            $next = $source[$i++];

            if ($next === '}') {
                break;
            }

            if ($next === '/') {
                $second = $source[$i++];
                if ($second === '/') {
                    while (!Character::isNewline($source[$i++] ?? null)) {
                        // Move forward
                    }
                } elseif ($second === '*') {
                    while (true) {
                        $char = $source[$i++] ?? null;

                        if ($char !== '*') {
                            continue;
                        }

                        do {
                            $char = $source[$i++] ?? null;
                        } while ($char === '*');

                        if ($char === '/') {
                            break;
                        }
                    }
                }
            }
        }

        return $i;
    }
}
