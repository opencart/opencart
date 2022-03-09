<?php
namespace Braintree;

class PaginatedResult
{
    private $_totalItems;
    private $_pageSize;
    private $_currentPage;

    public function __construct($totalItems, $pageSize, $currentPage)
    {
        $this->_totalItems = $totalItems;
        $this->_pageSize = $pageSize;
        $this->_currentPage = $currentPage;
    }

    public function getTotalItems()
    {
        return $this->_totalItems;
    }

    public function getPageSize()
    {
        return $this->_pageSize;
    }

    public function getCurrentPage()
    {
        return $this->_currentPage;
    }
}
class_alias('Braintree\PaginatedResult', 'Braintree_PaginatedResult');
