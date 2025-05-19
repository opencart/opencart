<?php namespace Todaymade\Daux\Format\HTML\ContentTypes\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Renderer\Inline\ImageRenderer as OriginalImageRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Xml\XmlNodeRendererInterface;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;
use Todaymade\Daux\Config;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\LinkNotFoundException;

class ImageRenderer implements NodeRendererInterface, XmlNodeRendererInterface, ConfigurationAwareInterface
{
    protected OriginalImageRenderer $parent;
    private Config $dauxConfig;

    public function __construct(Config $dauxConfig)
    {
        $this->dauxConfig = $dauxConfig;
        $this->parent = new OriginalImageRenderer();
    }

    /**
     * Relative URLs can be done using either the folder with
     * number prefix or the final name (with prefix stripped).
     * This ensures that we always use the final name when generating.
     *
     * @param mixed $url
     *
     * @throws LinkNotFoundException
     */
    protected function getCleanUrl($url)
    {
        // empty urls and anchors should
        // not go through the url resolver
        if (!DauxHelper::isValidUrl($url)) {
            return $url;
        }

        // Absolute urls, shouldn't either
        if (DauxHelper::isExternalUrl($url)) {
            return $url;
        }

        // Data URLs don't need resolution
        if (DauxHelper::isDataUrl($url)) {
            return $url;
        }

        try {
            $file = DauxHelper::resolveInternalFile($this->dauxConfig, $url);

            return DauxHelper::getRelativePath($this->dauxConfig->getCurrentPage()->getUrl(), $file->getUrl());
        } catch (LinkNotFoundException $e) {
            if ($this->dauxConfig->isStatic()) {
                throw $e;
            }
        }

        return $url;
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

        $node->setUrl($this->getCleanUrl($node->getUrl()));

        return $this->parent->render($node, $childRenderer);
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->parent->setConfiguration($configuration);
    }

    public function getXmlTagName(Node $node): string
    {
        return $this->parent->getXmlTagName($node);
    }

    public function getXmlAttributes(Node $node): array
    {
        return $this->parent->getXmlAttributes($node);
    }
}
