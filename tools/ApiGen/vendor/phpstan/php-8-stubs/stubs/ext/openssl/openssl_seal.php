<?php 

/**
 * @param string $sealed_data
 * @param array $encrypted_keys
 * @param string $iv
 */
function openssl_seal(string $data, &$sealed_data, &$encrypted_keys, array $public_key, string $cipher_algo, &$iv = null) : int|false
{
}