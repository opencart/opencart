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

use League\Uri\Exceptions\SyntaxError;
use ScssPhp\ScssPhp\Ast\Sass\Import;
use ScssPhp\ScssPhp\Ast\Sass\Import\DynamicImport;
use ScssPhp\ScssPhp\Ast\Sass\Import\StaticImport;
use ScssPhp\ScssPhp\Ast\Sass\Interpolation;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Ast\Sass\Statement\LoudComment;
use ScssPhp\ScssPhp\Ast\Sass\Statement\SilentComment;
use ScssPhp\ScssPhp\Util\Character;
use ScssPhp\ScssPhp\Value\SassString;

/**
 * A parser for the indented syntax.
 *
 * @internal
 */
final class SassParser extends StylesheetParser
{
    private int $currentIndentation = 0;

    /**
     * The indentation level of the next source line after the scanner's
     * position, or `null` if that hasn't been computed yet.
     *
     * A source line is any line that's not entirely whitespace.
     */
    private ?int $nextIndentation = null;

    /**
     * The beginning of the next source line after the scanner's position, or
     * `null` if the next indentation hasn't been computed yet.
     *
     * A source line is any line that's not entirely whitespace.
     */
    private ?int $nextIndentationEnd = null;

    /**
     * Whether the document is indented using spaces or tabs.
     *
     * If this is `true`, the document is indented using spaces. If it's `false`,
     * the document is indented using tabs. If it's `null`, we haven't yet seen
     * the indentation character used by the document.
     */
    private ?bool $spaces = null;

    public function getCurrentIndentation(): int
    {
        return $this->currentIndentation;
    }

    protected function isIndented(): bool
    {
        return true;
    }

    protected function styleRuleSelector(): Interpolation
    {
        $start = $this->scanner->getPosition();

        $buffer = new InterpolationBuffer();

        do {
            $buffer->addInterpolation($this->almostAnyValue(omitComments: true));
            $buffer->write("\n");
        } while (str_ends_with(rtrim($buffer->getTrailingString()), ',') && $this->scanCharIf(fn ($char) => Character::isNewline($char)));

        return $buffer->buildInterpolation($this->scanner->spanFrom($start));
    }

    protected function expectStatementSeparator(?string $name = null): void
    {
        if (!$this->atEndOfStatement()) {
            $this->expectNewline();
        }

        if ($this->peekIndentation() <= $this->currentIndentation) {
            return;
        }

        \assert($this->nextIndentationEnd !== null);

        $this->scanner->error(\sprintf('Nothing may be indented %s.', $name === null ? 'here' : "beneath a $name"), $this->nextIndentationEnd);
    }

    protected function atEndOfStatement(): bool
    {
        $nextChar = $this->scanner->peekChar();

        return $nextChar === null || Character::isNewline($nextChar);
    }

    protected function lookingAtChildren(): bool
    {
        return $this->atEndOfStatement() && $this->peekIndentation() > $this->currentIndentation;
    }

    protected function importArgument(): Import
    {
        switch ($this->scanner->peekChar()) {
            case 'u':
            case 'U':
                $start = $this->scanner->getPosition();
                if ($this->scanIdentifier('url')) {
                    if ($this->scanner->scanChar('(')) {
                        $this->scanner->setPosition($start);

                        return parent::importArgument();
                    } else {
                        $this->scanner->setPosition($start);
                    }
                }
                break;

            case "'":
            case '"':
                return parent::importArgument();
        }

        $start = $this->scanner->getPosition();
        $next = $this->scanner->peekChar();

        while ($next !== null && $next !== ',' && $next !== ';' && !Character::isNewline($next)) {
            $this->scanner->readUtf8Char();
            $next = $this->scanner->peekChar();
        }

        $url = $this->scanner->substring($start);
        $span = $this->scanner->spanFrom($start);

        if ($this->isPlainImportUrl($url)) {
            // Serialize $url as a Sass string because StaticImport expects it to
            // include quotes.
            return new StaticImport(new Interpolation([(string) new SassString($url)], $span), $span);
        }

        try {
            return new DynamicImport($this->parseImportUrl($url), $span);
        } catch (SyntaxError $e) {
            $this->error('Invalid URL: ' . $e->getMessage(), $span, $e);
        }
    }

    protected function scanElse(int $ifIndentation): bool
    {
        if ($this->peekIndentation() !== $ifIndentation) {
            return false;
        }

        $start = $this->scanner->getPosition();
        $startIndentation = $this->currentIndentation;
        $startNextIndentation = $this->nextIndentation;
        $startNextIndentationEnd = $this->nextIndentationEnd;
        $this->readIndentation();

        if ($this->scanner->scanChar('@') && $this->scanIdentifier('else')) {
            return true;
        }

        $this->scanner->setPosition($start);
        $this->currentIndentation = $startIndentation;
        $this->nextIndentation = $startNextIndentation;
        $this->nextIndentationEnd = $startNextIndentationEnd;

        return false;
    }

