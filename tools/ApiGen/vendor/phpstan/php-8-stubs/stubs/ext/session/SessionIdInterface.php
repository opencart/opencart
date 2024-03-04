<?php 

interface SessionIdInterface
{
    /**
     * @tentative-return-type
     * @return string
     */
    public function create_sid();
}