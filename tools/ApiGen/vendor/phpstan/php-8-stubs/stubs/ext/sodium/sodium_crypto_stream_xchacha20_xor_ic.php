<?php 

#[\Since('8.2')]
function sodium_crypto_stream_xchacha20_xor_ic(#[\SensitiveParameter] string $message, string $nonce, int $counter, #[\SensitiveParameter] string $key) : string
{
}