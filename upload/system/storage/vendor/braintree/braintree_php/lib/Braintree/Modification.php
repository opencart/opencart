<?php
class Braintree_Modification extends Braintree_Base
{
    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }

    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    public function __toString() {
        return get_called_class() . '[' . Braintree_Util::attributesToString($this->_attributes) . ']';
    }
}
