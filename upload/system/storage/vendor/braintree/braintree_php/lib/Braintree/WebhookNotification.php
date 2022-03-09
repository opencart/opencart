<?php
namespace Braintree;

class WebhookNotification extends Base
{
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
    const TRANSACTION_SETTLED = 'transaction_settled';
    const TRANSACTION_SETTLEMENT_DECLINED = 'transaction_settlement_declined';
    const DISBURSEMENT_EXCEPTION = 'disbursement_exception';
    const DISBURSEMENT = 'disbursement';
    const DISPUTE_OPENED = 'dispute_opened';
    const DISPUTE_LOST = 'dispute_lost';
    const DISPUTE_WON = 'dispute_won';
    const PARTNER_MERCHANT_CONNECTED = 'partner_merchant_connected';
    const PARTNER_MERCHANT_DISCONNECTED = 'partner_merchant_disconnected';
    const PARTNER_MERCHANT_DECLINED = 'partner_merchant_declined';
    const OAUTH_ACCESS_REVOKED = 'oauth_access_revoked';
    const CHECK = 'check';
    const ACCOUNT_UPDATER_DAILY_REPORT = 'account_updater_daily_report';
    const CONNECTED_MERCHANT_STATUS_TRANSITIONED = 'connected_merchant_status_transitioned';
    const CONNECTED_MERCHANT_PAYPAL_STATUS_CHANGED = 'connected_merchant_paypal_status_changed';
    const IDEAL_PAYMENT_COMPLETE = 'ideal_payment_complete';
    const IDEAL_PAYMENT_FAILED = 'ideal_payment_failed';
    // NEXT_MAJOR_VERSION remove GRANTED_PAYMENT_INSTRUMENT_UPDATE. Kind is not sent by Braintree Gateway.
    // Kind will either be GRANTOR_UPDATED_GRANTED_PAYMENT_METHOD or RECIPIENT_UPDATED_GRANTED_PAYMENT_METHOD.
    const GRANTED_PAYMENT_INSTRUMENT_UPDATE = 'granted_payment_instrument_update';
    const GRANTOR_UPDATED_GRANTED_PAYMENT_METHOD = 'grantor_updated_granted_payment_method';
    const RECIPIENT_UPDATED_GRANTED_PAYMENT_METHOD = 'recipient_updated_granted_payment_method';
    const GRANTED_PAYMENT_METHOD_REVOKED = 'granted_payment_method_revoked';
    const LOCAL_PAYMENT_COMPLETED = "local_payment_completed";

    public static function parse($signature, $payload) {
        return Configuration::gateway()->webhookNotification()->parse($signature, $payload);
    }

    public static function verify($challenge) {
        return Configuration::gateway()->webhookNotification()->verify($challenge);
    }

    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($attributes)
    {
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

        if (isset($wrapperNode['idealPayment'])) {
            $this->_set('idealPayment', IdealPayment::factory($wrapperNode['idealPayment']));
        }

        if (isset($wrapperNode['grantedPaymentInstrumentUpdate'])) {
            $this->_set('grantedPaymentInstrumentUpdate', GrantedPaymentInstrumentUpdate::factory($wrapperNode['grantedPaymentInstrumentUpdate']));
        }

        if ($attributes['kind'] == self::GRANTED_PAYMENT_METHOD_REVOKED) {
            $this->_set('revokedPaymentMethodMetadata', RevokedPaymentMethodMetadata::factory($wrapperNode));
        }

        if (isset($wrapperNode['localPayment'])) {
            $this->_set('localPaymentCompleted', LocalPaymentCompleted::factory($wrapperNode['localPayment']));
        }

        if (isset($wrapperNode['errors'])) {
            $this->_set('errors', new Error\ValidationErrorCollection($wrapperNode['errors']));
            $this->_set('message', $wrapperNode['message']);
        }
    }
}
class_alias('Braintree\WebhookNotification', 'Braintree_WebhookNotification');
