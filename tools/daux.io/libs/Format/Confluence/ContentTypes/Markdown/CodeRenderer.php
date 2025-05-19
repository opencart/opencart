<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

abstract class CodeRenderer implements NodeRendererInterface
{
    public function escapeCDATA($content)
    {
        return str_replace(']]>', ']]]]><![CDATA[>', $content);
    }

    public function getHTMLElement($body, $language)
    {
        $body = '<![CDATA[' . $this->escapeCDATA($body) . ']]>';

        $content = [];

        if ($language) {
            $content[] = new HtmlElement('ac:parameter', ['ac:name' => 'language'], $language);
        }

        $content[] = new HtmlElement('ac:plain-text-body', [], $body);

        return new HtmlElement(
            'ac:structured-macro',
            ['ac:name' => 'code'],
            $content
        );
    }
}
