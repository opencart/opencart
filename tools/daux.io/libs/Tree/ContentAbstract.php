<?php namespace Todaymade\Daux\Tree;

abstract class ContentAbstract extends Entry
{
    protected string $content;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
