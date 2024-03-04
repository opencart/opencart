<?php

/**
 * Abstract parent class for all SPL types.
 *
 * @link https://php.net/manual/en/class.spltype.php
 */
abstract class SplType
{
    /**
     * @var null Default value
     * @link https://php.net/manual/en/class.spltype.php#spltype.constants.default
     */
    public const __default = null;

    /**
     * Creates a new value of some type
     *
     * @param mixed $initial_value
     * @param bool $strict  If set to true then will throw UnexpectedValueException if value of other type will be assigned. True by default
     * @link https://php.net/manual/en/spltype.construct.php
     */
    public function __construct($initial_value = self::__default, $strict = true) {}
}

/**
 * The SplInt class is used to enforce strong typing of the integer type.
 *
 * @link https://php.net/manual/en/class.splint.php
 */
class SplInt extends SplType
{
    /**
     * @link https://php.net/manual/en/class.splint.php#splint.constants.default
     */
    public const __default = 0;
}

/**
 * The SplFloat class is used to enforce strong typing of the float type.
 *
 * @link https://php.net/manual/en/class.splfloat.php
 */
class SplFloat extends SplType
{
    public const __default = 0;
}

/**
 * SplEnum gives the ability to emulate and create enumeration objects natively in PHP.
 *
 * @link https://php.net/manual/en/class.splenum.php
 */
class SplEnum extends SplType
{
    /**
     * @link https://php.net/manual/en/class.splenum.php#splenum.constants.default
     */
    public const __default = null;

    /**
     * Returns all consts (possible values) as an array.
     *
     * @param bool $include_default Whether to include __default constant (property). False by default.
     * @return array
     * @link https://php.net/manual/en/splenum.getconstlist.php
     */
    public function getConstList($include_default = false) {}
}

/**
 * The SplBool class is used to enforce strong typing of the bool type.
 *
 * @link https://php.net/manual/en/class.splbool.php
 */
class SplBool extends SplEnum
{
    /**
     * @link https://php.net/manual/en/class.splbool.php#splbool.constants.default
     */
    public const __default = false;

    /**
     * @link https://php.net/manual/en/class.splbool.php#splbool.constants.false
     */
    public const false = false;

    /**
     * @link https://php.net/manual/en/class.splbool.php#splbool.constants.true
     */
    public const true = true;
}

/**
 * The SplString class is used to enforce strong typing of the string type.
 *
 * @link https://php.net/manual/en/class.splstring.php
 */
class SplString extends SplType
{
    /**
     * @link https://php.net/manual/en/class.splstring.php#splstring.constants.default
     */
    public const __default = 0;
}
