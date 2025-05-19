<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Todaymade\Daux\DauxHelper;

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

        // Default handling
        $element = parent::render($node, $childRenderer);

        $url = $node->getUrl();

        // empty urls, anchors and absolute urls
        // should not go through the url resolver
        if (!DauxHelper::isValidUrl($url) || DauxHelper::isExternalUrl($url)) {
            return $element;
        }

        // if there's a hash component in the url, ensure we
        // don't put that part through the resolver.
        $urlAndHash = explode('#', $url);
        $url = $urlAndHash[0];

        // Internal links
        $file = DauxHelper::resolveInternalFile($this->dauxConfig, $url);

        $linkProps = [];
        if (isset($urlAndHash[1])) {
            $linkProps['ac:anchor'] = $urlAndHash[1];
        }

        $confluence = $this->dauxConfig->getConfluenceConfiguration();

        $pageProps = [
            'ri:content-title' => trim(trim($confluence->getPrefix()) . ' ' . $file->getTitle()),
            'ri:space-key' => $confluence->getSpaceId(),
        ];

        $page = strval(new HtmlElement('ri:page', $pageProps, '', true));
        $children = $childRenderer->renderNodes($node->children());
        if (strpos($children, '<') !== false) {
            $children = '<ac:link-body>' . $children . '</ac:link-body>';
        } else {
            $children = '<ac:plain-text-link-body><![CDATA[' . $children . ']]></ac:plain-text-link-body>';
        }

        return new HtmlElement('ac:link', $linkProps, $page . $children);
    }
}
