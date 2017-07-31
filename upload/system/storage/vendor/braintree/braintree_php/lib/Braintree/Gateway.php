<?php
/**
 * Braintree Gateway module
 *
 * @package    Braintree
 * @category   Resources
 * @copyright  2014 Braintree, a division of PayPal, Inc.
 */
class Braintree_Gateway
{
    /**
     *
     * @var Braintree_Configuration
     */
    public $config;

    public function __construct($config)
    {
        if (is_array($config)) {
            $config = new Braintree_Configuration($config);
        }
        $this->config = $config;
    }

    /**
     * 
     * @return \Braintree_AddOnGateway
     */
    public function addOn()
    {
        return new Braintree_AddOnGateway($this);
    }

    /**
     * 
     * @return \Braintree_AddressGateway
     */
    public function address()
    {
        return new Braintree_AddressGateway($this);
    }

    /**
     * 
     * @return \Braintree_ClientTokenGateway
     */
    public function clientToken()
    {
        return new Braintree_ClientTokenGateway($this);
    }

    /**
     * 
     * @return \Braintree_CreditCardGateway
     */
    public function creditCard()
    {
        return new Braintree_CreditCardGateway($this);
    }

    /**
     * 
     * @return \Braintree_CreditCardVerificationGateway
     */
    public function creditCardVerification()
    {
        return new Braintree_CreditCardVerificationGateway($this);
    }

    /**
     * 
     * @return \Braintree_CustomerGateway
     */
    public function customer()
    {
        return new Braintree_CustomerGateway($this);
    }

    /**
     * 
     * @return \Braintree_DiscountGateway
     */
    public function discount()
    {
        return new Braintree_DiscountGateway($this);
    }

    /**
     * 
     * @return \Braintree_MerchantGateway
     */
    public function merchant()
    {
        return new Braintree_MerchantGateway($this);
    }

    /**
     * 
     * @return \Braintree_MerchantAccountGateway
     */
    public function merchantAccount()
    {
        return new Braintree_MerchantAccountGateway($this);
    }

    /**
     * 
     * @return \Braintree_OAuthGateway
     */
    public function oauth()
    {
        return new Braintree_OAuthGateway($this);
    }

    /**
     * 
     * @return \Braintree_PaymentMethodGateway
     */
    public function paymentMethod()
    {
        return new Braintree_PaymentMethodGateway($this);
    }

    /**
     * 
     * @return \Braintree_PaymentMethodNonceGateway
     */
    public function paymentMethodNonce()
    {
        return new Braintree_PaymentMethodNonceGateway($this);
    }

    /**
     * 
     * @return \Braintree_PayPalAccountGateway
     */
    public function payPalAccount()
    {
        return new Braintree_PayPalAccountGateway($this);
    }

    /**
     * 
     * @return \Braintree_PlanGateway
     */
    public function plan()
    {
        return new Braintree_PlanGateway($this);
    }

    /**
     * 
     * @return \Braintree_SettlementBatchSummaryGateway
     */
    public function settlementBatchSummary()
    {
        return new Braintree_SettlementBatchSummaryGateway($this);
    }

    /**
     * 
     * @return \Braintree_SubscriptionGateway
     */
    public function subscription()
    {
        return new Braintree_SubscriptionGateway($this);
    }

    /**
     * 
     * @return \Braintree_TransactionGateway
     */
    public function transaction()
    {
        return new Braintree_TransactionGateway($this);
    }

    /**
     * 
     * @return \Braintree_TransparentRedirectGateway
     */
    public function transparentRedirect()
    {
        return new Braintree_TransparentRedirectGateway($this);
    }
}
