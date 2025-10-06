<?php namespace Todaymade\Daux\Format\HTML\Test;

use PHPUnit\Framework\TestCase;
use Todaymade\Daux\Config as MainConfig;
use Todaymade\Daux\Format\HTML\ContentTypes\Markdown\CommonMarkConverter;

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

        $this->assertEquals("<h1><a id=\"test\" href=\"#test\" class=\"Permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Test</h1>\n", $converter->convert('# Test')->getContent());
    }

    public function testShouldAddTOCWhenAutoTOCisOn()
    {
        $config = $this->getConfig();
        $config['daux']['html']['auto_toc'] = true;
        $converter = new CommonMarkConverter($config);

        $source = '# Title';
        $expected = <<<'EXPECTED'
            <ul class="TableOfContents">
            <li>
            <a href="#title">Title</a>
            </li>
            </ul>
            <h1><a id="title" href="#title" class="Permalink" aria-hidden="true" title="Permalink">#</a>Title</h1>

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
            <ul class="TableOfContents">
            <li>
            <a href="#title">Title</a>
            </li>
            </ul>
            <h1><a id="title" href="#title" class="Permalink" aria-hidden="true" title="Permalink">#</a>Title</h1>

            EXPECTED;

        $this->assertEquals($expected, $converter->convert($source)->getContent());
    }

    public function testTOCToken()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Title";
        $expected = <<<'EXPECTED'
            <ul class="TableOfContents">
            <li>
            <a href="#title">Title</a>
            </li>
            </ul>
            <h1><a id="title" href="#title" class="Permalink" aria-hidden="true" title="Permalink">#</a>Title</h1>

            EXPECTED;

        $this->assertEquals($expected, $converter->convert($source)->getContent());
    }

    public function testUnicodeTOC()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# 基础操作\n# 操作基础";
        $expected = <<<'EXPECTED'
            <ul class="TableOfContents">
            <li>
            <a href="#ji-chu-cao-zuo">基础操作</a>
            </li>
            <li>
            <a href="#cao-zuo-ji-chu">操作基础</a>
            </li>
            </ul>
            <h1><a id="ji-chu-cao-zuo" href="#ji-chu-cao-zuo" class="Permalink" aria-hidden="true" title="Permalink">#</a>基础操作</h1>
            <h1><a id="cao-zuo-ji-chu" href="#cao-zuo-ji-chu" class="Permalink" aria-hidden="true" title="Permalink">#</a>操作基础</h1>

            EXPECTED;

        $this->assertEquals($expected, $converter->convert($source)->getContent());
    }

    public function testDuplicatedTOC()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Test\n# Test\n# Test";
        $expected = <<<'EXPECTED'
            <ul class="TableOfContents">
            <li>
            <a href="#test">Test</a>
            </li>
            <li>
            <a href="#test-1">Test</a>
            </li>
            <li>
            <a href="#test-2">Test</a>
            </li>
            </ul>
            <h1><a id="test" href="#test" class="Permalink" aria-hidden="true" title="Permalink">#</a>Test</h1>
            <h1><a id="test-1" href="#test-1" class="Permalink" aria-hidden="true" title="Permalink">#</a>Test</h1>
            <h1><a id="test-2" href="#test-2" class="Permalink" aria-hidden="true" title="Permalink">#</a>Test</h1>

            EXPECTED;

        $this->assertEquals($expected, $converter->convert($source)->getContent());
    }

    public function testEscapedTOC()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# TEST : Test";
        $expected = <<<'EXPECTED'
            <ul class="TableOfContents">
            <li>
            <a href="#test-test">TEST : Test</a>
            </li>
            </ul>
            <h1><a id="test-test" href="#test-test" class="Permalink" aria-hidden="true" title="Permalink">#</a>TEST : Test</h1>

            EXPECTED;

        $this->assertEquals($expected, $converter->convert($source)->getContent());
    }

    /**
     * @requires PHP < 8.1
     */
    public function testQuotesWorkCorrectly()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Daux's bug";
        $expected = <<<'EXPECTED'
            <ul class="TableOfContents">
            <li>
            <a href="#daux-s-bug">Daux’s bug</a>
            </li>
            </ul>
            <h1><a id="daux-s-bug" href="#daux-s-bug" class="Permalink" aria-hidden="true" title="Permalink">#</a>Daux’s bug</h1>

            EXPECTED;

        $this->assertEquals($expected, $converter->convert($source)->getContent());
    }
}
