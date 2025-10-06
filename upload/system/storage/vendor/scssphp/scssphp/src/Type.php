<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp;

/**
 * Block/node types
 *
 * @author Anthon Pang <anthon.pang@gmail.com>
 */
class Type
{
    /**
     * @internal
     */
    const T_ASSIGN = 'assign';
    /**
     * @internal
     */
    const T_AT_ROOT = 'at-root';
    /**
     * @internal
     */
    const T_BLOCK = 'block';
    /**
     * @deprecated
     * @internal
     */
    const T_BREAK = 'break';
    /**
     * @internal
     */
    const T_CHARSET = 'charset';
    const T_COLOR = 'color';
    /**
     * @internal
     */
    const T_COMMENT = 'comment';
    /**
     * @deprecated
     * @internal
     */
    const T_CONTINUE = 'continue';
    /**
     * @deprecated
     * @internal
     */
    const T_CONTROL = 'control';
    /**
     * @internal
     */
    const T_CUSTOM_PROPERTY = 'custom';
    /**
     * @internal
     */
    const T_DEBUG = 'debug';
    /**
     * @internal
     */
    const T_DIRECTIVE = 'directive';
    /**
     * @internal
     */
    const T_EACH = 'each';
    /**
     * @internal
     */
    const T_ELSE = 'else';
    /**
     * @internal
     */
    const T_ELSEIF = 'elseif';
    /**
     * @internal
     */
    const T_ERROR = 'error';
    /**
     * @internal
     */
    const T_EXPRESSION = 'exp';
    /**
     * @internal
     */
    const T_EXTEND = 'extend';
    /**
     * @internal
     */
    const T_FOR = 'for';
    /**
     * @internal
     */
    const T_FUNCTION = 'function';
    /**
     * @internal
     */
    const T_FUNCTION_REFERENCE = 'function-reference';
    /**
     * @internal
     */
    const T_FUNCTION_CALL = 'fncall';
    /**
     * @internal
     */
    const T_HSL = 'hsl';
    /**
     * @internal
     */
    const T_HWB = 'hwb';
    /**
     * @internal
     */
    const T_IF = 'if';
    /**
     * @internal
     */
    const T_IMPORT = 'import';
    /**
     * @internal
     */
    const T_INCLUDE = 'include';
    /**
     * @internal
     */
    const T_INTERPOLATE = 'interpolate';
    /**
     * @internal
     */
    const T_INTERPOLATED = 'interpolated';
    /**
     * @internal
     */
    const T_KEYWORD = 'keyword';
    const T_LIST = 'list';
    const T_MAP = 'map';
    /**
     * @internal
     */
    const T_MEDIA = 'media';
    /**
     * @internal
     */
    const T_MEDIA_EXPRESSION = 'mediaExp';
    /**
     * @internal
     */
    const T_MEDIA_TYPE = 'mediaType';
    /**
     * @internal
     */
    const T_MEDIA_VALUE = 'mediaValue';
    /**
     * @internal
     */
    const T_MIXIN = 'mixin';
    /**
     * @internal
     */
    const T_MIXIN_CONTENT = 'mixin_content';
    /**
     * @internal
     */
    const T_NESTED_PROPERTY = 'nestedprop';
    /**
     * @internal
     */
    const T_NOT = 'not';
    const T_NULL = 'null';
    const T_NUMBER = 'number';
    /**
     * @internal
     */
    const T_RETURN = 'return';
    /**
     * @internal
     */
    const T_ROOT = 'root';
    /**
     * @internal
     */
    const T_SCSSPHP_IMPORT_ONCE = 'scssphp-import-once';
    /**
     * @internal
     */
    const T_SELF = 'self';
    const T_STRING = 'string';
    /**
     * @internal
     */
    const T_UNARY = 'unary';
    /**
     * @internal
     */
    const T_VARIABLE = 'var';
    /**
     * @internal
     */
    const T_WARN = 'warn';
    /**
     * @internal
     */
    const T_WHILE = 'while';
}
