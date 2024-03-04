<?php 

#[\Until('8.2')]
function register_shutdown_function(callable $callback, mixed ...$args) : ?bool
{
}
#[\Since('8.2')]
function register_shutdown_function(callable $callback, mixed ...$args) : void
{
}