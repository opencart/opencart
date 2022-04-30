<?php
namespace Braintree;

/**
 * Braintree OAuth Revocation module
 *
 * PHP Version 5
 *
 * @package   Braintree
 */
class OAuthAccessRevocation extends Base
{
    /**
     * @ignore
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);

        return $instance;
    }

    /**
     * @ignore
     */
    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }
}
class_alias('Braintree\OAuthAccessRevocation', 'Braintree_OAuthAccessRevocation');
