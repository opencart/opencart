<?php
namespace Todaymade\Daux\Format\HTML;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Daux;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Format\Base\Page;

class GeneratorTest extends TestCase
{
    protected function getDaux($moreConfig = [])
    {
        $root = vfsStream::setup('root', null, [
            'index.md' => 'Homepage, welcome!',
            'Content' => [
                'Page.md' => 'some text content',
            ],
            'Widgets' => [
                'index.md' => 'Widget Folder',
                'Button.md' => 'Button',
                'image.svg' => '<xml...>',
            ],
        ]);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->withValidContentExtensions(['md'])
            ->with($moreConfig)
            ->build();

        $output = new NullOutput();
        $daux = new Daux($config, $output);
        $daux->generateTree();

        return $daux;
    }

    public function testGenerateSinglePage()
    {
        $daux = $this->getDaux();
        $generator = new Generator($daux);

        $config = $daux->getConfig();
        $baseUrl = '../';

        DauxHelper::rebaseConfiguration($config, $baseUrl);

        $daux->tree->setActiveNode($daux->tree['Widgets']['Button.html']);
        $generated = $generator->generateOne($daux->tree['Widgets']['Button.html'], $config);

        $this->assertInstanceOf(ContentPage::class, $generated);

        $content = $generated->getContent();

        // Assert previous page is present
        $this->assertStringContainsString('<li class=Pager--prev><a href="../Widgets/index.html">Previous</a></li>', $content);

        // Assert breadcrumb presence
        $this->assertStringContainsString('<a href="../Widgets/index.html">Widgets</a> <svg ', $content);
        $this->assertStringContainsString('</svg> <a href="../Widgets/Button.html">Button</a>', $content);

        // Assert search elements presence
        $this->assertStringContainsString('class="Search__field"', $content);
        $this->assertStringContainsString('<script type="text/javascript" src="../daux_libraries/search.min.js"', $content);
        $this->assertStringContainsString("window.search({'base_url': '../'})", $content);
        $this->assertStringContainsString("<link href='../daux_libraries/search.css' rel='stylesheet' type='text/css'>", $content);

        // Assert nav is correct
        $this->assertStringContainsString('class=\'Nav__item Nav__item--open has-children\'><a href="../Widgets/index.html"', $content);
        $this->assertStringContainsString('<li class=\'Nav__item Nav__item--active\'><a href="../Widgets/Button.html">', $content);
    }

    public function testGenerateAnalytics()
    {
        $daux = $this->getDaux([
            'html' => [
                'google_analytics' => 'ua_45734258',
                'plausible_domain' => 'daux.io',
                'piwik_analytics' => 'piwik.com',
                'piwik_analytics_id' => 12345,
            ],
        ]);
        $generator = new Generator($daux);

        $config = $daux->getConfig();
        $baseUrl = '../';

        DauxHelper::rebaseConfiguration($config, $baseUrl);

        $daux->tree->setActiveNode($daux->tree['Widgets']['Button.html']);
        $generated = $generator->generateOne($daux->tree['Widgets']['Button.html'], $config);

        $this->assertInstanceOf(ContentPage::class, $generated);

        $content = $generated->getContent();

        // Google Analytics
        $this->assertStringContainsString("ga('create', 'ua_45734258', '');", $content);

        // Piwik
        $this->assertStringContainsString('://piwik.com/', $content);
        $this->assertStringContainsString('["setSiteId", 12345]', $content);

        // Plausible.io
        $this->assertStringContainsString('<script async defer data-domain="daux.io" src="https://plausible.io/js/plausible.js"></script>', $content);
    }

