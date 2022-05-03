<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use Test\Setup;
use Test\Helper;
use Braintree;

class WebhookNotificationTest extends Setup
{
    public function setup()
    {
        self::integrationMerchantConfig();
    }

    public function testVerify()
    {
        $verificationString = Braintree\WebhookNotification::verify('20f9f8ed05f77439fe955c977e4c8a53');
        $this->assertEquals('integration_public_key|d9b899556c966b3f06945ec21311865d35df3ce4', $verificationString);
    }

    /**
     * @expectedException Braintree\Exception\InvalidChallenge
     * @expectedExceptionMessage challenge contains non-hex characters
     */
    public function testVerifyRaisesErrorWithInvalidChallenge()
    {
        $this->setExpectedException('Braintree\Exception\InvalidChallenge', 'challenge contains non-hex characters');

        Braintree\WebhookNotification::verify('bad challenge');
    }

    /**
     * @expectedException Braintree\Exception\Configuration
     * @expectedExceptionMessage Braintree\Configuration::merchantId needs to be set (or accessToken needs to be passed to Braintree\Gateway)
     */
    public function testVerifyRaisesErrorWhenEnvironmentNotSet()
    {
        Braintree\Configuration::reset();

        Braintree\WebhookNotification::verify('20f9f8ed05f77439fe955c977e4c8a53');
    }

