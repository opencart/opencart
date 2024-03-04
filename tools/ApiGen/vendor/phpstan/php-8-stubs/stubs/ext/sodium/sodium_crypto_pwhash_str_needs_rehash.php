<?php 

#endif
#if SODIUM_LIBRARY_VERSION_MAJOR > 9 || (SODIUM_LIBRARY_VERSION_MAJOR == 9 && SODIUM_LIBRARY_VERSION_MINOR >= 6)
function sodium_crypto_pwhash_str_needs_rehash(string $password, int $opslimit, int $memlimit) : bool
{
}