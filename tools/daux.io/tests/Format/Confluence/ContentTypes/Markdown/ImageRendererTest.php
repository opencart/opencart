<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Root;

class ImageRendererTest extends TestCase
{
    public static function provideImageCases()
    {
        return [
            'External image' => [
                <<<'EOD'
                    hey

                    ![Alt Text](https://google.com/image.png)

                    content outside
                    EOD,
                <<<'EOD'
                    <p>hey</p>
                    <p><ac:image><ri:url ri:value="https://google.com/image.png"></ri:url></ac:image></p>
                    <p>content outside</p>
                    EOD
            ],
            'Image attachment' => [
                <<<'EOD'
                    ![Alt Text](./image.png)
                    EOD,
                <<<'EOD'
                    <p><img src="./image.png" alt="Alt Text" /></p>
                    EOD
            ],
        ];
    }

    /**
     * @dataProvider provideImageCases
     *
     * @param mixed $expected
     * @param mixed $input
     */
    public function testRenderImage($input, $expected)
    {
        $structure = [
            'Content' => ['Page.md' => 'some text content', 'image.png' => 'not an actual image'],
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
