<?php 

/** @param callable $handler */
#[\Until('8.2')]
function xml_set_notation_decl_handler(\XMLParser $parser, $handler) : bool
{
}
/** @param callable $handler */
#[\Since('8.2')]
function xml_set_notation_decl_handler(\XMLParser $parser, $handler) : true
{
}