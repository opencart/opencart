<?php 

interface JsonSerializable
{
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function jsonSerialize();
}