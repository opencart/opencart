<?php 

interface DOMParentNode
{
    /** @param DOMNode|string $nodes */
    public function append(...$nodes) : void;
    /** @param DOMNode|string $nodes */
    public function prepend(...$nodes) : void;
}