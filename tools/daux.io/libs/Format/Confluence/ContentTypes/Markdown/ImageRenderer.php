<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Renderer\Inline\ImageRenderer as OriginalImageRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

class ImageRenderer implements NodeRendererInterface, ConfigurationAwareInterface
{
    protected ConfigurationInterface $config;

    protected OriginalImageRenderer $parent;

    public function __construct()
    {
        $this->parent = new OriginalImageRenderer();
    }

    /**
     * @param Image $node
     *
     * {@inheritDoc}
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        Image::assertInstanceOf($node);

        // External Images need special handling
        if (strpos($node->getUrl(), 'http') === 0) {
            return new HtmlElement(
                'ac:image',
                [],
                new HtmlElement('ri:url', ['ri:value' => $node->getUrl()])
            );
        }

        return $this->parent->render($node, $childRenderer);
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->parent->setConfiguration($configuration);
    }
}
