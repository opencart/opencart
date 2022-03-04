<?php

namespace Braintree;

use Iterator;

/**
 * Braintree PaginatedCollection
 * PaginatedCollection is a container object for paginated data
 *
 * Retrieves and pages through large collections of results
 *
 * example:
 * <code>
 * $result = MerchantAccount::all();
 *
 * foreach($result as $merchantAccount) {
 *   print_r($merchantAccount->status);
 * }
 * </code>
 */
class PaginatedCollection implements Iterator
{
    private $_pager;
    private $_pageSize;
    private $_currentPage;
    private $_index;
    private $_totalItems;
    private $_items;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($pager)
    {
        $this->_pager = $pager;
        $this->_pageSize = 0;
        $this->_currentPage = 0;
        $this->_totalItems = 0;
        $this->_index = 0;
    }

    /**
     * Returns the current item when iterating with foreach
     *
     * @return object of the current item
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->_items[($this->_index % $this->_pageSize)];
    }

    /**
     * Returns null
     *
     * @return null
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return null;
    }

    /**
     * Advances to the next item in the collection when iterating with foreach
     *
     * @return object of the next item in the collection
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        ++$this->_index;
    }

    /**
     * Rewinds the collection to the first item when iterating with foreach
     *
     * @return mixed collection with index set to 0
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->_index = 0;
        $this->_currentPage = 0;
        $this->_pageSize = 0;
        $this->_totalItems = 0;
        $this->_items = [];
    }

    /**
     * Returns whether the current item is valid when iterating with foreach
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        if ($this->_currentPage == 0 || $this->_index % $this->_pageSize == 0 && $this->_index < $this->_totalItems) {
            $this->_getNextPage();
        }

        return $this->_index < $this->_totalItems;
    }

    private function _getNextPage()
    {
        $this->_currentPage++;
        $object = $this->_pager['object'];
        $method = $this->_pager['method'];

        if (isset($this->_pager['query'])) {
            $query = $this->_pager['query'];
            $result = call_user_func(
                [$object, $method],
                $query,
                $this->_currentPage
            );
        } else {
            $result = call_user_func(
                [$object, $method],
                $this->_currentPage
            );
        }

        $this->_totalItems = $result->getTotalItems();
        $this->_pageSize = $result->getPageSize();
        $this->_items = $result->getCurrentPage();
    }
}
