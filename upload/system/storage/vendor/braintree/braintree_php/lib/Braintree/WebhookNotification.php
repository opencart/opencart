<?php

namespace Braintree;

/**
 * WebhookNotification class
 * module for webhook objects
 */
class WebhookNotification extends Base
{
    // phpcs:disable Generic.Files.LineLength
    const ACCOUNT_UPDATER_DAILY_REPORT = 'account_updater_daily_report';
    const CHECK = 'check';
    const CONNECTED_MERCHANT_PAYPAL_STATUS_CHANGED = 'connected_merchant_paypal_status_changed';
    const CONNECTED_MERCHANT_STATUS_TRANSITIONED = 'connected_merchant_status_transitioned';
    const DISBURSEMENT = 'disbursement';
    const DISBURSEMENT_EXCEPTION = 'disbursement_exception';
    const DISPUTE_ACCEPTED = 'dispute_accepted';
    const DISPUTE_DISPUTED = 'dispute_disputed';
    const DISPUTE_EXPIRED = 'dispute_expired';
    const DISPUTE_LOST = 'dispute_lost';
    const DISPUTE_OPENED = 'dispute_opened';
    const DISPUTE_WON = 'dispute_won';
    const GRANTED_PAYMENT_METHOD_REVOKED = 'granted_payment_method_revoked';
    const GRANTOR_UPDATED_GRANTED_PAYMENT_METHOD = 'grantor_updated_granted_payment_method';
    const LOCAL_PAYMENT_COMPLETED = "local_payment_completed";
    const LOCAL_PAYMENT_EXPIRED = "local_payment_expired";
    const LOCAL_PAYMENT_FUNDED = "local_payment_funded";
    const LOCAL_PAYMENT_REVERSED = "local_payment_reversed";
    const OAUTH_ACCESS_REVOKED = 'oauth_access_revoked';
    const PARTNER_MERCHANT_CONNECTED = 'partner_merchant_connected';
    const PARTNER_MERCHANT_DECLINED = 'partner_merchant_declined';
    const PARTNER_MERCHANT_DISCONNECTED = 'partner_merchant_disconnected';
    const PAYMENT_METHOD_CUSTOMER_DATA_UPDATED = 'payment_method_customer_data_updated';
    const PAYMENT_METHOD_REVOKED_BY_CUSTOMER = 'payment_method_revoked_by_customer';
    const RECIPIENT_UPDATED_GRANTED_PAYMENT_METHOD = 'recipient_updated_granted_payment_method';
    const SUBSCRIPTION_CANCELED = 'subscription_canceled';
    const SUBSCRIPTION_CHARGED_SUCCESSFULLY = 'subscription_charged_successfully';
    const SUBSCRIPTION_CHARGED_UNSUCCESSFULLY = 'subscription_charged_unsuccessfully';
    const SUBSCRIPTION_EXPIRED = 'subscription_expired';
    const SUBSCRIPTION_TRIAL_ENDED = 'subscription_trial_ended';
    const SUBSCRIPTION_WENT_ACTIVE = 'subscription_went_active';
    const SUBSCRIPTION_WENT_PAST_DUE = 'subscription_went_past_due';
    const SUB_MERCHANT_ACCOUNT_APPROVED = 'sub_merchant_account_approved';
    const SUB_MERCHANT_ACCOUNT_DECLINED = 'sub_merchant_account_declined';
    const TRANSACTION_DISBURSED = 'transaction_disbursed';
    const TRANSACTION_REVIEWED = 'transaction_reviewed';
    const TRANSACTION_SETTLED = 'transaction_settled';
    const TRANSACTION_SETTLEMENT_DECLINED = 'transaction_settlement_declined';
    // phpcs:enable Generic.Files.LineLength

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $signature used to verify before parsing
     * @param mixed  $payload   to be parsed
     *
     * @see WebHookNotificationGateway::parse()
     *
     * @return WebhookNotification object|Exception
     */
    public static function parse($signature, $payload)
    {
        return Configuration::gateway()->webhookNotification()->parse($signature, $payload);
    }

