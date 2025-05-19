<?php namespace Todaymade\Daux\Format\HTML\ContentTypes\Markdown;

use League\CommonMark\Extension\CommonMark\Renderer\Block\ListBlockRenderer;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContents;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Todaymade\Daux\Config;

class TableOfContentsRenderer implements NodeRendererInterface
{
    private Config $dauxConfig;

    private ListBlockRenderer $parent;

    public function __construct(Config $config)
    {
        $this->dauxConfig = $config;
        $this->parent = new ListBlockRenderer();
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        TableOfContents::assertInstanceOf($node);

        $content = $this->parent->render($node, $childRenderer);

        return $this->dauxConfig->getTemplateRenderer()
            ->getEngine($this->dauxConfig)
            ->render('theme::partials/table_of_contents', ['content' => $content]);
    }
}
