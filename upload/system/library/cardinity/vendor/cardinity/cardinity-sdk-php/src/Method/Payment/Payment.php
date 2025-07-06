<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\ResultObject;
use Cardinity\Method\Payment\ThreeDS2Data;
use Cardinity\Method\Payment\ThreeDS2AuthorizationInformation;

class Payment extends ResultObject
{
    /** @type string ID of the payment.
        Value assigned by Cardinity. */
    private $id;

    /** @type float Amount charged shown in #0.00 format. */
    private $amount;

    /** @type string Three-letter ISO currency code representing the currency in
        which the charge was made. */
    private $currency;

    /** @type string Payment creation time as defined in RFC 3339 Section 5.6.
        UTC timezone.
        Value assigned by Cardinity. */
    private $created;

    /** @type string Payment type.
        Can be one of the following: authorization, purchase.
        Value assigned by Cardinity. */
    private $type;

    /** @type boolean Indicates whether a payment was made in live or testing
        mode.
        Value assigned by Cardinity. */
    private $live;

    /** @type boolean Optional. Default: true.
        Used to indicate a transaction type while creating a payment: true -
        purchase, false - authorization. */
    private $settle;

    /** @type string Payment status.
        Can be one of the following: pending, approved, declined.
        Value assigned by Cardinity. */
    private $status;

    /** @type string Payment status reason.
    Value assigned by Cardinity. */
    private $threedsStatusReason;

    /** @type string Error message.
        Returned only if status is declined.
        Provides human readable information why the payment failed.
        Value assigned by Cardinity. */
    private $error;

    /** @type string Optional. Merchant advice code for a transaction.
        Returned only if status is declined.
        Provides information about transaction or the reason why transaction was declined.
        Value assigned by Cardinity. */
    private $merchantAdviceCode;

    /** @type string Optional. Order ID provided by a merchant.
        Must be between 2 and 50 characters [A-Za-z0-9'.-]. */
    private $orderId;

    /** @type string Payment description provided by a merchant.
        Maximum length 255 characters. */
    private $description;

    /** @type string Country of a customer provided by a merchant.
        ISO 3166-1 alpha-2 country code. */
    private $country;

    /** @type string Can be one the following: card, recurring. */
    private $paymentMethod;

    /** @type PaymentInstrumentInterface Payment instrument representing earlier described
        payment_method.
        Can be one of the following: card or recurring.
     */
    private $paymentInstrument;

    /** @deprecated
     * @type string Used to provide additional information (PATCH verb) once
        customer completes authorization process. */
    private $authorizeData;

    /** @type AuthorizationInformation Specific authorization object returned in case additional
        payment authorization is needed (i.e. payment status is pending).
        Value assigned by Cardinity. */
    private $authorizationInformation;

    /** @type ThreeDS2AuthorizationInformation */
    private $threeDS2AuthorizationInformation;

    /** @type string a descriptor to include in statement provided by a merchant. limit will vary based on merchant name
        Maximum length 25 characters. */
    private $statementDescriptorSuffix;

    /**
     * Gets the value of id.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     * @param mixed $id the id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the value of amount.
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets the value of amount.
     * @param mixed $amount the amount
     * @return void
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Gets the value of currency.
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets the value of currency.
     * @param mixed $currency the currency
     * @return void
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Gets the value of created.
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets the value of created.
     * @param mixed $created the created
     * @return void
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Gets the value of type.
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     * @param mixed $type the type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Gets the value of live.
     * @return mixed
     */
    public function getLive()
    {
        return $this->live;
    }

    /**
     * Sets the value of live.
     * @param mixed $live the live
     * @return void
     */
    public function setLive($live)
    {
        $this->live = $live;
    }

    /**
     * Gets the value of settle.
     * @return mixed
     */
    public function getSettle()
    {
        return $this->settle;
    }

    /**
     * Sets the value of settle.
     * @param mixed $settle the settle
     * @return void
     */
    public function setSettle($settle)
    {
        $this->settle = $settle;
    }

    /**
     * Gets the value of status.
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the value of status.
     * @param mixed $status the status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Gets the value of status.
     * @return mixed
     */
    public function getThreedsStatusReason()
    {
        return $this->threedsStatusReason;
    }

