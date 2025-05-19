<?php namespace Todaymade\Daux\Format\HTML\ContentTypes\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContents;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use Todaymade\Daux\Config;

class CommonMarkConverter extends \Todaymade\Daux\ContentTypes\Markdown\CommonMarkConverter
{
    public function __construct($config)
    {
        $config['heading_permalink'] = [
            'html_class' => 'Permalink',
            'symbol' => '#',
            'fragment_prefix' => '',
            'id_prefix' => '',
        ];

        $config['table_of_contents'] = [
            'html_class' => 'TableOfContents',
            'position' => 'placeholder',
            'placeholder' => '[TOC]',
        ];

        parent::__construct($config);
    }

    protected function extendEnvironment(Environment $environment, Config $config)
    {
        parent::extendEnvironment($environment, $config);

        $environment->addExtension(new HeadingPermalinkExtension());
        $environment->addExtension(new TableOfContentsExtension());

        $environment->addRenderer(TableOfContents::class, new TableOfContentsRenderer($config));
        $environment->addRenderer(FencedCode::class, new FencedCodeRenderer());
        $environment->addRenderer(Image::class, new ImageRenderer($config));
    }
}
