<?php // phpcs:disable PEAR.Commenting.FunctionComment.MissingParamComment

namespace Braintree;

use Countable;
use IteratorAggregate;
use ArrayAccess;
use OutOfRangeException;
use ArrayIterator;

/**
 * Braintree Generic collection
 *
 * Based on Generic Collection class from:
 * {@link http://codeutopia.net/code/library/CU/Collection.php}
 */

class Collection implements Countable, IteratorAggregate, ArrayAccess
{
    protected $_collection = [];

    /**
     * Add a value into the collection
     *
     * @param string $value to be added
     *
     * @return mixed
     */
    public function add($value)
    {
        $this->_collection[] = $value;
    }

    /**
     * Set index's value
     *
     * @param integer $index thats being set
     * @param mixed   $value to set at the index
     *
     * @throws OutOfRangeException
     *
     * @return mixed
     */
    public function set($index, $value)
    {
        if ($index >= $this->count()) {
            throw new OutOfRangeException('Index out of range');
        }

        $this->_collection[$index] = $value;
    }

    /**
     * Remove a value from the collection
     *
     * @param integer $index index to remove
     *
     * @throws OutOfRangeException if index is out of range
     *
     * @return mixed
     */
    public function remove($index)
    {
        if ($index >= $this->count()) {
            throw new OutOfRangeException('Index out of range');
        }

        array_splice($this->_collection, $index, 1);
    }

    /**
     * Return value at index
     *
     * @param integer $index of value to be retrieved
     *
     * @throws OutOfRangeException
     *
     * @return mixed
     */
    public function get($index)
    {
        if ($index >= $this->count()) {
            throw new OutOfRangeException('Index out of range');
        }

        return $this->_collection[$index];
    }

    /**
     * Determine if index exists
     *
     * @param integer $index to check for existence
     *
     * @return boolean
     */
    public function exists($index)
    {
        if ($index >= $this->count()) {
            return false;
        }

        return true;
    }
    /**
     * Return count of items in collection
     * Implements countable
     *
     * @return integer
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->_collection);
    }


    /**
     * Return an iterator
     * Implements IteratorAggregate
     *
     * @return ArrayIterator
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->_collection);
    }

    /**
     * Set offset to value
     * Implements ArrayAccess
     *
     * @param integer $offset
     * @param mixed   $value
     *
     * @see set
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Unset offset
     * Implements ArrayAccess
     *
     * @param integer $offset
     *
     * @see remove
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * get an offset's value
     * Implements ArrayAccess
     *
     * @param integer $offset
     *
     * @see get
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Determine if offset exists
     * Implements ArrayAccess
     *
     * @param integer $offset
     *
     * @see exists
     *
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }
}
