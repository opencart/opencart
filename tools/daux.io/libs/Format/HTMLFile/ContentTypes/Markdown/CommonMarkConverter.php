<?php namespace Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Normalizer\UniqueSlugNormalizerInterface;
use Todaymade\Daux\Config;

class CommonMarkConverter extends \Todaymade\Daux\Format\HTML\ContentTypes\Markdown\CommonMarkConverter
{
    /**
     * Create a new Markdown converter pre-configured for CommonMark.
     *
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        // As we are using a single page, we must make sure permalinks don't collide
        $config['slug_normalizer']['unique'] = UniqueSlugNormalizerInterface::PER_ENVIRONMENT;

        parent::__construct($config);
    }

    protected function extendEnvironment(Environment $environment, Config $config)
    {
        parent::extendEnvironment($environment, $config);
        $environment->addRenderer(Link::class, new LinkRenderer($config), 200);
    }
}
