<?php 

namespace Random\Engine;

/**
 * @strict-properties
 */
#[\Since('8.2')]
final class Mt19937 implements \Random\Engine
{
    public function __construct(int|null $seed = null, int $mode = MT_RAND_MT19937)
    {
    }
    public function generate() : string
    {
    }
    public function __serialize() : array
    {
    }
    public function __unserialize(array $data) : void
    {
    }
    public function __debugInfo() : array
    {
    }
}