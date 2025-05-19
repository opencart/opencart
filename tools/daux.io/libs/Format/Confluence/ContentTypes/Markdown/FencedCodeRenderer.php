<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\Xml;
use Todaymade\Daux\Config;

class FencedCodeRenderer extends CodeRenderer
{
    protected $supportedLanguages = [
        'actionscript3',
        'bash',
        'csharp',
        'coldfusion',
        'cpp',
        'css',
        'delphi',
        'diff',
        'erlang',
        'groovy',
        'html/xml',
        'java',
        'javafx',
        'javascript',
        'none',
        'perl',
        'php',
        'powershell',
        'python',
        'ruby',
        'scala',
        'sql',
        'vb',

        // Special treatment
        'tex',
        'mermaid',
    ];
    protected $knownConversions = ['html' => 'html/xml', 'xml' => 'html/xml', 'js' => 'javascript'];

    protected Config $dauxConfig;

    public function __construct(Config $dauxConfig)
    {
        $this->dauxConfig = $dauxConfig;
    }

    /**
     * @param bool $inTightList
     *
     * @return HtmlElement|string
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        FencedCode::assertInstanceOf($node);

        $language = $this->getLanguage($node->getInfoWords());

        if ($language === 'tex') {
            $this->dauxConfig['__confluence__tex'] = true;

            return new HtmlElement(
                'pre',
                [],
                new HtmlElement('code', ['class' => 'katex'], Xml::escape($node->getLiteral()))
            );
        }

        if ($language === 'mermaid') {
            $this->dauxConfig['__confluence__mermaid'] = true;
            // We render this as <pre> so confluence will leave the content as-is, otherwise it will remove
            // newlines and other formatting.
            // There is a script to transform it back to a <div>
            // Also, if the diagram can't be rendered at least it is displayed in a formatted way
            return new HtmlElement('pre', ['class' => 'mermaid'], Xml::escape($node->getLiteral()));
        }

        return $this->getHTMLElement($node->getLiteral(), $language);
    }

    public function getLanguage($infoWords)
    {
        if (empty($infoWords) || strlen($infoWords[0]) === 0) {
            return false;
        }

        $language = Xml::escape($infoWords[0]);

        if (array_key_exists($language, $this->knownConversions)) {
            $language = $this->knownConversions[$language];
        }

        if (in_array($language, $this->supportedLanguages)) {
            return $language;
        }

        return false;
    }
}
