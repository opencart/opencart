<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\Renderer\Inline\LinkRenderer as OriginalLinkRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;
use Todaymade\Daux\Config;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\LinkNotFoundException;

class LinkRenderer implements NodeRendererInterface, ConfigurationAwareInterface
{
    protected Config $dauxConfig;

    protected OriginalLinkRenderer $parent;

    public function __construct(Config $dauxConfig)
    {
        $this->dauxConfig = $dauxConfig;
        $this->parent = new OriginalLinkRenderer();
    }

    /**
     * @param Link $node
     *
     * {@inheritDoc}
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        Link::assertInstanceOf($node);

        $element = $this->parent->render($node, $childRenderer);

        $url = $node->getUrl();

        // empty urls and anchors should
        // not go through the url resolver
        if (!DauxHelper::isValidUrl($url)) {
            return $element;
        }

        // Absolute urls, shouldn't either
        if (DauxHelper::isExternalUrl($url)) {
            $element->setAttribute('class', 'Link--external');
            $element->setAttribute('rel', 'noopener noreferrer');

            return $element;
        }

        // if there's a hash component in the url, ensure we
        // don't put that part through the resolver.
        $urlAndHash = explode('#', $url);
        $url = $urlAndHash[0];

        $foundWithHash = false;

        try {
            $file = DauxHelper::resolveInternalFile($this->dauxConfig, $url);
            $url = DauxHelper::getRelativePath($this->dauxConfig->getCurrentPage()->getUrl(), $file->getUrl());
        } catch (LinkNotFoundException $e) {
            // For some reason, the filename could contain a # and thus the link needs to resolve to that.
            try {
                if (strlen($urlAndHash[1] ?? '') > 0) {
                    $file = DauxHelper::resolveInternalFile($this->dauxConfig, $url . '#' . $urlAndHash[1]);
                    $url = DauxHelper::getRelativePath($this->dauxConfig->getCurrentPage()->getUrl(), $file->getUrl());
                    $foundWithHash = true;
                }
            } catch (LinkNotFoundException $e2) {
                // If it's still not found here, we'll only
                // report on the first error as the second
                // one will tell the same.
            }

            if (!$foundWithHash) {
                if ($this->dauxConfig->isStatic()) {
                    throw $e;
                }

                $element->setAttribute('class', 'Link--broken');
            }
        }

        if (!$foundWithHash && isset($urlAndHash[1])) {
            $url .= '#' . $urlAndHash[1];
        }

        $element->setAttribute('href', $url);

        return $element;
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->parent->setConfiguration($configuration);
    }
}