    /*
     * Static methods redirecting to gateway class
     *
     * @param object $challenge to be verified
     *
     * @see WebHookNotificationGateway::verify()
     *
     * @return string|Exception
     */
    public static function verify($challenge)
    {
        return Configuration::gateway()->webhookNotification()->verify($challenge);
    }

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return WebhookNotification
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($attributes)
    {
        // phpcs:disable Generic.Files.LineLength
        $this->_attributes = $attributes;

        if (!isset($attributes['sourceMerchantId'])) {
            $this->_set('sourceMerchantId', null);
        }

        if (isset($attributes['subject']['apiErrorResponse'])) {
            $wrapperNode = $attributes['subject']['apiErrorResponse'];
        } else {
            $wrapperNode = $attributes['subject'];
        }

        if (isset($wrapperNode['subscription'])) {
            $this->_set('subscription', Subscription::factory($attributes['subject']['subscription']));
        }

        if (isset($wrapperNode['merchantAccount'])) {
            $this->_set('merchantAccount', MerchantAccount::factory($wrapperNode['merchantAccount']));
        }

        if (isset($wrapperNode['transaction'])) {
            $this->_set('transaction', Transaction::factory($wrapperNode['transaction']));
        }

        if (isset($wrapperNode['transactionReview'])) {
            $this->_set('transactionReview', TransactionReview::factory($wrapperNode['transactionReview']));
        }

        if (isset($wrapperNode['disbursement'])) {
            $this->_set('disbursement', Disbursement::factory($wrapperNode['disbursement']));
        }

        if (isset($wrapperNode['partnerMerchant'])) {
            $this->_set('partnerMerchant', PartnerMerchant::factory($wrapperNode['partnerMerchant']));
        }

        if (isset($wrapperNode['oauthApplicationRevocation'])) {
            $this->_set('oauthAccessRevocation', OAuthAccessRevocation::factory($wrapperNode['oauthApplicationRevocation']));
        }

        if (isset($wrapperNode['connectedMerchantStatusTransitioned'])) {
            $this->_set('connectedMerchantStatusTransitioned', ConnectedMerchantStatusTransitioned::factory($wrapperNode['connectedMerchantStatusTransitioned']));
        }

        if (isset($wrapperNode['connectedMerchantPaypalStatusChanged'])) {
            $this->_set('connectedMerchantPayPalStatusChanged', ConnectedMerchantPayPalStatusChanged::factory($wrapperNode['connectedMerchantPaypalStatusChanged']));
        }

        if (isset($wrapperNode['dispute'])) {
            $this->_set('dispute', Dispute::factory($wrapperNode['dispute']));
        }

        if (isset($wrapperNode['accountUpdaterDailyReport'])) {
            $this->_set('accountUpdaterDailyReport', AccountUpdaterDailyReport::factory($wrapperNode['accountUpdaterDailyReport']));
        }

        if (isset($wrapperNode['grantedPaymentInstrumentUpdate'])) {
            $this->_set('grantedPaymentInstrumentUpdate', GrantedPaymentInstrumentUpdate::factory($wrapperNode['grantedPaymentInstrumentUpdate']));
        }

        if (in_array($attributes['kind'], [self::GRANTED_PAYMENT_METHOD_REVOKED, self::PAYMENT_METHOD_REVOKED_BY_CUSTOMER])) {
            $this->_set('revokedPaymentMethodMetadata', RevokedPaymentMethodMetadata::factory($wrapperNode));
        }

        if (isset($wrapperNode['localPayment'])) {
            $this->_set('localPaymentCompleted', LocalPaymentCompleted::factory($wrapperNode['localPayment']));
        }

        if (isset($wrapperNode['localPaymentExpired'])) {
            $this->_set('localPaymentExpired', LocalPaymentExpired::factory($wrapperNode['localPaymentExpired']));
        }

        if (isset($wrapperNode['localPaymentFunded'])) {
            $this->_set('localPaymentFunded', LocalPaymentFunded::factory($wrapperNode['localPaymentFunded']));
        }

        if (isset($wrapperNode['localPaymentReversed'])) {
            $this->_set('localPaymentReversed', LocalPaymentReversed::factory($wrapperNode['localPaymentReversed']));
        }

        if (isset($wrapperNode['paymentMethodCustomerDataUpdatedMetadata'])) {
            $this->_set('paymentMethodCustomerDataUpdatedMetadata', PaymentMethodCustomerDataUpdatedMetadata::factory($wrapperNode['paymentMethodCustomerDataUpdatedMetadata']));
        }

        if (isset($wrapperNode['errors'])) {
            $this->_set('errors', new Error\ValidationErrorCollection($wrapperNode['errors']));
            $this->_set('message', $wrapperNode['message']);
        }
        // phpcs:enable Generic.Files.LineLength
    }
}
