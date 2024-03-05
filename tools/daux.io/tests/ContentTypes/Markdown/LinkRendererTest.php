<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Root;

class LinkRendererTest extends TestCase
{
    public static function providerRenderLink()
    {
        return [
            // /Widgets/Page_with_#_hash
            [
                '<a href="../Widgets/Page_with_hash.html">Link</a>',
                '[Link](../Widgets/Page_with_#_hash.md)',
                'Content/Page.html',
            ],
            [
                '<a href="../Widgets/Page_with_hash.html">Link</a>',
                '[Link](!Widgets/Page_with_#_hash)',
                'Content/Page.html',
            ],
            [
                '<a href="Page_with_hash.html">Link</a>',
                '[Link](Page_with_#_hash.md)',
                'Widgets/Page.html',
            ],

            // /Widgets/Page
            [
                '<a href="http://google.ch" class="Link--external" rel="noopener noreferrer">Link</a>',
                '[Link](http://google.ch)',
                'Widgets/Page.html',
            ],
            ['<a href="#features">Link</a>', '[Link](#features)', 'Widgets/Page.html'],
            ['<a href="Button.html">Link</a>', '[Link](Button.md)', 'Widgets/Page.html'],
            ['<a href="Button.html">Link</a>', '[Link](./Button.md)', 'Widgets/Page.html'],
            ['<a href="Button.html">Link</a>', '[Link](Button)', 'Widgets/Page.html'],
            ['<a href="Button.html">Link</a>', '[Link](./Button)', 'Widgets/Page.html'],
            ['<a href="Button.html">Link</a>', '[Link](!Widgets/Button)', 'Widgets/Page.html'],

            ['<a href="Button.html#Test">Link</a>', '[Link](./Button#Test)', 'Widgets/Page.html'],
            ['<a href="Button.html#Test">Link</a>', '[Link](!Widgets/Button#Test)', 'Widgets/Page.html'],

            // /Content/Page
            ['<a href="../Widgets/Button.html">Link</a>', '[Link](../Widgets/Button.md)', 'Content/Page.html'],
            ['<a href="../Widgets/Button.html">Link</a>', '[Link](!Widgets/Button)', 'Content/Page.html'],

            // Mailto links
            [
                '<a href="mailto:me@mydomain.com" class="Link--external" rel="noopener noreferrer">me@mydomain.com</a>',
                '[me@mydomain.com](mailto:me@mydomain.com)',
                'Content/Page.html',
            ],
        ];
    }

    /**
     * @dataProvider providerRenderLink
     *
     * @param mixed $expected
     * @param mixed $string
     * @param mixed $current
     */
    public function testRenderLink($expected, $string, $current)
    {
        $structure = [
            'Content' => [
                'Page.md' => 'some text content',
            ],
            'Widgets' => [
                'Page.md' => 'another page',
                'Button.md' => 'another page',
                'Page_with_#_hash.md' => 'page with hash',
            ],
        ];
        $root = vfsStream::setup('root', null, $structure);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->withValidContentExtensions(['md'])
            ->with([
                'base_url' => '',
            ])
            ->build();

        $tree = new Root($config);
        Builder::build($tree, []);

        $config = ConfigBuilder::withMode()->build();
        $config->setTree($tree);
        $config->setCurrentPage(DauxHelper::getFile($tree, $current));

        $converter = new CommonMarkConverter(['daux' => $config]);

        $this->assertEquals("<p>$expected</p>", trim($converter->convert($string)->getContent()));
    }
}
