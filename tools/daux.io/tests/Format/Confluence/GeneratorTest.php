<?php
namespace Todaymade\Daux\Format\Confluence;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\Output;
use Todaymade\Daux\Config as GlobalConfig;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Daux;

class GeneratorTest extends TestCase
{
    use ProphecyTrait;

    public static $contentHome = "<p>Homepage, welcome!</p>\n";
    public static $contentPage = "<p>some text content</p>\n";
    public static $contentWidget = "<p>Widget Folder</p>\n";
    public static $contentButton = "<p>Button</p>\n";

    public static $contentDummy = 'The content will come very soon !';

    public static function configurationTestProvider()
    {
        return [
            [
                [],
                "The following options are mandatory for confluence : 'base_url', 'user', 'pass'",
            ],
            [
                [
                    'base_url' => 'confluence.com',
                    'user' => 'admin',
                    'pass' => 'password',
                ],
                "You must specify an 'ancestor_id' or a 'root_id' for confluence.",
            ],
        ];
    }

    /**
     * @dataProvider configurationTestProvider
     */
    public function testConfiguration(array $confluenceConfig, string $expectedMessage)
    {
        $root = vfsStream::setup('root', null, []);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->with([
                'confluence' => $confluenceConfig,
            ])
            ->build();

        $output = new NullOutput();
        $daux = new Daux($config, $output);

        $this->expectException(ConfluenceConfigurationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $generator = new Generator($daux);
    }

    protected function initPublish($config, $tree): GlobalConfig
    {
        $root = vfsStream::setup('root', null, $tree);

        return ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->withValidContentExtensions(['md'])
            ->with([
                'base_url' => '',
                'confluence' => $config,
            ])
            ->build();
    }

    protected function runPublish(GlobalConfig $config, Api $api)
    {
        $width = 50;
        $input = new ArrayInput([]);
        $output = new class() extends Output {
            protected $output = '';

            protected function doWrite(string $message, bool $newline): void
            {
                $this->output .= $message;

                if ($newline) {
                    $this->output .= "\n";
                }
            }

            public function getOutput(): string
            {
                return trim($this->output);
            }
        };

        $daux = new Daux($config, $output);
        $daux->generateTree();

        $generator = new Generator($daux, $api);
        $generator->generateAll($input, $output, $width);

        return $output->getOutput();
    }

    public function testPublishAllPages()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'root_id' => 1,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
            'Widgets' => [
                'index.md' => 'Widget Folder',
                'Button.md' => 'Button',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 0;
        $idRoot = 1;
        $idContent = 2;
        $idPage = 3;
        $idWidgets = 4;
        $idWidgetsButton = 5;

        $api->getPage($idRoot)->willReturn([
            'space_key' => 'DOC',
            'ancestor_id' => $idBase,
            'id' => $idRoot,
            'version' => 1,
        ])->shouldBeCalled();

        $api->setSpace('DOC')->shouldBeCalled();

        // Nothing's published yet, return an empty array
        $api->getList($idRoot, true)->willReturn([]);

        // Create the structure
        $api->createPage($idRoot, 'Content', '')->willReturn($idContent)->shouldBeCalled();
        $api->createPage($idContent, 'Page', self::$contentDummy)->willReturn($idPage)->shouldBeCalled();
        $api->createPage($idRoot, 'Widgets', self::$contentDummy)->willReturn($idWidgets)->shouldBeCalled();
        $api->createPage($idWidgets, 'Button', self::$contentDummy)->willReturn($idWidgetsButton)->shouldBeCalled();

        // Set the content
        $api->updatePage($idBase, $idRoot, 2, 'My Project', self::$contentHome)->shouldBeCalled();
        $api->updatePage($idContent, $idPage, 2, 'Page', self::$contentPage)->shouldBeCalled();
        $api->updatePage($idRoot, $idWidgets, 2, 'Widgets', self::$contentWidget)->shouldBeCalled();
        $api->updatePage($idWidgets, $idWidgetsButton, 2, 'Button', self::$contentButton)->shouldBeCalled();

        $this->runPublish($config, $api->reveal());
    }

    public function testUpdatePublishedPages()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'root_id' => 1,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 0;
        $idRoot = 1;
        $idContent = 2;
        $idPage = 3;

        $api->getPage($idRoot)->willReturn([
            'space_key' => 'DOC',
            'ancestor_id' => $idBase,
            'id' => $idRoot,
            'version' => 1,
            'content' => '<p>Homepage, welcome!</p>',
        ])->shouldBeCalled();

        $api->setSpace('DOC')->shouldBeCalled();

        // Nothing's published yet, return an empty array
        $api->getList($idRoot, true)->willReturn([
            'Content' => [
                'id' => $idContent,
                'ancestor_id' => $idRoot,
                'space_key' => 'DOC',
                'title' => 'Content',
                'version' => 4,
                'content' => '',
                'children' => [
                    'Page' => [
                        'id' => $idPage,
                        'ancestor_id' => $idContent,
                        'space_key' => 'DOC',
                        'title' => 'Page',
                        'version' => 5,
                        'content' => '<p>old Content</p>',
                    ],
                ],
            ],
        ])->shouldBeCalled();

        // Set the content
        $api->updatePage($idBase, $idRoot, Argument::any(), 'My Project', Argument::any())->shouldNotBeCalled();
        $api->updatePage($idContent, $idPage, 6, 'Page', self::$contentPage)->shouldBeCalled();

        $this->runPublish($config, $api->reveal());
    }