    protected function children(callable $child): array
    {
        $children = [];

        $this->whileIndentedLower(function () use ($child, &$children) {
            $parsedChild = $this->child($child);

            if ($parsedChild !== null) {
                $children[] = $parsedChild;
            }
        });

        return $children;
    }

    protected function statements(callable $statement): array
    {
        $next = $this->scanner->peekChar();
        if ($next === "\t" || $next === ' ') {
            $this->scanner->error('Indenting at the beginning of the document is illegal.', 0, $this->scanner->getPosition());
        }

        $statements = [];

        while (!$this->scanner->isDone()) {
            $child = $this->child($statement);

            if ($child !== null) {
                $statements[] = $child;
            }

            $indentation = $this->readIndentation();
            \assert($indentation === 0);
        }

        return $statements;
    }

    /**
     * Consumes a child of the current statement.
     *
     * This consumes children that are allowed at all levels of the document; the
     * $child parameter is called to consume any children that are specifically
     * allowed in the caller's context.
     *
     * @param callable(): (Statement|null) $child
     */
    private function child(callable $child): ?Statement
    {
        return match ($this->scanner->peekChar()) {
            // Ignore empty lines.
            "\r", "\n", "\f" => null,
            '$' => $this->variableDeclarationWithoutNamespace(),
            '/' => match ($this->scanner->peekChar(1)) {
                '/' => $this->silentCommentStatement(),
                '*' => $this->loudCommentStatement(),
                default => $child(),
            },
            default => $child(),
        };
    }

    /**
     * Consumes an indented-style silent comment.
     */
    private function silentCommentStatement(): SilentComment
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expect('//');

        $buffer = '';
        $parentIndentation = $this->currentIndentation;

        do {
            $commentPrefix = $this->scanner->scanChar('/') ? '///' : '//';

            while (true) {
                $buffer .= $commentPrefix;

                // Skip the initial characters because we're already writing the
                // slashes.
                for ($i = \strlen($commentPrefix); $i < $this->currentIndentation - $parentIndentation; $i++) {
                    $buffer .= ' ';
                }

                while (!$this->scanner->isDone() && !Character::isNewline($this->scanner->peekChar())) {
                    $buffer .= $this->scanner->readUtf8Char();
                }

                $buffer .= "\n";

                if ($this->peekIndentation() < $parentIndentation) {
                    break 2;
                }

                if ($this->peekIndentation() === $parentIndentation) {
                    // Look ahead to the next line to see if it starts another comment.
                    if ($this->scanner->peekChar(1 + $parentIndentation) === '/' && $this->scanner->peekChar(2 + $parentIndentation) === '/') {
                        $this->readIndentation();
                    }
                    break;
                }

                $this->readIndentation();
            }
        } while ($this->scanner->scan('//'));

        return $this->lastSilentComment = new SilentComment($buffer, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes an indented-style loud context.
     */
    private function loudCommentStatement(): LoudComment
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expect('/*');

        $first = true;
        $buffer = new InterpolationBuffer();
        $buffer->write('/*');
        $parentIndentation = $this->currentIndentation;

        while (true) {
            if ($first) {
                // If the first line is empty, ignore it.
                $beginningOfComment = $this->scanner->getPosition();
                $this->spaces();
                if (Character::isNewline($this->scanner->peekChar())) {
                    $this->readIndentation();
                    $buffer->write(' ');
                } else {
                    $buffer->write($this->scanner->substring($beginningOfComment));
                }
            } else {
                $buffer->write("\n * ");
            }

            $first = false;

            for ($i = 3; $i < $this->currentIndentation - $parentIndentation; $i++) {
                $buffer->write(' ');
            }

            while (!$this->scanner->isDone()) {
                switch ($this->scanner->peekChar()) {
                    case "\n":
                    case "\r":
                    case "\f":
                        break 2;

                    case '#':
                        if ($this->scanner->peekChar(1) === '{') {
                            $buffer->add($this->singleInterpolation());
                        } else {
                            $buffer->write($this->scanner->readChar());
                        }
                        break;

                    default:
                        $buffer->write($this->scanner->readUtf8Char());
                }
            }

            if ($this->peekIndentation() <= $parentIndentation) {
                break;
            }

            // Preserve empty lines.
            while ($this->lookingAtDoubleNewline()) {
                $this->expectNewline();
                $buffer->write("\n *");
            }

            $this->readIndentation();
        }

        return new LoudComment($buffer->buildInterpolation($this->scanner->spanFrom($start)));
    }

    protected function whitespaceWithoutComments(): void
    {
        // This overrides whitespace consumption so that it doesn't consume
        // newlines.
        while (!$this->scanner->isDone()) {
            $next = $this->scanner->peekChar();
            if ($next !== "\t" && $next !== ' ') {
                break;
            }
            $this->scanner->readChar();
        }
    }