    public function testSampleNotificationReturnsAParsableNotification()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE, $webhookNotification->kind);
        $this->assertNotNull($webhookNotification->timestamp);
        $this->assertEquals("my_id", $webhookNotification->subscription->id);
        $this->assertNull($webhookNotification->sourceMerchantId);
    }

    public function testSampleNotificationContainsSourceMerchantIdIfSpecified()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id',
            'my_source_merchant_id'
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals($webhookNotification->sourceMerchantId, 'my_source_merchant_id');
    }

    public function testParsingModifiedSignatureRaisesError()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $this->setExpectedException('Braintree\Exception\InvalidSignature', 'signature does not match payload - one has been modified');

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'] . "bad",
            $sampleNotification['bt_payload']
        );
    }

    /**
     * @expectedException Braintree\Exception\Configuration
     * @expectedExceptionMessage Braintree\Configuration::merchantId needs to be set (or accessToken needs to be passed to Braintree\Gateway)
     */
    public function testParsingWithNoKeysRaisesError()
    {
        Braintree\Configuration::reset();

        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );
    }

    public function testParsingWebhookWithWrongKeysRaisesError()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        Braintree\Configuration::environment('development');
        Braintree\Configuration::merchantId('integration_merchant_id');
        Braintree\Configuration::publicKey('wrong_public_key');
        Braintree\Configuration::privateKey('wrong_private_key');

        $this->setExpectedException('Braintree\Exception\InvalidSignature', 'no matching public key');

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            "bad" . $sampleNotification['bt_payload']
        );
    }

    public function testParsingModifiedPayloadRaisesError()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $this->setExpectedException('Braintree\Exception\InvalidSignature');

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            "bad" . $sampleNotification['bt_payload']
        );
    }

    public function testParsingUnknownPublicKeyRaisesError()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $this->setExpectedException('Braintree\Exception\InvalidSignature');

        $webhookNotification = Braintree\WebhookNotification::parse(
            "bad" . $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );
    }

    public function testParsingInvalidSignatureRaisesError()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $this->setExpectedException('Braintree\Exception\InvalidSignature');

        $webhookNotification = Braintree\WebhookNotification::parse(
            "bad_signature",
            $sampleNotification['bt_payload']
        );
    }

    public function testParsingNullSignatureRaisesError()
    {
        $this->setExpectedException('Braintree\Exception\InvalidSignature', 'signature cannot be null');

        $webhookNotification = Braintree\WebhookNotification::parse(null, "payload");
    }

    public function testParsingNullPayloadRaisesError()
    {
        $this->setExpectedException('Braintree\Exception\InvalidSignature', 'payload cannot be null');

        $webhookNotification = Braintree\WebhookNotification::parse("signature", null);
    }

    public function testParsingInvalidCharactersRaisesError()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $this->setExpectedException('Braintree\Exception\InvalidSignature', 'payload contains illegal characters');

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            "~*~*invalid*~*~"
        );
    }

    public function testParsingAllowsAllValidCharacters()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $this->setExpectedException('Braintree\Exception\InvalidSignature', 'signature does not match payload - one has been modified');

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+=/\n"
        );
    }

    public function testParsingRetriesPayloadWithANewline()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE,
            'my_id'
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            rtrim($sampleNotification['bt_payload'])
        );
    }

    public function testAllowsParsingUsingGateway()
    {
        $gateway = new Braintree\Gateway([
            'privateKey' => 'integration_private_key',
            'publicKey' => 'integration_public_key',
            'merchantId' => 'integration_merchant_id',
            'environment' => 'development'
        ]);

        $sampleNotification = $gateway->webhookTesting()->sampleNotification(
            Braintree\WebhookNotification::CHECK,
            "my_id"
        );

        $webhookNotification = $gateway->webhookNotification()->parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::CHECK, $webhookNotification->kind);
    }

    public function testAllowsParsingUsingStaticMethods()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::CHECK,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::CHECK, $webhookNotification->kind);
    }

    public function testBuildsASampleNotificationForASubscriptionChargedSuccessfullyWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->subscription->id);
        $this->assertEquals(new DateTime('2016-03-21'), $webhookNotification->subscription->billingPeriodStartDate);
        $this->assertEquals(new DateTime('2017-03-31'), $webhookNotification->subscription->billingPeriodEndDate);
        $this->assertEquals(1, count($webhookNotification->subscription->transactions));

        $transaction = $webhookNotification->subscription->transactions[0];
        $this->assertEquals('submitted_for_settlement', $transaction->status);
        $this->assertEquals('49.99', $transaction->amount);
    }

    public function testBuildsASampleNotificationForASubscriptionChargedUnsuccessfullyWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUBSCRIPTION_CHARGED_UNSUCCESSFULLY,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::SUBSCRIPTION_CHARGED_UNSUCCESSFULLY, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->subscription->id);
        $this->assertEquals(new DateTime('2016-03-21'), $webhookNotification->subscription->billingPeriodStartDate);
        $this->assertEquals(new DateTime('2017-03-31'), $webhookNotification->subscription->billingPeriodEndDate);
        $this->assertEquals(1, count($webhookNotification->subscription->transactions));

        $transaction = $webhookNotification->subscription->transactions[0];
        $this->assertEquals('failed', $transaction->status);
        $this->assertEquals('49.99', $transaction->amount);
    }

    public function testBuildsASampleNotificationForAMerchantAccountApprovedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUB_MERCHANT_ACCOUNT_APPROVED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::SUB_MERCHANT_ACCOUNT_APPROVED, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->merchantAccount->id);
        $this->assertEquals(Braintree\MerchantAccount::STATUS_ACTIVE, $webhookNotification->merchantAccount->status);
        $this->assertEquals("master_ma_for_my_id", $webhookNotification->merchantAccount->masterMerchantAccount->id);
        $this->assertEquals(Braintree\MerchantAccount::STATUS_ACTIVE, $webhookNotification->merchantAccount->masterMerchantAccount->status);
    }

    public function testBuildsASampleNotificationForAMerchantAccountDeclinedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::SUB_MERCHANT_ACCOUNT_DECLINED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::SUB_MERCHANT_ACCOUNT_DECLINED, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->merchantAccount->id);
        $this->assertEquals(Braintree\MerchantAccount::STATUS_SUSPENDED, $webhookNotification->merchantAccount->status);
        $this->assertEquals("master_ma_for_my_id", $webhookNotification->merchantAccount->masterMerchantAccount->id);
        $this->assertEquals(Braintree\MerchantAccount::STATUS_SUSPENDED, $webhookNotification->merchantAccount->masterMerchantAccount->status);
        $this->assertEquals("Credit score is too low", $webhookNotification->message);
        $errors = $webhookNotification->errors->forKey('merchantAccount')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::MERCHANT_ACCOUNT_DECLINED_OFAC, $errors[0]->code);
    }

    public function testBuildsASampleNotificationForATransactionDisbursedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::TRANSACTION_DISBURSED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::TRANSACTION_DISBURSED, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->transaction->id);
        $this->assertEquals(100, $webhookNotification->transaction->amount);
        $this->assertNotNull($webhookNotification->transaction->disbursementDetails->disbursementDate);
    }

    public function testBuildsASampleNotificationForATransactionSettledWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::TRANSACTION_SETTLED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::TRANSACTION_SETTLED, $webhookNotification->kind);
        $transaction = $webhookNotification->transaction;
        $this->assertEquals("my_id", $transaction->id);
        $this->assertEquals("settled", $transaction->status);
        $this->assertEquals(100, $transaction->amount);
        $this->assertEquals('123456789', $transaction->usBankAccount->routingNumber);
        $this->assertEquals('1234', $transaction->usBankAccount->last4);
        $this->assertEquals('checking', $transaction->usBankAccount->accountType);
        $this->assertEquals('Dan Schulman', $transaction->usBankAccount->accountHolderName);
    }

    public function testBuildsASampleNotificationForATransactionSettlementDeclinedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::TRANSACTION_SETTLEMENT_DECLINED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::TRANSACTION_SETTLEMENT_DECLINED, $webhookNotification->kind);
        $transaction = $webhookNotification->transaction;
        $this->assertEquals("my_id", $transaction->id);
        $this->assertEquals("settlement_declined", $transaction->status);
        $this->assertEquals(100, $transaction->amount);
        $this->assertEquals('123456789', $transaction->usBankAccount->routingNumber);
        $this->assertEquals('1234', $transaction->usBankAccount->last4);
        $this->assertEquals('checking', $transaction->usBankAccount->accountType);
        $this->assertEquals('Dan Schulman', $transaction->usBankAccount->accountHolderName);
    }

    public function testBuildsASampleNotificationForADisputeOpenedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::DISPUTE_OPENED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::DISPUTE_OPENED, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->dispute->id);
        $this->assertEquals(Braintree\Dispute::OPEN, $webhookNotification->dispute->status);
        $this->assertEquals(Braintree\Dispute::CHARGEBACK, $webhookNotification->dispute->kind);
        $this->assertEquals(new DateTime('2014-03-21'), $webhookNotification->dispute->dateOpened);
    }

    public function testBuildsASampleNotificationForADisputeLostWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::DISPUTE_LOST,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::DISPUTE_LOST, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->dispute->id);
        $this->assertEquals(Braintree\Dispute::LOST, $webhookNotification->dispute->status);
        $this->assertEquals(Braintree\Dispute::CHARGEBACK, $webhookNotification->dispute->kind);
        $this->assertEquals(new DateTime('2014-03-21'), $webhookNotification->dispute->dateOpened);
    }

    public function testBuildsASampleNotificationForADisputeWonWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::DISPUTE_WON,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::DISPUTE_WON, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->dispute->id);
        $this->assertEquals(Braintree\Dispute::WON, $webhookNotification->dispute->status);
        $this->assertEquals(Braintree\Dispute::CHARGEBACK, $webhookNotification->dispute->kind);
        $this->assertEquals(new DateTime('2014-03-21'), $webhookNotification->dispute->dateOpened);
        $this->assertEquals(new DateTime('2014-03-22'), $webhookNotification->dispute->dateWon);
    }

    public function testBuildsASampleNotificationForADisbursementExceptionWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::DISBURSEMENT_EXCEPTION,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );


        $this->assertEquals(Braintree\WebhookNotification::DISBURSEMENT_EXCEPTION, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->disbursement->id);
        $this->assertEquals(false, $webhookNotification->disbursement->retry);
        $this->assertEquals(false, $webhookNotification->disbursement->success);
        $this->assertEquals("bank_rejected", $webhookNotification->disbursement->exceptionMessage);
        $this->assertEquals(100.00, $webhookNotification->disbursement->amount);
        $this->assertEquals("update_funding_information", $webhookNotification->disbursement->followUpAction);
        $this->assertEquals("merchant_account_token", $webhookNotification->disbursement->merchantAccount->id);
        $this->assertEquals(new DateTime("2014-02-10"), $webhookNotification->disbursement->disbursementDate);
        $this->assertEquals(["asdfg", "qwert"], $webhookNotification->disbursement->transactionIds);
    }

    public function testBuildsASampleNotificationForADisbursementWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::DISBURSEMENT,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );


        $this->assertEquals(Braintree\WebhookNotification::DISBURSEMENT, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->disbursement->id);
        $this->assertEquals(false, $webhookNotification->disbursement->retry);
        $this->assertEquals(true, $webhookNotification->disbursement->success);
        $this->assertEquals(NULL, $webhookNotification->disbursement->exceptionMessage);
        $this->assertEquals(100.00, $webhookNotification->disbursement->amount);
        $this->assertEquals(NULL, $webhookNotification->disbursement->followUpAction);
        $this->assertEquals("merchant_account_token", $webhookNotification->disbursement->merchantAccount->id);
        $this->assertEquals(new DateTime("2014-02-10"), $webhookNotification->disbursement->disbursementDate);
        $this->assertEquals(["asdfg", "qwert"], $webhookNotification->disbursement->transactionIds);
    }
    public function testBuildsASampleNotificationForAPartnerMerchantConnectedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::PARTNER_MERCHANT_CONNECTED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::PARTNER_MERCHANT_CONNECTED, $webhookNotification->kind);
        $this->assertEquals("public_id", $webhookNotification->partnerMerchant->merchantPublicId);
        $this->assertEquals("public_key", $webhookNotification->partnerMerchant->publicKey);
        $this->assertEquals("private_key", $webhookNotification->partnerMerchant->privateKey);
        $this->assertEquals("abc123", $webhookNotification->partnerMerchant->partnerMerchantId);
        $this->assertEquals("cse_key", $webhookNotification->partnerMerchant->clientSideEncryptionKey);
    }

    public function testBuildsASampleNotificationForAPartnerMerchantDisconnectedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::PARTNER_MERCHANT_DISCONNECTED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::PARTNER_MERCHANT_DISCONNECTED, $webhookNotification->kind);
        $this->assertEquals("abc123", $webhookNotification->partnerMerchant->partnerMerchantId);
    }

    public function testBuildsASampleNotificationForAPartnerMerchantDeclinedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::PARTNER_MERCHANT_DECLINED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::PARTNER_MERCHANT_DECLINED, $webhookNotification->kind);
        $this->assertEquals("abc123", $webhookNotification->partnerMerchant->partnerMerchantId);
    }

    public function testBuildsASampleNotificationForOAuthAccessRevokedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::OAUTH_ACCESS_REVOKED,
            'my_id'
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::OAUTH_ACCESS_REVOKED, $webhookNotification->kind);
        $this->assertEquals('my_id', $webhookNotification->oauthAccessRevocation->merchantId);
        $this->assertEquals("oauth_application_client_id", $webhookNotification->oauthAccessRevocation->oauthApplicationClientId);
    }

    public function testBuildsASampleNotificationForConnectedMerchantStatusTransitionedWebhook()
    {
        $gateway = new Braintree\Gateway([
            'privateKey' => 'integration_private_key',
            'publicKey' => 'integration_public_key',
            'merchantId' => 'integration_merchant_id',
            'environment' => 'development'
        ]);

        $sampleNotification = $gateway->webhookTesting()->sampleNotification(
            Braintree\WebhookNotification::CONNECTED_MERCHANT_STATUS_TRANSITIONED,
            "my_id"
        );

        $webhookNotification = $gateway->webhookNotification()->parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::CONNECTED_MERCHANT_STATUS_TRANSITIONED, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->connectedMerchantStatusTransitioned->merchantPublicId);
        $this->assertEquals("my_id", $webhookNotification->connectedMerchantStatusTransitioned->merchantId);
        $this->assertEquals("new_status", $webhookNotification->connectedMerchantStatusTransitioned->status);
        $this->assertEquals("oauth_application_client_id", $webhookNotification->connectedMerchantStatusTransitioned->oauthApplicationClientId);
    }

    public function testBuildsASampleNotificationForConnectedMerchantPayPalStatusChangedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::CONNECTED_MERCHANT_PAYPAL_STATUS_CHANGED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::CONNECTED_MERCHANT_PAYPAL_STATUS_CHANGED, $webhookNotification->kind);
        $this->assertEquals("my_id", $webhookNotification->connectedMerchantPayPalStatusChanged->merchantPublicId);
        $this->assertEquals("my_id", $webhookNotification->connectedMerchantPayPalStatusChanged->merchantId);
        $this->assertEquals("link", $webhookNotification->connectedMerchantPayPalStatusChanged->action);
        $this->assertEquals("oauth_application_client_id", $webhookNotification->connectedMerchantPayPalStatusChanged->oauthApplicationClientId);
    }

    public function testBuildsASampleNotificationForACheckWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::CHECK,
            ""
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification["bt_signature"],
            $sampleNotification["bt_payload"]
        );

        $this->assertEquals(Braintree\WebhookNotification::CHECK, $webhookNotification->kind);
    }

    public function testAccountUpdaterDailyReportWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::ACCOUNT_UPDATER_DAILY_REPORT,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::ACCOUNT_UPDATER_DAILY_REPORT, $webhookNotification->kind);
        $this->assertEquals("link-to-csv-report", $webhookNotification->accountUpdaterDailyReport->reportUrl);
        $this->assertEquals(new DateTime("2016-01-14"), $webhookNotification->accountUpdaterDailyReport->reportDate);
    }

    public function testIdealPaymentCompleteWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::IDEAL_PAYMENT_COMPLETE,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::IDEAL_PAYMENT_COMPLETE, $webhookNotification->kind);
        $idealPayment = $webhookNotification->idealPayment;

        $this->assertEquals("my_id", $idealPayment->id);
        $this->assertEquals("COMPLETE", $idealPayment->status);
        $this->assertEquals("ORDERABC", $idealPayment->orderId);
        $this->assertEquals("10.00", $idealPayment->amount);
        $this->assertEquals("https://example.com", $idealPayment->approvalUrl);
        $this->assertEquals("1234567890", $idealPayment->idealTransactionId);
    }

    public function testIdealPaymentFailedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::IDEAL_PAYMENT_FAILED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::IDEAL_PAYMENT_FAILED, $webhookNotification->kind);
        $idealPayment = $webhookNotification->idealPayment;

        $this->assertEquals("my_id", $idealPayment->id);
        $this->assertEquals("FAILED", $idealPayment->status);
        $this->assertEquals("ORDERABC", $idealPayment->orderId);
        $this->assertEquals("10.00", $idealPayment->amount);
        $this->assertEquals("https://example.com", $idealPayment->approvalUrl);
        $this->assertEquals("1234567890", $idealPayment->idealTransactionId);
    }

    public function testGrantorUpdatedGrantedPaymentMethodWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::GRANTOR_UPDATED_GRANTED_PAYMENT_METHOD,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::GRANTOR_UPDATED_GRANTED_PAYMENT_METHOD, $webhookNotification->kind);
        $update = $webhookNotification->grantedPaymentInstrumentUpdate;

        $this->assertEquals("vczo7jqrpwrsi2px", $update->grantOwnerMerchantId);
        $this->assertEquals("cf0i8wgarszuy6hc", $update->grantRecipientMerchantId);
        $this->assertEquals("ee257d98-de40-47e8-96b3-a6954ea7a9a4", $update->paymentMethodNonce->nonce);
        $this->assertEquals("abc123z", $update->token);
        $this->assertEquals(array("expiration-month", "expiration-year"), $update->updatedFields);
    }

    public function testRecipientUpdatedGrantedPaymentMethodWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::RECIPIENT_UPDATED_GRANTED_PAYMENT_METHOD,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::RECIPIENT_UPDATED_GRANTED_PAYMENT_METHOD, $webhookNotification->kind); $update = $webhookNotification->grantedPaymentInstrumentUpdate;

        $this->assertEquals("vczo7jqrpwrsi2px", $update->grantOwnerMerchantId);
        $this->assertEquals("cf0i8wgarszuy6hc", $update->grantRecipientMerchantId);
        $this->assertEquals("ee257d98-de40-47e8-96b3-a6954ea7a9a4", $update->paymentMethodNonce->nonce);
        $this->assertEquals("abc123z", $update->token);
        $this->assertEquals(array("expiration-month", "expiration-year"), $update->updatedFields);
    }

    public function testGrantedPaymentMethodRevokedCreditCardWebhook()
    {
        $xmlPayload = "
            <notification>
                <source-merchant-id>12345</source-merchant-id>
                <timestamp type='datetime'>2018-10-10T22:46:41Z</timestamp>
                <kind>granted_payment_method_revoked</kind>
                <subject>
                    <credit-card>
                        <bin>555555</bin>
                        <card-type>MasterCard</card-type>
                        <cardholder-name>Amber Ankunding</cardholder-name>
                        <commercial>Unknown</commercial>
                        <country-of-issuance>Unknown</country-of-issuance>
                        <created-at type='datetime'>2018-10-10T22:46:41Z</created-at>
                        <customer-id>credit_card_customer_id</customer-id>
                        <customer-location>US</customer-location>
                        <debit>Unknown</debit>
                        <default type='boolean'>true</default>
                        <durbin-regulated>Unknown</durbin-regulated>
                        <expiration-month>06</expiration-month>
                        <expiration-year>2020</expiration-year>
                        <expired type='boolean'>false</expired>
                        <global-id>cGF5bWVudG1ldGhvZF8zcHQ2d2hz</global-id>
                        <healthcare>Unknown</healthcare>
                        <image-url>https://assets.braintreegateway.com/payment_method_logo/mastercard.png?environment=test</image-url>
                        <issuing-bank>Unknown</issuing-bank>
                        <last-4>4444</last-4>
                        <payroll>Unknown</payroll>
                        <prepaid>Unknown</prepaid>
                        <product-id>Unknown</product-id>
                        <subscriptions type='array'/>
                        <token>credit_card_token</token>
                        <unique-number-identifier>08199d188e37460163207f714faf074a</unique-number-identifier>
                        <updated-at type='datetime'>2018-10-10T22:46:41Z</updated-at>
                        <venmo-sdk type='boolean'>false</venmo-sdk>
                        <verifications type='array'/>
                    </credit-card>
                </subject>
            </notification>
        ";

        $sampleNotification = Helper::sampleNotificationFromXml($xmlPayload);

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::GRANTED_PAYMENT_METHOD_REVOKED, $webhookNotification->kind);
        $metadata = $webhookNotification->revokedPaymentMethodMetadata;

        $this->assertEquals("credit_card_customer_id", $metadata->customerId);
        $this->assertEquals("credit_card_token", $metadata->token);
        $this->assertTrue($metadata->revokedPaymentMethod instanceof Braintree\CreditCard);
    }

    public function testGrantedPaymentMethodRevokedPayPalAccountWebhook()
	{
		$xmlPayload = "
			<notification>
				<source-merchant-id>12345</source-merchant-id>
				<timestamp type='datetime'>2018-10-10T22:46:41Z</timestamp>
				<kind>granted_payment_method_revoked</kind>
				<subject>
					<paypal-account>
						<billing-agreement-id>billing_agreement_id</billing-agreement-id>
						<created-at type='dateTime'>2018-10-11T21:10:33Z</created-at>
						<customer-id>paypal_customer_id</customer-id>
						<default type='boolean'>true</default>
						<email>johndoe@example.com</email>
						<global-id>cGF5bWVudG1ldGhvZF9wYXlwYWxfdG9rZW4</global-id>
						<image-url>https://assets.braintreegateway.com/payment_method_logo/mastercard.png?environment=test</image-url>
						<subscriptions type='array'></subscriptions>
						<token>paypal_token</token>
						<updated-at type='dateTime'>2018-10-11T21:10:33Z</updated-at>
						<payer-id>a6a8e1a4</payer-id>
					</paypal-account>
				</subject>
			</notification>";

		$sampleNotification = Helper::sampleNotificationFromXml($xmlPayload);

		$webhookNotification = Braintree\WebhookNotification::parse(
			$sampleNotification['bt_signature'],
			$sampleNotification['bt_payload']
		);

		$this->assertEquals(Braintree\WebhookNotification::GRANTED_PAYMENT_METHOD_REVOKED, $webhookNotification->kind);
		$metadata = $webhookNotification->revokedPaymentMethodMetadata;

		$this->assertEquals("paypal_customer_id", $metadata->customerId);
		$this->assertEquals("paypal_token", $metadata->token);
		$this->assertTrue($metadata->revokedPaymentMethod instanceof Braintree\PayPalAccount);
	}

	public function testGrantedPaymentMethodRevokedVenmoAccountWebhook()
	{
		$xmlPayload = "
			 <notification>
				<source-merchant-id>12345</source-merchant-id>
				<timestamp type='datetime'>2018-10-10T22:46:41Z</timestamp>
				<kind>granted_payment_method_revoked</kind>
				<subject>
					<venmo-account>
						<created-at type='dateTime'>2018-10-11T21:28:37Z</created-at>
						<updated-at type='dateTime'>2018-10-11T21:28:37Z</updated-at>
						<default type='boolean'>true</default>
						<image-url>https://assets.braintreegateway.com/payment_method_logo/mastercard.png?environment=test</image-url>
						<token>venmo_token</token>
						<source-description>Venmo Account: venmojoe</source-description>
						<username>venmojoe</username>
						<venmo-user-id>456</venmo-user-id>
						<subscriptions type='array'/>
						<customer-id>venmo_customer_id</customer-id>
						<global-id>cGF5bWVudG1ldGhvZF92ZW5tb2FjY291bnQ</global-id>
					</venmo-account>
				</subject>
			 </notification>";

		$sampleNotification = Helper::sampleNotificationFromXml($xmlPayload);

		$webhookNotification = Braintree\WebhookNotification::parse(
			$sampleNotification['bt_signature'],
			$sampleNotification['bt_payload']
		);

		$this->assertEquals(Braintree\WebhookNotification::GRANTED_PAYMENT_METHOD_REVOKED, $webhookNotification->kind);
		$metadata = $webhookNotification->revokedPaymentMethodMetadata;

		$this->assertEquals("venmo_customer_id", $metadata->customerId);
		$this->assertEquals("venmo_token", $metadata->token);
		$this->assertTrue($metadata->revokedPaymentMethod instanceof Braintree\VenmoAccount);
	}


    public function testLocalPaymentCompletedWebhook()
    {
        $sampleNotification = Braintree\WebhookTesting::sampleNotification(
            Braintree\WebhookNotification::LOCAL_PAYMENT_COMPLETED,
            "my_id"
        );

        $webhookNotification = Braintree\WebhookNotification::parse(
            $sampleNotification['bt_signature'],
            $sampleNotification['bt_payload']
        );

        $this->assertEquals(Braintree\WebhookNotification::LOCAL_PAYMENT_COMPLETED, $webhookNotification->kind);
        $localPaymentCompleted = $webhookNotification->localPaymentCompleted;

        $this->assertEquals("a-payment-id", $localPaymentCompleted->paymentId);
        $this->assertEquals("a-payer-id", $localPaymentCompleted->payerId);
    }
}
