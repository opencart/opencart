<?php
namespace Todaymade\Daux\Tree;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Daux;

class RootTest extends TestCase
{
    public function testHotPath()
    {
        $root = vfsStream::setup('root', null, [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
            'Widgets' => [
                'index.md' => 'Widget Folder',
                'Button.md' => 'Button',
            ],
        ]);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->withValidContentExtensions(['md'])
            ->build();

        $output = new NullOutput();
        $daux = new Daux($config, $output);
        $daux->generateTree();

        $root = $daux->tree;

        $this->assertFalse($root->isHotPath($root['Widgets']['Button.html']));

        $root->setActiveNode($root['Widgets']['Button.html']);
        $this->assertTrue($root->isHotPath($root['Widgets']['Button.html']));
        $this->assertTrue($root['Widgets']['Button.html']->isHotPath());
        $this->assertTrue($root->isHotPath($root['Widgets']));
        $this->assertFalse($root->isHotPath($root['Content']['Page.html']));

        $this->assertTrue($root->isHotPath()); // Root is always on the hot path
    }
}
