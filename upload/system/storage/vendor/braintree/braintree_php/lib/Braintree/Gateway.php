<?php // phpcs:disable Generic.Commenting.DocComment.MissingShort

namespace Braintree;

/**
 * Braintree Gateway module
 */
class Gateway
{
    /**
     *
     * @var Configuration
     */
    public $config;

    /**
     *
     * @var GraphQLClient
     */
    public $graphQLClient;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($config)
    {
        if (is_array($config)) {
            $config = new Configuration($config);
        }
        $this->config = $config;
        $this->graphQLClient = new GraphQLClient($config);
    }

    /**
     *
     * @return AddOnGateway
     */
    public function addOn()
    {
        return new AddOnGateway($this);
    }

    /**
     *
     * @return AddressGateway
     */
    public function address()
    {
        return new AddressGateway($this);
    }

    /**
     *
     * @return ApplePayGateway
     */
    public function applePay()
    {
        return new ApplePayGateway($this);
    }

    /**
     *
     * @return ClientTokenGateway
     */
    public function clientToken()
    {
        return new ClientTokenGateway($this);
    }

    /**
     *
     * @return CreditCardGateway
     */
    public function creditCard()
    {
        return new CreditCardGateway($this);
    }

    /**
     *
     * @return CreditCardVerificationGateway
     */
    public function creditCardVerification()
    {
        return new CreditCardVerificationGateway($this);
    }

    /**
     *
     * @return CustomerGateway
     */
    public function customer()
    {
        return new CustomerGateway($this);
    }

    /**
     *
     * @return DiscountGateway
     */
    public function discount()
    {
        return new DiscountGateway($this);
    }

    /**
     *
     * @return DisputeGateway
     */
    public function dispute()
    {
        return new DisputeGateway($this);
    }

    /**
     *
     * @return DocumentUploadGateway
     */
    public function documentUpload()
    {
        return new DocumentUploadGateway($this);
    }

    /**
     *
     * @return MerchantGateway
     */
    public function merchant()
    {
        return new MerchantGateway($this);
    }

    /**
     *
     * @return MerchantAccountGateway
     */
    public function merchantAccount()
    {
        return new MerchantAccountGateway($this);
    }

    /**
     *
     * @return OAuthGateway
     */
    public function oauth()
    {
        return new OAuthGateway($this);
    }

    /**
     *
     * @return PaymentMethodGateway
     */
    public function paymentMethod()
    {
        return new PaymentMethodGateway($this);
    }

    /**
     *
     * @return PaymentMethodNonceGateway
     */
    public function paymentMethodNonce()
    {
        return new PaymentMethodNonceGateway($this);
    }

    /**
     *
     * @return PayPalAccountGateway
     */
    public function payPalAccount()
    {
        return new PayPalAccountGateway($this);
    }

    /**
     *
     * @return PlanGateway
     */
    public function plan()
    {
        return new PlanGateway($this);
    }

    /**
     *
     * @return SettlementBatchSummaryGateway
     */
    public function settlementBatchSummary()
    {
        return new SettlementBatchSummaryGateway($this);
    }

    /**
     *
     * @return SubscriptionGateway
     */
    public function subscription()
    {
        return new SubscriptionGateway($this);
    }

    /**
     *
     * @return TestingGateway
     */
    public function testing()
    {
        return new TestingGateway($this);
    }

    /**
     *
     * @return TransactionGateway
     */
    public function transaction()
    {
        return new TransactionGateway($this);
    }

    /**
     *
     * @return TransactionLineItemGateway
     */
    public function transactionLineItem()
    {
        return new TransactionLineItemGateway($this);
    }

    /**
     *
     * @return UsBankAccountGateway
     */
    public function usBankAccount()
    {
        return new UsBankAccountGateway($this);
    }

    /**
     *
     * @return UsBankAccountVerificationGateway
     */
    public function usBankAccountVerification()
    {
        return new UsBankAccountVerificationGateway($this);
    }

    /**
     *
     * @return WebhookNotificationGateway
     */
    public function webhookNotification()
    {
        return new WebhookNotificationGateway($this);
    }

    /**
     *
     * @return WebhookTestingGateway
     */
    public function webhookTesting()
    {
        return new WebhookTestingGateway($this);
    }
}
