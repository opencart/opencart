<?php

require_once 'MyfatoorahApiV2.php';

/**
 *  PaymentMyfatoorahApiV2 handle the payment process of MyFatoorah API endpoints
 * 
 * @author MyFatoorah <tech@myfatoorah.com>
 * @copyright 2021 MyFatoorah, All rights reserved
 * @license GNU General Public License v3.0
 */
class PaymentMyfatoorahApiV2 extends MyfatoorahApiV2 {

    /**
     * To specify either the payment will be onsite or offsite
     * (default value: false)
     * 
     * @var boolean 
     */
    protected $isDirectPayment = false;

//---------------------------------------------------------------------------------------------------------------------------------------------------    

    /**
     * List available Payment Gateways.
     * 
     * @param real $invoiceValue
     * @param string $displayCurrencyIso
     * @return array
     */
    public function getVendorGateways($invoiceValue = 0, $displayCurrencyIso = '') {

        $postFields = [
            'InvoiceAmount' => $invoiceValue,
            'CurrencyIso'   => $displayCurrencyIso,
        ];

        $json = $this->callAPI("$this->apiURL/v2/InitiatePayment", $postFields, null, 'Initiate Payment');

        return $json->Data->PaymentMethods;
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * List available Payment Gateways by type (direct, normal)
     * 
     * @param bool $isDirect
     * @return array
     */
    public function getVendorGatewaysByType($isDirect = false) {

        try {
            $gateways = $this->getVendorGateways();
        } catch (Exception $ex) {
            return [];
        }

        foreach ($gateways as $g) {
            if ($g->IsDirectPayment) {
                $directMethods[] = $g;
            } else if ($g->PaymentMethodCode != 'ap') {
                $normalMethods[] = $g;
            } else if ($this->isAppleSystem()) {
                //add apple payment for IOS systems
                $normalMethods[] = $g;
            }
        }

        return ($isDirect && isset($directMethods)) ? $directMethods : ((!$isDirect && isset($normalMethods)) ? $normalMethods : []);
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
    private function isAppleSystem() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        if (stripos($userAgent, 'iPod') || stripos($userAgent, 'iPhone') || stripos($userAgent, 'iPad')) {
            return true;
        }

        $browsers = ['Opera', 'Edg', 'Chrome', 'Safari', 'Firefox', 'MSIE', 'Trident'];

        $userBrowser = null;
        foreach ($browsers as $b) {
            if (strpos($userAgent, $b) !== false) {
                $userBrowser = $b;
                break;
            }
        }
        if (!$userBrowser || $userBrowser == 'Safari') {
            return true;
        }

        return false;
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Get Payment Method Object
     * 
     * @param string $gateway
     * @param string $gatewayType ['PaymentMethodId', 'PaymentMethodCode']
     * @param real $invoiceValue
     * @param string $displayCurrencyIso
     * @return object
     * @throws Exception
     */
    public function getPaymentMethod($gateway, $gatewayType = 'PaymentMethodId', $invoiceValue = 0, $displayCurrencyIso = '') {

        $paymentMethods = $this->getVendorGateways($invoiceValue, $displayCurrencyIso);

        foreach ($paymentMethods as $method) {
            if ($method->$gatewayType == $gateway) {
                $pm = $method;
                break;
            }
        }

        if (!isset($pm)) {
            throw new Exception('Please contact Account Manager to enable the used payment method in your account');
        }

        if ($this->isDirectPayment && !$pm->IsDirectPayment) {
            throw new Exception($pm->PaymentMethodEn . ' Direct Payment Method is not activated. Kindly, contact your MyFatoorah account manager or sales representative to activate it.');
        }

        return $pm;
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Get the invoice/payment URL and the invoice id
     * 
     * @param array $curlData
     * @param string $gateway (default value: 'myfatoorah')
     * @param integer|string $orderId (default value: null) used in log file
     * @return array
     */
    public function getInvoiceURL($curlData, $gateway = 'myfatoorah', $orderId = null) {

        $this->log('----------------------------------------------------------------------------------------------------------------------------------');

        $this->isDirectPayment = false;

        if ($gateway == 'myfatoorah') {
            return $this->sendPayment($curlData, $orderId);
        } else {
            return $this->excutePayment($curlData, $gateway, $orderId);
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 
     * @param array $curlData
     * @param integer|string $gatewayId
     * @param integer|string $orderId (default value: null) used in log file
     * @return array
     */
    private function excutePayment($curlData, $gatewayId, $orderId = null) {

        $curlData['PaymentMethodId'] = $gatewayId;

        $json = $this->callAPI("$this->apiURL/v2/ExecutePayment", $curlData, $orderId, 'Excute Payment'); //__FUNCTION__

        return ['invoiceURL' => $json->Data->PaymentURL, 'invoiceId' => $json->Data->InvoiceId];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 
     * @param array $curlData
     * @param integer|string $orderId (default value: null) used in log file
     * @return array
     */
    private function sendPayment($curlData, $orderId = null) {

        $curlData['NotificationOption'] = 'Lnk';

        $json = $this->callAPI("$this->apiURL/v2/SendPayment", $curlData, $orderId, 'Send Payment');

        return ['invoiceURL' => $json->Data->InvoiceURL, 'invoiceId' => $json->Data->InvoiceId];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Get the direct payment URL and the invoice id
     * 
     * @param array $curlData
     * @param integer|string $gateway
     * @param array $cardInfo
     * @param integer|string $orderId (default value: null) used in log file
     * @return array
     */
    public function directPayment($curlData, $gateway, $cardInfo, $orderId = null) {

        $this->log('----------------------------------------------------------------------------------------------------------------------------------');

        $this->isDirectPayment = true;

        $data = $this->excutePayment($curlData, $gateway, $orderId);

        $json = $this->callAPI($data['invoiceURL'], $cardInfo, $orderId, 'Direct Payment'); //__FUNCTION__
        return ['invoiceURL' => $json->Data->PaymentURL, 'invoiceId' => $data['invoiceId']];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Get the Payment Transaction Status
     * 
     * @param string $keyId
     * @param string $KeyType
     * @param integer|string $orderId (default value: null)
     * @param string $price
     * @param string $currncy
     * @return object
     * @throws Exception
     */
    public function getPaymentStatus($keyId, $KeyType, $orderId = null, $price = null, $currncy = null) {

        //payment inquiry
        $curlData = ['Key' => $keyId, 'KeyType' => $KeyType];
        $json     = $this->callAPI("$this->apiURL/v2/GetPaymentStatus", $curlData, $orderId, 'Get Payment Status');

        $msgLog = 'Order #' . $json->Data->CustomerReference . ' ----- Get Payment Status';

        //check for the order information
        if (!$this->checkOrderInformation($json, $orderId, $price, $currncy)) {
            $err = 'Trying to call data of another order';
            $this->log("$msgLog - Exception is $err");
            throw new Exception($err);
        }


        //check invoice status (Paid and Not Paid Cases)
        if ($json->Data->InvoiceStatus == 'Paid' || $json->Data->InvoiceStatus == 'DuplicatePayment') {

            $json->Data = $this->getSuccessData($json);
            $this->log("$msgLog - Status is Paid");
        } else if ($json->Data->InvoiceStatus != 'Paid') {

            $json->Data = $this->getErrorData($json, $keyId, $KeyType);
            $this->log("$msgLog - Status is " . $json->Data->InvoiceStatus . '. Error is ' . $json->Data->InvoiceError);
        }

        return $json->Data;
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 
     * @param object $json
     * @param string $orderId
     * @param string $price
     * @param string $currncy
     * @return boolean
     */
    private function checkOrderInformation($json, $orderId = null, $price = null, $currncy = null) {
        //check for the order ID
        if ($orderId && $json->Data->CustomerReference != $orderId) {
            return false;
        }

        //check for the order price and currency
        $invoiceDisplayValue = explode(' ', $json->Data->InvoiceDisplayValue);
        if ($price && $invoiceDisplayValue[0] != $price) {
            return false;
        }
        if ($currncy && $invoiceDisplayValue[1] != $currncy) {
            return false;
        }

        return true;
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 
     * @param object $json
     * @return object
     */
    private function getSuccessData($json) {
        foreach ($json->Data->InvoiceTransactions as $transaction) {
            if ($transaction->TransactionStatus == 'Succss') {
                $json->Data->InvoiceStatus = 'Paid';
                $json->Data->InvoiceError  = '';

                $json->Data->focusTransaction = $transaction;
                return $json->Data;
            }
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 
     * @param object $json
     * @param string $keyId
     * @param string $KeyType
     * @return object
     */
    private function getErrorData($json, $keyId, $KeyType) {

        //------------------
        //case 1: payment is Failed
        $focusTransaction = $this->{"getLastTransactionOf$KeyType"}($json, $keyId);
        if ($focusTransaction && $focusTransaction->TransactionStatus == 'Failed') {
            $json->Data->InvoiceStatus = 'Failed';
            $json->Data->InvoiceError  = $focusTransaction->Error . '.';

            $json->Data->focusTransaction = $focusTransaction;

            return $json->Data;
        }

        //------------------
        //case 2: payment is Expired
        //all myfatoorah gateway is set to Asia/Kuwait
        $ExpiryDateTime = $json->Data->ExpiryDate . ' ' . $json->Data->ExpiryTime;
        $ExpiryDate     = new \DateTime($ExpiryDateTime, new \DateTimeZone('Asia/Kuwait'));
        $currentDate    = new \DateTime('now', new \DateTimeZone('Asia/Kuwait'));

        if ($ExpiryDate < $currentDate) {
            $json->Data->InvoiceStatus = 'Expired';
            $json->Data->InvoiceError  = 'Invoice is expired since ' . $json->Data->ExpiryDate . '.';

            return $json->Data;
        }

        //------------------
        //case 3: payment is Pending
        //payment is pending .. user has not paid yet and the invoice is not expired
        $json->Data->InvoiceStatus = 'Pending';
        $json->Data->InvoiceError  = 'Pending Payment.';

        return $json->Data;
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
    function getLastTransactionOfPaymentId($json, $keyId) {
        foreach ($json->Data->InvoiceTransactions as $transaction) {
            if ($transaction->PaymentId == $keyId && $transaction->Error) {
                return $transaction;
            }
        }
    }

    function getLastTransactionOfInvoiceId($json) {
        usort($json->Data->InvoiceTransactions, function ($a, $b) {
            return strtotime($a->TransactionDate) - strtotime($b->TransactionDate);
        });

        return end($json->Data->InvoiceTransactions);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Refund a given Payment
     * 
     * @param integer|string $paymentId
     * @param real|string $amount
     * @param string $currencyCode
     * @param string $reason
     * @param integer|string $orderId (default value: null)
     * @return object
     */
    public function refund($paymentId, $amount, $currencyCode, $reason, $orderId = null) {

        $rate = $this->getCurrencyRate($currencyCode);
        $url  = "$this->apiURL/v2/MakeRefund";

        $postFields = array(
            'KeyType'                 => 'PaymentId',
            'Key'                     => $paymentId,
            'RefundChargeOnCustomer'  => false,
            'ServiceChargeOnCustomer' => false,
            'Amount'                  => $amount / $rate,
            'Comment'                 => $reason,
        );

        return $this->callAPI($url, $postFields, $orderId, 'Make Refund');
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 
     * @param array $curlData
     * @param integer|string $sessionId
     * @param integer|string $orderId (default value: null) used in log file
     * @return array
     */
    public function embeddedPayment($curlData, $sessionId, $orderId = null) {

        $curlData['SessionId'] = $sessionId;

        $json = $this->callAPI("$this->apiURL/v2/ExecutePayment", $curlData, $orderId, 'Excute Payment'); //__FUNCTION__

        return ['invoiceURL' => $json->Data->PaymentURL, 'invoiceId' => $json->Data->InvoiceId];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 
     * @param integer|string $orderId (default value: null) used in log file
     * @return array
     */
    public function getEmbeddedSession($orderId = null) {

        $json = $this->callAPI("$this->apiURL/v2/InitiateSession", '', $orderId, 'Initiate Session'); //__FUNCTION__
        return $json->Data;
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
}
