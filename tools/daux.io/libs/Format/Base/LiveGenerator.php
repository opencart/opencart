<?php namespace Todaymade\Daux\Format\Base;

use Todaymade\Daux\Config;
use Todaymade\Daux\Tree\Entry;

interface LiveGenerator extends Generator
{
    /**
     * @return \Todaymade\Daux\Format\Base\Page
     */
    public function generateOne(Entry $node, Config $config);
}
