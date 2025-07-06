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

namespace ScssPhp\ScssPhp\Visitor;

use ScssPhp\ScssPhp\Value\SassBoolean;
use ScssPhp\ScssPhp\Value\SassCalculation;
use ScssPhp\ScssPhp\Value\SassColor;
use ScssPhp\ScssPhp\Value\SassFunction;
use ScssPhp\ScssPhp\Value\SassList;
use ScssPhp\ScssPhp\Value\SassMap;
use ScssPhp\ScssPhp\Value\SassMixin;
use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\SassString;

/**
 * An interface for visitors that traverse SassScript $values.
 *
 * @internal
 *
 * @template T
 */
interface ValueVisitor
{
    /**
     * @return T
     */
    public function visitBoolean(SassBoolean $value);

    /**
     * @return T
     */
    public function visitCalculation(SassCalculation $value);

    /**
     * @return T
     */
    public function visitColor(SassColor $value);

    /**
     * @return T
     */
    public function visitFunction(SassFunction $value);

    /**
     * @return T
     */
    public function visitMixin(SassMixin $value);

    /**
     * @return T
     */
    public function visitList(SassList $value);

    /**
     * @return T
     */
    public function visitMap(SassMap $value);

    /**
     * @return T
     */
    public function visitNull();

    /**
     * @return T
     */
    public function visitNumber(SassNumber $value);

    /**
     * @return T
     */
    public function visitString(SassString $value);
}
