<?php
namespace Braintree;

class WebhookTestingGateway
{

    public function __construct($gateway)
    {
        $this->config = $gateway->config;
        $this->config->assertHasAccessTokenOrKeys();
    }

    public function sampleNotification($kind, $id, $sourceMerchantId = null)
    {
        $xml = self::_sampleXml($kind, $id, $sourceMerchantId);
        $payload = base64_encode($xml) . "\n";
        $signature = $this->config->getPublicKey() . "|" . Digest::hexDigestSha1($this->config->getPrivateKey(), $payload);

        return [
            'bt_signature' => $signature,
            'bt_payload' => $payload
        ];
    }

    private static function _sampleXml($kind, $id, $sourceMerchantId)
    {
        switch ($kind) {
            case WebhookNotification::SUB_MERCHANT_ACCOUNT_APPROVED:
                $subjectXml = self::_merchantAccountApprovedSampleXml($id);
                break;
            case WebhookNotification::SUB_MERCHANT_ACCOUNT_DECLINED:
                $subjectXml = self::_merchantAccountDeclinedSampleXml($id);
                break;
            case WebhookNotification::TRANSACTION_DISBURSED:
                $subjectXml = self::_transactionDisbursedSampleXml($id);
                break;
            case WebhookNotification::TRANSACTION_SETTLED:
                $subjectXml = self::_transactionSettledSampleXml($id);
                break;
            case WebhookNotification::TRANSACTION_SETTLEMENT_DECLINED:
                $subjectXml = self::_transactionSettlementDeclinedSampleXml($id);
                break;
            case WebhookNotification::DISBURSEMENT_EXCEPTION:
                $subjectXml = self::_disbursementExceptionSampleXml($id);
                break;
            case WebhookNotification::DISBURSEMENT:
                $subjectXml = self::_disbursementSampleXml($id);
                break;
            case WebhookNotification::PARTNER_MERCHANT_CONNECTED:
                $subjectXml = self::_partnerMerchantConnectedSampleXml($id);
                break;
            case WebhookNotification::PARTNER_MERCHANT_DISCONNECTED:
                $subjectXml = self::_partnerMerchantDisconnectedSampleXml($id);
                break;
            case WebhookNotification::PARTNER_MERCHANT_DECLINED:
                $subjectXml = self::_partnerMerchantDeclinedSampleXml($id);
                break;
            case WebhookNotification::OAUTH_ACCESS_REVOKED:
                $subjectXml = self::_oauthAccessRevocationSampleXml($id);
                break;
            case WebhookNotification::CONNECTED_MERCHANT_STATUS_TRANSITIONED:
                $subjectXml = self::_connectedMerchantStatusTransitionedSampleXml($id);
                break;
            case WebhookNotification::CONNECTED_MERCHANT_PAYPAL_STATUS_CHANGED:
                $subjectXml = self::_connectedMerchantPayPalStatusChangedSampleXml($id);
                break;
            case WebhookNotification::DISPUTE_OPENED:
                $subjectXml = self::_disputeOpenedSampleXml($id);
                break;
            case WebhookNotification::DISPUTE_LOST:
                $subjectXml = self::_disputeLostSampleXml($id);
                break;
            case WebhookNotification::DISPUTE_WON:
                $subjectXml = self::_disputeWonSampleXml($id);
                break;
            case WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY:
                $subjectXml = self::_subscriptionChargedSuccessfullySampleXml($id);
                break;
            case WebhookNotification::SUBSCRIPTION_CHARGED_UNSUCCESSFULLY:
                $subjectXml = self::_subscriptionChargedUnsuccessfullySampleXml($id);
                break;
            case WebhookNotification::CHECK:
                $subjectXml = self::_checkSampleXml();
                break;
            case WebhookNotification::ACCOUNT_UPDATER_DAILY_REPORT:
                $subjectXml = self::_accountUpdaterDailyReportSampleXml($id);
                break;
            case WebhookNotification::IDEAL_PAYMENT_COMPLETE:
                $subjectXml = self::_idealPaymentCompleteSampleXml($id);
                break;
            case WebhookNotification::IDEAL_PAYMENT_FAILED:
                $subjectXml = self::_idealPaymentFailedSampleXml($id);
                break;
            // NEXT_MAJOR_VERSION remove GRANTED_PAYMENT_INSTRUMENT_UPDATE
            case WebhookNotification::GRANTED_PAYMENT_INSTRUMENT_UPDATE:
                $subjectXml = self::_grantedPaymentInstrumentUpdateSampleXml();
                break;
            case WebhookNotification::GRANTOR_UPDATED_GRANTED_PAYMENT_METHOD:
                $subjectXml = self::_grantedPaymentInstrumentUpdateSampleXml();
                break;
            case WebhookNotification::RECIPIENT_UPDATED_GRANTED_PAYMENT_METHOD:
                $subjectXml = self::_grantedPaymentInstrumentUpdateSampleXml();
                break;
            case WebhookNotification::LOCAL_PAYMENT_COMPLETED:
                $subjectXml = self::_localPaymentCompletedSampleXml();
                break;
            default:
                $subjectXml = self::_subscriptionSampleXml($id);
                break;
        }
        $timestamp = self::_timestamp();

        $sourceMerchantIdXml = '';
        if (!is_null($sourceMerchantId)) {
            $sourceMerchantIdXml = "<source-merchant-id>{$sourceMerchantId}</source-merchant-id>";
        }

        return "
        <notification>
            <timestamp type=\"datetime\">{$timestamp}</timestamp>
            <kind>{$kind}</kind>
            {$sourceMerchantIdXml}
            <subject>{$subjectXml}</subject>
        </notification>
        ";
    }

