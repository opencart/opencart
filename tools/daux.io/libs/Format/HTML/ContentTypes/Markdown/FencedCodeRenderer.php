<?php namespace Todaymade\Daux\Format\HTML\ContentTypes\Markdown;

use Highlight\Highlighter;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\Xml;

class FencedCodeRenderer implements NodeRendererInterface
{
    private Highlighter $hl;

    public function __construct()
    {
        $this->hl = new Highlighter();
    }

    public function pre($attrs, $content)
    {
        return new HtmlElement(
            'pre',
            [],
            new HtmlElement('code', $attrs->export(), $content)
        );
    }

    /**
     * @param bool $inTightList
     *
     * @return HtmlElement|string
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        FencedCode::assertInstanceOf($node);

        $attrs = $node->data->getData('attributes');

        $content = $node->getLiteral();

        $language = $this->getLanguage($node->getInfoWords());

        if ($language === 'tex') {
            $attrs['class'] = 'katex';

            return $this->pre($attrs, Xml::escape($content));
        }

        if ($language === 'mermaid') {
            return new HtmlElement('div', ['class' => 'mermaid'], Xml::escape($content));
        }

        return $this->renderCode($content, $language, $attrs);
    }

    public function renderCode($content, $language, $attrs)
    {
        $highlighted = false;
        if ($language) {
            $attrs['class'] = isset($attrs['class']) ? $attrs['class'] . ' ' : '';

            try {
                $highlighted = $this->hl->highlight($language, $content);
                $content = $highlighted->value;
                $attrs['class'] .= 'hljs ' . $highlighted->language;
            } catch (\Exception $e) {
                $attrs['class'] .= 'language-' . $language;
            }
        }

        if (!$highlighted) {
            $content = Xml::escape($content);
        }

        return $this->pre($attrs, $content);
    }

    public function getLanguage($infoWords)
    {
        if (empty($infoWords) || strlen($infoWords[0]) === 0) {
            return false;
        }

        return Xml::escape($infoWords[0]);
    }
}
