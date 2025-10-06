<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Root;

class AdmonitionRendererTest extends TestCase
{
    public static function provideAdmonitionCases()
    {
        return [
            'Note with content before and after' => [
                <<<'EOD'
                    hey

                    !!! note "Title"
                        Content in

                    content outside
                    EOD,
                <<<'EOD'
                    <p>hey</p>
                    <ac:structured-macro ac:name="info"><ac:parameter ac:name="title">Title</ac:parameter><ac:rich-text-body><p>Content in</p></ac:rich-text-body></ac:structured-macro>
                    <p>content outside</p>
                    EOD
            ],
            [
                <<<'EOD'
                    !!! note
                        Note's content
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="info"><ac:rich-text-body><p>Note’s content</p></ac:rich-text-body></ac:structured-macro>
                    EOD
            ],
            [
                <<<'EOD'
                    !!! warning "WARNING !!!"
                        * one
                        * two
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="note"><ac:parameter ac:name="title">WARNING !!!</ac:parameter><ac:rich-text-body><ul>
                    <li>one</li>
                    <li>two</li>
                    </ul></ac:rich-text-body></ac:structured-macro>
                    EOD
            ],
            [
                <<<'EOD'
                    !!! danger "This is dangerous"
                        > one
                        > two
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="warning"><ac:parameter ac:name="title">This is dangerous</ac:parameter><ac:rich-text-body><blockquote>
                    <p>one
                    two</p>
                    </blockquote></ac:rich-text-body></ac:structured-macro>
                    EOD
            ],
            'Content in title is treated as inline Markdown' => [
                <<<'EOD'
                    !!! danger "Use `<ViewLoader>` instead of sharing components across plugins"
                        Sharing components across plugins is dangerous as they will be built with a version of React controlled in one plugin.
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="warning"><ac:parameter ac:name="title"><![CDATA[Use <code>&lt;ViewLoader&gt;</code> instead of sharing components across plugins]]></ac:parameter><ac:rich-text-body><p>Sharing components across plugins is dangerous as they will be built with a version of React controlled in one plugin.</p></ac:rich-text-body></ac:structured-macro>
                    EOD
            ],
        ];
    }

    /**
     * @dataProvider provideAdmonitionCases
     *
     * @param mixed $expected
     * @param mixed $input
     */
    public function testRenderAdmonition($input, $expected)
    {
        $structure = [
            'Content' => ['Page.md' => 'some text content'],
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

        $converter = new CommonMarkConverter(['daux' => $config]);

        $this->assertEquals($expected, trim($converter->convert($input)->getContent()));
    }
}