    public function testDeletePages()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'root_id' => 1,
            'delete' => true,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 0;
        $idRoot = 1;
        $idContent = 2;
        $idPage = 3;
        $idToDelete = 4;

        $api->getPage($idRoot)->willReturn([
            'space_key' => 'DOC',
            'ancestor_id' => $idBase,
            'id' => $idRoot,
            'version' => 1,
            'content' => '<p>Homepage, welcome!</p>',
        ])->shouldBeCalled();

        $api->setSpace('DOC')->shouldBeCalled();

        // Nothing's published yet, return an empty array
        $api->getList($idRoot, true)->willReturn([
            'Content' => [
                'id' => $idContent,
                'ancestor_id' => $idRoot,
                'space_key' => 'DOC',
                'title' => 'Content',
                'version' => 4,
                'content' => '',
                'children' => [
                    'Page' => [
                        'id' => $idPage,
                        'ancestor_id' => $idContent,
                        'space_key' => 'DOC',
                        'title' => 'Page',
                        'version' => 5,
                        'content' => '<p>some text content</p>',
                    ],
                    'Other Page' => [
                        'id' => $idToDelete,
                        'ancestor_id' => $idContent,
                        'space_key' => 'DOC',
                        'title' => 'Other Page',
                        'version' => 9,
                        'content' => '<p>usless content</p>',
                    ],
                ],
            ],
        ])->shouldBeCalled();

        // No content to set
        $api->updatePage($idBase, $idRoot, Argument::any(), 'My Project', Argument::any())->shouldNotBeCalled();
        $api->updatePage($idContent, $idPage, 6, 'Page', Argument::any())->shouldNotBeCalled();

        // Delete the unneeded page
        $api->deletePage($idToDelete)->shouldBeCalled();

        $output = $this->runPublish($config, $api->reveal());

