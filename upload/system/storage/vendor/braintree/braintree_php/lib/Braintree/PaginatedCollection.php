<?php
namespace Braintree;

use Iterator;

/**
 * Braintree PaginatedCollection
 * PaginatedCollection is a container object for paginated data
 *
 * retrieves and pages through large collections of results
 *
 * example:
 * <code>
 * $result = MerchantAccount::all();
 *
 * foreach($result as $merchantAccount) {
 *   print_r($merchantAccount->status);
 * }
 * </code>
 *
 * @package    Braintree
 * @subpackage Utility
 */
class PaginatedCollection implements Iterator
{
    private $_pager;
    private $_pageSize;
    private $_currentPage;
    private $_index;
    private $_totalItems;
    private $_items;

    /**
     * set up the paginated collection
     *
     * expects an array of an object and method to call on it
     *
     * @param array $pager
     */
    public function  __construct($pager)
    {
        $this->_pager = $pager;
        $this->_pageSize = 0;
        $this->_currentPage = 0;
        $this->_totalItems = 0;
        $this->_index = 0;
    }

    /**
     * returns the current item when iterating with foreach
     */
    public function current()
    {
        return $this->_items[($this->_index % $this->_pageSize)];
    }

    public function key()
    {
        return null;
    }

    /**
     * advances to the next item in the collection when iterating with foreach
     */
    public function next()
    {
        ++$this->_index;
    }

    /**
     * rewinds the collection to the first item when iterating with foreach
     */
    public function rewind()
    {
        $this->_index = 0;
        $this->_currentPage = 0;
        $this->_pageSize = 0;
        $this->_totalItems = 0;
        $this->_items = [];
    }

    /**
     * returns whether the current item is valid when iterating with foreach
     */
    public function valid()
    {
        if ($this->_currentPage == 0 || $this->_index % $this->_pageSize == 0 && $this->_index < $this->_totalItems)
        {
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

        $this->_totalItems= $result->getTotalItems();
        $this->_pageSize = $result->getPageSize();
        $this->_items = $result->getCurrentPage();
    }
}
class_alias('Braintree\PaginatedCollection', 'Braintree_PaginatedCollection');