    /**
     * Sets the value of threeds status reason.
     * @param mixed $threedsStatusReason the status reason
     * @return void
     */
    public function setThreedsStatusReason($threedsStatusReason)
    {
        $this->threedsStatusReason = $threedsStatusReason;
    }


    /**
     * Gets the value of error.
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets the value of error.
     * @param mixed $error the error
     * @return void
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Gets the value of merchantAdviceCode.
     * @return mixed
     */
    public function getMerchantAdviceCode()
    {
        return $this->merchantAdviceCode;
    }

    /**
     * Sets the value of merchantAdviceCode.
     * @param string $merchantAdviceCode the code
     * @return void
     */
    public function setMerchantAdviceCode(string $merchantAdviceCode): void
    {
        $this->merchantAdviceCode = $merchantAdviceCode;
    }

    /**
     * Gets the value of orderId.
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Sets the value of orderId.
     * @param mixed $orderId the order id
     * @return void
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Gets the value of description.
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     * @param mixed $description the description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Gets the value of country.
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the value of country.
     * @param mixed $country the country
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Gets the value of paymentMethod.
     * @return mixed
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Sets the value of paymentMethod.
     * @param mixed $paymentMethod the payment method
     * @return void
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Gets the value of paymentInstrument.
     * @return PaymentInstrumentInterface
     */
    public function getPaymentInstrument()
    {
        return $this->paymentInstrument;
    }

    /**
     * Sets the value of paymentInstrument.
     * @param PaymentInstrumentInterface $paymentInstrument the payment instrument
     * @return void
     */
    public function setPaymentInstrument(PaymentInstrumentInterface $paymentInstrument)
    {
        $this->paymentInstrument = $paymentInstrument;
    }

    /**
     * @deprecated method is deprecated and shouldn't be used.
     * Gets the value of authorizeData.
     * @return mixed
     */
    public function getAuthorizeData()
    {
        return $this->authorizeData;
    }

    /**
     * @deprecated method is deprecated and shouldn't be used.
     * Sets the value of authorizeData.
     * @param mixed $authorizeData the authorize data
     * @return void
     */
    public function setAuthorizeData($authorizeData)
    {
        $this->authorizeData = $authorizeData;
    }

    /**
     * Gets the value of authorizationInformation.
     * @return AuthorizationInformation
     */
    public function getAuthorizationInformation()
    {
        return $this->authorizationInformation;
    }

    /**
     * Sets the value of authorizationInformation.
     * @param AuthorizationInformation $authorizationInformation the authorization information
     * @return void
     */
    public function setAuthorizationInformation(AuthorizationInformation $authorizationInformation)
    {
        $this->authorizationInformation = $authorizationInformation;
    }

    /**
     * @return ThreeDS2AuthorizationInformation
     */
    public function getThreeds2Data()
    {
        return $this->threeDS2AuthorizationInformation;
    }

    /**
     * @param ThreeDS2AuthorizationInformation
     * @return VOID
     */
    public function setThreeds2Data(
        ThreeDS2AuthorizationInformation $threeDS2AuthorizationInformation
    ){
        $this->threeDS2AuthorizationInformation = $threeDS2AuthorizationInformation;
    }

     /**
     * Gets the value of statementDescriptorSuffix.
     * @return mixed
     */
    public function getStatementDescriptorSuffix()
    {
        return $this->statementDescriptorSuffix;
    }

    /**
     * Sets the value of statementDescriptorSuffix.
     * @param mixed $statementDescriptorSuffix the description included in statement
     * @return void
     */
    public function setStatementDescriptorSuffix($statementDescriptorSuffix)
    {
        $this->statementDescriptorSuffix = $statementDescriptorSuffix;
    }


    /**
     * @return BOOL is it 3D secure v1?
     */
    public function isThreedsV1() : bool
    {
        return $this->authorizationInformation != null;
    }

    /**
     * @return BOOL is it 3D secure v2?
     */
    public function isThreedsV2() : bool
    {
        return $this->threeDS2AuthorizationInformation != null;
    }

    /**
     * Check if payment is pending
     * @return boolean
     */
    public function isPending()
    {
        return $this->getStatus() === 'pending';
    }

    /**
     * Check if payment is approved
     * @return boolean
     */
    public function isApproved()
    {
        return $this->getStatus() === 'approved';
    }

    /**
     * Check if payment is declined
     * @return boolean
     */
    public function isDeclined()
    {
        return $this->getStatus() === 'declined';
    }
}
