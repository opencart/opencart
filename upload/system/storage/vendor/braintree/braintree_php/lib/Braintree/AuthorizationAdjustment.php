<?php
namespace Braintree;

/**
 * Creates an instance of AuthorizationAdjustment as returned from a transaction
 *
 * @package Braintree
 *
 * @property-read string $amount
 * @property-read boolean $success
 * @property-read \DateTime $timestamp
 *
 */

class AuthorizationAdjustment extends Base
{
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);

        return $instance;
    }

    protected function _initialize($authorizationAdjustmentAttribs)
    {
        $this->_attributes = $authorizationAdjustmentAttribs;
    }

    public function  __toString()
    {
        return __CLASS__ . '[' . Util::attributesToString($this->_attributes) . ']';
    }
}
class_alias('Braintree\AuthorizationAdjustment', 'Braintree_Authorization_Adjustment');
