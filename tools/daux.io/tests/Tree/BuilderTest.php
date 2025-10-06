<?php
namespace Todaymade\Daux\Tree;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;

class BuilderTest extends TestCase
{
    public static function providerRemoveSorting()
    {
        return [
            ['01_before', 'before'],
            ['-Down', 'Down'],
            ['+Up', 'Up'],
            ['01_numeric', 'numeric'],
            ['01_A_File', 'A_File'],
            ['A_File', 'A_File'],
            ['01_Continuing', 'Continuing'],
            ['-01_Coming', 'Coming'],
            ['-02_Soon', 'Soon'],
            ['01_Getting_Started', 'Getting_Started'],
            ['API_Calls', 'API_Calls'],
            ['200_Something_Else-Cool', 'Something_Else-Cool'],
            ['_5_Ways_to_Be_Happy', '5_Ways_to_Be_Happy'],
            ['+02_Soon', 'Soon'],
            ['Before_but_after', 'Before_but_after'],
            ['Continuing', 'Continuing'],
            ['01_GitHub_Flavored_Markdown', 'GitHub_Flavored_Markdown'],
            ['Code_Test', 'Code_Test'],
            ['05_Code_Highlighting', 'Code_Highlighting'],
            ['1', '1'],
        ];
    }

    /**
     * @dataProvider providerRemoveSorting
     *
     * @param mixed $value
     * @param mixed $expected
     */
    public function testRemoveSorting($value, $expected)
    {
        $this->assertEquals($expected, Builder::removeSortingInformations($value));
    }

    public function testGetOrCreateDirNew()
    {
        $config = ConfigBuilder::withMode()->build();
        $root = new Root($config);

        $dir = Builder::getOrCreateDir($root, 'directory');

        $this->assertSame($root, $dir->getParent());
        $this->assertEquals('directory', $dir->getTitle());
        $this->assertEquals('directory', $dir->getUri());
    }

    public function testGetOrCreateDirExisting()
    {
        $config = ConfigBuilder::withMode()->build();
        $root = new Root($config);
        $directory = new Directory($root, 'directory');
        $directory->setTitle('directory');

        $dir = Builder::getOrCreateDir($root, 'directory');

        $this->assertSame($root, $dir->getParent());
        $this->assertEquals('directory', $dir->getTitle());
        $this->assertEquals('directory', $dir->getUri());
        $this->assertSame($directory, $dir);
    }

    public function getStaticRoot()
    {
        $config = ConfigBuilder::withMode()
            ->withValidContentExtensions(['md'])
            ->build();

        return new Root($config);
    }

    public static function providerCreatePage()
    {
        return [
            // File, Url, Uri, Title
            ['A Page.md', 'dir/A_Page.html', 'A_Page.html', 'A Page'],
            ['Page#1.md', 'dir/Page1.html', 'Page1.html', 'Page#1'],
            ['你好世界.md', 'dir/ni_hao_shi_jie.html', 'ni_hao_shi_jie.html', '你好世界'],
        ];
    }

    /**
     * @dataProvider providerCreatePage
     *
     * @param mixed $file
     * @param mixed $url
     * @param mixed $uri
     * @param mixed $title
     */
    public function testGetOrCreatePage($file, $url, $uri, $title)
    {
        $directory = new Directory($this->getStaticRoot(), 'dir');

        $entry = Builder::getOrCreatePage($directory, $file);

        $this->assertSame($directory, $entry->getParent());
        $this->assertEquals($url, $entry->getUrl());
        $this->assertEquals($uri, $entry->getUri());
        $this->assertEquals($title, $entry->getTitle());
        $this->assertInstanceOf('Todaymade\Daux\Tree\Content', $entry);
    }

    public function testChangeUri()
    {
        $directory = new Directory($this->getStaticRoot(), 'dir');

        $entry = Builder::getOrCreatePage($directory, 'A Page.md');

        $this->assertSame($directory, $entry->getParent());

        $this->assertEquals('dir/A_Page.html', $entry->getUrl());
        $this->assertEquals('A_Page.html', $entry->getUri());
        $this->assertEquals('A Page', $entry->getTitle());
        $this->assertInstanceOf('Todaymade\Daux\Tree\Content', $entry);

        $entry->setUri('New_Page.html');

        $this->assertEquals('dir/New_Page.html', $entry->getUrl());
        $this->assertEquals('New_Page.html', $entry->getUri());
        $this->assertEquals('A Page', $entry->getTitle());

        $this->assertEquals(
            ['New_Page.html'],
            array_keys($directory->getEntries())
        );
    }