    public function testGenerateSinglePageWithoutSearch()
    {
        $daux = $this->getDaux(['html' => ['search' => false]]);
        $generator = new Generator($daux);

        $config = $daux->getConfig();
        $baseUrl = '../';

        DauxHelper::rebaseConfiguration($config, $baseUrl);

        $daux->tree->setActiveNode($daux->tree['Widgets']['Button.html']);
        $generated = $generator->generateOne($daux->tree['Widgets']['Button.html'], $config);

        $this->assertInstanceOf(ContentPage::class, $generated);

        $content = $generated->getContent();

        // Assert previous page is present
        $this->assertStringContainsString('<li class=Pager--prev><a href="../Widgets/index.html">Previous</a></li>', $content);

        // Assert breadcrumb presence
        $this->assertStringContainsString('<a href="../Widgets/index.html">Widgets</a> <svg ', $content);
        $this->assertStringContainsString('</svg> <a href="../Widgets/Button.html">Button</a>', $content);

        // Assert search elements presence
        $this->assertStringNotContainsString('class="Search__field"', $content);
        $this->assertStringNotContainsString('<script type="text/javascript" src="../daux_libraries/search.min.js"', $content);
        $this->assertStringNotContainsString("window.search({'base_url': '../'})", $content);
        $this->assertStringNotContainsString("<link href='../daux_libraries/search.css' rel='stylesheet' type='text/css'>", $content);

        // Assert nav is correct
        $this->assertStringContainsString('class=\'Nav__item Nav__item--open has-children\'><a href="../Widgets/index.html"', $content);
        $this->assertStringContainsString('<li class=\'Nav__item Nav__item--active\'><a href="../Widgets/Button.html">', $content);
    }

    public function testGenerateSidebarLinks()
    {
        $daux = $this->getDaux(['html' => [
            'search' => false,
            'links' => [
                'Bing' => 'https://bing.com',
                'Google' => 'https://google.com',
            ],
            'twitter' => ['onigoetz'],
            'powered_by' => 'Powered by Daux.io',
        ]]);
        $generator = new Generator($daux);

        $config = $daux->getConfig();
        $baseUrl = '../';

        DauxHelper::rebaseConfiguration($config, $baseUrl);

        $daux->tree->setActiveNode($daux->tree['Widgets']['Button.html']);
        $generated = $generator->generateOne($daux->tree['Widgets']['Button.html'], $config);

        $this->assertInstanceOf(ContentPage::class, $generated);

        $content = $generated->getContent();

        // Twitter link
        $this->assertStringContainsString('<span class="Twitter__button__label">Follow @onigoetz</span>', $content);

        // Other links
        $this->assertStringContainsString('<a href="https://bing.com" target="_blank"  rel="noopener noreferrer">Bing<', $content);
        $this->assertStringContainsString('<a href="https://google.com" target="_blank"  rel="noopener noreferrer">Google<', $content);

        $this->assertStringContainsString('Powered by Daux.io', $content);
    }

    public function testGenerateLandingPage()
    {
        $daux = $this->getDaux(['html' => [
            'search' => false,
            'repo' => 'dauxio/daux.io',
            'links' => [
                'Bing' => 'https://bing.com',
                'Google' => 'https://google.com',
            ],
            'buttons' => [
                'Gitlab' => 'https://gitlab.com',
            ],
        ]]);
        $generator = new Generator($daux);

        $config = $daux->getConfig();
        $baseUrl = '';

        DauxHelper::rebaseConfiguration($config, $baseUrl);

        $daux->tree->setActiveNode($daux->tree['index.html']);
        $generated = $generator->generateOne($daux->tree['index.html'], $config);

        $this->assertInstanceOf(ContentPage::class, $generated);

        $content = $generated->getContent();

        // Twitter link
        $this->assertStringContainsString('Homepage, welcome!', $content);

        // Extra buttons
        $this->assertStringContainsString('<a href="https://gitlab.com" class="Button Button--secondary Button--hero">Gitlab</a>', $content);

        // Other links
        $this->assertStringContainsString('<a href="Content/Page.html" class="Button Button--primary Button--hero">View Documentation', $content);
        $this->assertStringContainsString('<a href="https://github.com/dauxio/daux.io" class="Button Button--secondary Button--hero"', $content);
    }

    public function testGenerateRawPage()
    {
        $daux = $this->getDaux();
        $generator = new Generator($daux);

        $config = $daux->getConfig();
        $baseUrl = '../';

        DauxHelper::rebaseConfiguration($config, $baseUrl);

        $daux->tree->setActiveNode($daux->tree['Widgets']['image.svg']);
        $generated = $generator->generateOne($daux->tree['Widgets']['image.svg'], $config);

        $this->assertInstanceOf(RawPage::class, $generated);

        $this->assertEquals('<xml...>', file_get_contents($generated->getFile()));
    }
}
