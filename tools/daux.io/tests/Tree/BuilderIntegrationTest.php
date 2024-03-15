<?php
namespace Todaymade\Daux\Tree;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;

class BuilderIntegrationTest extends TestCase
{
    private vfsStreamDirectory $root;

    public function setUp(): void
    {
        $structure = [
            'Contents' => [
                'Page.md' => 'some text content',
            ],
            'Widgets' => [
                'Page.md' => 'another page',
                'Button.md' => 'another page',
            ],
        ];
        $this->root = vfsStream::setup('root', null, $structure);
    }

    public function testCreateHierarchy()
    {
        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($this->root->url())
            ->withValidContentExtensions(['md'])
            ->build();

        $tree = new Root($config);
        Builder::build($tree, []);

        $this->assertCount(2, $tree);

        $this->assertTrue(array_key_exists('Contents', $tree->getEntries()));
        $this->assertInstanceOf(Directory::class, $tree['Contents']);
        $this->assertTrue(array_key_exists('Widgets', $tree->getEntries()));
        $this->assertInstanceOf(Directory::class, $tree['Widgets']);

        // TODO :: should not be Page.html, this should not depend on the mode
        $this->assertEquals('Page', $tree['Contents']['Page.html']->getTitle());
        $this->assertInstanceOf(Content::class, $tree['Contents']['Page.html']);

        $this->assertEquals('Contents/Page.md', $tree['Contents']['Page.html']->getRelativePath());
    }
}
