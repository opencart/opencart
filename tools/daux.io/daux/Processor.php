<?php namespace Todaymade\Daux\Extension;

use Todaymade\Daux\Tree\Root;

class Processor extends \Todaymade\Daux\Processor
{
    public function manipulateTree(Root $root)
    {
        print_r($root->dump());
    }
}
