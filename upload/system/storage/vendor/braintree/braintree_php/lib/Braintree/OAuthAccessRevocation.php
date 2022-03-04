<?php

namespace Braintree;

/**
 * Braintree OAuth Revocation module
 *
 * A revoked OAuth access token
 *
 * For more information, see {@link https://developer.paypal.com/braintree/docs/guides/extend/oauth/access-tokens/php#managing-access-tokens our developer docs}
 */
class OAuthAccessRevocation extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return OauthAccessRevocation
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);

        return $instance;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }
}