    private static function _merchantAccountApprovedSampleXml($id)
    {
        return "
        <merchant_account>
            <id>{$id}</id>
            <master_merchant_account>
                <id>master_ma_for_{$id}</id>
                <status>active</status>
            </master_merchant_account>
            <status>active</status>
        </merchant_account>
        ";
    }

    private static function _merchantAccountDeclinedSampleXml($id)
    {
        return "
        <api-error-response>
            <message>Credit score is too low</message>
            <errors>
                <errors type=\"array\"/>
                    <merchant-account>
                        <errors type=\"array\">
                            <error>
                                <code>82621</code>
                                <message>Credit score is too low</message>
                                <attribute type=\"symbol\">base</attribute>
                            </error>
                        </errors>
                    </merchant-account>
                </errors>
                <merchant-account>
                    <id>{$id}</id>
                    <status>suspended</status>
                    <master-merchant-account>
                        <id>master_ma_for_{$id}</id>
                        <status>suspended</status>
                    </master-merchant-account>
                </merchant-account>
        </api-error-response>
        ";
    }

    private static function _transactionDisbursedSampleXml($id)
    {
        return "
        <transaction>
            <id>${id}</id>
            <amount>100</amount>
            <disbursement-details>
                <disbursement-date type=\"date\">2013-07-09</disbursement-date>
            </disbursement-details>
        </transaction>
        ";
    }

    private static function _transactionSettledSampleXml($id)
    {
        return "
        <transaction>
          <id>${id}</id>
          <status>settled</status>
          <type>sale</type>
          <currency-iso-code>USD</currency-iso-code>
          <amount>100.00</amount>
          <merchant-account-id>ogaotkivejpfayqfeaimuktty</merchant-account-id>
          <payment-instrument-type>us_bank_account</payment-instrument-type>
          <us-bank-account>
            <routing-number>123456789</routing-number>
            <last-4>1234</last-4>
            <account-type>checking</account-type>
            <account-holder-name>Dan Schulman</account-holder-name>
          </us-bank-account>
        </transaction>
        ";
    }

    private static function _transactionSettlementDeclinedSampleXml($id)
    {
        return "
        <transaction>
          <id>${id}</id>
          <status>settlement_declined</status>
          <type>sale</type>
          <currency-iso-code>USD</currency-iso-code>
          <amount>100.00</amount>
          <merchant-account-id>ogaotkivejpfayqfeaimuktty</merchant-account-id>
          <payment-instrument-type>us_bank_account</payment-instrument-type>
          <us-bank-account>
            <routing-number>123456789</routing-number>
            <last-4>1234</last-4>
            <account-type>checking</account-type>
            <account-holder-name>Dan Schulman</account-holder-name>
          </us-bank-account>
        </transaction>
        ";
    }

    private static function _disbursementExceptionSampleXml($id)
    {
        return "
        <disbursement>
          <id>${id}</id>
          <transaction-ids type=\"array\">
            <item>asdfg</item>
            <item>qwert</item>
          </transaction-ids>
          <success type=\"boolean\">false</success>
          <retry type=\"boolean\">false</retry>
          <merchant-account>
            <id>merchant_account_token</id>
            <currency-iso-code>USD</currency-iso-code>
            <sub-merchant-account type=\"boolean\">false</sub-merchant-account>
            <status>active</status>
          </merchant-account>
          <amount>100.00</amount>
          <disbursement-date type=\"date\">2014-02-10</disbursement-date>
          <exception-message>bank_rejected</exception-message>
          <follow-up-action>update_funding_information</follow-up-action>
        </disbursement>
        ";
    }

