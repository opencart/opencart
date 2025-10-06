<?php
namespace Todaymade\Daux\Format\Template;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Todaymade\Daux\Config;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Format\HTML\Template;
use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Root;

/**
 * Class TranslateTest.
 */
class TranslateTest extends TestCase
{
    protected function getTree(Config $config)
    {
        $structure = [
            'en' => [
                'Page.md' => 'some text content',
            ],
            'it' => [
                'Page.md' => 'another page',
            ],
        ];
        $root = vfsStream::setup('root', null, $structure);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->withValidContentExtensions(['md'])
            ->build();

        $tree = new Root($config);
        Builder::build($tree, []);

        return $tree;
    }

    public static function translateDataProvider()
    {
        return [
            ['Previous', 'en'],
            ['Pagina precedente', 'it'],
        ];
    }

    /**
     * @dataProvider translateDataProvider
     *
     * @param mixed $expectedTranslation
     * @param mixed $language
     */
    public function testTranslate($expectedTranslation, $language)
    {
        $current = $language . '/Page.html';

        $config = new Config();
        $config->setTree($this->getTree($config));
        $config->setValue('docs_directory', '');
        $config->setValue('valid_content_extensions', []);

        $root = new Root($config);
        $entry = Builder::getOrCreatePage($root, 'index');

        $config->merge([
            'title' => '',
            'index' => $entry,
            'language' => $language,
            'base_url' => '',
            'templates' => '',
            'page' => [
                'language' => $language,
            ],
            'html' => [
                'search' => '',
                'toggle_code' => false,
                'piwik_analytics' => '',
                'google_analytics' => '',
            ],
            'theme' => [
                'js' => [''],
                'css' => [''],
                'fonts' => [''],
                'favicon' => '',
                'templates' => 'name',
            ],
            'strings' => [
                'en' => ['Link_previous' => 'Previous'],
                'it' => ['Link_previous' => 'Pagina precedente'],
            ],
        ]);

        $config->setCurrentPage(DauxHelper::getFile($config->getTree(), $current));

        $template = new Template($config);
        $value = $template->getEngine($config)->getFunction('translate')->call(null, ['Link_previous']);

        $this->assertEquals($expectedTranslation, $value);
    }
}
