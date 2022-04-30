<?php
namespace Braintree;

/**
 * Braintree OAuthCredentials module
 *
 * @package    Braintree
 * @category   Resources
 */
class OAuthResult extends Base
{
    protected function _initialize($attribs)
    {
        $this->_attributes = $attribs;
    }

    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /**
     * returns a string representation of the result
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) .']';
    }
}
class_alias('Braintree\OAuthResult', 'Braintree_OAuthResult');
