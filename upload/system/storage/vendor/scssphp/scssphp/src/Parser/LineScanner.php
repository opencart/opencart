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

/**
 * A subclass of {@see StringScanner} that tracks line and column information.
 *
 * @internal
 */
final class LineScanner extends StringScanner
{
    /**
     * @var int
     */
    private int $line = 0;

    /**
     * @var int
     */
    private int $column = 0;

    public function getLine(): int
    {
        return $this->line;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * Whether the current position is between a CR character and an LF
     * character.
     */
    private function betweenCRLF(): bool
    {
        return $this->peekChar(-1) === "\r" && $this->peekChar() === "\n";
    }

    public function setPosition(int $position): void
    {
        $newPosition = $position;
        $oldPosition = $this->getPosition();
        parent::setPosition($position);

        if ($newPosition > $oldPosition) {
            $newlines = $this->newlinesIn($this->substring($oldPosition, $newPosition));
            $this->line += \count($newlines);

            if ($newlines === []) {
                $this->column += $newPosition - $oldPosition;
            } else {
                $last = $newlines[\count($newlines) - 1];
                $end = $last[1] + \strlen($last[0]);

                $this->column = $newPosition - $end;
            }
        } else {
            $newlines = $this->newlinesIn($this->substring($newPosition, $oldPosition));

            if ($this->betweenCRLF()) {
                array_pop($newlines);
            }
            $this->line -= \count($newlines);

            if ($newlines === []) {
                $this->column -= $oldPosition - $newPosition;
            } else {
                $lastCrlfPosition = strrpos($this->getString(), "\r\n", $newPosition);
                if ($lastCrlfPosition === false) {
                    $lastCrlfPosition = -1;
                }
                $lastLfPosition = strrpos($this->getString(), "\n", $newPosition);
                if ($lastLfPosition === false) {
                    $lastLfPosition = -1;
                }
                $lastNewLinePosition = max($lastCrlfPosition, $lastLfPosition);
                $this->column = $newPosition - $lastNewLinePosition - 1;
            }
        }
    }

    /**
     * @phpstan-impure
     */
    public function scanChar(string $char): bool
    {
        if (!parent::scanChar($char)) {
            return false;
        }

        $this->adjustLineAndColumn($char);
        return true;
    }

    /**
     * @phpstan-impure
     */
    public function readChar(): string
    {
        $character = parent::readChar();
        $this->adjustLineAndColumn($character);

        return $character;
    }

    /**
     * @phpstan-impure
     */
    public function readUtf8Char(): string
    {
        $character = parent::readUtf8Char();
        $this->adjustLineAndColumn($character);

        return $character;
    }

    /**
     * Adjusts {@see line} and {@see column} after having consumed $character.
     */
    private function adjustLineAndColumn(string $character): void
    {
        if ($character === "\n" || ($character === "\r" && $this->peekChar() !== "\n")) {
            $this->line += 1;
            $this->column = 0;
        } else {
            $this->column += \strlen($character);
        }
    }

    /**
     * @phpstan-impure
     */
    public function scan(string $string): bool
    {
        if (!parent::scan($string)) {
            return false;
        }

        $newlines = $this->newlinesIn($string);
        $this->line += \count($newlines);

        if ($newlines === []) {
            $this->column += \strlen($string);
        } else {
            $last = $newlines[\count($newlines) - 1];
            $end = $last[1] + \strlen($last[0]);

            $this->column = \strlen($string) - $end;
        }

        return true;
    }

    /**
     * @return list<array{string, int}>
     */
    private function newlinesIn(string $text): array
    {
        preg_match_all('/\r\n?|\n/', $text, $matches, PREG_OFFSET_CAPTURE);

        $newlines = $matches[0];

        if ($this->betweenCRLF()) {
            array_pop($newlines);
        }

        return $newlines;
    }
}
