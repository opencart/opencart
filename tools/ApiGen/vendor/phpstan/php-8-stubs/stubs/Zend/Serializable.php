<?php 

interface Serializable
{
    /** @return string|null */
    public function serialize();
    /** @return void */
    public function unserialize(string $data);
}