    private static function _disbursementSampleXml($id)
    {
        return "
        <disbursement>
          <id>${id}</id>
          <transaction-ids type=\"array\">
            <item>asdfg</item>
            <item>qwert</item>
          </transaction-ids>
          <success type=\"boolean\">true</success>
          <retry type=\"boolean\">false</retry>
          <merchant-account>
            <id>merchant_account_token</id>
            <currency-iso-code>USD</currency-iso-code>
            <sub-merchant-account type=\"boolean\">false</sub-merchant-account>
            <status>active</status>
          </merchant-account>
          <amount>100.00</amount>
          <disbursement-date type=\"date\">2014-02-10</disbursement-date>
          <exception-message nil=\"true\"/>
          <follow-up-action nil=\"true\"/>
        </disbursement>
        ";
    }

    private static function _disputeOpenedSampleXml($id)
    {
        return "
        <dispute>
          <amount>250.00</amount>
          <amount-disputed>250.0</amount-disputed>
          <amount-won>245.00</amount-won>
          <currency-iso-code>USD</currency-iso-code>
          <received-date type=\"date\">2014-03-01</received-date>
          <reply-by-date type=\"date\">2014-03-21</reply-by-date>
          <kind>chargeback</kind>
          <status>open</status>
          <reason>fraud</reason>
          <id>${id}</id>
          <transaction>
            <id>${id}</id>
            <amount>250.00</amount>
          </transaction>
          <date-opened type=\"date\">2014-03-21</date-opened>
        </dispute>
        ";
    }

    private static function _disputeLostSampleXml($id)
    {
        return "
        <dispute>
          <amount>250.00</amount>
          <amount-disputed>250.0</amount-disputed>
          <amount-won>245.00</amount-won>
          <currency-iso-code>USD</currency-iso-code>
          <received-date type=\"date\">2014-03-01</received-date>
          <reply-by-date type=\"date\">2014-03-21</reply-by-date>
          <kind>chargeback</kind>
          <status>lost</status>
          <reason>fraud</reason>
          <id>${id}</id>
          <transaction>
            <id>${id}</id>
            <amount>250.00</amount>
            <next_billing-date type=\"date\">2020-02-10</next_billing-date>
          </transaction>
          <date-opened type=\"date\">2014-03-21</date-opened>
        </dispute>
        ";
    }

    private static function _disputeWonSampleXml($id)
    {
        return "
        <dispute>
          <amount>250.00</amount>
          <amount-disputed>250.0</amount-disputed>
          <amount-won>245.00</amount-won>
          <currency-iso-code>USD</currency-iso-code>
          <received-date type=\"date\">2014-03-01</received-date>
          <reply-by-date type=\"date\">2014-03-21</reply-by-date>
          <kind>chargeback</kind>
          <status>won</status>
          <reason>fraud</reason>
          <id>${id}</id>
          <transaction>
            <id>${id}</id>
            <amount>250.00</amount>
          </transaction>
          <date-opened type=\"date\">2014-03-21</date-opened>
          <date-won type=\"date\">2014-03-22</date-won>
        </dispute>
        ";
    }

    private static function _subscriptionSampleXml($id)
    {
        return "
        <subscription>
            <id>{$id}</id>
            <transactions type=\"array\">
            </transactions>
            <add_ons type=\"array\">
            </add_ons>
            <discounts type=\"array\">
            </discounts>
        </subscription>
        ";
    }

    private static function _subscriptionChargedSuccessfullySampleXml($id)
    {
        return "
        <subscription>
            <id>{$id}</id>
            <billing-period-start-date type=\"date\">2016-03-21</billing-period-start-date>
            <billing-period-end-date type=\"date\">2017-03-31</billing-period-end-date>
            <transactions type=\"array\">
                <transaction>
                    <id>{$id}</id>
                    <status>submitted_for_settlement</status>
                    <amount>49.99</amount>
                </transaction>
            </transactions>
            <add_ons type=\"array\">
            </add_ons>
            <discounts type=\"array\">
            </discounts>
        </subscription>
        ";
    }

    private static function _subscriptionChargedUnsuccessfullySampleXml($id)
    {
        return "
        <subscription>
            <id>{$id}</id>
            <billing-period-start-date type=\"date\">2016-03-21</billing-period-start-date>
            <billing-period-end-date type=\"date\">2017-03-31</billing-period-end-date>
            <transactions type=\"array\">
                <transaction>
                    <id>{$id}</id>
                    <status>failed</status>
                    <amount>49.99</amount>
                </transaction>
            </transactions>
            <add_ons type=\"array\">
            </add_ons>
            <discounts type=\"array\">
            </discounts>
        </subscription>
        ";
    }

