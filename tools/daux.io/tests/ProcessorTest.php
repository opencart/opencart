<?php namespace Todaymade\Daux;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Directory;
use Todaymade\Daux\Tree\Root;

class ProcessorTest extends TestCase
{
    public function dump($node)
    {
        $details = [
            'title' => $node['title'],
            'url' => $node['url'],
        ];

        if (array_key_exists('children', $node)) {
            $details['children'] = [];
            foreach ($node['children'] as $child) {
                $details['children'][] = $this->dump($child);
            }
        }

        return $details;
    }

    public function testAddPage()
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

        $width = 80;
        $input = new ArrayInput([]);

        $output = new NullOutput();
        $daux = new Daux($config, $output);

        $daux->setProcessor(new class($daux, $output, $width) extends Processor {
            public function manipulateTree(Root $root)
            {
                $new = Builder::getOrCreateDir($root, 'New Pages');

                $movies = Builder::getOrCreateDir($new, 'Movies');

                $page = Builder::getOrCreatePage($movies, 'index');
                $page->setContent('The index page for the new folder');

                $page = Builder::getOrCreatePage($movies, 'A New Hope');
                $page->setContent('A long time ago in a galaxy far away');

                // Delete through method or array native
                $root['Widgets']->removeChild($root['Widgets']['index.html']);
                unset($root['Widgets']['Button.html']);

                $dir = new Directory($root['Widgets'], 'somedir');
                $dir->setTitle('Somedir');
            }
        });

        $daux->generateTree();

        $this->assertEquals(
            [
                'title' => null,
                'url' => 'vfs://root',
                'children' => [
                    [
                        'title' => 'My Project',
                        'url' => 'index.html',
                    ],
                    [
                        'title' => 'Content',
                        'url' => 'Content',
                        'children' => [
                            [
                                'title' => 'Page',
                                'url' => 'Content/Page.html',
                            ],
                        ],
                    ],
                    [
                        'title' => 'New Pages',
                        'url' => 'New_Pages',
                        'children' => [
                            [
                                'title' => 'Movies',
                                'url' => 'New_Pages/Movies',
                                'children' => [
                                    [
                                        'title' => 'A New Hope',
                                        'url' => 'New_Pages/Movies/A_New_Hope.html',
                                    ],
                                    [
                                        'title' => 'Movies',
                                        'url' => 'New_Pages/Movies/index.html',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Widgets',
                        'url' => 'Widgets',
                        'children' => [
                            ['title' => 'Somedir', 'url' => 'Widgets/somedir'],
                        ],
                        // node still exists but children are deleted
                    ],
                ],
            ],
            $this->dump($daux->tree->dump())
        );

        $this->assertFalse($daux->tree['Widgets']->hasContent());
        $this->assertTrue($daux->tree['New_Pages']->hasContent());
        $this->assertTrue($daux->tree->hasContent());

        $this->assertFalse($daux->tree['New_Pages']['Movies']['A_New_Hope.html']->isIndex());
        $this->assertTrue($daux->tree['New_Pages']['Movies']['index.html']->isIndex());

        $this->assertTrue(isset($daux->tree['New_Pages']['Movies']['A_New_Hope.html']));
    }
}
