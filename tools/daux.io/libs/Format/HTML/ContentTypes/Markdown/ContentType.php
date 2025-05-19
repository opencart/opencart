<?php namespace Todaymade\Daux\Format\HTML\ContentTypes\Markdown;

class ContentType extends \Todaymade\Daux\ContentTypes\Markdown\ContentType
{
    protected function createConverter()
    {
        return new CommonMarkConverter(['daux' => $this->config]);
    }
}