    public function testGetOrCreatePageAutoMarkdown()
    {
        $directory = new Directory($this->getStaticRoot(), 'dir');

        $entry = Builder::getOrCreatePage($directory, 'A Page');

        $this->assertSame($directory, $entry->getParent());
        $this->assertEquals('dir/A_Page.html', $entry->getUrl());
        $this->assertEquals('A_Page.html', $entry->getUri());
        $this->assertEquals('A Page', $entry->getTitle());
        $this->assertInstanceOf('Todaymade\Daux\Tree\Content', $entry);
    }

    public function testGetOrCreateIndexPage()
    {
        $directory = new Directory($this->getStaticRoot(), 'dir');
        $directory->setTitle('Tutorials');

        $entry = Builder::getOrCreatePage($directory, 'index.md');

        $this->assertSame($directory, $entry->getParent());
        $this->assertEquals('dir/index.html', $entry->getUrl());
        $this->assertEquals('Tutorials', $entry->getTitle());
        $this->assertInstanceOf('Todaymade\Daux\Tree\Content', $entry);
    }

    public function testGetOrCreatePageExisting()
    {
        $directory = new Directory($this->getStaticRoot(), 'dir');
        $existingEntry = new Content($directory, 'A_Page.html');
        $existingEntry->setContent('-');

        $entry = Builder::getOrCreatePage($directory, 'A Page.md');

        $this->assertSame($directory, $entry->getParent());
        $this->assertSame($existingEntry, $entry);
        $this->assertEquals('dir/A_Page.html', $entry->getUrl());
        $this->assertEquals('A_Page.html', $entry->getUri());
        $this->assertInstanceOf('Todaymade\Daux\Tree\Content', $entry);
    }

    public function testGetOrCreateRawPage()
    {
        $directory = new Directory($this->getStaticRoot(), 'dir');

        $entry = Builder::getOrCreatePage($directory, 'file.json');

        $this->assertSame($directory, $entry->getParent());
        $this->assertEquals('dir/file.json', $entry->getUrl());
        $this->assertEquals('file.json', $entry->getUri());
        $this->assertInstanceOf('Todaymade\Daux\Tree\ComputedRaw', $entry);
    }

    public function testUnicodeFilenames()
    {
        $structure = [
            'Page.md' => 'another page',
            'Button.md' => 'another page',
            '你好世界.md' => 'another page',
            '22.png' => '',
        ];
        $root = vfsStream::setup('root', null, $structure);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->withValidContentExtensions(['md'])
            ->build();

        $tree = new Root($config);
        Builder::build($tree, []);

        $this->assertEquals(
            ['22.png', 'Button.html', 'Page.html', 'ni_hao_shi_jie.html'],
            array_keys($tree->getEntries())
        );
    }

    public function testIgnoredFiles()
    {
        $structure = [
            'Page.md' => 'another page',
            'Button.md' => 'another page',
            '22.png' => '',
            'ignored.json' => '',
            'dir' => [
                'nofile.md' => 'nocontent',
            ],
            '.git' => [
                'config' => '',
            ],
        ];
        $root = vfsStream::setup('root', null, $structure);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->withValidContentExtensions(['md'])
            ->with([
                'ignore' => [
                    'files' => ['ignored.json'],
                    'folders' => ['dir'],
                ],
            ])
            ->build();

        $tree = new Root($config);
        Builder::build($tree, $config->getIgnore());

        $this->assertEquals(
            ['22.png', 'Button.html', 'Page.html'],
            array_keys($tree->getEntries())
        );
    }

    public function testIndexFrontMatter()
    {
        $structure = [
            'folder' => [
                'index.md' => "---\ntitle: new Title\n---\nThe content",
                'Page.md' => 'another page',
                'Button.md' => 'another page',
            ],
        ];
        $root = vfsStream::setup('root', null, $structure);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->withValidContentExtensions(['md'])
            ->build();

        $tree = new Root($config);
        Builder::build($tree, []);

        $this->assertTrue(array_key_exists('folder', $tree->getEntries()));
        $this->assertEquals('new Title', $tree->getEntries()['folder']->getTitle());
    }
}
