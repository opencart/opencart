<?php 

#ifdef HAVE_AESGCM
function sodium_crypto_aead_aes256gcm_decrypt(string $ciphertext, string $additional_data, string $nonce, string $key) : string|false
{
}