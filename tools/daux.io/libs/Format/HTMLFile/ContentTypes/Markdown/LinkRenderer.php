<?php namespace Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\LinkNotFoundException;

class LinkRenderer extends \Todaymade\Daux\ContentTypes\Markdown\LinkRenderer
{
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

        $element = parent::render($node, $childRenderer);

        $url = $node->getUrl();

        // empty urls and anchors should
        // not go through the url resolver
        if (!DauxHelper::isValidUrl($url)) {
            return $element;
        }

        // Absolute urls, shouldn't either
        if (DauxHelper::isExternalUrl($url)) {
            $element->setAttribute('class', 'Link--external');

            return $element;
        }

        // if there's a hash component in the url, we can directly use it as all pages are in the same file
        $urlAndHash = explode('#', $url);
        if (isset($urlAndHash[1])) {
            $element->setAttribute('href', '#' . $urlAndHash[1]);

            return $element;
        }

        try {
            $file = DauxHelper::resolveInternalFile($this->dauxConfig, $url);
            $url = $file->getUrl();
        } catch (LinkNotFoundException $e) {
            if ($this->daux->isStatic()) {
                throw $e;
            }

            $element->setAttribute('class', 'Link--broken');
        }

        $url = str_replace('/', '_', $url);
        $element->setAttribute('href', "#file_$url");

        return $element;
    }
}
