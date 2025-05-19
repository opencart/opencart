<?php namespace Todaymade\Daux\ContentTypes\Markdown;

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
            'Content before and after' => [
                <<<'EOD'
                    hey

                    !!! note "Title"
                        Content in

                    content outside
                    EOD,
                <<<'EOD'
                    <p>hey</p>
                    <div class="Admonition Admonition--note"><p class="Admonition__title">Title</p><p>Content in</p></div>
                    <p>content outside</p>
                    EOD
            ],
            'Simple admonition' => [
                <<<'EOD'
                    !!! note
                        Note's content
                    EOD,
                <<<'EOD'
                    <div class="Admonition Admonition--note"><p>Note’s content</p></div>
                    EOD
            ],
            'warning' => [
                <<<'EOD'
                    !!! warning "WARNING !!!"
                        * one
                        * two
                    EOD,
                <<<'EOD'
                    <div class="Admonition Admonition--warning"><p class="Admonition__title">WARNING !!!</p><ul>
                    <li>one</li>
                    <li>two</li>
                    </ul></div>
                    EOD
            ],
            'danger' => [
                <<<'EOD'
                    !!! danger "This is dangerous"
                        > one
                        > two
                    EOD,
                <<<'EOD'
                    <div class="Admonition Admonition--danger"><p class="Admonition__title">This is dangerous</p><blockquote>
                    <p>one
                    two</p>
                    </blockquote></div>
                    EOD
            ],
            'Not an admonition' => [
                <<<'EOD'
                    !!! This is not an admonition !!!
                    EOD,
                <<<'EOD'
                    <p>!!! This is not an admonition !!!</p>
                    EOD
            ],
            'Indented code blocks are ignored' => [
                <<<'EOD'
                        Code Block
                    EOD,
                <<<'EOD'
                    <pre><code>Code Block
                    </code></pre>
                    EOD
            ],
            'Content in title is treated as inline Markdown' => [
                <<<'EOD'
                    !!! danger "Use `<ViewLoader>` instead of sharing components across plugins"
                        Sharing components across plugins is dangerous as they will be built with a version of React controlled in one plugin.
                    EOD,
                <<<'EOD'
                    <div class="Admonition Admonition--danger"><p class="Admonition__title">Use <code>&lt;ViewLoader&gt;</code> instead of sharing components across plugins</p><p>Sharing components across plugins is dangerous as they will be built with a version of React controlled in one plugin.</p></div>
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
    public function testRenderLink($input, $expected)
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
