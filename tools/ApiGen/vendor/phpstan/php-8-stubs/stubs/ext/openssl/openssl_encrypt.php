<?php 

/** @param string $tag */
function openssl_encrypt(string $data, string $cipher_algo, string $passphrase, int $options = 0, string $iv = "", &$tag = null, string $aad = "", int $tag_length = 16) : string|false
{
}