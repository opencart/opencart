<?php 

#[\Until('8.1')]
function hash_init(string $algo, int $flags = 0, string $key = "") : \HashContext
{
}
/** @refcount 1 */
#[\Since('8.1')]
function hash_init(string $algo, int $flags = 0, string $key = "", array $options = []) : \HashContext
{
}