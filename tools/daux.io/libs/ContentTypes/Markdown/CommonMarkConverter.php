<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use Todaymade\Daux\Config;
use Todaymade\Daux\ContentTypes\Markdown\Admonition\AdmonitionBlock;
use Todaymade\Daux\ContentTypes\Markdown\Admonition\AdmonitionParser;
use Todaymade\Daux\ContentTypes\Markdown\Admonition\AdmonitionRenderer;

class CommonMarkConverter extends MarkdownConverter
{
    /**
     * Create a new Markdown converter pre-configured for CommonMark.
     *
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        // We have a custom normalizer that does some transliteration
        $config['slug_normalizer']['instance'] = new TextNormalization();

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new AutolinkExtension());
        $environment->addExtension(new SmartPunctExtension());
        $environment->addExtension(new StrikethroughExtension());
        $environment->addExtension(new TableExtension());

        $environment->addExtension(new DauxExtension());

        $environment->addBlockStartParser(AdmonitionParser::blockStartParser());
        $environment->addRenderer(AdmonitionBlock::class, new AdmonitionRenderer());

        $environment->addRenderer(Link::class, new LinkRenderer($config['daux']));

        $this->extendEnvironment($environment, $config['daux']);

        if ($config['daux']->hasProcessorInstance()) {
            $config['daux']->getProcessorInstance()->extendCommonMarkEnvironment($environment);
        }

        parent::__construct($environment);
    }

    protected function extendEnvironment(Environment $environment, Config $config)
    {
        // Nothing to see here for now
    }
}
