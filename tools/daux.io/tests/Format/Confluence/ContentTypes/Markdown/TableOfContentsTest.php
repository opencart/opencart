<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use PHPUnit\Framework\TestCase;
use Todaymade\Daux\Config as MainConfig;

class Engine
{
    public function render($template, $data)
    {
        return $data['content'];
    }
}

class Template extends \Todaymade\Daux\Format\HTML\Template
{
    public function getEngine(MainConfig $config)
    {
        return new Engine();
    }
}

class TableOfContentsTest extends TestCase
{
    public function getConfig()
    {
        $config = new MainConfig();
        $config->setTemplateRenderer(new Template($config));

        return ['daux' => $config];
    }

    public function testNoTOCByDefault()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $this->assertEquals("<h1>Test</h1>\n", $converter->convert('# Test')->getContent());
    }

    public function testTOCToken()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Title";
        $expected = <<<'EXPECTED'
            <ac:structured-macro ac:name="toc"></ac:structured-macro>
            <h1>Title</h1>

            EXPECTED;

        $this->assertEquals($expected, $converter->convert($source)->getContent());
    }

    public function testShouldAddTOCWhenAutoTOCisOn()
    {
        $config = $this->getConfig();
        $config['daux']['html']['auto_toc'] = true;
        $converter = new CommonMarkConverter($config);

        $source = '# Title';
        $expected = <<<'EXPECTED'
            <ac:structured-macro ac:name="toc"></ac:structured-macro>
            <h1>Title</h1>

            EXPECTED;

        $this->assertEquals($expected, $converter->convert($source)->getContent());
    }

    public function testShouldNotAddTOCWhenAutoTOCisOnAndTOCisPresent()
    {
        $config = $this->getConfig();
        $config['daux']['html']['auto_toc'] = true;
        $converter = new CommonMarkConverter($config);

        $source = "Some Content\n[TOC]\n# Title";
        $expected = <<<'EXPECTED'
            <p>Some Content</p>
            <ac:structured-macro ac:name="toc"></ac:structured-macro>
            <h1>Title</h1>

            EXPECTED;

        $this->assertEquals($expected, $converter->convert($source)->getContent());
    }
}
