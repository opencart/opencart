<?php namespace Todaymade\Daux\ContentTypes\Markdown\Admonition;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Block\BlockContinueParserWithInlinesInterface;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\InlineParserEngineInterface;
use League\CommonMark\Parser\MarkdownParserStateInterface;

final class AdmonitionParser extends AbstractBlockContinueParser implements BlockContinueParserWithInlinesInterface
{
    /** @psalm-readonly */
    private AdmonitionBlock $block;

    /** @psalm-readonly */
    private ?string $title;

    /** @psalm-readonly */
    private Paragraph $titleBlock;

    public function __construct(string $type, ?string $title)
    {
        $this->title = $title;
        $this->titleBlock = new Paragraph();
        $this->block = new AdmonitionBlock($type, $this->titleBlock);
    }

    public function getBlock(): AdmonitionBlock
    {
        return $this->block;
    }

    public function isContainer(): bool
    {
        return true;
    }

    public function canContain(AbstractBlock $childBlock): bool
    {
        return true;
    }

    /**
     * Admonition works like any blockQuote, but indented.
     */
    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        if ($cursor->isIndented()) {
            $cursor->advanceBy(Cursor::INDENT_LEVEL, true);

            return BlockContinue::at($cursor);
        }

        if ($cursor->isBlank()) {
            $cursor->advanceToNextNonSpaceOrTab();

            return BlockContinue::at($cursor);
        }

        return BlockContinue::none();
    }

    public static function blockStartParser(): BlockStartParserInterface
    {
        return new class() implements BlockStartParserInterface {
            public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
            {
                if ($cursor->isIndented()) {
                    return BlockStart::none();
                }

                if ($cursor->getNextNonSpaceCharacter() !== '!') {
                    return BlockStart::none();
                }

                $tmpCursor = clone $cursor;
                $rest = $tmpCursor->getRemainder();

                $re = '/!!! ([a-z]+)(?: "(.*)")?\s*$/m';
                $result = preg_match($re, $rest, $matches);

                if (!$result) {
                    return BlockStart::none();
                }

                $cursor->advanceBy(strlen($matches[0]));

                $type = $matches[1];
                $title = array_key_exists(2, $matches) ? $matches[2] : null;

                return BlockStart::of(new AdmonitionParser($type, $title))->at($cursor);
            }
        };
    }

    public function parseInlines(InlineParserEngineInterface $inlineParser): void
    {
        if ($this->title) {
            $inlineParser->parse($this->title, $this->titleBlock);
        }
    }
}
