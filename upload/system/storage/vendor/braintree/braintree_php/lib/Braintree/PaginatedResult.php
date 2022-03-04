<?php

namespace Braintree;

/**
 * Paginated Results class
 */
class PaginatedResult
{
    private $_totalItems;
    private $_pageSize;
    private $_currentPage;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($totalItems, $pageSize, $currentPage)
    {
        $this->_totalItems = $totalItems;
        $this->_pageSize = $pageSize;
        $this->_currentPage = $currentPage;
    }

    /*
     * Getter method for totalItems
     *
     * @return int
     */
    public function getTotalItems()
    {
        return $this->_totalItems;
    }

    /*
     * Getter method for page size
     *
     * @return int
     */
    public function getPageSize()
    {
        return $this->_pageSize;
    }

    /*
     * Getter method for the current page index
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->_currentPage;
    }
}
