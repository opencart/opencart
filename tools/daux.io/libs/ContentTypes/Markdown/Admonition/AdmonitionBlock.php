<?php namespace Todaymade\Daux\ContentTypes\Markdown\Admonition;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Block\Paragraph;

class AdmonitionBlock extends AbstractBlock
{
    private string $type;
    private ?Paragraph $title;

    public function __construct(string $type, ?Paragraph $title)
    {
        $this->type = $type;
        $this->title = $title;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getTitle(): ?Paragraph
    {
        return $this->title;
    }
}
