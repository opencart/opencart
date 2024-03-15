<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Todaymade\Daux\ContentTypes\Markdown\Admonition\AdmonitionBlock;

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
        $type = 'info';

        if ($node->getType() == 'warning') {
            $type = 'note';
        }

        if ($node->getType() == 'danger') {
            $type = 'warning';
        }

        $content = [];

        if ($node->getTitle()->hasChildren()) {
            $title = $childRenderer->renderNodes($node->getTitle()->children());

            $content[] = new HtmlElement(
                'ac:parameter',
                ['ac:name' => 'title'],
                str_contains($title, '<') ? '<![CDATA[' . $title . ']]>' : $title
            );
        }

        $content[] = new HtmlElement('ac:rich-text-body', [], $childRenderer->renderNodes($node->children()));

        return new HtmlElement('ac:structured-macro', ['ac:name' => $type], $content);
    }
}
