<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class Pager {
    private $_pagingFunc;

    public function __construct($pagingFunc) {
        $this->_pagingFunc = $pagingFunc;
    }

    public function page ($page) {
        return call_user_func($this->_pagingFunc, $page);
    }
}

class PaginatedCollectionTest extends Setup
{
    public function testFetchesOnePageWhenPageAndTotalSizesMatch()
    {

        $pager = [
            'object' => new Pager(function ($page) {
                if ($page > 1)
                {
                    throw new \Exception('too many pages fetched');
                }
                else
                {
                    return new Braintree\PaginatedResult(1, 1, [1]);
                }
            }),
            'method' => 'page',
        ];
        $collection = new Braintree\PaginatedCollection($pager);

        $items = [];
        foreach($collection as $item)
        {
            array_push($items, $item);
        }

        $this->assertEquals(1, $items[0]);
        $this->assertEquals(1, count($items));
    }

    public function testFetchCollectionOfLessThanOnePage()
    {
        $pager = [
            'object' => new Pager(function ($page) {
                if ($page > 1)
                {
                    throw new \Exception('too many pages fetched');
                }
                else
                {
                    return new Braintree\PaginatedResult(2, 5, [1, 2]);
                }
            }),
            'method' => 'page',
        ];
        $collection = new Braintree\PaginatedCollection($pager);

        $items = [];
        foreach($collection as $item)
        {
            array_push($items, $item);
        }

        $this->assertEquals(1, $items[0]);
        $this->assertEquals(2, $items[1]);
        $this->assertEquals(2, count($items));
    }

    public function testFetchesMultiplePages()
    {
        $pager = [
            'object' => new Pager(function ($page) {
                if ($page > 2)
                {
                    throw new \Exception('too many pages fetched');
                }
                else
                {
                    return new Braintree\PaginatedResult(2, 1, [$page]);
                }
            }),
            'method' => 'page',
        ];
        $collection = new Braintree\PaginatedCollection($pager);

        $items = [];
        foreach($collection as $item)
        {
            array_push($items, $item);
        }

        $this->assertEquals(1, $items[0]);
        $this->assertEquals(2, $items[1]);
        $this->assertEquals(2, count($items));
    }
}
