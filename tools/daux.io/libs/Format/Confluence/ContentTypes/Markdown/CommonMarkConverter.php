<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContents;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContentsPlaceholder;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use Todaymade\Daux\Config;
use Todaymade\Daux\ContentTypes\Markdown\Admonition\AdmonitionBlock;

class CommonMarkConverter extends \Todaymade\Daux\ContentTypes\Markdown\CommonMarkConverter
{
    public function __construct($config)
    {
        $config['table_of_contents'] = [
            'position' => 'placeholder',
            'placeholder' => '[TOC]',
        ];

        $config['heading_permalink']['fragment_prefix'] = '';

        parent::__construct($config);
    }

    protected function extendEnvironment(Environment $environment, Config $config)
    {
        parent::extendEnvironment($environment, $config);

        $environment->addExtension(new TableOfContentsExtension());
        $environment->addExtension(new FakeHeadingPermalinkExtension());

        $environment->addRenderer(TableOfContents::class, new TableOfContentsRenderer());
        $environment->addRenderer(TableOfContentsPlaceholder::class, new TableOfContentsRenderer());
        $environment->addRenderer(FencedCode::class, new FencedCodeRenderer($config));
        $environment->addRenderer(IndentedCode::class, new IndentedCodeRenderer());
        $environment->addRenderer(Image::class, new ImageRenderer());
        $environment->addRenderer(Link::class, new LinkRenderer($config), 200);
        $environment->addRenderer(AdmonitionBlock::class, new AdmonitionRenderer(), 200);
    }
}
