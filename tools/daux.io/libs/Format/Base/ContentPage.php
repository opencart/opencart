<?php namespace Todaymade\Daux\Format\Base;

use Todaymade\Daux\Config;
use Todaymade\Daux\ContentTypes\ContentType;
use Todaymade\Daux\Tree\Content;

abstract class ContentPage extends SimplePage
{
    protected Content $file;

    protected Config $config;

    protected ContentType $contentType;

    protected $generatedContent;

    public function __construct($title, $content)
    {
        $this->initializePage($title, $content);
    }

    public function setFile(Content $file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @deprecated use setConfig instead
     */
    public function setParams(Config $config)
    {
        $this->setConfig($config);
    }

    /**
     * @param ContentType $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    public function getPureContent()
    {
        if (!$this->generatedContent) {
            $this->generatedContent = $this->contentType->convert($this->content, $this->getFile());
        }

        return $this->generatedContent;
    }

    protected function generatePage()
    {
        return $this->getPureContent();
    }

    public static function fromFile(Content $file, $config, ContentType $contentType)
    {
        $page = new static($file->getTitle(), $file->getContent());
        $page->setFile($file);
        $page->setConfig($config);
        $page->setContentType($contentType);

        return $page;
    }
}
