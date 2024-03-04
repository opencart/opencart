<?php

// Start of lua v2.0.6.
// The actual lua version is different from the PECL package version

/**
 * @link https://secure.php.net/manual/en/class.lua.php
 */
class Lua
{
    /**
     * @var string
     *
     * @link https://secure.php.net/manual/en/class.lua.php#lua.constants.lua-version
     */
    public const LUA_VERSION = '5.1.4';

    /**
     * @param null|string $lua_script_file
     * @link https://secure.php.net/manual/en/lua.construct.php
     */
    public function __construct(?string $lua_script_file = null) {}

    /**
     * @link https://secure.php.net/manual/en/lua.assign.php
     *
     * @param string $name
     * @param mixed $value
     *
     * @return $this|null Returns $this or NULL on failure.
     */
    public function assign(string $name, $value) {}

    /**
     * @link https://secure.php.net/manual/en/lua.call.php
     *
     * @param callable $lua_func Function name in lua
     * @param array $args Arguments passed to the Lua function
     * @param bool $use_self Whether to use self
     *
     * @return mixed|false Returns result of the called function, null for wrong arguments or FALSE on other failure.
     */
    public function call(callable $lua_func, array $args = [], bool $use_self = false) {}

    /**
     * @link https://secure.php.net/manual/en/lua.eval.php
     *
     * @param string $statements
     *
     * @return mixed|false Returns result of evaled code, NULL for wrong arguments or FALSE on other failure.
     */
    public function eval(string $statements) {}

    /**
     * @link https://secure.php.net/manual/en/lua.include.php
     *
     * @param string $file
     *
     * @return mixed|false Returns result of included code, NULL for wrong arguments or FALSE on other failure.
     */
    public function include(string $file) {}

    /**
     * @link https://secure.php.net/manual/en/lua.getversion.php
     *
     * @return string Returns Lua::LUA_VERSION
     */
    public function getVersion(): string {}

    /**
     * @link https://secure.php.net/manual/en/lua.registercallback.php
     *
     * @param string $name
     * @param callable $function A valid PHP function callback
     *
     * @return $this|null|false Returns $this, NULL for wrong arguments or FALSE on other failure.
     */
    public function registerCallback(string $name, callable $function) {}
}
