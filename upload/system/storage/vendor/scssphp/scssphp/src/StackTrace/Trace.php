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

namespace ScssPhp\ScssPhp\StackTrace;

/**
 * A stack trace, comprised of a list of stack frames.
 */
final class Trace
{
    /**
     * @var list<Frame>
     * @readonly
     */
    private readonly array $frames;

    /**
     * @param list<Frame> $frames
     */
    public function __construct(array $frames)
    {
        $this->frames = $frames;
    }

    /**
     * @return list<Frame>
     */
    public function getFrames(): array
    {
        return $this->frames;
    }

    public function getFormattedTrace(): string
    {
        $longest = 0;

        foreach ($this->frames as $frame) {
            $length = \strlen($frame->getLocation());
            $longest = max($longest, $length);
        }

        return implode(array_map(fn(Frame $frame) => str_pad($frame->getLocation(), $longest) . '  ' . $frame->getMember() . "\n", $this->frames));
    }
}
