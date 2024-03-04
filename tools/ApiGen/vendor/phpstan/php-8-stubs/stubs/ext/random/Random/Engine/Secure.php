<?php 

namespace Random\Engine;

/**
 * @strict-properties
 * @not-serializable
 */
#[\Since('8.2')]
final class Secure implements \Random\CryptoSafeEngine
{
    /** @implementation-alias Random\Engine\Mt19937::generate */
    public function generate() : string
    {
    }
}