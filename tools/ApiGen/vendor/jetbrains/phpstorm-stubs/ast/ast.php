<?php

namespace ast {
/** Metadata entry for a single AST kind, as returned by ast\get_metadata(). */
class Metadata
{
    /** @var string[] List of supported flags. The flags are given as names of constants, such as "ast\flags\TYPE_STRING". */
    public $flags;

    /** @var bool Whether the flags are exclusive or combinable. Exclusive flags should be checked using ===, while combinable flags should be checked using &. */
    public $flagsCombinable;

    /** @var int AST node kind (one of the `ast\AST_*` constants). */
    public $kind;

    /** @var string Name of the node kind (e.g. "AST_NAME"). */
    public $name;
}

/** This class describes a single node in a PHP AST. */
class Node
{
    /** @var array Child nodes (may be empty) */
    public $children;

    /** @var int Line the node ends on. */
    public $endLineno;

    /** @var int Certain node kinds have flags that can be set. These will be a bitfield of `ast\flags\*` constants. */
    public $flags;

    /** @var int AST Node Kind. Values are one of `ast\AST_*` constants. */
    public $kind;

    /** @var int Line the node starts on. */
    public $lineno;

    /**
     * A constructor which accepts any types for the properties.
     * For backwards compatibility reasons, all values are optional and can be any type, and properties default to null.
     *
     * @param int|null $kind
     * @param int|null $flags
     * @param array|null $children
     * @param int|null $lineno
     */
    public function __construct(?int $kind = null, ?int $flags = null, ?array $children = null, ?int $lineno = null) {}
}

/**
 * @param int $kind AST_* constant value defining the kind of an AST node
 * @return string String representation of AST kind value
 */
function get_kind_name(int $kind): string {}
/**
 * Provides metadata for the AST kinds.
 *
 * The returned array is a map from AST kind to a Metadata object.
 *
 * @return Metadata[] Metadata about AST kinds
 */
function get_metadata(): array {}
/**
 * Returns currently supported AST versions.
 *
 * @param bool $exclude_deprecated Whether to exclude deprecated versions
 * @return int[] Array of supported AST versions
 */
function get_supported_versions(bool $exclude_deprecated = false): array {}
/**
 * @param int $kind AST_* constant value defining the kind of an AST node
 * @return bool Returns true if AST kind uses flags
 */
function kind_uses_flags(int $kind): bool {}
/**
 * Parses code string and returns an AST root node.
 *
 * @param string $code Code to parse
 * @param int    $version  AST version
 * @param string $filename file name for ParseError messages
 * @return Node Root node of AST
 *
 * @see https://github.com/nikic/php-ast for version information
 */
function parse_code(string $code, int $version, string $filename = 'string code'): Node {}
/**
 * Parses code from a file and returns an AST root node.
 *
 * @param string $filename file name to parse
 * @param int    $version  AST version
 * @return Node Root node of AST
 *
 * @see https://github.com/nikic/php-ast for version information
 */
function parse_file(string $filename, int $version): Node {}
/** Numerically indexed children of an argument list of a function/method invocation */
const AST_ARG_LIST = 128;
/** Numerically indexed children of an array literal. */
const AST_ARRAY = 129;
/** An element of an array literal. The key is `null` if there is no key (children: value, key) */
const AST_ARRAY_ELEM = 526;
/** A short arrow function declaration. (children: name, docComment, params, stmts, returnType, __declId) */
const AST_ARROW_FUNC = 71;
/** An assignment of the form `var = expr` (children: var, expr) */
const AST_ASSIGN = 518;
/** An assignment operation of the form `var op= expr`. The operation is determined by the flags `ast\flags\BINARY_*` (children: var, expr) */
const AST_ASSIGN_OP = 520;
/** An assignment by reference, of the form `var =& expr`. (children: var, expr) */
const AST_ASSIGN_REF = 519;
/** A single attribute of an element of the form `class(args)` (children: class, args) */
const AST_ATTRIBUTE = 547;
/** Numerically indexed ast\AST_ATTRIBUTE children of an attribute group */
const AST_ATTRIBUTE_GROUP = 146;
/** Numerically indexed ast\AST_ATTRIBUTE_GROUP children of an attribute list of an element */
const AST_ATTRIBUTE_LIST = 145;
/** A binary operation of the form `left op right`. The operation is determined by the flags `ast\flags\BINARY_*` (children: left, right) */
const AST_BINARY_OP = 521;
/** A break statement. The depth is null or an integer. (children: depth) */
const AST_BREAK = 286;
/** A global function invocation of the form `expr(args)` (children: expr, args) */
const AST_CALL = 516;
/** A cast operation of the form `(type)expr`. The flags are the type: `ast\flags\TYPE_*` (children: expr) */
const AST_CAST = 261;
/** An individual catch block catching an `ast\AST_CLASS_LIST` of possible classes. This is of the form `catch(class var) { stmts }`. (children: class, var, stmts) */
const AST_CATCH = 773;
/** A list of 1 or more `ast\AST_CATCH` nodes for a try statement. (numerically indexed children) */
const AST_CATCH_LIST = 135;
/** A class declaration of the form `docComment name EXTENDS extends IMPLEMENTS implements { stmts }` (children: name, docComment, extends, implements, stmts, __declId) */
const AST_CLASS = 70;
/** A class constant usage of the form `class::const`. (children: class, const) */
const AST_CLASS_CONST = 517;
/** A class constant declaration with one or more class constants. (numerically indexed children) */
const AST_CLASS_CONST_DECL = 140;
/** A class constant declaration with attributes and the list of one or more class constants. (children: const, attributes) */
const AST_CLASS_CONST_GROUP = 546;
/** A usage of `class::CLASS` in AST version 70 (children: class) */
const AST_CLASS_NAME = 276;
/** An expression cloning an object, of the form `clone(expr)`. (children: expr) */
const AST_CLONE = 266;
/** A closure declaration. (children: name, docComment, params, uses, stmts, returnType, __declId) */
const AST_CLOSURE = 68;
/** A list of one or more nodes of type `ast\AST_CLOSURE_VAR` for a closure declaration. (numerically indexed children) */
const AST_CLOSURE_USES = 137;
/** A variable in the list of `uses` of a closure declaration. (children: name) */
const AST_CLOSURE_VAR = 2049;
/** A conditional expression of the form `cond ? true : false` or `cond ?: false`. (children: cond, true, false) */
const AST_CONDITIONAL = 771;
/** A usage of a global constant `name`. (children: name) */
const AST_CONST = 257;
/** A declaration of a group of class constants of kind `ast\AST_CONST_ELEM`. (numerically indexed children) */
const AST_CONST_DECL = 139;
/** A declaration of a class constant. (children: name, value, docComment) */
const AST_CONST_ELEM = 776;
/** A continue statement with a depth of `null` or an integer. (children: depth) */
const AST_CONTINUE = 287;
/** A declare statement at the top of a php file (children: declares, stmts) */
const AST_DECLARE = 538;
/** A usage of an array/array-like field, of the form `expr[dim]` (children: expr, dim) */
const AST_DIM = 512;
/** A do-while statement of the form `do {stmts} while (cond);`. (children: stmts, cond) */
const AST_DO_WHILE = 534;
/** An echo statement or inline HTML (children: expr) */
const AST_ECHO = 283;
/** An `empty(expr)` expression (children: expr) */
const AST_EMPTY = 262;
/** interpolated string with non-literals, e.g. `"foo$bar"` or heredoc (numerically indexed children) */
const AST_ENCAPS_LIST = 130;
/** An `exit`/`die` statement. (children: expr) */
const AST_EXIT = 267;
/** A comma separated list of expressions, e.g. for the condition of a `for` loop. (numerically indexed children) */
const AST_EXPR_LIST = 131;
/** A for loop of the form `for (init; cond; loop) { stmts; }`. (children: init, cond, loop, stmts) */
const AST_FOR = 1024;
/** A foreach loop of the form `foreach (expr as [key =>] value) {stmts} (children: expr, value, key, stmts) */
const AST_FOREACH = 1025;
/** A global function declaration. (children: name, docComment, params, stmts, returnType, __declId) */
const AST_FUNC_DECL = 67;
/** A usage of a global variable of the form `global var`. (children: var) */
const AST_GLOBAL = 277;
/** A goto statement of the form `goto label;` (children: label) */
const AST_GOTO = 285;
/** A use statement (for classes, namespaces, functions, and/or constants) containing a list of one or more elements. (children: prefix, uses) */
const AST_GROUP_USE = 545;
/** A `__halt_compiler;` statement. (children: offset) */
const AST_HALT_COMPILER = 282;
/** A list of `ast\AST_IF_ELEM` nodes for a chain of 1 or more `if`/`elseif`/`else` statements (numerically indexed children) */
const AST_IF = 133;
/** An `if`/`elseif`/`elseif` statement of the form `if (cond) stmts` (children: cond, stmts) */
const AST_IF_ELEM = 535;
/** An `include*(expr)`, `require*(expr)`, or `eval(expr)` statement. The type can be determined from the flags (`ast\flags\EXEC_*`). (children: expr) */
const AST_INCLUDE_OR_EVAL = 269;
/** An `expr instanceof class` expression. (children: expr, class) */
const AST_INSTANCEOF = 528;
/** An `isset(var)` expression. (children: var) */
const AST_ISSET = 263;
/** A `name:` expression (a target for `goto name`). (children: name) */
const AST_LABEL = 280;
/** Used for `list() = ` unpacking, etc. Predates AST version 50. (numerically indexed children) */
const AST_LIST = 255;
/** A magic constant (depends on flags that are one of `ast\flags\MAGIC_*`) */
const AST_MAGIC_CONST = 0;
/** A match expression of the form `match(cond) { stmts }` (children: cond, stmts) */
const AST_MATCH = 548;
/** An arm of a match expression of the form `cond => expr` (children: cond, expr) */
const AST_MATCH_ARM = 549;
/** Numerically indexed children of the kind `ast\AST_MATCH_ARM` for the statements of a match expression */
const AST_MATCH_ARM_LIST = 147;
/** A method declaration. (children: name, docComment, params, stmts, returnType, __declId) */
const AST_METHOD = 69;
/** An invocation of an instance method, of the form `expr->method(args)` (children: expr, method, args) */
const AST_METHOD_CALL = 768;
/** A reference to a method when using a trait inside a class declaration. (children: class, method) */
const AST_METHOD_REFERENCE = 541;
/** A name token (e.g. of a constant/class/class type) (children: name) */
const AST_NAME = 2048;
/** A named argument in an argument list of a function/method call. (children: name, expr) */
const AST_NAMED_ARG = 550;
/** A namespace declaration of the form `namespace name;` or `namespace name { stmts }`. (children: name, stmts) */
const AST_NAMESPACE = 542;
/** A list of names (e.g. for catching multiple classes in a `catch` statement) (numerically indexed children) */
const AST_NAME_LIST = 141;
/** An object creation expression of the form `new class(args)` (children: class, args) */
const AST_NEW = 527;
/** A nullable node with a child node of kind `ast\AST_TYPE` or `ast\AST_NAME` (children: type) */
const AST_NULLABLE_TYPE = 2050;
/** A nullsafe method call of the form `expr?->method(args)`. (children: expr, method, args) */
const AST_NULLSAFE_METHOD_CALL = 769;
/** A nullsafe property read of the form `expr?->prop`. (children: expr, prop) */
const AST_NULLSAFE_PROP = 514;
/** A parameter of a function, closure, or method declaration. (children: type, name, default) */
const AST_PARAM = 1280;
/** The list of parameters of a function, closure, or method declaration. (numerically indexed children) */
const AST_PARAM_LIST = 136;
/** A `var--` expression. (children: var) */
const AST_POST_DEC = 274;
/** A `var++` expression. (children: var) */
const AST_POST_INC = 273;
/** A `--var` expression. (children: var) */
const AST_PRE_DEC = 272;
/** A `++var` expression. (children: var) */
const AST_PRE_INC = 271;
/** A `print expr` expression.  (children: expr) */
const AST_PRINT = 268;
/** An instance property usage of the form `expr->prop` (children: expr, prop) */
const AST_PROP = 513;
/** A single group of property declarations inside a class. (numerically indexed children) */
const AST_PROP_DECL = 138;
/** A class property declaration. (children: name, default, docComment) */
const AST_PROP_ELEM = 775;
/** A class property group declaration with optional type information for the group (in PHP 7.4+). Used in AST version 70+ (children: type, props, __declId) */
const AST_PROP_GROUP = 774;
/** Used for `&$v` in `foreach ($a as &$v)` (children: var) */
const AST_REF = 281;
/** A `return;` or `return expr` statement. (children: expr) */
const AST_RETURN = 279;
/** A `\`some_shell_command\`` expression. (children: expr) */
const AST_SHELL_EXEC = 265;
/** A declaration of a static local variable of the form `static var = default`. (children: var, default) */
const AST_STATIC = 532;
/** A call of a static method, of the form `class::method(args)`. (children: class, method, args) */
const AST_STATIC_CALL = 770;
/** A usage of a static property, of the form `class::prop`. (children: class, prop) */
const AST_STATIC_PROP = 515;
/** A list of statements. The statements are usually nodes but can be non-nodes, e.g. `;2;`. (numerically indexed children) */
const AST_STMT_LIST = 132;
/** A switch statement of the form `switch(cond) { stmts }`. `stmts` is a node of the kind `ast\AST_SWITCH_LIST`. (children: cond, stmts) */
const AST_SWITCH = 536;
/** A case statement of a switch, of the form `case cond: stmts` (children: cond, stmts) */
const AST_SWITCH_CASE = 537;
/** The full list of nodes inside a switch statement body, each of kind `ast\AST_SWITCH_CASE`. (numerically indexed children) */
const AST_SWITCH_LIST = 134;
/** A throw statement of the form `throw expr;` (children: expr) */
const AST_THROW = 284;
/** The optional adaptations to a statement using a trait inside a class (numerically indexed children) */
const AST_TRAIT_ADAPTATIONS = 142;
/** Adds an alias inside of a use of a trait (`method as alias`) (children: method, alias) */
const AST_TRAIT_ALIAS = 544;
/** Indicates the precedent of a trait over another trait (`method INSTEADOF insteadof`) (children: method, insteadof) */
const AST_TRAIT_PRECEDENCE = 540;
/** A try/catch(es)/finally block. (children: try, catches, finally) */
const AST_TRY = 772;
/** A type such as `bool`. Other types such as specific classes are represented as `ast\AST_NAME`s (depends on flags of `ast\flags\TYPE_*`) */
const AST_TYPE = 1;
/** A union type made up of individual types, such as `bool|int` (numerically indexed children) */
const AST_TYPE_UNION = 144;
/** A unary operation of the form `op expr` (e.g. `-expr`, the flags can be one of `ast\flags\UNARY_*`) (children: expr) */
const AST_UNARY_OP = 270;
/** An expression unpacking an array/iterable (i.e. `...expr`) (children: expr) */
const AST_UNPACK = 258;
/** `unset(var)` - A statement unsetting the expression `var` (children: var) */
const AST_UNSET = 278;
/** A list of uses of classes/namespaces, functions, and constants. The child nodes are of the kind `ast\AST_USE_ELEM` (numerically indexed children) */
const AST_USE = 143;
/** A single use statement for a group of (children: name, alias) */
const AST_USE_ELEM = 543;
/** Represents `use traits {[adaptations]}` within a class declaration (children: traits, adaptations) */
const AST_USE_TRAIT = 539;
/** An occurrence of a variable `$name` or `${name}`. (children: name) */
const AST_VAR = 256;
/** A while loop of the form `while (cond) { stmts }` (children: cond, stmts) */
const AST_WHILE = 533;
/** An expression of the form `yield [key =>] value` (children: value, key) */
const AST_YIELD = 529;
/** An expression of the form `yield from expr` (children: expr) */
const AST_YIELD_FROM = 275;
}

