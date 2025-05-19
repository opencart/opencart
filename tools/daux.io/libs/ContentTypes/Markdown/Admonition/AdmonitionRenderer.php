<?php namespace Todaymade\Daux\ContentTypes\Markdown\Admonition;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class AdmonitionRenderer implements NodeRendererInterface
{
    /**
     * @param AdmonitionBlock $node
     *
     * {@inheritDoc}
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        $title = '';
        if ($node->getTitle()->hasChildren()) {
            $node->getTitle()->data->set('attributes.class', 'Admonition__title');

            $title = $childRenderer->renderNodes([$node->getTitle()]);
        }

        $children = $childRenderer->renderNodes($node->children());

        return new HtmlElement(
            'div',
            ['class' => 'Admonition Admonition--' . $node->getType()],
            $title . $children
        );
    }
}
