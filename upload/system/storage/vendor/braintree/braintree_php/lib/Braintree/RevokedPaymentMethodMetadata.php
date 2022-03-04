<?php

namespace Braintree;

/**
 * Braintree RevokedPaymentMethodMetadata module
 */

/**
 * Manages Braintree RevokedPaymentMethodMetadata
 *
 * See our {@link https://developer.paypal.com/braintree/docs/guides/extend/grant-api/revocation developer docs} for more information
 */
class RevokedPaymentMethodMetadata extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return RevokedPaymentMethodMetadata
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->revokedPaymentMethod = PaymentMethodParser::parsePaymentMethod($attributes);
        $instance->customerId = $instance->revokedPaymentMethod->customerId;
        $instance->token = $instance->revokedPaymentMethod->token;
        return $instance;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
