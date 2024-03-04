<?php 

#if ZEND_DEBUG && defined(ZTS)
function zend_thread_id() : int
{
}