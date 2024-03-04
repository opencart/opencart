<?php 

namespace Random;

#[\Since('8.2')]
interface Engine
{
    public function generate() : string;
}