        $this->assertEquals(
            <<<'EOD'
                Generating Tree ...                     [  OK  ]
                Start Publishing...
                Finding Root Page...
                Getting already published pages...      [  OK  ]
                Create placeholder pages...             [  OK  ]
                Publishing updates...
                - Homepage                              [  OK  ]
                - Content/Page                          [  OK  ]
                Deleting obsolete pages...
                - Content/Other Page
                EOD,
            $output
        );
    }

    public function testWarnAboutDelete()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'root_id' => 1,
            'delete' => false,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 0;
        $idRoot = 1;
        $idContent = 2;
        $idPage = 3;
        $idToDelete = 4;

        $api->getPage($idRoot)->willReturn([
            'space_key' => 'DOC',
            'ancestor_id' => $idBase,
            'id' => $idRoot,
            'version' => 1,
            'content' => '<p>Homepage, welcome!</p>',
        ])->shouldBeCalled();

        $api->setSpace('DOC')->shouldBeCalled();

        // Nothing's published yet, return an empty array
        $api->getList($idRoot, true)->willReturn([
            'Content' => [
                'id' => $idContent,
                'ancestor_id' => $idRoot,
                'space_key' => 'DOC',
                'title' => 'Content',
                'version' => 4,
                'content' => '',
                'children' => [
                    'Page' => [
                        'id' => $idPage,
                        'ancestor_id' => $idContent,
                        'space_key' => 'DOC',
                        'title' => 'Page',
                        'version' => 5,
                        'content' => '<p>some text content</p>',
                    ],
                    'Other Page' => [
                        'id' => $idToDelete,
                        'ancestor_id' => $idContent,
                        'space_key' => 'DOC',
                        'title' => 'Other Page',
                        'version' => 9,
                        'content' => '<p>usless content</p>',
                    ],
                ],
            ],
        ])->shouldBeCalled();

        // No content to set
        $api->updatePage($idBase, $idRoot, Argument::any(), 'My Project', Argument::any())->shouldNotBeCalled();
        $api->updatePage($idContent, $idPage, 6, 'Page', Argument::any())->shouldNotBeCalled();

        // Delete the unneeded page
        $api->deletePage($idToDelete)->shouldNotBeCalled();

        $output = $this->runPublish($config, $api->reveal());

        $this->assertEquals(
            <<<'EOD'
                Generating Tree ...                     [  OK  ]
                Start Publishing...
                Finding Root Page...
                Getting already published pages...      [  OK  ]
                Create placeholder pages...             [  OK  ]
                Publishing updates...
                - Homepage                              [  OK  ]
                - Content/Page                          [  OK  ]
                Listing obsolete pages...
                > The following pages will not be deleted, but just listed for information.
                > If you want to delete these pages, you need to set the --delete flag on the command.
                - Content/Other Page
                EOD,
            $output
        );
    }

    public function testFindRootByAncestor()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'ancestor_id' => 1,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 1;
        $idRoot = 10;
        $idContent = 11;
        $idPage = 12;

        $api->getList($idBase)->willReturn([
            'My Project' => [
                'title' => 'My Project',
                'space_key' => 'DOC',
                'id' => $idRoot,
                'ancestor_id' => $idBase,
                'version' => 1,
            ],
            'Other page' => [
                'title' => 'Other page',
                'space_key' => 'DOC',
                'id' => 4,
                'ancestor_id' => $idBase,
                'version' => 1,
            ],
        ])->shouldBeCalled();

        $api->setSpace('DOC')->shouldBeCalled();

        // Nothing's published yet, return an empty array
        $api->getList($idRoot, true)->willReturn([])->shouldBeCalled();

        // Create the structure
        $api->createPage($idRoot, 'Content', '')->willReturn($idContent)->shouldBeCalled();
        $api->createPage($idContent, 'Page', self::$contentDummy)->willReturn($idPage)->shouldBeCalled();

        // Set the content
        $api->updatePage($idBase, $idRoot, 2, 'My Project', self::$contentHome)->shouldBeCalled();
        $api->updatePage($idContent, $idPage, 2, 'Page', self::$contentPage)->shouldBeCalled();

        $this->runPublish($config, $api->reveal());
    }

    public function testCreateRootAndPublishAllPages()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'ancestor_id' => 1,
            'create_root_if_missing' => true,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 1;
        $idRoot = 10;
        $idContent = 11;
        $idPage = 12;

        // Page not found in ancestor
        $api->getList($idBase)->willReturn([])->shouldBeCalled();

        // Page not found so we are getting the ancestor to get the space key
        $api->getPage($idBase)->willReturn([
            'space_key' => 'DOC',
            'id' => $idBase,
            'version' => 1,
        ])->shouldBeCalled();

        $api->setSpace('DOC')->shouldBeCalled();

        // Get page
        $api->createPage($idBase, 'My Project', self::$contentDummy)->willReturn($idRoot)->shouldBeCalled();

        // Get details now that we have the page
        $api->getPage($idRoot)->willReturn([
            'space_key' => 'DOC',
            'ancestor_id' => $idBase,
            'id' => $idRoot,
            'version' => 1,
        ])->shouldBeCalled();

        // Nothing's published yet, return an empty array
        $api->getList($idRoot, true)->willReturn([])->shouldBeCalled();

        // Create the structure
        $api->createPage($idRoot, 'Content', '')->willReturn($idContent)->shouldBeCalled();
        $api->createPage($idContent, 'Page', self::$contentDummy)->willReturn($idPage)->shouldBeCalled();

        // Set the content
        $api->updatePage($idBase, $idRoot, 2, 'My Project', self::$contentHome)->shouldBeCalled();
        $api->updatePage($idContent, $idPage, 2, 'Page', self::$contentPage)->shouldBeCalled();

        $this->runPublish($config, $api->reveal());
    }

    public function testCreateRootWithSiblingSpaceAndPublishAllPages()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'ancestor_id' => 1,
            'create_root_if_missing' => true,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 1;
        $idRoot = 10;
        $idContent = 11;
        $idPage = 12;

        // Page not found in ancestor
        $api->getList($idBase)->willReturn([
            'Other page' => [
                'title' => 'Other page',
                'space_key' => 'DOC',
                'id' => 4,
                'ancestor_id' => $idBase,
                'version' => 1,
            ],
        ])->shouldBeCalled();

        $api->setSpace('DOC')->shouldBeCalled();

        // Get page
        $api->createPage($idBase, 'My Project', self::$contentDummy)->willReturn($idRoot)->shouldBeCalled();

        // Get details now that we have the page
        $api->getPage($idRoot)->willReturn([
            'space_key' => 'DOC',
            'ancestor_id' => $idBase,
            'id' => $idRoot,
            'version' => 1,
        ])->shouldBeCalled();

        // Nothing's published yet, return an empty array
        $api->getList($idRoot, true)->willReturn([])->shouldBeCalled();

        // Create the structure
        $api->createPage($idRoot, 'Content', '')->willReturn($idContent)->shouldBeCalled();
        $api->createPage($idContent, 'Page', self::$contentDummy)->willReturn($idPage)->shouldBeCalled();

        // Set the content
        $api->updatePage($idBase, $idRoot, 2, 'My Project', self::$contentHome)->shouldBeCalled();
        $api->updatePage($idContent, $idPage, 2, 'Page', self::$contentPage)->shouldBeCalled();

        $this->runPublish($config, $api->reveal());
    }

    public function testRootNotFound()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'ancestor_id' => 1,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 1;

        // Page not found in ancestor
        $api->getList($idBase)->willReturn([])->shouldBeCalled();

        $this->expectException(ConfluenceConfigurationException::class);
        $this->expectExceptionMessage(
            'Could not find a page named \'My Project\' with the specified ancestor_id.'
            . ' To create the page automatically, add \'"create_root_if_missing": true\' in the \'confluence\''
            . ' section of your Daux configuration.'
        );

        $this->runPublish($config, $api->reveal());
    }

    public function testRootNotFoundButFoundSiblings()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'ancestor_id' => 1,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 1;

        // Page not found in ancestor
        $api->getList($idBase)->willReturn([
            'Other page' => [
                'title' => 'Other page',
                'space_key' => 'DOC',
                'id' => 4,
                'ancestor_id' => $idBase,
                'version' => 1,
            ],
        ])->shouldBeCalled();

        $this->expectException(ConfluenceConfigurationException::class);
        $this->expectExceptionMessage(
            "Could not find a page named 'My Project' but found ['Other page']."
            . ' To create the page automatically, add \'"create_root_if_missing": true\' in the \'confluence\''
            . ' section of your Daux configuration.'
        );

        $this->runPublish($config, $api->reveal());
    }

    public function testPublishAttachments()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'root_id' => 10,
            'create_root_if_missing' => true,
        ], [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content ![an image](./image.svg)',
                'image.svg' => '<xml>',
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 1;
        $idRoot = 10;
        $idContent = 11;
        $idPage = 12;

        // Get details now that we have the page
        $api->getPage($idRoot)->willReturn([
            'space_key' => 'DOC',
            'ancestor_id' => $idBase,
            'id' => $idRoot,
            'version' => 1,
        ])->shouldBeCalled();

        $api->setSpace('DOC')->shouldBeCalled();

        // Nothing's published yet, return an empty array
        $api->getList($idRoot, true)->willReturn([])->shouldBeCalled();

        // Create the structure
        $api->createPage($idRoot, 'Content', '')->willReturn($idContent)->shouldBeCalled();
        $api->createPage($idContent, 'Page', self::$contentDummy)->willReturn($idPage)->shouldBeCalled();

        // Set the content
        $api->updatePage($idBase, $idRoot, 2, 'My Project', self::$contentHome)->shouldBeCalled();
        $img = '<p>some text content <ac:image ac:alt="an image">'
            . "<ri:attachment ri:filename=\"image.svg\" /></ac:image></p>\n";
        $api->updatePage($idContent, $idPage, 2, 'Page', $img)->shouldBeCalled();
        $api->uploadAttachment(
            $idPage,
            Argument::allOf(
                Argument::withEntry('filename', 'image.svg'),
                Argument::withEntry('file', Argument::type(\Todaymade\Daux\Tree\Raw::class))
            ),
            Argument::type('callable')
        )->shouldBeCalled();

        $this->runPublish($config, $api->reveal());
    }

    public function testMixed()
    {
        $config = $this->initPublish([
            'base_url' => 'confluence.com',
            'user' => 'admin',
            'pass' => 'password',
            'root_id' => 10,
            'delete' => true,
            'print_diff' => true,
        ], [
            'index.md' => 'Homepage, welcome!', // identical content, don't update
            'Content' => [
                'Page.md' => 'some text content ,[an image](./image.svg)', // should not be updated
                'image.svg' => '<xml>',
                // "Other page" is getting deleted
            ],
            'Widgets' => [
                'index.md' => 'this will get created', // new page
                'Button.md' => 'A new Button !', // new page
            ],
        ]);

        $api = $this->prophesize(Api::class);

        $idBase = 1;
        $idRoot = 10;
        $idContent = 11;
        $idPage = 12;
        $idToDelete = 13;

        // Get details now that we have the page
        $api->getPage($idRoot)->willReturn([
            'space_key' => 'DOC',
            'ancestor_id' => $idBase,
            'id' => $idRoot,
            'version' => 1,
        ])->shouldBeCalled();

        $api->setSpace('DOC')->shouldBeCalled();

        // Nothing's published yet, return an empty array
        $api->getList($idRoot, true)->willReturn([
            'Content' => [
                'id' => $idContent,
                'ancestor_id' => $idRoot,
                'space_key' => 'DOC',
                'title' => 'Content',
                'version' => 4,
                'content' => '',
                'children' => [
                    'Page' => [
                        'id' => $idPage,
                        'ancestor_id' => $idContent,
                        'space_key' => 'DOC',
                        'title' => 'Page',
                        'version' => 5,
                        'content' => '<p>some text content</p>',
                    ],
                    'Other Page' => [
                        'id' => $idToDelete,
                        'ancestor_id' => $idContent,
                        'space_key' => 'DOC',
                        'title' => 'Other Page',
                        'version' => 9,
                        'content' => '<p>usless content</p>',
                    ],
                ],
            ],
        ])->shouldBeCalled();

        // print_diff is a dry run, no modifications should be made
        $api->createPage(Argument::any(), Argument::any(), Argument::any())->shouldNotBeCalled();
        $api->updatePage(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())
            ->shouldNotBeCalled();
        $api->uploadAttachment(Argument::any(), Argument::any(), Argument::any())->shouldNotBeCalled();
        $api->deletePage(Argument::any())->shouldNotBeCalled();

        $output = $this->runPublish($config, $api->reveal());

        $this->assertEquals(
            <<<'EOD'
                Generating Tree ...                     [  OK  ]
                Start Publishing...
                Finding Root Page...
                Getting already published pages...      [  OK  ]
                The following changes will be applied
                - My Project (update)
                  - Content (update)
                    - Page (update)
                    - Other Page (delete)
                  - Widgets (create)
                    - Button (create)
                EOD,
            $output
        );
    }
}
