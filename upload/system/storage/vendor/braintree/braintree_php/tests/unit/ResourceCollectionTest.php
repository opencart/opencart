<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class TestResource
{
    public function lookup($id) {
        return ResourceCollectionTest::$values[intval($id)];
    }

    public function fetch($ids)
    {

        return array_map(__NAMESPACE__ . '\TestResource::lookup', $ids);
    }
}

class ResourceCollectionTest extends Setup
{
    public static $values = ["a", "b", "c", "d", "e"];

    public function testIterateOverResults()
    {

        $response = [
            'searchResults' => [
                'pageSize' => 2,
                'ids' => ['0', '1', '2', '3', '4']
            ]
        ];

        $object = new TestResource();
        $pager = [
            'object' => $object,
            'method' => 'fetch',
            'methodArgs' => []
        ];

        $collection = new Braintree\ResourceCollection($response, $pager);

        $count = 0;
        $index = 0;
        foreach ($collection as $value)
        {
            $this->assertEquals(self::$values[$index], $value);
            $index += 1;
            $count += 1;
        }

        $this->assertEquals(5, $count);
    }

    public function testDoesntIterateWhenNoResults()
    {

        $response = [
            'searchResults' => [
                'pageSize' => 2,
                'ids' => []
            ]
        ];

        $object = new TestResource();
        $pager = [
            'object' => $object,
            'method' => 'fetch',
            'methodArgs' => []
        ];

        $collection = new Braintree\ResourceCollection($response, $pager);

        $count = 0;
        $index = 0;
        foreach ($collection as $value)
        {
            $index += 1;
            $count += 1;
            break;
        }

        $this->assertEquals(0, $count);
        $this->assertEquals(0, $index);
    }

    public function testGetIdsReturnsArrayOfIds()
    {

        $response = [
            'searchResults' => [
                'pageSize' => 2,
                'ids' => ["1", "2", "3"]
            ]
        ];

        $object = new TestResource();
        $pager = [
            'object' => $object,
            'method' => 'fetch',
            'methodArgs' => []
        ];

        $collection = new Braintree\ResourceCollection($response, $pager);

        $this->assertEquals($collection->getIds(), ["1", "2", "3"]);
    }

    public function testGetIdsReturnsAnEmptyArrayIfNoIds()
    {

        $response = [
            'searchResults' => [
                'pageSize' => 2,
                'ids' => []
            ]
        ];

        $object = new TestResource();
        $pager = [
            'object' => $object,
            'method' => 'fetch',
            'methodArgs' => []
        ];

        $collection = new Braintree\ResourceCollection($response, $pager);

        $this->assertEquals($collection->getIds(), []);
    }
}
