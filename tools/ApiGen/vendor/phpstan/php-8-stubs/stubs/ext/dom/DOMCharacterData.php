<?php 

class DOMCharacterData extends \DOMNode implements \DOMChildNode
{
    /**
     * @tentative-return-type
     * @return bool
     */
    public function appendData(string $data)
    {
    }
    /** @return string|false */
    public function substringData(int $offset, int $count)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function insertData(int $offset, string $data)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function deleteData(int $offset, int $count)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function replaceData(int $offset, int $count, string $data)
    {
    }
    /** @param DOMNode|string $nodes */
    public function replaceWith(...$nodes) : void
    {
    }
    public function remove() : void
    {
    }
    /** @param DOMNode|string $nodes */
    public function before(...$nodes) : void
    {
    }
    /** @param DOMNode|string $nodes */
    public function after(...$nodes) : void
    {
    }
}