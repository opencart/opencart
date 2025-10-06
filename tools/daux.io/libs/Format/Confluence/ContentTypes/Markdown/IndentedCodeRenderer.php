<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

class IndentedCodeRenderer extends CodeRenderer
{
    /**
     * @param IndentedCode $node
     *
     * {@inheritDoc}
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        IndentedCode::assertInstanceOf($node);

        return $this->getHTMLElement($node->getLiteral(), '');
    }
}
