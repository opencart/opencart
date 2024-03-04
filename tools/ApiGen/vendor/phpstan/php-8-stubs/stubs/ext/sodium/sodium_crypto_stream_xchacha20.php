<?php 

#if defined(crypto_stream_xchacha20_KEYBYTES)
#[\Since('8.1')]
function sodium_crypto_stream_xchacha20(int $length, string $nonce, string $key) : string
{
}