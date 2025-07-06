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
 * A deprecated feature in the language.
 *
 * Code consuming this enum outside Scssphp must not rely on exhaustiveness checks. New values will be added
 * in this enum in minor versions of the package without considering that as a BC break.
 */
enum Deprecation: string
{
    /**
     * Deprecation for passing a string directly to meta.call().
     */
    case callString = 'call-string';

    /**
     * Deprecation for @elseif.
     */
    case elseif = 'elseif';

    /**
     * Deprecation for @-moz-document.
     */
    case mozDocument = 'moz-document';

    /**
     * Deprecation for declaring new variables with !global.
     */
    case newGlobal = 'new-global';

    /**
     * Deprecation for / operator for division.
     */
    case slashDiv = 'slash-div';

    /**
     * Deprecation for leading, trailing, and repeated combinators.
     */
    case bogusCombinators = 'bogus-combinators';

    /**
     * Deprecation for ambiguous + and - operators.
     */
    case strictUnary = 'strict-unary';

    /**
     * Deprecation for passing invalid units to built-in functions.
     */
    case functionUnits = 'function-units';

    /**
     * Deprecation for using !default or !global multiple times for one variable.
     */
    case duplicateVarFlags = 'duplicate-var-flags';

    /**
     * Deprecation for passing percentages to the Sass abs() function.
     */
    case absPercent = 'abs-percent';

    /**
     * Deprecation for function and mixin names beginning with --.
     */
    case cssFunctionMixin = 'css-function-mixin';

    /**
     * Deprecation for declarations after or between nested rules.
     */
    case mixedDecls = 'mixed-decls';

    /**
     * Deprecation for meta.feature-exists.
     */
    case featureExists = 'feature-exists';

    /**
     * Used for deprecations coming from user-authored code.
     */
    case userAuthored = 'user-authored';

    public function getDescription(): ?string
    {
        return match ($this) {
            self::callString => 'Passing a string directly to meta.call().',
            self::elseif => '@elseif.',
            self::mozDocument => '@-moz-document.',
            self::newGlobal => 'Declaring new variables with !global.',
            self::slashDiv => '/ operator for division.',
            self::bogusCombinators => 'Leading, trailing, and repeated combinators.',
            self::strictUnary => 'Ambiguous + and - operators.',
            self::functionUnits => 'Passing invalid units to built-in functions.',
            self::duplicateVarFlags => 'Using !default or !global multiple times for one variable.',
            self::absPercent => 'Passing percentages to the Sass abs() function.',
            self::cssFunctionMixin => 'Function and mixin names beginning with --.',
            self::mixedDecls => 'Declarations after or between nested rules.',
            self::featureExists => 'meta.feature-exists',
            self::userAuthored => null,
        };
    }

    /**
     * The version in which this feature was first deprecated.
     */
    public function getDeprecatedIn(): ?string
    {
        return match ($this) {
            self::callString => '1.2.0',
            self::elseif => '2.0.0',
            self::mozDocument => '2.0.0',
            self::newGlobal => '2.0.0',
            self::slashDiv => null,
            self::bogusCombinators => '2.0.0',
            self::strictUnary => '2.0.0',
            self::functionUnits => '2.0.0',
            self::duplicateVarFlags => '2.0.0',
            self::absPercent => '2.0.0',
            self::cssFunctionMixin => '2.0.0',
            self::mixedDecls => '2.0.0',
            self::featureExists => '2.0.0',
            self::userAuthored => null,
        };
    }

    /**
     * The version this feature was fully removed in, making the
     * deprecation obsolete.
     *
     * For deprecations that are not yet obsolete, this should be null.
     */
    public function getObsoleteIn(): ?string
    {
        return null; // For now, no deprecation is obsolete
    }

    public function isFuture(): bool
    {
        if ($this === self::userAuthored) {
            return false;
        }

        return $this->getDeprecatedIn() === null;
    }

    public function getStatus(): DeprecationStatus
    {
        if ($this === self::userAuthored) {
            return DeprecationStatus::user;
        }

        if ($this->isFuture()) {
            return DeprecationStatus::future;
        }

        if ($this->getObsoleteIn() !== null) {
            return DeprecationStatus::obsolete;
        }

        return DeprecationStatus::active;
    }
}
