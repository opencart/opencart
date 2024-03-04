<?php

// Start of tokenizer v.0.1
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * Split given source into PHP tokens
 * @link https://php.net/manual/en/function.token-get-all.php
 * @param string $code <p>
 * The PHP source to parse.
 * </p>
 * @param int $flags
 * <p>
 * <p>
 * Valid flags:
 * </p><ul>
 * <li>
 *
 * <b>TOKEN_PARSE</b> - Recognises the ability to use
 * reserved words in specific contexts.
 * </li>
 * </ul>
 * </p>
 * @return array An array of token identifiers. Each individual token identifier is either
 * a single character (i.e.: ;, .,
 * &gt;, !, etc...),
 * or a three element array containing the token index in element 0, the string
 * content of the original token in element 1 and the line number in element 2.
 */
#[Pure]
function token_get_all(string $code, #[PhpStormStubsElementAvailable(from: '7.0')] int $flags = 0): array {}

/**
 * Get the symbolic name of a given PHP token
 * @link https://php.net/manual/en/function.token-name.php
 * @param int $id <p>
 * The token value.
 * </p>
 * @return string The symbolic name of the given <i>token</i>.
 */
#[Pure]
function token_name(int $id): string {}

define('TOKEN_PARSE', 1);
define('T_REQUIRE_ONCE', 276);
define('T_REQUIRE', 275);
define('T_EVAL', 274);
define('T_INCLUDE_ONCE', 273);
define('T_INCLUDE', 272);
define('T_LOGICAL_OR', 277);
define('T_LOGICAL_XOR', 278);
define('T_LOGICAL_AND', 279);
define('T_PRINT', 280);
define('T_YIELD', 281);
define('T_DOUBLE_ARROW', 386);
define('T_YIELD_FROM', 282);
define('T_POW_EQUAL', 402);
define('T_SR_EQUAL', 362);
define('T_SL_EQUAL', 361);
define('T_XOR_EQUAL', 360);
define('T_OR_EQUAL', 359);
define('T_AND_EQUAL', 358);
define('T_MOD_EQUAL', 357);
define('T_CONCAT_EQUAL', 356);
define('T_DIV_EQUAL', 355);
define('T_MUL_EQUAL', 354);
define('T_MINUS_EQUAL', 353);
define('T_PLUS_EQUAL', 352);
/**
 * @since 7.4
 */
define('T_COALESCE_EQUAL', 363);
define('T_COALESCE', 400);
define('T_BOOLEAN_OR', 364);
define('T_BOOLEAN_AND', 365);
define('T_SPACESHIP', 372);
define('T_IS_NOT_IDENTICAL', 369);
define('T_IS_IDENTICAL', 368);
define('T_IS_NOT_EQUAL', 367);
define('T_IS_EQUAL', 366);
define('T_IS_GREATER_OR_EQUAL', 371);
define('T_IS_SMALLER_OR_EQUAL', 370);
define('T_SR', 374);
define('T_SL', 373);
define('T_INSTANCEOF', 283);
define('T_UNSET_CAST', 383);
define('T_BOOL_CAST', 382);
define('T_OBJECT_CAST', 381);
define('T_ARRAY_CAST', 380);
define('T_STRING_CAST', 379);
define('T_DOUBLE_CAST', 378);
define('T_INT_CAST', 377);
define('T_DEC', 376);
define('T_INC', 375);
define('T_POW', 401);
define('T_CLONE', 285);
define('T_NEW', 284);
define('T_ELSEIF', 288);
define('T_ELSE', 289);
define('T_ENDIF', 290);
define('T_PUBLIC', 326);
define('T_PROTECTED', 325);
define('T_PRIVATE', 324);
define('T_FINAL', 323);
define('T_ABSTRACT', 322);
define('T_STATIC', 321);
define('T_LNUMBER', 260);
define('T_DNUMBER', 261);
define('T_STRING', 262);
define('T_VARIABLE', 266);
define('T_INLINE_HTML', 267);
define('T_ENCAPSED_AND_WHITESPACE', 268);
define('T_CONSTANT_ENCAPSED_STRING', 269);
define('T_STRING_VARNAME', 270);
define('T_NUM_STRING', 271);
define('T_EXIT', 286);
define('T_IF', 287);
define('T_ECHO', 291);
define('T_DO', 292);
define('T_WHILE', 293);
define('T_ENDWHILE', 294);
define('T_FOR', 295);
define('T_ENDFOR', 296);
define('T_FOREACH', 297);
define('T_ENDFOREACH', 298);
define('T_DECLARE', 299);
define('T_ENDDECLARE', 300);
define('T_AS', 301);
define('T_SWITCH', 302);
define('T_ENDSWITCH', 303);
define('T_CASE', 304);
define('T_DEFAULT', 305);
define('T_MATCH', 306);
define('T_BREAK', 307);
define('T_CONTINUE', 308);
define('T_GOTO', 309);
define('T_FUNCTION', 310);
define('T_CONST', 312);
define('T_RETURN', 313);
define('T_TRY', 314);
define('T_CATCH', 315);
define('T_FINALLY', 316);
define('T_THROW', 317);
define('T_USE', 318);
define('T_INSTEADOF', 319);
define('T_GLOBAL', 320);
define('T_VAR', 328);
define('T_UNSET', 329);
define('T_ISSET', 330);
define('T_EMPTY', 331);
define('T_HALT_COMPILER', 332);
define('T_CLASS', 333);
define('T_TRAIT', 334);
define('T_INTERFACE', 335);
/**
 * @since 8.1
 */
define('T_ENUM', 336);
define('T_EXTENDS', 337);
define('T_IMPLEMENTS', 338);
define('T_OBJECT_OPERATOR', 384);
define('T_LIST', 340);
define('T_ARRAY', 341);
define('T_CALLABLE', 342);
define('T_LINE', 343);
define('T_FILE', 344);
define('T_DIR', 345);
define('T_CLASS_C', 346);
define('T_TRAIT_C', 347);
define('T_METHOD_C', 348);
define('T_FUNC_C', 349);
define('T_COMMENT', 387);
define('T_DOC_COMMENT', 388);
define('T_OPEN_TAG', 389);
define('T_OPEN_TAG_WITH_ECHO', 390);
define('T_CLOSE_TAG', 391);
define('T_WHITESPACE', 392);
define('T_START_HEREDOC', 393);
define('T_END_HEREDOC', 394);
define('T_DOLLAR_OPEN_CURLY_BRACES', 395);
define('T_CURLY_OPEN', 396);
define('T_PAAMAYIM_NEKUDOTAYIM', 397);
define('T_NAMESPACE', 339);
define('T_NS_C', 350);
define('T_NS_SEPARATOR', 398);
define('T_ELLIPSIS', 399);
define('T_DOUBLE_COLON', 397);
/**
 * @since 7.4
 */
define('T_FN', 311);
/**
 * @removed 7.0
 */
define('T_BAD_CHARACTER', 405);

/**
 * @since 8.0
 */
define('T_NAME_FULLY_QUALIFIED', 263);
/**
 * @since 8.0
 */
define('T_NAME_RELATIVE', 264);
/**
 * @since 8.0
 */
define('T_NAME_QUALIFIED', 265);
/**
 * @since 8.0
 */
define('T_ATTRIBUTE', 351);
/**
 * @since 8.0
 */
define('T_NULLSAFE_OBJECT_OPERATOR', 385);

/**
 * @since 8.1
 */
define('T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG', 403);

/**
 * @since 8.1
 */
define('T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG', 404);

/**
 * @since 8.1
 */
define('T_READONLY', 327);

/**
 * @removed 7.0
 */
define('T_CHARACTER', 315);
