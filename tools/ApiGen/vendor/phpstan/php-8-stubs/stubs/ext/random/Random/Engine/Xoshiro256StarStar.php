<?php 

namespace Random\Engine;

/**
 * @strict-properties
 */
#[\Since('8.2')]
final class Xoshiro256StarStar implements \Random\Engine
{
    public function __construct(string|int|null $seed = null)
    {
    }
    /** @implementation-alias Random\Engine\Mt19937::generate */
    public function generate() : string
    {
    }
    public function jump() : void
    {
    }
    public function jumpLong() : void
    {
    }
    /** @implementation-alias Random\Engine\Mt19937::__serialize */
    public function __serialize() : array
    {
    }
    /** @implementation-alias Random\Engine\Mt19937::__unserialize */
    public function __unserialize(array $data) : void
    {
    }
    /** @implementation-alias Random\Engine\Mt19937::__debugInfo */
    public function __debugInfo() : array
    {
    }
}