    private static function _checkSampleXml()
    {
        return "
            <check type=\"boolean\">true</check>
        ";
    }

    private static function _partnerMerchantConnectedSampleXml($id)
    {
        return "
        <partner-merchant>
          <merchant-public-id>public_id</merchant-public-id>
          <public-key>public_key</public-key>
          <private-key>private_key</private-key>
          <partner-merchant-id>abc123</partner-merchant-id>
          <client-side-encryption-key>cse_key</client-side-encryption-key>
        </partner-merchant>
        ";
    }

    private static function _partnerMerchantDisconnectedSampleXml($id)
    {
        return "
        <partner-merchant>
          <partner-merchant-id>abc123</partner-merchant-id>
        </partner-merchant>
        ";
    }

    private static function _partnerMerchantDeclinedSampleXml($id)
    {
        return "
        <partner-merchant>
          <partner-merchant-id>abc123</partner-merchant-id>
        </partner-merchant>
        ";
    }

    private static function _oauthAccessRevocationSampleXml($id)
    {
        return "
        <oauth-application-revocation>
          <merchant-id>{$id}</merchant-id>
          <oauth-application-client-id>oauth_application_client_id</oauth-application-client-id>
        </oauth-application-revocation>
        ";
    }

    private static function _accountUpdaterDailyReportSampleXml($id)
    {
        return "
        <account-updater-daily-report>
            <report-date type=\"date\">2016-01-14</report-date>
            <report-url>link-to-csv-report</report-url>
        </account-updater-daily-report>
        ";
    }

    private static function _connectedMerchantStatusTransitionedSampleXml($id)
    {
        return "
        <connected-merchant-status-transitioned>
          <merchant-public-id>{$id}</merchant-public-id>
          <status>new_status</status>
          <oauth-application-client-id>oauth_application_client_id</oauth-application-client-id>
        </connected-merchant-status-transitioned>
        ";
    }

    private static function _connectedMerchantPayPalStatusChangedSampleXml($id)
    {
        return "
        <connected-merchant-paypal-status-changed>
          <merchant-public-id>{$id}</merchant-public-id>
          <action>link</action>
          <oauth-application-client-id>oauth_application_client_id</oauth-application-client-id>
        </connected-merchant-paypal-status-changed>
        ";
    }

    private static function _idealPaymentCompleteSampleXml($id)
    {
        return "
        <ideal-payment>
          <id>{$id}</id>
          <status>COMPLETE</status>
          <issuer>ABCISSUER</issuer>
          <order-id>ORDERABC</order-id>
          <currency>EUR</currency>
          <amount>10.00</amount>
          <created-at>2016-11-29T23:27:34.547Z</created-at>
          <approval-url>https://example.com</approval-url>
          <ideal-transaction-id>1234567890</ideal-transaction-id>
        </ideal-payment>
        ";
    }

    private static function _idealPaymentFailedSampleXml($id)
    {
        return "
        <ideal-payment>
          <id>{$id}</id>
          <status>FAILED</status>
          <issuer>ABCISSUER</issuer>
          <order-id>ORDERABC</order-id>
          <currency>EUR</currency>
          <amount>10.00</amount>
          <created-at>2016-11-29T23:27:34.547Z</created-at>
          <approval-url>https://example.com</approval-url>
          <ideal-transaction-id>1234567890</ideal-transaction-id>
        </ideal-payment>
        ";
    }

    private static function _grantedPaymentInstrumentUpdateSampleXml()
	{
        return "
		<granted-payment-instrument-update>
		  <grant-owner-merchant-id>vczo7jqrpwrsi2px</grant-owner-merchant-id>
		  <grant-recipient-merchant-id>cf0i8wgarszuy6hc</grant-recipient-merchant-id>
		  <payment-method-nonce>
			<nonce>ee257d98-de40-47e8-96b3-a6954ea7a9a4</nonce>
			<consumed type='boolean'>false</consumed>
			<locked type='boolean'>false</locked>
		  </payment-method-nonce>
		  <token>abc123z</token>
		  <updated-fields type='array'>
			<item>expiration-month</item>
			<item>expiration-year</item>
		  </updated-fields>
		</granted-payment-instrument-update>
        ";
    }

    private static function _localPaymentCompletedSampleXml()
	{
        return "
		<local-payment>
            <payment-id>a-payment-id</payment-id>
            <payer-id>a-payer-id</payer-id>
		</local-payment>
        ";
    }

    private static function _timestamp()
    {
        $originalZone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $timestamp = strftime('%Y-%m-%dT%TZ');
        date_default_timezone_set($originalZone);

        return $timestamp;
    }
}
class_alias('Braintree\WebhookTestingGateway', 'Braintree_WebhookTestingGateway');
