<?php namespace Todaymade\Daux\Format\Base;

abstract class SimplePage implements Page
{
    protected $title;
    protected $content;

    public function __construct($title, $content)
    {
        $this->initializePage($title, $content);
    }

    public function getPureContent()
    {
        return $this->content;
    }

    public function getContent()
    {
        return $this->generatePage();
    }

    protected function initializePage($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    protected function generatePage()
    {
        return $this->content;
    }
}
