<?php namespace Todaymade\Daux\Format\Base;

use Todaymade\Daux\Exception;

abstract class RawPage implements Page
{
    protected $file;

    public function __construct($filename)
    {
        $this->file = $filename;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getPureContent()
    {
        throw new Exception('you should not use getPureContent() to show a raw content');
    }

    public function getContent()
    {
        throw new Exception('you should not use getContent() to show a raw content');
    }
}