namespace ast\flags {
/** Marks an `ast\AST_ARRAY_ELEM` as a reference */
const ARRAY_ELEM_REF = 1;
/** Marks an `ast\AST_ARRAY` as using the `list(...)` syntax */
const ARRAY_SYNTAX_LIST = 1;
/** Marks an `ast\AST_ARRAY` as using the `array(...)` syntax */
const ARRAY_SYNTAX_LONG = 2;
/** Marks an `ast\AST_ARRAY` as using the `[...]` syntax */
const ARRAY_SYNTAX_SHORT = 3;
/** Marks an ast\AST_BINARY_OP as being a `+` */
const BINARY_ADD = 1;
/** Marks an ast\AST_BINARY_OP as being a `&` */
const BINARY_BITWISE_AND = 10;
/** Marks an ast\AST_BINARY_OP as being a `|` */
const BINARY_BITWISE_OR = 9;
/** Marks an ast\AST_BINARY_OP as being a `^` */
const BINARY_BITWISE_XOR = 11;
/** Marks an ast\AST_BINARY_OP as being a `&&` or `and` */
const BINARY_BOOL_AND = 259;
/** Marks an ast\AST_BINARY_OP as being a `||` or `or` */
const BINARY_BOOL_OR = 258;
/** Marks an ast\AST_BINARY_OP as being an `xor` */
const BINARY_BOOL_XOR = 15;
/** Marks an ast\AST_BINARY_OP as being a `??` */
const BINARY_COALESCE = 260;
/** Marks an ast\AST_BINARY_OP as being a `.` */
const BINARY_CONCAT = 8;
/** Marks an ast\AST_BINARY_OP as being a `/` */
const BINARY_DIV = 4;
/** Marks an ast\AST_BINARY_OP as being a `==` */
const BINARY_IS_EQUAL = 18;
/** Marks an ast\AST_BINARY_OP as being a `>` */
const BINARY_IS_GREATER = 256;
/** Marks an ast\AST_BINARY_OP as being a `>=` */
const BINARY_IS_GREATER_OR_EQUAL = 257;
/** Marks an ast\AST_BINARY_OP as being a `===` */
const BINARY_IS_IDENTICAL = 16;
/** Marks an ast\AST_BINARY_OP as being a `!=` */
const BINARY_IS_NOT_EQUAL = 19;
/** Marks an ast\AST_BINARY_OP as being a `!==` */
const BINARY_IS_NOT_IDENTICAL = 17;
/** Marks an ast\AST_BINARY_OP as being a `<` */
const BINARY_IS_SMALLER = 20;
/** Marks an ast\AST_BINARY_OP as being a `<=` */
const BINARY_IS_SMALLER_OR_EQUAL = 21;
/** Marks an ast\AST_BINARY_OP as being a `%` */
const BINARY_MOD = 5;
/** Marks an ast\AST_BINARY_OP as being a `*` */
const BINARY_MUL = 3;
/** Marks an ast\AST_BINARY_OP as being a `**` */
const BINARY_POW = 12;
/** Marks an ast\AST_BINARY_OP as being a `<<` */
const BINARY_SHIFT_LEFT = 6;
/** Marks an ast\AST_BINARY_OP as being a `>>` */
const BINARY_SHIFT_RIGHT = 7;
/** Marks an ast\AST_BINARY_OP as being a `<=>` */
const BINARY_SPACESHIP = 170;
/** Marks an ast\AST_BINARY_OP as being a `-` */
const BINARY_SUB = 2;
/** Marks a `ast\AST_CLASS` (class-like declaration) as being abstract */
const CLASS_ABSTRACT = 64;
/** Marks a `ast\AST_CLASS` (class-like declaration) as being anonymous */
const CLASS_ANONYMOUS = 4;
/** Marks a `ast\AST_CLASS` (class-like declaration) as being final */
const CLASS_FINAL = 32;
/** Marks a `ast\AST_CLASS` (class-like declaration) as being an interface */
const CLASS_INTERFACE = 1;
/** Marks a `ast\AST_CLASS` (class-like declaration) as being a trait */
const CLASS_TRAIT = 2;
/** Marks an `ast\AST_CLOSURE_USE` as using a variable by reference */
const CLOSURE_USE_REF = 1;
/** Marks an `ast\AST_DIM` as using the alternative `expr{dim}` syntax */
const DIM_ALTERNATIVE_SYNTAX = 2;
/** Marks an `ast\AST_EXEC` as being an `eval(...)` */
const EXEC_EVAL = 1;
/** Marks an `ast\AST_EXEC` as being an `include` */
const EXEC_INCLUDE = 2;
/** Marks an `ast\AST_EXEC` as being an `include_once` */
const EXEC_INCLUDE_ONCE = 4;
/** Marks an `ast\AST_EXEC` as being a `require` */
const EXEC_REQUIRE = 8;
/** Marks an `ast\AST_EXEC` as being a `require_once` */
const EXEC_REQUIRE_ONCE = 16;
/** Marks an `ast\AST_FUNC_DECL` as being a generator */
const FUNC_GENERATOR = 16777216;
/** Marks an `ast\AST_FUNC_DECL` as returning a value by reference */
const FUNC_RETURNS_REF = 4096;
/** Marks an `ast\AST_MAGIC_CONST` as being `__CLASS__` */
const MAGIC_CLASS = 378;
/** Marks an `ast\AST_MAGIC_CONST` as being `__DIR__` */
const MAGIC_DIR = 377;
/** Marks an `ast\AST_MAGIC_CONST` as being `__FILE__` */
const MAGIC_FILE = 376;
/** Marks an `ast\AST_MAGIC_CONST` as being `__FUNCTION__` */
const MAGIC_FUNCTION = 381;
/** Marks an `ast\AST_MAGIC_CONST` as being `__LINE__` */
const MAGIC_LINE = 375;
/** Marks an `ast\AST_MAGIC_CONST` as being `__METHOD__` */
const MAGIC_METHOD = 380;
/** Marks an `ast\AST_MAGIC_CONST` as being `__NAMESPACE__` */
const MAGIC_NAMESPACE = 382;
/** Marks an `ast\AST_MAGIC_CONST` as being `__TRAIT__` */
const MAGIC_TRAIT = 379;
/** Marks an element declaration as being `abstract` */
const MODIFIER_ABSTRACT = 64;
/** Marks an element declaration as being `final` */
const MODIFIER_FINAL = 32;
/** Marks an element declaration as being `private` */
const MODIFIER_PRIVATE = 4;
/** Marks an element declaration as being `protected` */
const MODIFIER_PROTECTED = 2;
/** Marks an element declaration as being `public` */
const MODIFIER_PUBLIC = 1;
/** Marks an element declaration as being `static` */
const MODIFIER_STATIC = 16;
/** Marks an `ast\AST_NAME` as being fully qualified (`\Name`) */
const NAME_FQ = 0;
/** Marks an `ast\AST_NAME` as being not fully qualified (`Name`) */
const NAME_NOT_FQ = 1;
/** Marks an `ast\AST_NAME` as being relative to the current namespace (`namespace\Name`) */
const NAME_RELATIVE = 2;
/** Marks a promoted constructor property as being `private` */
const PARAM_MODIFIER_PRIVATE = 4;
/** Marks a promoted constructor property as being `protected` */
const PARAM_MODIFIER_PROTECTED = 2;
/** Marks a promoted constructor property as being `public` */
const PARAM_MODIFIER_PUBLIC = 1;
/** Marks an `ast\AST_PARAM` as being a reference (`&$x`) */
const PARAM_REF = 8;
/** Marks an `ast\AST_PARAM` as being variadic (`...$x`) */
const PARAM_VARIADIC = 16;
/** Marks a `ast\AST_CONDITIONAL` as being parenthesized (`(cond ? true : false)`) */
const PARENTHESIZED_CONDITIONAL = 1;
/** Marks a function-like as returning an argument by reference */
const RETURNS_REF = 4096;
/** Marks an `ast\AST_TYPE` as being `array` */
const TYPE_ARRAY = 7;
/** Marks an `ast\AST_TYPE` as being `bool` */
const TYPE_BOOL = 17;
/** Marks an `ast\AST_TYPE` as being `callable` */
const TYPE_CALLABLE = 12;
/** Marks an `ast\AST_TYPE` as being `float` */
const TYPE_DOUBLE = 5;
/** Marks an `ast\AST_TYPE` as being `false` (for union types) */
const TYPE_FALSE = 2;
/** Marks an `ast\AST_TYPE` as being `iterable` */
const TYPE_ITERABLE = 13;
/** Marks an `ast\AST_TYPE` as being `int` */
const TYPE_LONG = 4;
/** Marks an `ast\AST_TYPE` as being `mixed` */
const TYPE_MIXED = 16;
/** Marks an `ast\AST_TYPE` as being `null` (for union types) */
const TYPE_NULL = 1;
/** Marks an `ast\AST_TYPE` as being `object` */
const TYPE_OBJECT = 8;
/** Marks an `ast\AST_TYPE` as being `static` (for return types) */
const TYPE_STATIC = 15;
/** Marks an `ast\AST_TYPE` as being `string` */
const TYPE_STRING = 6;
/** Marks an `ast\AST_TYPE` as being `void` (for return types) */
const TYPE_VOID = 14;
/** Marks an `ast\AST_UNARY_OP` as being `~expr` */
const UNARY_BITWISE_NOT = 13;
/** Marks an `ast\AST_UNARY_OP` as being `!expr` */
const UNARY_BOOL_NOT = 14;
/** Marks an `ast\AST_UNARY_OP` as being `-expr` */
const UNARY_MINUS = 262;
/** Marks an `ast\AST_UNARY_OP` as being `+expr` */
const UNARY_PLUS = 261;
/** Marks an `ast\AST_UNARY_OP` as being `@expr` */
const UNARY_SILENCE = 260;
/** Marks an `ast\AST_USE` or `ast\AST_GROUP_USE` (namespace use statement) as being `use const name;` */
const USE_CONST = 4;
/** Marks an `ast\AST_USE` or `ast\AST_GROUP_USE` (namespace use statement) as being `use function name;` */
const USE_FUNCTION = 2;
/** Marks an `ast\AST_USE` or `ast\AST_GROUP_USE` (namespace use statement) as being `use name;` */
const USE_NORMAL = 1;
}
