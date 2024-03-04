<?php 

#[\Until('8.1')]
function openssl_decrypt(string $data, string $cipher_algo, string $passphrase, int $options = 0, string $iv = "", string $tag = "", string $aad = "") : string|false
{
}
#[\Since('8.1')]
function openssl_decrypt(string $data, string $cipher_algo, string $passphrase, int $options = 0, string $iv = "", ?string $tag = null, string $aad = "") : string|false
{
}