    protected function loudComment(): void
    {
        // This overrides loud comment consumption so that it doesn't consume
        // multi-line comments.
        $this->scanner->expect('/*');
        while (true) {
            $next = $this->scanner->readUtf8Char();

            if (Character::isNewline($next)) {
                $this->scanner->error('expected */.');
            }

            if ($next !== '*') {
                continue;
            }

            do {
                $next = $this->scanner->readUtf8Char();
            } while ($next === '*');

            if ($next === '/') {
                break;
            }
        }
    }

    /**
     * Expect and consume a single newline character.
     */
    private function expectNewline(): void
    {
        switch ($this->scanner->peekChar()) {
            case ';':
                $this->scanner->error("semicolons aren't allowed in the indented syntax.");

            case "\r":
                $this->scanner->readChar();
                if ($this->scanner->peekChar() === "\n") {
                    $this->scanner->readChar();
                }
                break;

            case "\n":
            case "\f":
                $this->scanner->readChar();
                break;

            default:
                $this->scanner->error('expected newline.');
        }
    }

    /**
     * Returns whether the scanner is immediately before *two* newlines.
     */
    private function lookingAtDoubleNewline(): bool
    {
        return match ($this->scanner->peekChar()) {
            "\r" => match ($this->scanner->peekChar(1)) {
                "\n" => Character::isNewline($this->scanner->peekChar(2)),
                "\r", "\f" => true,
                default => false,
            },
            "\n", "\f" => Character::isNewline($this->scanner->peekChar(1)),
            default => false,
        };
    }

    /**
     * As long as the scanner's position is indented beneath the starting line,
     * runs $body to consume the next statement.
     *
     * @param callable(): void $body
     */
    private function whileIndentedLower(callable $body): void
    {
        $parentIndentation = $this->currentIndentation;
        $childIndentation = null;

        while ($this->peekIndentation() > $parentIndentation) {
            $indentation = $this->readIndentation();
            $childIndentation ??= $indentation;

            if ($childIndentation !== $indentation) {
                $this->scanner->error(
                    "Inconsistent indentation, expected $childIndentation spaces.",
                    $this->scanner->getPosition() - $this->scanner->getColumn(),
                    $this->scanner->getColumn()
                );
            }

            $body();
        }
    }

    /**
     * Consumes indentation whitespace and returns the indentation level of the
     * next line.
     *
     * @phpstan-impure
     */
    private function readIndentation(): int
    {
        $currentIndentation = $this->currentIndentation = $this->nextIndentation ??= $this->peekIndentation();
        \assert($this->nextIndentationEnd !== null);
        $this->scanner->setPosition($this->nextIndentationEnd);
        $this->nextIndentation = null;
        $this->nextIndentationEnd = null;

        return $currentIndentation;
    }

    /**
     * Returns the indentation level of the next line.
     */
    private function peekIndentation(): int
    {
        if ($this->nextIndentation !== null) {
            return $this->nextIndentation;
        }

        if ($this->scanner->isDone()) {
            $this->nextIndentation = 0;
            $this->nextIndentationEnd = $this->scanner->getPosition();

            return 0;
        }

        $start = $this->scanner->getPosition();

        do {
            $containsTab = false;
            $containsSpace = false;
            $nextIndentation = 0;

            while (true) {
                switch ($this->scanner->peekChar()) {
                    case ' ':
                        $containsSpace = true;
                        break;

                    case "\t":
                        $containsTab = true;
                        break;

                    default:
                        break 2;
                }

                $nextIndentation++;
                $this->scanner->readChar();
            }

            if ($this->scanner->isDone()) {
                $this->nextIndentation = 0;
                $this->nextIndentationEnd = $this->scanner->getPosition();
                $this->scanner->setPosition($start);

                return 0;
            }
        } while ($this->scanCharIf(fn ($char) => Character::isNewline($char)));

        $this->checkIndentationConsistency($containsTab, $containsSpace);

        $this->nextIndentation = $nextIndentation;
        if ($nextIndentation > 0) {
            $this->spaces ??= $containsSpace;
        }
        $this->nextIndentationEnd = $this->scanner->getPosition();
        $this->scanner->setPosition($start);

        return $nextIndentation;
    }

    /**
     * Ensures that the document uses consistent characters for indentation.
     *
     * The $containsTab and $containsSpace parameters refer to a single line of
     * indentation that has just been parsed.
     */
    private function checkIndentationConsistency(bool $containsTab, bool $containsSpace): void
    {
        if ($containsTab) {
            if ($containsSpace) {
                $this->scanner->error('Tabs and spaces may not be mixed.', $this->scanner->getPosition() - $this->scanner->getColumn(), $this->scanner->getColumn());
            }

            if ($this->spaces === true) {
                $this->scanner->error('Expected spaces, was tabs.', $this->scanner->getPosition() - $this->scanner->getColumn(), $this->scanner->getColumn());
            }
        } elseif ($containsSpace && $this->spaces === false) {
            $this->scanner->error('Expected tabs, was spaces.', $this->scanner->getPosition() - $this->scanner->getColumn(), $this->scanner->getColumn());
        }
    }
}
