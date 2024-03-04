<?php 

#[\Until('8.2')]
function sodium_crypto_generichash_update(string &$state, string $message) : bool
{
}
#[\Since('8.2')]
function sodium_crypto_generichash_update(string &$state, string $message) : true
{
}