<?php
namespace Braintree;

/**
 * Braintree ApplePayOptions module
 * Manages configuration and options for Apple Pay
 *
 * @package    Braintree
 * @category   Resources
 *
 * @property-read array $domains
 */

class ApplePayOptions extends Base
{
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }
}
class_alias('Braintree\ApplePayOptions', 'Braintree_ApplePayOptions');
