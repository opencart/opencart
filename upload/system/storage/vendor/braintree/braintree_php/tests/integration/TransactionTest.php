<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use Test;
use Test\Setup;
use Test\Braintree\CreditCardNumbers\CardTypeIndicators;
use Braintree;

class TransactionTest extends Setup
{
  public function testCloneTransaction()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'orderId' => '123',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/2011',
            ],
            'customer' => [
                'firstName' => 'Dan',
            ],
            'billing' => [
                'firstName' => 'Carl',
            ],
            'shipping' => [
                'firstName' => 'Andrew',
            ]
      ]);
      $this->assertTrue($result->success);
      $transaction = $result->transaction;

      $cloneResult = Braintree\Transaction::cloneTransaction(
          $transaction->id,
          [
              'amount' => '123.45',
              'channel' => 'MyShoppingCartProvider',
              'options' => ['submitForSettlement' => false]
          ]
      );
      Test\Helper::assertPrintable($cloneResult);
      $this->assertTrue($cloneResult->success);
      $cloneTransaction = $cloneResult->transaction;
      $this->assertEquals('Dan', $cloneTransaction->customerDetails->firstName);
      $this->assertEquals('Carl', $cloneTransaction->billingDetails->firstName);
      $this->assertEquals('Andrew', $cloneTransaction->shippingDetails->firstName);
      $this->assertEquals('510510******5100', $cloneTransaction->creditCardDetails->maskedNumber);
      $this->assertEquals('authorized', $cloneTransaction->status);
      $this->assertEquals('123.45', $cloneTransaction->amount);
      $this->assertEquals('MyShoppingCartProvider', $cloneTransaction->channel);
    }

  public function testCreateTransactionUsingNonce()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ],
            "share" => true
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals('47.00', $transaction->amount);
    }

  public function testCreateEloCardTransaction()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'creditCard' => [
                'number' => '5066991111111118',
                'expirationMonth' => '10',
                'expirationYear' => '2020',
                'cvv' => '737',
            ],
            'merchantAccountId' => 'adyen_ma',
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals('47.00', $transaction->amount);
    }

  public function testGatewayCreateTransactionUsingNonce()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ],
            "share" => true
        ]);

        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $result = $gateway->transaction()->sale([
            'amount' => '47.00',
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals('47.00', $transaction->amount);
    }

  public function testSaleWithIdealPaymentId()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'merchantAccountId' => 'ideal_merchant_account',
            'paymentMethodNonce' => Test\Helper::generateValidIdealPaymentId(),
            'orderId' => 'ABC123',
            'options' => [
                'submitForSettlement' => true,
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::SETTLED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals(Braintree\PaymentInstrumentType::IDEAL_PAYMENT, $transaction->paymentInstrumentType);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertRegExp('/^idealpayment_\w{6,}$/', $transaction->idealPayment->idealPaymentId);
        $this->assertRegExp('/^\d{16,}$/', $transaction->idealPayment->idealTransactionId);
        $this->assertRegExp('/^https:\/\//', $transaction->idealPayment->imageUrl);
        $this->assertNotNull($transaction->idealPayment->maskedIban);
        $this->assertNotNull($transaction->idealPayment->bic);
  }

  public function testCreateWithAccountTypeCredit()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '47.00',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$hiper,
              'expirationMonth' => '10',
              'expirationYear' => '2020',
              'cvv' => '737',
          ],
          'options' => [
              'creditCard' => [
                  'accountType' => 'credit'
            ]
          ],
          'merchantAccountId' => 'hiper_brl',
      ]);

      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $this->assertEquals('credit', $transaction->creditCard['accountType']);
  }

  public function testCreateWithAccountTypeDebit()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '47.00',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$hiper,
              'expirationMonth' => '10',
              'expirationYear' => '2020',
              'cvv' => '737',
          ],
          'options' => [
              'submitForSettlement' => true,
              'creditCard' => [
                  'accountType' => 'debit'
            ]
          ],
          'merchantAccountId' => 'hiper_brl',
      ]);

      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $this->assertEquals('debit', $transaction->creditCard['accountType']);
  }

  public function testCreateErrorsWithAccountTypeIsInvalid()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '47.00',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$hiper,
              'expirationMonth' => '10',
              'expirationYear' => '2020',
              'cvv' => '737',
          ],
          'options' => [
              'creditCard' => [
                  'accountType' => 'wrong'
            ]
          ],
          'merchantAccountId' => 'hiper_brl',
      ]);

      $this->assertFalse($result->success);
      $errors = $result->errors->forKey('transaction')->forKey('options')->forKey('creditCard')->onAttribute('accountType');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_OPTIONS_CREDIT_CARD_ACCOUNT_TYPE_IS_INVALID, $errors[0]->code);
  }

  public function testCreateErrorsWithAccountTypeNotSupported()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '47.00',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationMonth' => '10',
              'expirationYear' => '2020',
              'cvv' => '737',
          ],
          'options' => [
              'creditCard' => [
                  'accountType' => 'credit'
            ]
          ],
      ]);

      $this->assertFalse($result->success);
      $errors = $result->errors->forKey('transaction')->forKey('options')->forKey('creditCard')->onAttribute('accountType');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_OPTIONS_CREDIT_CARD_ACCOUNT_TYPE_NOT_SUPPORTED, $errors[0]->code);
  }

  public function testCreateErrorsWithAccountTypeDebitDoesNotSupportAuths()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '47.00',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$hiper,
              'expirationMonth' => '10',
              'expirationYear' => '2020',
              'cvv' => '737',
          ],
          'options' => [
              'creditCard' => [
                  'accountType' => 'debit'
            ]
          ],
          'merchantAccountId' => 'hiper_brl',
      ]);

      $this->assertFalse($result->success);
      $errors = $result->errors->forKey('transaction')->forKey('options')->forKey('creditCard')->onAttribute('accountType');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_OPTIONS_CREDIT_CARD_ACCOUNT_TYPE_DEBIT_DOES_NOT_SUPPORT_AUTHS, $errors[0]->code);
  }

  public function testSaleAndSkipAdvancedFraudChecking()
  {
        $gateway = Test\Helper::advancedFraudIntegrationMerchantGateway();
        $result = $gateway->transaction()->sale([
          'amount' => Braintree\Test\TransactionAmounts::$authorize,
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'options' => [
              'skipAdvancedFraudChecking' => true
          ]
      ]);
      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $this->assertFalse(property_exists($transaction, "riskData"));
  }

  public function testSaleAndSkipAvs()
  {
      $result = Braintree\Transaction::sale([
          'amount' => Braintree\Test\TransactionAmounts::$authorize,
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2019',
          ],
          'options' => [
              'skipAvs' => true
          ]
      ]);
      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $this->assertNull($transaction->avsErrorResponseCode);
      $this->assertEquals($transaction->avsStreetAddressResponseCode, 'B');
  }

  public function testSaleAndSkipCvv()
  {
      $result = Braintree\Transaction::sale([
          'amount' => Braintree\Test\TransactionAmounts::$authorize,
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2019',
          ],
          'options' => [
              'skipCvv' => true
          ]
      ]);
      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $this->assertEquals($transaction->cvvResponseCode, 'B');
  }

  public function testSaleWithLevel3SummaryFields()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'shippingAmount' => '1.00',
          'discountAmount' => '2.00',
          'shipsFromPostalCode' => '12345',
      ]);

      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $this->assertEquals('1.00', $transaction->shippingAmount);
      $this->assertEquals('2.00', $transaction->discountAmount);
      $this->assertEquals('12345', $transaction->shipsFromPostalCode);
  }

  public function testSaleWhenDiscountAmountFormatIsInvalid()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'discountAmount' => '123.456',
      ]);

      $this->assertFalse($result->success);
      $baseErrors = $result->errors->forKey('transaction')->onAttribute('discountAmount');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_DISCOUNT_AMOUNT_FORMAT_IS_INVALID, $baseErrors[0]->code);
  }

  public function testSaleWhenDiscountAmountCannotBeNegative()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'discountAmount' => '-2.00',
      ]);

      $this->assertFalse($result->success);
      $baseErrors = $result->errors->forKey('transaction')->onAttribute('discountAmount');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_DISCOUNT_AMOUNT_CANNOT_BE_NEGATIVE, $baseErrors[0]->code);
  }

  public function testSaleWhenDiscountAmountIsTooLarge()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'discountAmount' => '2147483647',
      ]);

      $this->assertFalse($result->success);
      $baseErrors = $result->errors->forKey('transaction')->onAttribute('discountAmount');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_DISCOUNT_AMOUNT_IS_TOO_LARGE, $baseErrors[0]->code);
  }

  public function testSaleWhenShippingAmountFormatIsInvalid()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'shippingAmount' => '1a00',
      ]);

      $this->assertFalse($result->success);
      $baseErrors = $result->errors->forKey('transaction')->onAttribute('shippingAmount');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SHIPPING_AMOUNT_FORMAT_IS_INVALID, $baseErrors[0]->code);
  }

  public function testSaleWhenShippingAmountCannotBeNegative()
  {
    $result = Braintree\Transaction::sale([
        'amount' => '35.05',
        'creditCard' => [
            'number' => Braintree\Test\CreditCardNumbers::$visa,
            'expirationDate' => '05/2009',
        ],
        'shippingAmount' => '-1.00',
    ]);

    $this->assertFalse($result->success);
    $baseErrors = $result->errors->forKey('transaction')->onAttribute('shippingAmount');
    $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SHIPPING_AMOUNT_CANNOT_BE_NEGATIVE, $baseErrors[0]->code);
  }

  public function testSaleWhenShippingAmountIsTooLarge()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'shippingAmount' => '2147483647',
      ]);

      $this->assertFalse($result->success);
      $baseErrors = $result->errors->forKey('transaction')->onAttribute('shippingAmount');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SHIPPING_AMOUNT_IS_TOO_LARGE, $baseErrors[0]->code);
  }

  public function testSaleWhenShipsFromPostalCodeIsTooLong()
  {
      $result = Braintree\Transaction::sale([
        'amount' => '35.05',
        'creditCard' => [
            'number' => Braintree\Test\CreditCardNumbers::$visa,
            'expirationDate' => '05/2009',
        ],
        'shipsFromPostalCode' => '12345678901',
      ]);

      $this->assertFalse($result->success);
      $baseErrors = $result->errors->forKey('transaction')->onAttribute('shipsFromPostalCode');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SHIPS_FROM_POSTAL_CODE_IS_TOO_LONG, $baseErrors[0]->code);
  }

  public function testSaleWhenShipsFromPostalCodeIsInvalid()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'shipsFromPostalCode' => [1, 2, 3],
      ]);

      $this->assertFalse($result->success);
      $baseErrors = $result->errors->forKey('transaction')->onAttribute('shipsFromPostalCode');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SHIPS_FROM_POSTAL_CODE_IS_INVALID, $baseErrors[0]->code);
  }

  public function testSaleWhenShipsFromPostalCodeInvalidCharacters()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'shipsFromPostalCode' => '1$345',
      ]);

      $this->assertFalse($result->success);
      $baseErrors = $result->errors->forKey('transaction')->onAttribute('shipsFromPostalCode');
      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SHIPS_FROM_POSTAL_CODE_INVALID_CHARACTERS, $baseErrors[0]->code);
  }

  public function testSale_withLineItemsZero()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '45.15',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ]
      ]);

      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $lineItems = $transaction->lineItems();
      $this->assertEquals(0, sizeof($lineItems));
  }

  public function testSale_withLineItemsSingleOnlyRequiredFields()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [[
              'quantity' => '1.0232',
              'name' => 'Name #1',
              'kind' => Braintree\TransactionLineItem::DEBIT,
              'unitAmount' => '45.1232',
              'totalAmount' => '45.15',
          ]]
      ]);

      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $lineItems = $transaction->lineItems();
      $this->assertEquals(1, sizeof($lineItems));

      $lineItem = $lineItems[0];
      $this->assertEquals('1.0232', $lineItem->quantity);
      $this->assertEquals('Name #1', $lineItem->name);
      $this->assertEquals(Braintree\TransactionLineItem::DEBIT, $lineItem->kind);
      $this->assertEquals('45.1232', $lineItem->unitAmount);
      $this->assertEquals('45.15', $lineItem->totalAmount);
  }

  public function testSale_withLineItemsSingleZeroAmounts()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [[
              'quantity' => '1.0232',
              'name' => 'Name #1',
              'kind' => Braintree\TransactionLineItem::DEBIT,
              'unitAmount' => '45.1232',
              'totalAmount' => '45.15',
              'discountAmount' => '0',
              'taxAmount' => '0',
              'unitTaxAmount' => '0',
          ]]
      ]);

      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $lineItems = $transaction->lineItems();
      $this->assertEquals(1, sizeof($lineItems));

      $lineItem = $lineItems[0];
      $this->assertEquals('1.0232', $lineItem->quantity);
      $this->assertEquals('Name #1', $lineItem->name);
      $this->assertEquals(Braintree\TransactionLineItem::DEBIT, $lineItem->kind);
      $this->assertEquals('45.1232', $lineItem->unitAmount);
      $this->assertEquals('45.15', $lineItem->totalAmount);
      $this->assertEquals('0', $lineItem->discountAmount);
      $this->assertEquals('0', $lineItem->taxAmount);
      $this->assertEquals('0', $lineItem->unitTaxAmount);
  }

  public function testSale_withLineItemsSingle()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '45.15',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [[
              'quantity' => '1.0232',
              'name' => 'Name #1',
              'description' => 'Description #1',
              'kind' => Braintree\TransactionLineItem::DEBIT,
              'unitAmount' => '45.1232',
              'unitTaxAmount' => '1.23',
              'unitOfMeasure' => 'gallon',
              'discountAmount' => '1.02',
              'taxAmount' => '4.50',
              'totalAmount' => '45.15',
              'productCode' => '23434',
              'commodityCode' => '9SAASSD8724',
              'url' => 'https://example.com/products/23434',
          ]]
      ]);

      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $lineItems = $transaction->lineItems();
      $this->assertEquals(1, sizeof($lineItems));

      $lineItem = $lineItems[0];
      $this->assertEquals('1.0232', $lineItem->quantity);
      $this->assertEquals('Name #1', $lineItem->name);
      $this->assertEquals('Description #1', $lineItem->description);
      $this->assertEquals(Braintree\TransactionLineItem::DEBIT, $lineItem->kind);
      $this->assertEquals('45.1232', $lineItem->unitAmount);
      $this->assertEquals('1.23', $lineItem->unitTaxAmount);
      $this->assertEquals('gallon', $lineItem->unitOfMeasure);
      $this->assertEquals('1.02', $lineItem->discountAmount);
      $this->assertEquals('4.50', $lineItem->taxAmount);
      $this->assertEquals('45.15', $lineItem->totalAmount);
      $this->assertEquals('23434', $lineItem->productCode);
      $this->assertEquals('9SAASSD8724', $lineItem->commodityCode);
      $this->assertEquals('https://example.com/products/23434', $lineItem->url);
  }

  public function testSale_withLineItemsMultiple()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'description' => 'Description #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '2.02',
                  'name' => 'Name #2',
                  'description' => 'Description #2',
                  'kind' => Braintree\TransactionLineItem::CREDIT,
                  'unitAmount' => '5',
                  'unitOfMeasure' => 'gallon',
                  'totalAmount' => '45.15',
              ]
          ]
      ]);

      $this->assertTrue($result->success);
      $transaction = $result->transaction;
      $lineItems = $transaction->lineItems();
      $this->assertEquals(2, sizeof($lineItems));

      $lineItem1 = null;
      foreach ($lineItems as $lineItem) {
          if ($lineItem->name == 'Name #1') {
              $lineItem1 = $lineItem;
              break;
          }
      }
      if ($lineItem1 == null) {
          $this->fail('TransactionLineItem with name \'Name #1\' not returned.');
      }
      $this->assertEquals('1.0232', $lineItem1->quantity);
      $this->assertEquals('Name #1', $lineItem1->name);
      $this->assertEquals('Description #1', $lineItem1->description);
      $this->assertEquals(Braintree\TransactionLineItem::DEBIT, $lineItem1->kind);
      $this->assertEquals('45.1232', $lineItem1->unitAmount);
      $this->assertEquals('gallon', $lineItem1->unitOfMeasure);
      $this->assertEquals('1.02', $lineItem1->discountAmount);
      $this->assertEquals('4.50', $lineItem1->taxAmount);
      $this->assertEquals('45.15', $lineItem1->totalAmount);
      $this->assertEquals('23434', $lineItem1->productCode);
      $this->assertEquals('9SAASSD8724', $lineItem1->commodityCode);

      $lineItem2 = null;
      foreach ($lineItems as $lineItem) {
          if ($lineItem->name == 'Name #2') {
              $lineItem2 = $lineItem;
              break;
          }
      }
      if ($lineItem2 == null) {
          $this->fail('TransactionLineItem with name \'Name #2\' not returned.');
      }
      $this->assertEquals('2.02', $lineItem2->quantity);
      $this->assertEquals('Name #2', $lineItem2->name);
      $this->assertEquals('Description #2', $lineItem2->description);
      $this->assertEquals(Braintree\TransactionLineItem::CREDIT, $lineItem2->kind);
      $this->assertEquals('5', $lineItem2->unitAmount);
      $this->assertEquals('gallon', $lineItem2->unitOfMeasure);
      $this->assertEquals('45.15', $lineItem2->totalAmount);
      $this->assertEquals(null, $lineItem2->discountAmount);
      $this->assertEquals(null, $lineItem2->taxAmount);
      $this->assertEquals(null, $lineItem2->productCode);
      $this->assertEquals(null, $lineItem2->commodityCode);
  }

  public function testSale_withLineItemsValidationErrorCommodityCodeIsTooLong()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
				  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '0123456789123',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_COMMODITY_CODE_IS_TOO_LONG,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('commodityCode')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorDescriptionIsTooLong()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'description' => "This is a line item description which is far too long. Like, way too long to be practical. We don't like how long this line item description is.",
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_DESCRIPTION_IS_TOO_LONG,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('description')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorDiscountAmountIsTooLarge()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '2147483648',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_DISCOUNT_AMOUNT_IS_TOO_LARGE,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('discountAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorDiscountAmountCannotBeNegative()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '-2',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_DISCOUNT_AMOUNT_CANNOT_BE_NEGATIVE,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('discountAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorTaxAmountFormatIsInvalid()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.511',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
          ],
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_TAX_AMOUNT_FORMAT_IS_INVALID,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index0')->onAttribute('taxAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorTaxAmountIsTooLarge()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '2147483648',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
          ],
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_TAX_AMOUNT_IS_TOO_LARGE,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index0')->onAttribute('taxAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorTaxAmountCannotBeNegative()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '-2',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
          ],
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_TAX_AMOUNT_CANNOT_BE_NEGATIVE,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index0')->onAttribute('taxAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorKindIsRequired()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_KIND_IS_REQUIRED,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('kind')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorNameIsRequired()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_NAME_IS_REQUIRED,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('name')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorNameIsTooLong()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => '123456789012345678901234567890123456',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_NAME_IS_TOO_LONG,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('name')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorProductCodeIsTooLong()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::CREDIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '123456789012345678901234567890123456',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_PRODUCT_CODE_IS_TOO_LONG,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('productCode')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorQuantityIsRequired()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::CREDIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_QUANTITY_IS_REQUIRED,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('quantity')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorQuantityIsTooLarge()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '2147483648',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::CREDIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_QUANTITY_IS_TOO_LARGE,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('quantity')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorTotalAmountIsRequired()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::CREDIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_TOTAL_AMOUNT_IS_REQUIRED,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('totalAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorTotalAmountIsTooLarge()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::CREDIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '2147483648',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_TOTAL_AMOUNT_IS_TOO_LARGE,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('totalAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorTotalAmountMustBeGreaterThanZero()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::CREDIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '-2',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_TOTAL_AMOUNT_MUST_BE_GREATER_THAN_ZERO,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('totalAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorUnitAmountIsRequired()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::CREDIT,
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_UNIT_AMOUNT_IS_REQUIRED,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('unitAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorUnitAmountIsTooLarge()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\TransactionLineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\TransactionLineItem::CREDIT,
                  'unitAmount' => '2147483648',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_UNIT_AMOUNT_IS_TOO_LARGE,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('unitAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorUnitAmountMustBeGreaterThanZero()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\Transaction\LineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\Transaction\LineItem::CREDIT,
                  'unitAmount' => '-2',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_UNIT_AMOUNT_MUST_BE_GREATER_THAN_ZERO,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('unitAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorUnitOfMeasureIsTooLong()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #1',
                  'kind' => Braintree\Transaction\LineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.0232',
                  'name' => 'Name #2',
                  'kind' => Braintree\Transaction\LineItem::CREDIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => '1234567890123',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_UNIT_OF_MEASURE_IS_TOO_LONG,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('unitOfMeasure')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorUnitTaxAmountFormatIsInvalid()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.2322',
                  'name' => 'Name #1',
                  'kind' => Braintree\Transaction\LineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.2322',
                  'name' => 'Name #2',
				  'kind' => Braintree\Transaction\LineItem::CREDIT,
                  'unitAmount' => '45.0122',
                  'unitTaxAmount' => '2.012',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_UNIT_TAX_AMOUNT_FORMAT_IS_INVALID,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('unitTaxAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorUnitTaxAmountIsTooLarge()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.2322',
                  'name' => 'Name #1',
                  'kind' => Braintree\Transaction\LineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitTaxAmount' => '1.23',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.2322',
                  'name' => 'Name #2',
                  'kind' => Braintree\Transaction\LineItem::CREDIT,
                  'unitAmount' => '45.0122',
                  'unitTaxAmount' => '2147483648',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_UNIT_TAX_AMOUNT_IS_TOO_LARGE,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('unitTaxAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorUnitTaxAmountCannotBeNegative()
  {
      $result = Braintree\Transaction::sale([
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [
              [
                  'quantity' => '1.2322',
                  'name' => 'Name #1',
                  'kind' => Braintree\Transaction\LineItem::DEBIT,
                  'unitAmount' => '45.1232',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ],
              [
                  'quantity' => '1.2322',
                  'name' => 'Name #2',
                  'kind' => Braintree\Transaction\LineItem::CREDIT,
                  'unitAmount' => '45.0122',
                  'unitTaxAmount' => '-1.23',
                  'unitOfMeasure' => 'gallon',
                  'discountAmount' => '1.02',
                  'taxAmount' => '4.50',
                  'totalAmount' => '45.15',
                  'productCode' => '23434',
                  'commodityCode' => '9SAASSD8724',
              ]
          ]
      ]);

      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_LINE_ITEM_UNIT_TAX_AMOUNT_CANNOT_BE_NEGATIVE,
          $result->errors->forKey('transaction')->forKey('lineItems')->forKey('index1')->onAttribute('unitTaxAmount')[0]->code
      );
  }

  public function testSale_withLineItemsValidationErrorTooManyLineItems()
  {
	  $transactionParams = [
          'amount' => '35.05',
          'creditCard' => [
              'number' => Braintree\Test\CreditCardNumbers::$visa,
              'expirationDate' => '05/2009',
          ],
          'lineItems' => [],
      ];

      for ($i = 0; $i < 250; $i++) {
          array_push($transactionParams['lineItems'], [
              'quantity' => '2.02',
              'name' => 'Line item #' . $i,
              'kind' => Braintree\Transaction\LineItem::CREDIT,
              'unitAmount' => '5',
              'unitOfMeasure' => 'gallon',
              'totalAmount' => '10.1',
          ]);
      }

      $result = Braintree\Transaction::sale($transactionParams);
      $this->assertFalse($result->success);
      $this->assertEquals(
          Braintree\Error\Codes::TRANSACTION_TOO_MANY_LINE_ITEMS,
          $result->errors->forKey('transaction')->onAttribute('lineItems')[0]->code
      );
  }

  public function testCreateTransactionUsingFakeApplePayNonce()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$applePayAmEx
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('47.00', $transaction->amount);
        $applePayDetails = $transaction->applePayCardDetails;
        $this->assertSame(Braintree\ApplePayCard::AMEX, $applePayDetails->cardType);
        $this->assertContains("AmEx ", $applePayDetails->sourceDescription);
        $this->assertContains("AmEx ", $applePayDetails->paymentInstrumentName);
        $this->assertTrue(intval($applePayDetails->expirationMonth) > 0);
        $this->assertTrue(intval($applePayDetails->expirationYear) > 0);
        $this->assertNotNull($applePayDetails->cardholderName);
    }

  public function testCreateTransactionUsingRawApplePayParams()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '1.02',
            'applePayCard' => [
                'number' => "370295001292109",
                'cardholderName' => "JANE SMITH",
                'cryptogram' => "AAAAAAAA/COBt84dnIEcwAA3gAAGhgEDoLABAAhAgAABAAAALnNCLw==",
                'expirationMonth' => "10",
                'expirationYear' => "17",
                'eciIndicator' => "07"
            ]
        ]);
        $this->assertTrue($result->success);
    }

  public function testCreateTransactionUsingRawApplePayParamsInSnakeCaseForBackwardsCompatibility()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '1.02',
            'apple_pay_card' => [
                'number' => "370295001292109",
                'cardholder_name' => "JANE SMITH",
                'cryptogram' => "AAAAAAAA/COBt84dnIEcwAA3gAAGhgEDoLABAAhAgAABAAAALnNCLw==",
                'expiration_month' => "10",
                'expiration_year' => "17",
                'eci_indicator' => "07"
            ]
        ]);
        $this->assertTrue($result->success);
    }

  public function testCreateTransactionUsingFakeAndroidPayProxyCardNonce()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$androidPayDiscover
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('47.00', $transaction->amount);
        $androidPayCardDetails = $transaction->androidPayCardDetails;
        $this->assertSame(Braintree\CreditCard::DISCOVER, $androidPayCardDetails->cardType);
        $this->assertSame("1117", $androidPayCardDetails->last4);
        $this->assertNull($androidPayCardDetails->token);
        $this->assertSame(Braintree\CreditCard::DISCOVER, $androidPayCardDetails->virtualCardType);
        $this->assertSame("1117", $androidPayCardDetails->virtualCardLast4);
        $this->assertSame(Braintree\CreditCard::DISCOVER, $androidPayCardDetails->sourceCardType);
        $this->assertSame("1111", $androidPayCardDetails->sourceCardLast4);
        $this->assertSame("Discover 1111", $androidPayCardDetails->sourceDescription);
        $this->assertContains('android_pay', $androidPayCardDetails->imageUrl);
        $this->assertTrue(intval($androidPayCardDetails->expirationMonth) > 0);
        $this->assertTrue(intval($androidPayCardDetails->expirationYear) > 0);
    }

  public function testCreateTransactionUsingFakeAndroidPayNetworkTokenNonce()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$androidPayMasterCard
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('47.00', $transaction->amount);
        $androidPayCardDetails = $transaction->androidPayCardDetails;
        $this->assertSame(Braintree\CreditCard::MASTER_CARD, $androidPayCardDetails->cardType);
        $this->assertSame("4444", $androidPayCardDetails->last4);
        $this->assertNull($androidPayCardDetails->token);
        $this->assertSame(Braintree\CreditCard::MASTER_CARD, $androidPayCardDetails->virtualCardType);
        $this->assertSame("4444", $androidPayCardDetails->virtualCardLast4);
        $this->assertSame(Braintree\CreditCard::MASTER_CARD, $androidPayCardDetails->sourceCardType);
        $this->assertSame("4444", $androidPayCardDetails->sourceCardLast4);
        $this->assertSame("MasterCard 4444", $androidPayCardDetails->sourceDescription);
        $this->assertContains('android_pay', $androidPayCardDetails->imageUrl);
        $this->assertTrue(intval($androidPayCardDetails->expirationMonth) > 0);
        $this->assertTrue(intval($androidPayCardDetails->expirationYear) > 0);
    }

    public function testCreateTransactionUsingFakeAmexExpressCheckoutNonce()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeAmexDirectMerchantAccountId(),
            'paymentMethodNonce' => Braintree\Test\Nonces::$amexExpressCheckout
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('47.00', $transaction->amount);
        $amexExpressCheckoutCardDetails = $transaction->amexExpressCheckoutCardDetails;

        $this->assertSame(Braintree\CreditCard::AMEX, $amexExpressCheckoutCardDetails->cardType);
        $this->assertSame("341111", $amexExpressCheckoutCardDetails->bin);
        $this->assertSame("12/21", $amexExpressCheckoutCardDetails->cardMemberExpiryDate);
        $this->assertSame("0005", $amexExpressCheckoutCardDetails->cardMemberNumber);
        $this->assertNull($amexExpressCheckoutCardDetails->token);
        $this->assertNotNull($amexExpressCheckoutCardDetails->sourceDescription);
        $this->assertContains(".png", $amexExpressCheckoutCardDetails->imageUrl);
        $this->assertTrue(intval($amexExpressCheckoutCardDetails->expirationMonth) > 0);
        $this->assertTrue(intval($amexExpressCheckoutCardDetails->expirationYear) > 0);
    }

    public function testCreateTransactionUsingFakeVenmoAccountNonceAndProfileId()
    {
        $result = Braintree\Transaction::sale(array(
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeVenmoAccountMerchantAccountId(),
            'paymentMethodNonce' => Braintree\Test\Nonces::$venmoAccount,
            'options' => [
                'venmo' => [
                    'profileId' => "integration_venmo_merchant_public_id"
                ]
            ]
        ));

        $this->assertTrue($result->success);
    }

    public function testCreateTransactionUsingFakeVenmoAccountNonceAndProfileIdUsingSnakeCaseKeyforProfileId()
    {
        $result = Braintree\Transaction::sale(array(
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeVenmoAccountMerchantAccountId(),
            'paymentMethodNonce' => Braintree\Test\Nonces::$venmoAccount,
            'options' => [
                'venmo' => [
                    'profile_id' => "integration_venmo_merchant_public_id"
                ]
            ]
        ));

        $this->assertTrue($result->success);
    }

    public function testCreateTransactionUsingFakeVenmoAccountNonce()
    {
        $result = Braintree\Transaction::sale(array(
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeVenmoAccountMerchantAccountId(),
            'paymentMethodNonce' => Braintree\Test\Nonces::$venmoAccount
        ));

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('47.00', $transaction->amount);
        $this->assertEquals(Braintree\PaymentInstrumentType::VENMO_ACCOUNT, $transaction->paymentInstrumentType);
        $venmoAccountDetails = $transaction->venmoAccountDetails;

        $this->assertNull($venmoAccountDetails->token);
        $this->assertNotNull($venmoAccountDetails->sourceDescription);
        $this->assertContains(".png", $venmoAccountDetails->imageUrl);
        $this->assertSame("venmojoe", $venmoAccountDetails->username);
        $this->assertSame("Venmo-Joe-1", $venmoAccountDetails->venmoUserId);
    }

    public function testCannotCreateTransactionUsingFakeCoinbaseNonce()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '17.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$coinbase
        ]);

        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Error\Codes::PAYMENT_METHOD_NO_LONGER_SUPPORTED, $result->errors->forKey('transaction')->onAttribute('base')[0]->code);
    }

  public function testCreateTransactionReturnsPaymentInstrumentType()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ],
            "share" => true
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\PaymentInstrumentType::CREDIT_CARD, $transaction->paymentInstrumentType);
    }

  public function testCloneTransactionAndSubmitForSettlement()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/2011',
            ]
        ]);

      $this->assertTrue($result->success);
      $transaction = $result->transaction;

      $cloneResult = Braintree\Transaction::cloneTransaction($transaction->id, ['amount' => '123.45', 'options' => ['submitForSettlement' => true]]);
      $cloneTransaction = $cloneResult->transaction;
      $this->assertEquals('submitted_for_settlement', $cloneTransaction->status);
    }

  public function testCloneWithValidations()
    {
        $result = Braintree\Transaction::credit([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/2011'
            ]
      ]);
      $this->assertTrue($result->success);
      $transaction = $result->transaction;

      $cloneResult = Braintree\Transaction::cloneTransaction($transaction->id, ['amount' => '123.45']);
      $this->assertFalse($cloneResult->success);

      $baseErrors = $cloneResult->errors->forKey('transaction')->onAttribute('base');

      $this->assertEquals(Braintree\Error\Codes::TRANSACTION_CANNOT_CLONE_CREDIT, $baseErrors[0]->code);
    }

  public function testSale()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertNotNull($transaction->processorAuthorizationCode);
        $this->assertEquals('510510', $transaction->creditCardDetails->bin);
        $this->assertEquals('5100', $transaction->creditCardDetails->last4);
        $this->assertEquals('The Cardholder', $transaction->creditCardDetails->cardholderName);
        $this->assertEquals(1000, $result->transaction->processorResponseCode);
        $this->assertEquals("Approved", $result->transaction->processorResponseText);
        $this->assertEquals(Braintree\ProcessorResponseTypes::APPROVED, $result->transaction->processorResponseType);
    }

  public function testSaleWithAccessToken()
    {
        $credentials = Test\Braintree\OAuthTestHelper::createCredentials([
            'clientId' => 'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret',
            'merchantId' => 'integration_merchant_id',
        ]);

        $gateway = new Braintree\Gateway([
            'accessToken' => $credentials->accessToken,
        ]);

        $result = $gateway->transaction()->sale([
            'amount' => '100.00',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertNotNull($transaction->processorAuthorizationCode);
        $this->assertEquals('510510', $transaction->creditCardDetails->bin);
        $this->assertEquals('5100', $transaction->creditCardDetails->last4);
        $this->assertEquals('The Cardholder', $transaction->creditCardDetails->cardholderName);
    }

  public function testSaleWithRiskData()
    {
        $gateway = Test\Helper::advancedFraudIntegrationMerchantGateway();
        $result = $gateway->transaction()->sale([
            'amount' => '100.00',
            'deviceSessionId' => 'abc123',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertNotNull($transaction->riskData);
        $this->assertNotNull($transaction->riskData->decision);
        $this->assertNotNull($transaction->riskData->id);
        $this->assertNotNull($transaction->riskData->deviceDataCaptured);
        $this->assertNotNull($transaction->riskData->fraudServiceProvider);
    }

  public function testRecurring()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'recurring' => true,
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(true, $transaction->recurring);
    }

  public function testTransactionSourceWithRecurringFirst()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'transactionSource' => 'recurring_first',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(true, $transaction->recurring);
    }

  public function testTransactionSourceWithRecurring()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'transactionSource' => 'recurring',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(true, $transaction->recurring);
    }

  public function testTransactionSourceWithMerchant()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'transactionSource' => 'merchant',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(false, $transaction->recurring);
    }

  public function testTransactionSourceWithMoto()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'transactionSource' => 'moto',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(False, $transaction->recurring);
    }

  public function testTransactionSourceInvalid()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'transactionSource' => 'invalid_value',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_TRANSACTION_SOURCE_IS_INVALID, $result->errors->forKey('transaction')->onAttribute('transactionSource')[0]->code);
    }

  public function testSale_withServiceFee()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '10.00',
            'merchantAccountId' => Test\Helper::nonDefaultSubMerchantAccountId(),
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'serviceFeeAmount' => '1.00'
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('1.00', $transaction->serviceFeeAmount);
    }

  public function testSale_isInvalidIfTransactionMerchantAccountIsNotSub()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '10.00',
            'merchantAccountId' => Test\Helper::nonDefaultMerchantAccountId(),
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'serviceFeeAmount' => '1.00'
        ]);
        $this->assertFalse($result->success);
        $transaction = $result->transaction;
        $serviceFeeErrors = $result->errors->forKey('transaction')->onAttribute('serviceFeeAmount');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SERVICE_FEE_AMOUNT_NOT_ALLOWED_ON_MASTER_MERCHANT_ACCOUNT, $serviceFeeErrors[0]->code);
    }

  public function testSale_isInvalidIfSubMerchantAccountHasNoServiceFee()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '10.00',
            'merchantAccountId' => Test\Helper::nonDefaultSubMerchantAccountId(),
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertFalse($result->success);
        $transaction = $result->transaction;
        $serviceFeeErrors = $result->errors->forKey('transaction')->onAttribute('merchantAccountId');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SUB_MERCHANT_ACCOUNT_REQUIRES_SERVICE_FEE_AMOUNT, $serviceFeeErrors[0]->code);
    }

  public function testSale_withVenmoSdkSession()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '10.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => [
                'venmoSdkSession' => Braintree\Test\VenmoSdk::getTestSession()
            ]
        ]);
        $this->assertEquals(true, $result->success);
        $transaction = $result->transaction;
        $this->assertEquals(false, $transaction->creditCardDetails->venmoSdk);
    }

  public function testSale_withVenmoSdkPaymentMethodCode()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '10.00',
            'venmoSdkPaymentMethodCode' => Braintree\Test\VenmoSdk::$visaPaymentMethodCode
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals("411111", $transaction->creditCardDetails->bin);
    }

  public function testSale_withLevel2Attributes()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'expirationDate' => '05/2011',
                'number' => '5105105105105100'
            ],
            'taxExempt' => true,
            'taxAmount' => '10.00',
            'purchaseOrderNumber' => '12345'
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;

        $this->assertTrue($transaction->taxExempt);
        $this->assertEquals('10.00', $transaction->taxAmount);
        $this->assertEquals('12345', $transaction->purchaseOrderNumber);
    }

  public function testSale_withInvalidTaxAmountAttribute()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'expirationDate' => '05/2011',
                'number' => '5105105105105100'
            ],
            'taxAmount' => 'abc'
        ]);

        $this->assertFalse($result->success);

        $taxAmountErrors = $result->errors->forKey('transaction')->onAttribute('taxAmount');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_TAX_AMOUNT_FORMAT_IS_INVALID, $taxAmountErrors[0]->code);
    }

  public function testSale_withServiceFeeTooLarge()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '10.00',
            'merchantAccountId' => Test\Helper::nonDefaultSubMerchantAccountId(),
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'serviceFeeAmount' => '20.00'
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('serviceFeeAmount');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SERVICE_FEE_AMOUNT_IS_TOO_LARGE, $errors[0]->code);
    }

  public function testSale_withTooLongPurchaseOrderAttribute()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'expirationDate' => '05/2011',
                'number' => '5105105105105100'
            ],
            'purchaseOrderNumber' => 'aaaaaaaaaaaaaaaaaa'
        ]);

        $this->assertFalse($result->success);

        $purchaseOrderNumberErrors = $result->errors->forKey('transaction')->onAttribute('purchaseOrderNumber');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_PURCHASE_ORDER_NUMBER_IS_TOO_LONG, $purchaseOrderNumberErrors[0]->code);
    }

  public function testSale_withInvalidPurchaseOrderNumber()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'expirationDate' => '05/2011',
                'number' => '5105105105105100'
            ],
            'purchaseOrderNumber' => "\x80\x90\xA0"
        ]);

        $this->assertFalse($result->success);

        $purchaseOrderNumberErrors = $result->errors->forKey('transaction')->onAttribute('purchaseOrderNumber');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_PURCHASE_ORDER_NUMBER_IS_INVALID, $purchaseOrderNumberErrors[0]->code);
    }

  public function testSale_withAllAttributes()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'orderId' => '123',
            'channel' => 'MyShoppingCardProvider',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/2011',
                'cvv' => '123'
            ],
            'customer' => [
                'firstName' => 'Dan',
                'lastName' => 'Smith',
                'company' => 'Braintree',
                'email' => 'dan@example.com',
                'phone' => '419-555-1234',
                'fax' => '419-555-1235',
                'website' => 'http://braintreepayments.com'
            ],
            'billing' => [
                'firstName' => 'Carl',
                'lastName' => 'Jones',
                'company' => 'Braintree',
                'streetAddress' => '123 E Main St',
                'extendedAddress' => 'Suite 403',
                'locality' => 'Chicago',
                'region' => 'IL',
                'postalCode' => '60622',
                'countryName' => 'United States of America',
                'countryCodeAlpha2' => 'US',
                'countryCodeAlpha3' => 'USA',
                'countryCodeNumeric' => '840'
            ],
            'shipping' => [
                'firstName' => 'Andrew',
                'lastName' => 'Mason',
                'company' => 'Braintree',
                'streetAddress' => '456 W Main St',
                'extendedAddress' => 'Apt 2F',
                'locality' => 'Bartlett',
                'region' => 'IL',
                'postalCode' => '60103',
                'countryName' => 'United States of America',
                'countryCodeAlpha2' => 'US',
                'countryCodeAlpha3' => 'USA',
                'countryCodeNumeric' => '840'
            ]
      ]);
        Test\Helper::assertPrintable($result);
      $this->assertTrue($result->success);
      $transaction = $result->transaction;

      $this->assertNotNull($transaction->id);
      $this->assertInstanceOf('DateTime', $transaction->authorizationExpiresAt);
      $this->assertInstanceOf('DateTime', $transaction->updatedAt);
      $this->assertInstanceOf('DateTime', $transaction->createdAt);
      $this->assertNull($transaction->refundId);

      $this->assertEquals(Test\Helper::defaultMerchantAccountId(), $transaction->merchantAccountId);
      $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
      $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
      $this->assertEquals('100.00', $transaction->amount);
      $this->assertEquals('USD', $transaction->currencyIsoCode);
      $this->assertEquals('123', $transaction->orderId);
      $this->assertEquals('MyShoppingCardProvider', $transaction->channel);
      $this->assertEquals('MasterCard', $transaction->creditCardDetails->cardType);
      $this->assertEquals('1000', $transaction->processorResponseCode);
      $this->assertEquals('Approved', $transaction->processorResponseText);
      $this->assertNull($transaction->voiceReferralNumber);
      $this->assertFalse($transaction->taxExempt);

      $this->assertEquals('M', $transaction->avsPostalCodeResponseCode);
      $this->assertEquals('M', $transaction->avsStreetAddressResponseCode);
      $this->assertEquals('M', $transaction->cvvResponseCode);

      $this->assertEquals('Dan', $transaction->customerDetails->firstName);
      $this->assertEquals('Smith', $transaction->customerDetails->lastName);
      $this->assertEquals('Braintree', $transaction->customerDetails->company);
      $this->assertEquals('dan@example.com', $transaction->customerDetails->email);
      $this->assertEquals('419-555-1234', $transaction->customerDetails->phone);
      $this->assertEquals('419-555-1235', $transaction->customerDetails->fax);
      $this->assertEquals('http://braintreepayments.com', $transaction->customerDetails->website);

      $this->assertEquals('Carl', $transaction->billingDetails->firstName);
      $this->assertEquals('Jones', $transaction->billingDetails->lastName);
      $this->assertEquals('Braintree', $transaction->billingDetails->company);
      $this->assertEquals('123 E Main St', $transaction->billingDetails->streetAddress);
      $this->assertEquals('Suite 403', $transaction->billingDetails->extendedAddress);
      $this->assertEquals('Chicago', $transaction->billingDetails->locality);
      $this->assertEquals('IL', $transaction->billingDetails->region);
      $this->assertEquals('60622', $transaction->billingDetails->postalCode);
      $this->assertEquals('United States of America', $transaction->billingDetails->countryName);
      $this->assertEquals('US', $transaction->billingDetails->countryCodeAlpha2);
      $this->assertEquals('USA', $transaction->billingDetails->countryCodeAlpha3);
      $this->assertEquals('840', $transaction->billingDetails->countryCodeNumeric);

      $this->assertEquals('Andrew', $transaction->shippingDetails->firstName);
      $this->assertEquals('Mason', $transaction->shippingDetails->lastName);
      $this->assertEquals('Braintree', $transaction->shippingDetails->company);
      $this->assertEquals('456 W Main St', $transaction->shippingDetails->streetAddress);
      $this->assertEquals('Apt 2F', $transaction->shippingDetails->extendedAddress);
      $this->assertEquals('Bartlett', $transaction->shippingDetails->locality);
      $this->assertEquals('IL', $transaction->shippingDetails->region);
      $this->assertEquals('60103', $transaction->shippingDetails->postalCode);
      $this->assertEquals('United States of America', $transaction->shippingDetails->countryName);
      $this->assertEquals('US', $transaction->shippingDetails->countryCodeAlpha2);
      $this->assertEquals('USA', $transaction->shippingDetails->countryCodeAlpha3);
      $this->assertEquals('840', $transaction->shippingDetails->countryCodeNumeric);

      $this->assertNotNull($transaction->processorAuthorizationCode);
      $this->assertEquals('510510', $transaction->creditCardDetails->bin);
      $this->assertEquals('5100', $transaction->creditCardDetails->last4);
      $this->assertEquals('510510******5100', $transaction->creditCardDetails->maskedNumber);
      $this->assertEquals('The Cardholder', $transaction->creditCardDetails->cardholderName);
      $this->assertEquals('05', $transaction->creditCardDetails->expirationMonth);
      $this->assertEquals('2011', $transaction->creditCardDetails->expirationYear);
      $this->assertNotNull($transaction->creditCardDetails->imageUrl);
    }

  public function testSale_withCustomFields()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'customFields' => [
                'store_me' => 'custom value'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $customFields = $transaction->customFields;
        $this->assertEquals('custom value', $customFields['store_me']);
    }

  public function testSale_withExpirationMonthAndYear()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationMonth' => '5',
                'expirationYear' => '2012'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('05', $transaction->creditCardDetails->expirationMonth);
        $this->assertEquals('2012', $transaction->creditCardDetails->expirationYear);
    }

  public function testSale_underscoresAllCustomFields()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'customFields' => [
                'storeMe' => 'custom value'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $customFields = $transaction->customFields;
        $this->assertEquals('custom value', $customFields['store_me']);
    }

  public function testSale_withInvalidCustomField()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'customFields' => [
                'invalidKey' => 'custom value'
            ]
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('customFields');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_CUSTOM_FIELD_IS_INVALID, $errors[0]->code);
        $this->assertEquals('Custom field is invalid: invalidKey.', $errors[0]->message);
    }

  public function testSale_withMerchantAccountId()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'merchantAccountId' => Test\Helper::nonDefaultMerchantAccountId(),
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Test\Helper::nonDefaultMerchantAccountId(), $transaction->merchantAccountId);
    }

  public function testSale_withoutMerchantAccountIdFallsBackToDefault()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Test\Helper::defaultMerchantAccountId(), $transaction->merchantAccountId);
    }

  public function testSale_withShippingAddressId()
    {
        $customer = Braintree\Customer::create([
            'firstName' => 'Mike',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/12'
            ]
        ])->customer;

        $address = Braintree\Address::create([
            'customerId' => $customer->id,
            'streetAddress' => '123 Fake St.'
        ])->address;

        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'customerId' => $customer->id,
            'shippingAddressId' => $address->id
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('123 Fake St.', $transaction->shippingDetails->streetAddress);
        $this->assertEquals($address->id, $transaction->shippingDetails->id);
    }

  public function testSale_withBillingAddressId()
    {
        $customer = Braintree\Customer::create([
            'firstName' => 'Mike',
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/12'
            ]
        ])->customer;

        $address = Braintree\Address::create([
            'customerId' => $customer->id,
            'streetAddress' => '123 Fake St.'
        ])->address;

        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'customerId' => $customer->id,
            'billingAddressId' => $address->id
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('123 Fake St.', $transaction->billingDetails->streetAddress);
        $this->assertEquals($address->id, $transaction->billingDetails->id);
    }

  public function testSaleNoValidate()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertEquals('510510', $transaction->creditCardDetails->bin);
        $this->assertEquals('5100', $transaction->creditCardDetails->last4);
    }

  public function testSale_withSoftDecline()
    {

        $gateway = Test\Helper::integrationMerchantGateway();
        $result = $gateway->transaction()->sale([
            'amount' => Braintree\Test\TransactionAmounts::$decline,
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
        ]);
        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Transaction::PROCESSOR_DECLINED, $result->transaction->status);
        $this->assertEquals(2000, $result->transaction->processorResponseCode);
        $this->assertEquals("Do Not Honor", $result->transaction->processorResponseText);
        $this->assertEquals(Braintree\ProcessorResponseTypes::SOFT_DECLINED, $result->transaction->processorResponseType);
        $this->assertEquals("2000 : Do Not Honor", $result->transaction->additionalProcessorResponse);
    }

  public function testSale_withHardDecline()
    {

        $gateway = Test\Helper::integrationMerchantGateway();
        $result = $gateway->transaction()->sale([
            'amount' => Braintree\Test\TransactionAmounts::$hardDecline,
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
        ]);
        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Transaction::PROCESSOR_DECLINED, $result->transaction->status);
        $this->assertEquals(2015, $result->transaction->processorResponseCode);
        $this->assertEquals("Transaction Not Allowed", $result->transaction->processorResponseText);
        $this->assertEquals(Braintree\ProcessorResponseTypes::HARD_DECLINED, $result->transaction->processorResponseType);
        $this->assertEquals("2015 : Transaction Not Allowed", $result->transaction->additionalProcessorResponse);
    }

  public function testSale_withExistingCustomer()
    {
        $customer = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com'
        ])->customer;

        $transaction = Braintree\Transaction::sale([
            'amount' => '100.00',
            'customerId' => $customer->id,
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/12'
            ]
        ])->transaction;
        $this->assertEquals($transaction->creditCardDetails->maskedNumber, '401288******1881');
        $this->assertNull($transaction->vaultCreditCard());
    }

  public function testSale_andStoreShippingAddressInVault()
    {
        $customer = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com'
        ])->customer;

        $transaction = Braintree\Transaction::sale([
            'amount' => '100.00',
            'customerId' => $customer->id,
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/12'
            ],
            'shipping' => [
                'firstName' => 'Darren',
                'lastName' => 'Stevens'
            ],
            'options' => [
                'storeInVault' => true,
                'storeShippingAddressInVault' => true
            ]
        ])->transaction;

        $customer = Braintree\Customer::find($customer->id);
        $this->assertEquals('Darren', $customer->addresses[0]->firstName);
        $this->assertEquals('Stevens', $customer->addresses[0]->lastName);
    }

  public function testSale_withExistingCustomer_storeInVault()
    {
        $customer = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com'
        ])->customer;

        $transaction = Braintree\Transaction::sale([
            'amount' => '100.00',
            'customerId' => $customer->id,
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/12'
            ],
            'options' => [
                'storeInVault' => true
            ]
        ])->transaction;
        $this->assertEquals($transaction->creditCardDetails->maskedNumber, '401288******1881');
        $this->assertEquals($transaction->vaultCreditCard()->maskedNumber, '401288******1881');
    }

  public function testCredit()
    {
        $result = Braintree\Transaction::credit([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $transaction->status);
        $this->assertEquals(Braintree\Transaction::CREDIT, $transaction->type);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertEquals('510510', $transaction->creditCardDetails->bin);
        $this->assertEquals('5100', $transaction->creditCardDetails->last4);
    }

  public function testCreditNoValidate()
    {
        $transaction = Braintree\Transaction::creditNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::CREDIT, $transaction->type);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $transaction->status);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertEquals('510510', $transaction->creditCardDetails->bin);
        $this->assertEquals('5100', $transaction->creditCardDetails->last4);
    }

  public function testCredit_withMerchantAccountId()
    {
        $result = Braintree\Transaction::credit([
            'amount' => '100.00',
            'merchantAccountId' => Test\Helper::nonDefaultMerchantAccountId(),
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Test\Helper::nonDefaultMerchantAccountId(), $transaction->merchantAccountId);
    }

  public function testCredit_withoutMerchantAccountIdFallsBackToDefault()
    {
        $result = Braintree\Transaction::credit([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Test\Helper::defaultMerchantAccountId(), $transaction->merchantAccountId);
    }

  public function testCredit_withServiceFeeNotAllowed()
    {
        $result = Braintree\Transaction::credit([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'serviceFeeAmount' => '12.75'
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SERVICE_FEE_IS_NOT_ALLOWED_ON_CREDITS, $errors[0]->code);
    }

  public function testSubmitForSettlement_nullAmount()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $submitResult = Braintree\Transaction::submitForSettlement($transaction->id);
        $this->assertEquals(true, $submitResult->success);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submitResult->transaction->status);
        $this->assertEquals('100.00', $submitResult->transaction->amount);
    }

  public function testSubmitForSettlement_amountLessThanServiceFee()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '10.00',
            'merchantAccountId' => Test\Helper::nonDefaultSubMerchantAccountId(),
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'serviceFeeAmount' => '5.00'
        ]);
        $submitResult = Braintree\Transaction::submitForSettlement($transaction->id, '1.00');
        $errors = $submitResult->errors->forKey('transaction')->onAttribute('amount');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SETTLEMENT_AMOUNT_IS_LESS_THAN_SERVICE_FEE_AMOUNT, $errors[0]->code);
    }

  public function testSubmitForSettlement_withAmount()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $submitResult = Braintree\Transaction::submitForSettlement($transaction->id, '50.00');
        $this->assertEquals(true, $submitResult->success);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submitResult->transaction->status);
        $this->assertEquals('50.00', $submitResult->transaction->amount);
    }

  public function testSubmitForSettlement_withOrderId()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $submitResult = Braintree\Transaction::submitForSettlement($transaction->id, '67.00', ['orderId' => 'ABC123']);
        $this->assertEquals(true, $submitResult->success);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submitResult->transaction->status);
        $this->assertEquals('ABC123', $submitResult->transaction->orderId);
        $this->assertEquals('67.00', $submitResult->transaction->amount);
    }

  public function testSubmitForSettlement_withDescriptor()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        $params = [
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555',
                'url' => 'ebay.com'
            ]
        ];

        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $submitResult = Braintree\Transaction::submitForSettlement($transaction->id, '67.00', $params);
        $this->assertEquals(true, $submitResult->success);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submitResult->transaction->status);
        $this->assertEquals('123*123456789012345678', $submitResult->transaction->descriptor->name);
        $this->assertEquals('3334445555', $submitResult->transaction->descriptor->phone);
        $this->assertEquals('ebay.com', $submitResult->transaction->descriptor->url);
    }

  public function testSubmitForSettlement_withInvalidParams()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        $params = ['invalid' => 'invalid'];

        $this->setExpectedException('InvalidArgumentException', 'invalid keys: invalid');
        Braintree\Transaction::submitForSettlement($transaction->id, '67.00', $params);
    }

  public function testSubmitForSettlementNoValidate_whenValidWithoutAmount()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $submittedTransaction = Braintree\Transaction::submitForSettlementNoValidate($transaction->id);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submittedTransaction->status);
        $this->assertEquals('100.00', $submittedTransaction->amount);
    }

  public function testSubmitForSettlementNoValidate_whenValidWithAmount()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $submittedTransaction = Braintree\Transaction::submitForSettlementNoValidate($transaction->id, '99.00');
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submittedTransaction->status);
        $this->assertEquals('99.00', $submittedTransaction->amount);
    }

  public function testSubmitForSettlementNoValidate_whenInvalid()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->setExpectedException('Braintree\Exception\ValidationsFailed');
        $submittedTransaction = Braintree\Transaction::submitForSettlementNoValidate($transaction->id, '101.00');
    }

  public function testUpdateDetails()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
                ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $updateOptions = [
            'amount' => '90.00',
            'orderId' => '123',
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555',
                'url' => 'ebay.com'
            ]
        ];

        $result = Braintree\Transaction::updateDetails($transaction->id, $updateOptions);
        $this->assertEquals(true, $result->success);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $result->transaction->status);
        $this->assertEquals('90.00', $result->transaction->amount);
    }

  public function testUpdateDetails_withInvalidParams()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
                ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $updateOptions = [
            'amount' => '90.00',
            'invalid' => 'some value'
        ];

        $this->setExpectedException('InvalidArgumentException', 'invalid keys: invalid');
        Braintree\Transaction::updateDetails($transaction->id, $updateOptions);
    }

  public function testUpdateDetails_withInvalidAmount()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
                ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $updateOptions = [
            'amount' => '900.00',
            'orderId' => '123',
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555',
                'url' => 'ebay.com'
            ]
        ];

        $result = Braintree\Transaction::updateDetails($transaction->id, $updateOptions);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('amount');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_SETTLEMENT_AMOUNT_IS_TOO_LARGE, $errors[0]->code);

    }

  public function testUpdateDetails_withInvalidDescriptor()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
                ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $updateOptions = [
            'amount' => '90.00',
            'orderId' => '123',
            'descriptor' => [
                'name' => 'invalid name',
                'phone' => 'invalid phone',
                'url' => 'invalid way too long url'
            ]
        ];

        $result = Braintree\Transaction::updateDetails($transaction->id, $updateOptions);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->forKey('descriptor')->onAttribute('name');
        $this->assertEquals(Braintree\Error\Codes::DESCRIPTOR_NAME_FORMAT_IS_INVALID, $errors[0]->code);

        $errors = $result->errors->forKey('transaction')->forKey('descriptor')->onAttribute('phone');
        $this->assertEquals(Braintree\Error\Codes::DESCRIPTOR_PHONE_FORMAT_IS_INVALID, $errors[0]->code);

        $errors = $result->errors->forKey('transaction')->forKey('descriptor')->onAttribute('url');
        $this->assertEquals(Braintree\Error\Codes::DESCRIPTOR_URL_FORMAT_IS_INVALID, $errors[0]->code);
    }

  public function testUpdateDetails_withInvalidOrderId()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
                ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $updateOptions = [
            'amount' => '90.00',
            'orderId' => str_repeat('x', 256),
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555',
                'url' => 'ebay.com'
            ]
        ];

        $result = Braintree\Transaction::updateDetails($transaction->id, $updateOptions);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('orderId');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_ORDER_ID_IS_TOO_LONG, $errors[0]->code);
    }

  public function testUpdateDetails_withInvalidProcessor()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'merchantAccountId' => Test\Helper::fakeAmexDirectMerchantAccountId(),
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$amexPayWithPoints['Success'],
                'expirationDate' => '05/12'
            ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $updateOptions = [
            'amount' => '90.00',
            'orderId' => '123',
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555',
                'url' => 'ebay.com'
            ]
        ];

        $result = Braintree\Transaction::updateDetails($transaction->id, $updateOptions);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_PROCESSOR_DOES_NOT_SUPPORT_UPDATING_DETAILS, $errors[0]->code);
    }

  public function testUpdateDetails_withBadStatus()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        $updateOptions = [
            'amount' => '90.00',
            'orderId' => '123',
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555',
                'url' => 'ebay.com'
            ]
        ];

        $result = Braintree\Transaction::updateDetails($transaction->id, $updateOptions);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_CANNOT_UPDATE_DETAILS_NOT_SUBMITTED_FOR_SETTLEMENT, $errors[0]->code);
    }

  public function testVoid()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $voidResult = Braintree\Transaction::void($transaction->id);
        $this->assertEquals(true, $voidResult->success);
        $this->assertEquals(Braintree\Transaction::VOIDED, $voidResult->transaction->status);
    }

  public function test_countryValidationError_inconsistency()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'billing' => [
                'countryCodeAlpha2' => 'AS',
                'countryCodeAlpha3' => 'USA'
            ]
        ]);
        $this->assertFalse($result->success);

        $errors = $result->errors->forKey('transaction')->forKey('billing')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_INCONSISTENT_COUNTRY, $errors[0]->code);
    }

  public function test_countryValidationError_incorrectAlpha2()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'billing' => [
                'countryCodeAlpha2' => 'ZZ'
            ]
        ]);
        $this->assertFalse($result->success);

        $errors = $result->errors->forKey('transaction')->forKey('billing')->onAttribute('countryCodeAlpha2');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_COUNTRY_CODE_ALPHA2_IS_NOT_ACCEPTED, $errors[0]->code);
    }

  public function test_countryValidationError_incorrectAlpha3()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'billing' => [
                'countryCodeAlpha3' => 'ZZZ'
            ]
        ]);
        $this->assertFalse($result->success);

        $errors = $result->errors->forKey('transaction')->forKey('billing')->onAttribute('countryCodeAlpha3');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_COUNTRY_CODE_ALPHA3_IS_NOT_ACCEPTED, $errors[0]->code);
    }

  public function test_countryValidationError_incorrectNumericCode()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'billing' => [
                'countryCodeNumeric' => '000'
            ]
        ]);
        $this->assertFalse($result->success);

        $errors = $result->errors->forKey('transaction')->forKey('billing')->onAttribute('countryCodeNumeric');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_COUNTRY_CODE_NUMERIC_IS_NOT_ACCEPTED, $errors[0]->code);
    }

  public function testVoid_withValidationError()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $voided = Braintree\Transaction::voidNoValidate($transaction->id);
        $this->assertEquals(Braintree\Transaction::VOIDED, $voided->status);
        $result = Braintree\Transaction::void($transaction->id);
        $this->assertEquals(false, $result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_CANNOT_BE_VOIDED, $errors[0]->code);
    }

  public function testVoidNoValidate()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $voided = Braintree\Transaction::voidNoValidate($transaction->id);
        $this->assertEquals(Braintree\Transaction::VOIDED, $voided->status);
    }

  public function testVoidNoValidate_throwsIfNotInvalid()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $voided = Braintree\Transaction::voidNoValidate($transaction->id);
        $this->assertEquals(Braintree\Transaction::VOIDED, $voided->status);
        $this->setExpectedException('Braintree\Exception\ValidationsFailed');
        $voided = Braintree\Transaction::voidNoValidate($transaction->id);
    }

  public function testFind()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $transaction = Braintree\Transaction::find($result->transaction->id);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertEquals('510510', $transaction->creditCardDetails->bin);
        $this->assertEquals('5100', $transaction->creditCardDetails->last4);
    }

  public function testFindExposesDisbursementDetails()
    {
        $transaction = Braintree\Transaction::find("deposittransaction");

        $this->assertEquals(true, $transaction->isDisbursed());

        $disbursementDetails = $transaction->disbursementDetails;
        $this->assertEquals('100.00', $disbursementDetails->settlementAmount);
        $this->assertEquals('USD', $disbursementDetails->settlementCurrencyIsoCode);
        $this->assertEquals('1', $disbursementDetails->settlementCurrencyExchangeRate);
        $this->assertEquals(false, $disbursementDetails->fundsHeld);
        $this->assertEquals(true, $disbursementDetails->success);
        $this->assertEquals(new DateTime('2013-04-10'), $disbursementDetails->disbursementDate);
    }

  public function testFindExposesAuthorizationAdjustments()
    {
        $transaction = Braintree\Transaction::find("authadjustmenttransaction");

        $authorizationAdjustment = $transaction->authorizationAdjustments[0];
        $this->assertEquals('-20.00', $authorizationAdjustment->amount);
        $this->assertInstanceOf('DateTime', $authorizationAdjustment->timestamp);
        $this->assertEquals(true, $authorizationAdjustment->success);
        $this->assertEquals('1000', $authorizationAdjustment->processorResponseCode);
        $this->assertEquals('Approved', $authorizationAdjustment->processorResponseText);
        $this->assertEquals(Braintree\ProcessorResponseTypes::APPROVED, $authorizationAdjustment->processorResponseType);
    }

  public function testFindExposesAuthorizationAdjustmentsSoftDeclined()
    {
        $transaction = Braintree\Transaction::find("authadjustmenttransactionsoftdeclined");

        $authorizationAdjustment = $transaction->authorizationAdjustments[0];
        $this->assertEquals('-20.00', $authorizationAdjustment->amount);
        $this->assertInstanceOf('DateTime', $authorizationAdjustment->timestamp);
        $this->assertEquals(false, $authorizationAdjustment->success);
        $this->assertEquals('3000', $authorizationAdjustment->processorResponseCode);
        $this->assertEquals('Processor Network Unavailable - Try Again', $authorizationAdjustment->processorResponseText);
        $this->assertEquals(Braintree\ProcessorResponseTypes::SOFT_DECLINED, $authorizationAdjustment->processorResponseType);
    }

  public function testFindExposesAuthorizationAdjustmentsHardDeclined()
    {
        $transaction = Braintree\Transaction::find("authadjustmenttransactionharddeclined");

        $authorizationAdjustment = $transaction->authorizationAdjustments[0];
        $this->assertEquals('-20.00', $authorizationAdjustment->amount);
        $this->assertInstanceOf('DateTime', $authorizationAdjustment->timestamp);
        $this->assertEquals(false, $authorizationAdjustment->success);
        $this->assertEquals('2015', $authorizationAdjustment->processorResponseCode);
        $this->assertEquals('Transaction Not Allowed', $authorizationAdjustment->processorResponseText);
        $this->assertEquals(Braintree\ProcessorResponseTypes::HARD_DECLINED, $authorizationAdjustment->processorResponseType);
    }

  public function testFindExposesDisputes()
    {
        $transaction = Braintree\Transaction::find("disputedtransaction");

        $dispute = $transaction->disputes[0];
        $this->assertEquals('250.00', $dispute->amount);
        $this->assertEquals('USD', $dispute->currencyIsoCode);
        $this->assertEquals(Braintree\Dispute::FRAUD, $dispute->reason);
        $this->assertEquals(Braintree\Dispute::WON, $dispute->status);
        $this->assertEquals(new DateTime('2014-03-01'), $dispute->receivedDate);
        $this->assertEquals(new DateTime('2014-03-21'), $dispute->replyByDate);
        $this->assertEquals("disputedtransaction", $dispute->transactionDetails->id);
        $this->assertEquals("1000.00", $dispute->transactionDetails->amount);
        $this->assertEquals(Braintree\Dispute::CHARGEBACK, $dispute->kind);
        $this->assertEquals(new DateTime('2014-03-01'), $dispute->dateOpened);
        $this->assertEquals(new DateTime('2014-03-07'), $dispute->dateWon);
    }

  public function testFindExposesThreeDSecureInfo()
    {
        $transaction = Braintree\Transaction::find("threedsecuredtransaction");

        $info = $transaction->threeDSecureInfo;
        $this->assertEquals("Y", $info->enrolled);
        $this->assertEquals("authenticate_successful", $info->status);
        $this->assertTrue($info->liabilityShifted);
        $this->assertTrue($info->liabilityShiftPossible);
    }

  public function testFindExposesNullThreeDSecureInfo()
    {
        $transaction = Braintree\Transaction::find("settledtransaction");

        $this->assertNull($transaction->threeDSecureInfo);
    }

  public function testFindExposesRetrievals()
    {
        $transaction = Braintree\Transaction::find("retrievaltransaction");

        $dispute = $transaction->disputes[0];
        $this->assertEquals('1000.00', $dispute->amount);
        $this->assertEquals('USD', $dispute->currencyIsoCode);
        $this->assertEquals(Braintree\Dispute::RETRIEVAL, $dispute->reason);
        $this->assertEquals(Braintree\Dispute::OPEN, $dispute->status);
        $this->assertEquals("retrievaltransaction", $dispute->transactionDetails->id);
        $this->assertEquals("1000.00", $dispute->transactionDetails->amount);
    }

  public function testFindExposesPayPalDetails()
    {
        $transaction = Braintree\Transaction::find("settledtransaction");
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->assertNotNull($transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->authorizationId);
        $this->assertNotNull($transaction->paypalDetails->payerId);
        $this->assertNotNull($transaction->paypalDetails->payerFirstName);
        $this->assertNotNull($transaction->paypalDetails->payerLastName);
        $this->assertNotNull($transaction->paypalDetails->payerStatus);
        $this->assertNotNull($transaction->paypalDetails->sellerProtectionStatus);
        $this->assertNotNull($transaction->paypalDetails->captureId);
        $this->assertNotNull($transaction->paypalDetails->refundId);
        $this->assertNotNull($transaction->paypalDetails->transactionFeeAmount);
        $this->assertNotNull($transaction->paypalDetails->transactionFeeCurrencyIsoCode);
    }

  public function testSale_storeInVault()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'cardholderName' => 'Card Holder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'customer' => [
                'firstName' => 'Dan',
                'lastName' => 'Smith',
                'company' => 'Braintree',
                'email' => 'dan@example.com',
                'phone' => '419-555-1234',
                'fax' => '419-555-1235',
                'website' => 'http://getbraintree.com'
            ],
            'options' => [
                'storeInVault' => true
            ]
        ]);
        $this->assertNotNull($transaction->creditCardDetails->token);
        $creditCard = $transaction->vaultCreditCard();
        $this->assertEquals('510510', $creditCard->bin);
        $this->assertEquals('5100', $creditCard->last4);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
        $this->assertEquals('Card Holder', $creditCard->cardholderName);
        $customer = $transaction->vaultCustomer();
        $this->assertEquals('Dan', $customer->firstName);
        $this->assertEquals('Smith', $customer->lastName);
        $this->assertEquals('Braintree', $customer->company);
        $this->assertEquals('dan@example.com', $customer->email);
        $this->assertEquals('419-555-1234', $customer->phone);
        $this->assertEquals('419-555-1235', $customer->fax);
        $this->assertEquals('http://getbraintree.com', $customer->website);
    }

  public function testSale_storeInVaultOnSuccessWithSuccessfulTransaction()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'cardholderName' => 'Card Holder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'customer' => [
                'firstName' => 'Dan',
                'lastName' => 'Smith',
                'company' => 'Braintree',
                'email' => 'dan@example.com',
                'phone' => '419-555-1234',
                'fax' => '419-555-1235',
                'website' => 'http://getbraintree.com'
            ],
            'options' => [
                'storeInVaultOnSuccess' => true
            ]
        ]);
        $this->assertNotNull($transaction->creditCardDetails->token);
        $creditCard = $transaction->vaultCreditCard();
        $this->assertEquals('510510', $creditCard->bin);
        $this->assertEquals('5100', $creditCard->last4);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
        $this->assertEquals('Card Holder', $creditCard->cardholderName);
        $customer = $transaction->vaultCustomer();
        $this->assertEquals('Dan', $customer->firstName);
        $this->assertEquals('Smith', $customer->lastName);
        $this->assertEquals('Braintree', $customer->company);
        $this->assertEquals('dan@example.com', $customer->email);
        $this->assertEquals('419-555-1234', $customer->phone);
        $this->assertEquals('419-555-1235', $customer->fax);
        $this->assertEquals('http://getbraintree.com', $customer->website);
    }

  public function testSale_storeInVaultOnSuccessWithFailedTransaction()
    {
        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$decline,
            'creditCard' => [
                'cardholderName' => 'Card Holder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'customer' => [
                'firstName' => 'Dan',
                'lastName' => 'Smith',
                'company' => 'Braintree',
                'email' => 'dan@example.com',
                'phone' => '419-555-1234',
                'fax' => '419-555-1235',
                'website' => 'http://getbraintree.com'
            ],
            'options' => [
                'storeInVaultOnSuccess' => true
            ]
        ]);
        $transaction = $result->transaction;
        $this->assertNull($transaction->creditCardDetails->token);
        $this->assertNull($transaction->vaultCreditCard());
        $this->assertNull($transaction->customerDetails->id);
        $this->assertNull($transaction->vaultCustomer());
    }

  public function testSale_withFraudParams()
    {
        $result = Braintree\Transaction::sale([
            'deviceSessionId' => '123abc',
            'fraudMerchantId' => '456',
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ]
        ]);

        $this->assertTrue($result->success);
    }

  public function testSale_withRiskData()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'riskData' => [
                'customer_browser' => 'IE5',
                'customer_ip' => '192.168.0.1'
            ]
        ]);

        $this->assertTrue($result->success);
    }

  public function testSale_withDescriptor()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555',
                'url' => 'ebay.com'
            ]
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('123*123456789012345678', $transaction->descriptor->name);
        $this->assertEquals('3334445555', $transaction->descriptor->phone);
        $this->assertEquals('ebay.com', $transaction->descriptor->url);
    }

  public function testSale_withDescriptorValidation()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'descriptor' => [
                'name' => 'badcompanyname12*badproduct12',
                'phone' => '%bad4445555',
                'url' => '12345678901234'
            ]
        ]);
        $this->assertFalse($result->success);
        $transaction = $result->transaction;

        $errors = $result->errors->forKey('transaction')->forKey('descriptor')->onAttribute('name');
        $this->assertEquals(Braintree\Error\Codes::DESCRIPTOR_NAME_FORMAT_IS_INVALID, $errors[0]->code);

        $errors = $result->errors->forKey('transaction')->forKey('descriptor')->onAttribute('phone');
        $this->assertEquals(Braintree\Error\Codes::DESCRIPTOR_PHONE_FORMAT_IS_INVALID, $errors[0]->code);

        $errors = $result->errors->forKey('transaction')->forKey('descriptor')->onAttribute('url');
        $this->assertEquals(Braintree\Error\Codes::DESCRIPTOR_URL_FORMAT_IS_INVALID, $errors[0]->code);
    }

  public function testSale_withHoldInEscrow()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::nonDefaultSubMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => [
                'holdInEscrow' => true
            ],
            'serviceFeeAmount' => '1.00'
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::ESCROW_HOLD_PENDING, $transaction->escrowStatus);
    }

  public function testSale_withHoldInEscrowFailsForMasterMerchantAccount()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::nonDefaultMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => [
                'holdInEscrow' => true
            ]
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('base');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_CANNOT_HOLD_IN_ESCROW,
            $errors[0]->code
        );
    }

  public function testSale_withThreeDSecureOptionRequired()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::threeDSecureMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/09'
            ],
            'options' => [
                'threeDSecure' => [
                    'required' => true
                ]
            ]
        ]);
        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Transaction::THREE_D_SECURE, $result->transaction->gatewayRejectionReason);
    }

  public function testSale_withThreeDSecureOptionRequiredInSnakeCase()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::threeDSecureMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/09'
            ],
            'options' => [
                'three_d_secure' => [
                    'required' => true
                ]
            ]
        ]);
        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Transaction::THREE_D_SECURE, $result->transaction->gatewayRejectionReason);
    }

  public function testSale_withThreeDSecureToken()
    {
        $threeDSecureToken = Test\Helper::create3DSVerification(
            Test\Helper::threeDSecureMerchantAccountId(),
            [
                'number' => '4111111111111111',
                'expirationMonth' => '05',
                'expirationYear' => '2009'
            ]
        );
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::threeDSecureMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/09'
            ],
            'threeDSecureToken' => $threeDSecureToken
        ]);
        $this->assertTrue($result->success);
    }

  public function testSale_returnsErrorIfThreeDSecureToken()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::threeDSecureMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/09'
            ],
            'threeDSecureToken' => NULL
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('threeDSecureToken');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_THREE_D_SECURE_TOKEN_IS_INVALID,
            $errors[0]->code
        );
    }

  public function testSale_returnsErrorIf3dsLookupDataDoesNotMatchTransactionData()
    {
        $threeDSecureToken = Test\Helper::create3DSVerification(
            Test\Helper::threeDSecureMerchantAccountId(),
            [
                'number' => '4111111111111111',
                'expirationMonth' => '05',
                'expirationYear' => '2009'
            ]
        );

        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::threeDSecureMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/09'
            ],
            'threeDSecureToken' => $threeDSecureToken
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('threeDSecureToken');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_THREE_D_SECURE_TRANSACTION_DATA_DOESNT_MATCH_VERIFY,
            $errors[0]->code
        );
    }

  public function testSale_withThreeDSecurePassThru()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::threeDSecureMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/09'
            ],
            'threeDSecurePassThru' => [
                'eciFlag' => '02',
                'cavv' => 'some_cavv',
                'xid' => 'some_xid'
            ],
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $result->transaction->status);
    }

  public function testSale_returnsErrorsWhenThreeDSecurePassThruMerchantAcountDoesNotSupportCardType()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => 'adyen_ma',
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/09'
            ],
            'threeDSecurePassThru' => [
                'eciFlag' => '02',
                'cavv' => 'some_cavv',
                'xid' => 'some_xid'
            ],
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_THREE_D_SECURE_MERCHANT_ACCOUNT_DOES_NOT_SUPPORT_CARD_TYPE,
            $errors->onAttribute("merchantAccountId")[0]->code
        );
    }

  public function testSale_returnsErrorsWhenThreeDSecurePassThruIsMissingEciFlag()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::threeDSecureMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/09'
            ],
            'threeDSecurePassThru' => [
                'eciFlag' => '',
                'cavv' => 'some_cavv',
                'xid' => 'some_xid'
            ],
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->forKey('threeDSecurePassThru');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_THREE_D_SECURE_ECI_FLAG_IS_REQUIRED,
            $errors->onAttribute("eciFlag")[0]->code
        );
    }

  public function testSale_returnsErrorsWhenThreeDSecurePassThruIsMissingCavvOrXid()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::threeDSecureMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/09'
            ],
            'threeDSecurePassThru' => [
                'eciFlag' => '06',
                'cavv' => '',
                'xid' => ''
            ],
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->forKey('threeDSecurePassThru');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_THREE_D_SECURE_CAVV_IS_REQUIRED,
            $errors->onAttribute("cavv")[0]->code
        );
    }

  public function testSale_returnsErrorsWhenThreeDSecurePassThruEciFlagIsInvalid()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::threeDSecureMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/09'
            ],
            'threeDSecurePassThru' => [
                'eciFlag' => 'bad_eci_flag',
                'cavv' => 'some_cavv',
                'xid' => 'some_xid'
            ],
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->forKey('threeDSecurePassThru');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_THREE_D_SECURE_ECI_FLAG_IS_INVALID,
            $errors->onAttribute("eciFlag")[0]->code
        );
    }

  public function testHoldInEscrow_afterSale()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::nonDefaultSubMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'serviceFeeAmount' => '1.00'
        ]);
        $result = Braintree\Transaction::holdInEscrow($result->transaction->id);
        $this->assertTrue($result->success);
        $this->assertEquals(Braintree\Transaction::ESCROW_HOLD_PENDING, $result->transaction->escrowStatus);
    }

  public function testHoldInEscrow_afterSaleFailsWithMasterMerchantAccount()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::nonDefaultMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $result = Braintree\Transaction::holdInEscrow($result->transaction->id);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('base');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_CANNOT_HOLD_IN_ESCROW,
            $errors[0]->code
        );
    }

  public function testSubmitForRelease_FromEscrow()
    {
        $transaction = $this->createEscrowedTransaction();
        $result = Braintree\Transaction::releaseFromEscrow($transaction->id);
        $this->assertTrue($result->success);
        $this->assertEquals(Braintree\Transaction::ESCROW_RELEASE_PENDING, $result->transaction->escrowStatus);
    }

  public function testSubmitForRelease_fromEscrowFailsForTransactionsNotHeldInEscrow()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::nonDefaultMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $result = Braintree\Transaction::releaseFromEscrow($result->transaction->id);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('base');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_CANNOT_RELEASE_FROM_ESCROW,
            $errors[0]->code
        );
    }

  public function testCancelRelease_fromEscrow()
    {
        $transaction = $this->createEscrowedTransaction();
        $result = Braintree\Transaction::releaseFromEscrow($transaction->id);
        $result = Braintree\Transaction::cancelRelease($transaction->id);
        $this->assertTrue($result->success);
        $this->assertEquals(
            Braintree\Transaction::ESCROW_HELD,
            $result->transaction->escrowStatus
        );
    }

  public function testCancelRelease_fromEscrowFailsIfTransactionNotSubmittedForRelease()
    {
        $transaction = $this->createEscrowedTransaction();
        $result = Braintree\Transaction::cancelRelease($transaction->id);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('base');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_CANNOT_CANCEL_RELEASE,
            $errors[0]->code
        );
    }

  public function testCreateFromTransparentRedirect()
    {
        Test\Helper::suppressDeprecationWarnings();
        $queryString = $this->createTransactionViaTr(
            [
                'transaction' => [
                    'customer' => [
                        'first_name' => 'First'
                    ],
                    'credit_card' => [
                        'number' => '5105105105105100',
                        'expiration_date' => '05/12'
                    ]
                ]
            ],
            [
                'transaction' => [
                    'type' => Braintree\Transaction::SALE,
                    'amount' => '100.00'
                ]
            ]
        );
        $result = Braintree\Transaction::createFromTransparentRedirect($queryString);
        Test\Helper::assertPrintable($result);
        $this->assertTrue($result->success);
        $this->assertEquals('100.00', $result->transaction->amount);
        $this->assertEquals(Braintree\Transaction::SALE, $result->transaction->type);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $result->transaction->status);
        $creditCard = $result->transaction->creditCardDetails;
        $this->assertEquals('510510', $creditCard->bin);
        $this->assertEquals('5100', $creditCard->last4);
        $this->assertEquals('US', $creditCard->customerLocation);
        $this->assertEquals('MasterCard', $creditCard->cardType);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
        $this->assertEquals('510510******5100', $creditCard->maskedNumber);
        $customer = $result->transaction->customerDetails;
        $this->assertequals('First', $customer->firstName);
    }

  public function testCreateFromTransparentRedirectWithInvalidParams()
    {
        Test\Helper::suppressDeprecationWarnings();
        $queryString = $this->createTransactionViaTr(
            [
                'transaction' => [
                    'bad_key' => 'bad_value',
                    'customer' => [
                        'first_name' => 'First'
                    ],
                    'credit_card' => [
                        'number' => '5105105105105100',
                        'expiration_date' => '05/12'
                    ]
                ]
            ],
            [
                'transaction' => [
                    'type' => Braintree\Transaction::SALE,
                    'amount' => '100.00'
                ]
            ]
        );
        try {
            $result = Braintree\Transaction::createFromTransparentRedirect($queryString);
            $this->fail();
        } catch (Braintree\Exception\Authorization $e) {
            $this->assertEquals("Invalid params: transaction[bad_key]", $e->getMessage());
        }
    }

  public function testCreateFromTransparentRedirect_withParamsInTrData()
    {
        Test\Helper::suppressDeprecationWarnings();
        $queryString = $this->createTransactionViaTr(
            [
            ],
            [
                'transaction' => [
                    'type' => Braintree\Transaction::SALE,
                    'amount' => '100.00',
                    'customer' => [
                        'firstName' => 'First'
                    ],
                    'creditCard' => [
                        'number' => '5105105105105100',
                        'expirationDate' => '05/12'
                    ]
                ]
            ]
        );
        $result = Braintree\Transaction::createFromTransparentRedirect($queryString);
        $this->assertTrue($result->success);
        $this->assertEquals('100.00', $result->transaction->amount);
        $this->assertEquals(Braintree\Transaction::SALE, $result->transaction->type);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $result->transaction->status);
        $creditCard = $result->transaction->creditCardDetails;
        $this->assertEquals('510510', $creditCard->bin);
        $this->assertEquals('5100', $creditCard->last4);
        $this->assertEquals('US', $creditCard->customerLocation);
        $this->assertEquals('MasterCard', $creditCard->cardType);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
        $this->assertEquals('510510******5100', $creditCard->maskedNumber);
        $customer = $result->transaction->customerDetails;
        $this->assertequals('First', $customer->firstName);
    }

  public function testCreateFromTransparentRedirect_withValidationErrors()
    {
        Test\Helper::suppressDeprecationWarnings();
        $queryString = $this->createTransactionViaTr(
            [
                'transaction' => [
                    'customer' => [
                        'first_name' => str_repeat('x', 256),
                    ],
                    'credit_card' => [
                        'number' => 'invalid',
                        'expiration_date' => ''
                    ]
                ]
            ],
            [
                'transaction' => ['type' => Braintree\Transaction::SALE]
            ]
        );
        $result = Braintree\Transaction::createFromTransparentRedirect($queryString);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->forKey('customer')->onAttribute('firstName');
        $this->assertEquals(Braintree\Error\Codes::CUSTOMER_FIRST_NAME_IS_TOO_LONG, $errors[0]->code);
        $errors = $result->errors->forKey('transaction')->forKey('creditCard')->onAttribute('number');
        $this->assertTrue(count($errors) > 0);
        $errors = $result->errors->forKey('transaction')->forKey('creditCard')->onAttribute('expirationDate');
        $this->assertEquals(Braintree\Error\Codes::CREDIT_CARD_EXPIRATION_DATE_IS_REQUIRED, $errors[0]->code);
    }

  public function testRefund()
    {
        $transaction = $this->createTransactionToRefund();
        $result = Braintree\Transaction::refund($transaction->id);
        $this->assertTrue($result->success);
        $refund = $result->transaction;
        $this->assertEquals(Braintree\Transaction::CREDIT, $refund->type);
        $this->assertEquals($transaction->id, $refund->refundedTransactionId);
        $this->assertEquals($refund->id, Braintree\Transaction::find($transaction->id)->refundId);
    }

  public function testRefundWithPartialAmount()
    {
        $transaction = $this->createTransactionToRefund();
        $result = Braintree\Transaction::refund($transaction->id, '50.00');
        $this->assertTrue($result->success);
        $this->assertEquals(Braintree\Transaction::CREDIT, $result->transaction->type);
        $this->assertEquals("50.00", $result->transaction->amount);
    }

  public function testMultipleRefundsWithPartialAmounts()
    {
        $transaction = $this->createTransactionToRefund();

        $transaction1 = Braintree\Transaction::refund($transaction->id, '50.00')->transaction;
        $this->assertEquals(Braintree\Transaction::CREDIT, $transaction1->type);
        $this->assertEquals("50.00", $transaction1->amount);

        $transaction2 = Braintree\Transaction::refund($transaction->id, '50.00')->transaction;
        $this->assertEquals(Braintree\Transaction::CREDIT, $transaction2->type);
        $this->assertEquals("50.00", $transaction2->amount);

        $transaction = Braintree\Transaction::find($transaction->id);

        $expectedRefundIds = [$transaction1->id, $transaction2->id];
        $refundIds = $transaction->refundIds;
        sort($expectedRefundIds);
        sort($refundIds);

        $this->assertEquals($expectedRefundIds, $refundIds);
    }

  public function testRefundWithUnsuccessfulPartialAmount()
    {
        $transaction = $this->createTransactionToRefund();
        $result = Braintree\Transaction::refund($transaction->id, '150.00');
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->onAttribute('amount');
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_REFUND_AMOUNT_IS_TOO_LARGE,
            $errors[0]->code
        );
    }

  public function testRefundWithOptionsParam()
    {
        $transaction = $this->createTransactionToRefund();
        $options = [
            "orderId" => 'abcd',
            "amount" => '1.00'
        ];
        $result = Braintree\Transaction::refund($transaction->id, $options);
        $this->assertTrue($result->success);
        $this->assertEquals(
            'abcd',
            $result->transaction->orderId
        );
        $this->assertEquals(
            '1.00',
            $result->transaction->amount
        );
    }

  public function testGatewayRejectionOnApplicationIncomplete()
    {
        $gateway = new Braintree\Gateway([
            'clientId' => 'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret'
        ]);

        $result = $gateway->merchant()->create([
            'email' => 'name@email.com',
            'countryCodeAlpha3' => 'USA',
            'paymentMethods' => ['credit_card', 'paypal']
        ]);

        $gateway = new Braintree\Gateway([
            'accessToken' => $result->credentials->accessToken,
        ]);

        $result = $gateway->transaction()->sale([
            'amount' => '4000.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/20'
            ]
        ]);
        $this->assertFalse($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::APPLICATION_INCOMPLETE, $transaction->gatewayRejectionReason);
    }

  public function testGatewayRejectionOnAvs()
    {
        $old_merchant_id = Braintree\Configuration::merchantId();
        $old_public_key = Braintree\Configuration::publicKey();
        $old_private_key = Braintree\Configuration::privateKey();

        Braintree\Configuration::merchantId('processing_rules_merchant_id');
        Braintree\Configuration::publicKey('processing_rules_public_key');
        Braintree\Configuration::privateKey('processing_rules_private_key');

        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'billing' => [
                'streetAddress' => '200 2nd Street'
            ],
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        Braintree\Configuration::merchantId($old_merchant_id);
        Braintree\Configuration::publicKey($old_public_key);
        Braintree\Configuration::privateKey($old_private_key);

        $this->assertFalse($result->success);
        Test\Helper::assertPrintable($result);
        $transaction = $result->transaction;

        $this->assertEquals(Braintree\Transaction::AVS, $transaction->gatewayRejectionReason);
    }

  public function testGatewayRejectionOnAvsAndCvv()
    {
        $old_merchant_id = Braintree\Configuration::merchantId();
        $old_public_key = Braintree\Configuration::publicKey();
        $old_private_key = Braintree\Configuration::privateKey();

        Braintree\Configuration::merchantId('processing_rules_merchant_id');
        Braintree\Configuration::publicKey('processing_rules_public_key');
        Braintree\Configuration::privateKey('processing_rules_private_key');

        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'billing' => [
                'postalCode' => '20000'
            ],
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cvv' => '200'
            ]
        ]);

        Braintree\Configuration::merchantId($old_merchant_id);
        Braintree\Configuration::publicKey($old_public_key);
        Braintree\Configuration::privateKey($old_private_key);

        $this->assertFalse($result->success);
        $transaction = $result->transaction;

        $this->assertEquals(Braintree\Transaction::AVS_AND_CVV, $transaction->gatewayRejectionReason);
    }

  public function testGatewayRejectionOnCvv()
    {
        $old_merchant_id = Braintree\Configuration::merchantId();
        $old_public_key = Braintree\Configuration::publicKey();
        $old_private_key = Braintree\Configuration::privateKey();

        Braintree\Configuration::merchantId('processing_rules_merchant_id');
        Braintree\Configuration::publicKey('processing_rules_public_key');
        Braintree\Configuration::privateKey('processing_rules_private_key');

        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cvv' => '200'
            ]
        ]);

        Braintree\Configuration::merchantId($old_merchant_id);
        Braintree\Configuration::publicKey($old_public_key);
        Braintree\Configuration::privateKey($old_private_key);

        $this->assertFalse($result->success);
        $transaction = $result->transaction;

        $this->assertEquals(Braintree\Transaction::CVV, $transaction->gatewayRejectionReason);
    }

  public function testGatewayRejectionOnFraud()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '4000111111111511',
                'expirationDate' => '05/17',
                'cvv' => '333'
            ]
        ]);

        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Transaction::FRAUD, $result->transaction->gatewayRejectionReason);
    }

  public function testSnapshotPlanIdAddOnsAndDiscountsFromSubscription()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'addOns' => [
                'add' => [
                    [
                        'amount' => '11.00',
                        'inheritedFromId' => 'increase_10',
                        'quantity' => 2,
                        'numberOfBillingCycles' => 5
                    ],
                    [
                        'amount' => '21.00',
                        'inheritedFromId' => 'increase_20',
                        'quantity' => 3,
                        'numberOfBillingCycles' => 6
                    ]
                ],
            ],
            'discounts' => [
                'add' => [
                    [
                        'amount' => '7.50',
                        'inheritedFromId' => 'discount_7',
                        'quantity' => 2,
                        'neverExpires' => true
                    ]
                ]
            ]
        ]);

        $transaction = $result->subscription->transactions[0];

        $this->assertEquals($transaction->planId, $plan['id']);

        $addOns = $transaction->addOns;
        SubscriptionHelper::sortModificationsById($addOns);

        $this->assertEquals($addOns[0]->amount, "11.00");
        $this->assertEquals($addOns[0]->id, "increase_10");
        $this->assertEquals($addOns[0]->quantity, 2);
        $this->assertEquals($addOns[0]->numberOfBillingCycles, 5);
        $this->assertFalse($addOns[0]->neverExpires);

        $this->assertEquals($addOns[1]->amount, "21.00");
        $this->assertEquals($addOns[1]->id, "increase_20");
        $this->assertEquals($addOns[1]->quantity, 3);
        $this->assertEquals($addOns[1]->numberOfBillingCycles, 6);
        $this->assertFalse($addOns[1]->neverExpires);

        $discounts = $transaction->discounts;
        $this->assertEquals($discounts[0]->amount, "7.50");
        $this->assertEquals($discounts[0]->id, "discount_7");
        $this->assertEquals($discounts[0]->quantity, 2);
        $this->assertEquals($discounts[0]->numberOfBillingCycles, null);
        $this->assertTrue($discounts[0]->neverExpires);
    }

  public function createTransactionViaTr($regularParams, $trParams)
    {
        Test\Helper::suppressDeprecationWarnings();
        $trData = Braintree\TransparentRedirect::transactionData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );
        return Test\Helper::submitTrRequest(
            Braintree\Transaction::createTransactionUrl(),
            $regularParams,
            $trData
        );
    }

  public function createTransactionToRefund()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => ['submitForSettlement' => true]
        ]);
        Braintree\Test\Transaction::settle($transaction->id);
        return $transaction;
    }

  public function createEscrowedTransaction()
    {
        $result = Braintree\Transaction::sale([
            'merchantAccountId' => Test\Helper::nonDefaultSubMerchantAccountId(),
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => [
                'holdInEscrow' => true
            ],
            'serviceFeeAmount' => '1.00'
        ]);
        Test\Helper::escrow($result->transaction->id);
        return $result->transaction;
    }

  public function testCardTypeIndicators()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => CardTypeIndicators::PREPAID,
                'expirationDate' => '05/12',
            ]
        ]);

        $this->assertEquals(Braintree\CreditCard::PREPAID_YES, $transaction->creditCardDetails->prepaid);

        $prepaid_card_transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => CardTypeIndicators::COMMERCIAL,
                'expirationDate' => '05/12',
            ]
        ]);

        $this->assertEquals(Braintree\CreditCard::COMMERCIAL_YES, $prepaid_card_transaction->creditCardDetails->commercial);

        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => CardTypeIndicators::PAYROLL,
                'expirationDate' => '05/12',
            ]
        ]);

        $this->assertEquals(Braintree\CreditCard::PAYROLL_YES, $transaction->creditCardDetails->payroll);
        $this->assertEquals("MSA", $transaction->creditCardDetails->productId);

        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => CardTypeIndicators::HEALTHCARE,
                'expirationDate' => '05/12',
            ]
        ]);

        $this->assertEquals(Braintree\CreditCard::HEALTHCARE_YES, $transaction->creditCardDetails->healthcare);
        $this->assertEquals("J3", $transaction->creditCardDetails->productId);

        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => CardTypeIndicators::DURBIN_REGULATED,
                'expirationDate' => '05/12',
            ]
        ]);

        $this->assertEquals(Braintree\CreditCard::DURBIN_REGULATED_YES, $transaction->creditCardDetails->durbinRegulated);

        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => CardTypeIndicators::DEBIT,
                'expirationDate' => '05/12',
            ]
        ]);

        $this->assertEquals(Braintree\CreditCard::DEBIT_YES, $transaction->creditCardDetails->debit);

        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => CardTypeIndicators::ISSUING_BANK,
                'expirationDate' => '05/12',
            ]
        ]);

        $this->assertEquals("NETWORK ONLY", $transaction->creditCardDetails->issuingBank);

        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => CardTypeIndicators::COUNTRY_OF_ISSUANCE,
                'expirationDate' => '05/12',
            ]
        ]);

        $this->assertEquals("USA", $transaction->creditCardDetails->countryOfIssuance);
    }


  public function testCreate_withVaultedPayPal()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $customer = Braintree\Customer::createNoValidate();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce
        ]);
        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodToken' => $paymentMethodToken,
        ]);
        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
    }

  public function testCreate_withFuturePayPal()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withLocalPaymentWebhookContent()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'options' => [
                'submitForSettlement' => True,
            ],
            'paypalAccount' => [
                'paymentId' => 'PAY-1234',
                'payerId' => 'PAYER-1234',
            ],
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('PAY-1234', $transaction->paypalDetails->paymentId);
        $this->assertEquals('PAYER-1234', $transaction->paypalDetails->payerId);
    }

  public function testCreate_withPayeeId()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'paypalAccount' => [
                'payeeId' => 'fake-payee-id'
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->assertNotNull($transaction->paypalDetails->payeeId);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withPayeeIdInOptions()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'paypalAccount' => [],
            'options' => [
                'payeeId' => 'fake-payee-id'
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->assertNotNull($transaction->paypalDetails->payeeId);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withPayeeIdInOptionsPayPal()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'paypalAccount' => [],
            'options' => [
                'paypal' => [
                    'payeeId' => 'fake-payee-id'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->assertNotNull($transaction->paypalDetails->payeeId);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withPayeeEmail()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'paypalAccount' => [
                'payeeEmail' => 'payee@example.com'
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->assertNotNull($transaction->paypalDetails->payeeEmail);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withPayeeEmailInOptions()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'paypalAccount' => [],
            'options' => [
                'payeeEmail' => 'payee@example.com'
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->assertNotNull($transaction->paypalDetails->payeeEmail);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withPayeeEmailInOptionsPayPal()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'paypalAccount' => [],
            'options' => [
                'paypal' => [
                    'payeeEmail' => 'payee@example.com'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->assertNotNull($transaction->paypalDetails->payeeEmail);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withPayPalCustomField()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'paypalAccount' => [],
            'options' => [
                'paypal' => [
                    'customField' => 'custom field stuff'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('custom field stuff', $transaction->paypalDetails->customField);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withPayPalSupplementaryData()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'paypalAccount' => [],
            'options' => [
                'paypal' => [
                    'supplementaryData' => [
                        'key1' => 'value',
                        'key2' => 'value'
                    ]
                ]
            ]
        ]);

        // note - supplementary data is not returned in response
        $this->assertTrue($result->success);
    }

  public function testCreate_withPayPalDescription()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'paypalAccount' => [],
            'options' => [
                'paypal' => [
                    'description' => 'Product description'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('Product description', $transaction->paypalDetails->description);
    }

  public function testCreate_withPayPalReturnsPaymentInstrumentType()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\PaymentInstrumentType::PAYPAL_ACCOUNT, $transaction->paymentInstrumentType);
        $this->assertNotNull($transaction->paypalDetails->debugId);
    }

  public function testCreate_withFuturePayPalAndVault()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'storeInVault' => true
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $foundPayPalAccount = Braintree\PaymentMethod::find($paymentMethodToken);
        $this->assertEquals($paymentMethodToken, $foundPayPalAccount->token);
    }

  public function testCreate_withOnetimePayPal()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'access_token' => 'PAYPAL_ACCESS_TOKEN',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withOnetimePayPalAndDoesNotVault()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'access_token' => 'PAYPAL_ACCESS_TOKEN',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'storeInVault' => true
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
        $this->assertNotNull($transaction->paypalDetails->imageUrl);
        $this->assertNotNull($transaction->paypalDetails->debugId);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethod::find($paymentMethodToken);
    }

  public function testCreate_withPayPalAndSubmitForSettlement()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;
        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::SETTLING, $transaction->status);
    }

  public function testCreate_withPayPalHandlesBadUnvalidatedNonces()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'access_token' => 'PAYPAL_ACCESS_TOKEN',
                'consent_code' => 'PAYPAL_CONSENT_CODE'
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->forKey('paypalAccount')->errors;
        $this->assertEquals(Braintree\Error\Codes::PAYPAL_ACCOUNT_CANNOT_HAVE_BOTH_ACCESS_TOKEN_AND_CONSENT_CODE, $errors[0]->code);
    }

  public function testCreate_withPayPalHandlesNonExistentNonces()
    {
        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => 'NON_EXISTENT_NONCE',
            'options' => [
                'submitForSettlement' => true
            ]
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->errors;
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_PAYMENT_METHOD_NONCE_UNKNOWN, $errors[0]->code);
    }

  public function testVoid_withPayPal()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);
        $voided_transaction = Braintree\Transaction::voidNoValidate($result->transaction->id);
        $this->assertEquals(Braintree\Transaction::VOIDED, $voided_transaction->status);
    }

  public function testVoid_failsOnDeclinedPayPal()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$decline,
            'paymentMethodNonce' => $nonce
        ]);
        $this->setExpectedException('Braintree\Exception\ValidationsFailed');
        Braintree\Transaction::voidNoValidate($result->transaction->id);
    }

  public function testRefund_withPayPal()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $transactionResult = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $this->assertTrue($transactionResult->success);
        Braintree\Test\Transaction::settle($transactionResult->transaction->id);

        $result = Braintree\Transaction::refund($transactionResult->transaction->id);
        $this->assertTrue($result->success);
        $this->assertEquals($result->transaction->type, Braintree\Transaction::CREDIT);
    }

  public function testRefund_withPayPalAssignsRefundId()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $transactionResult = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $this->assertTrue($transactionResult->success);
        $originalTransaction = $transactionResult->transaction;
        Braintree\Test\Transaction::settle($transactionResult->transaction->id);

        $result = Braintree\Transaction::refund($transactionResult->transaction->id);
        $this->assertTrue($result->success);
        $refundTransaction = $result->transaction;
        $updatedOriginalTransaction = Braintree\Transaction::find($originalTransaction->id);
        $this->assertEquals($refundTransaction->id, $updatedOriginalTransaction->refundId);
    }

  public function testRefund_withPayPalAssignsRefundedTransactionId()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $transactionResult = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $this->assertTrue($transactionResult->success);
        $originalTransaction = $transactionResult->transaction;
        Braintree\Test\Transaction::settle($transactionResult->transaction->id);

        $result = Braintree\Transaction::refund($transactionResult->transaction->id);
        $this->assertTrue($result->success);
        $refundTransaction = $result->transaction;
        $this->assertEquals($refundTransaction->refundedTransactionId, $originalTransaction->id);
    }

  public function testRefund_withPayPalFailsIfNotSettled()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $transactionResult = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
        ]);

        $this->assertTrue($transactionResult->success);

        $result = Braintree\Transaction::refund($transactionResult->transaction->id);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('transaction')->errors;
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_CANNOT_REFUND_UNLESS_SETTLED, $errors[0]->code);
    }

  public function testRefund_partialWithPayPal()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $transactionResult = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $this->assertTrue($transactionResult->success);
        Braintree\Test\Transaction::settle($transactionResult->transaction->id);

        $result = Braintree\Transaction::refund(
            $transactionResult->transaction->id,
            $transactionResult->transaction->amount / 2
        );

        $this->assertTrue($result->success);
        $this->assertEquals($result->transaction->type, Braintree\Transaction::CREDIT);
        $this->assertEquals($result->transaction->amount, $transactionResult->transaction->amount / 2);
    }

  public function testRefund_multiplePartialWithPayPal()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $transactionResult = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $this->assertTrue($transactionResult->success);
        $originalTransaction = $transactionResult->transaction;
        Braintree\Test\Transaction::settle($originalTransaction->id);

        $firstRefund = Braintree\Transaction::refund(
            $transactionResult->transaction->id,
            $transactionResult->transaction->amount / 2
        );
        $this->assertTrue($firstRefund->success);
        $firstRefundTransaction = $firstRefund->transaction;

        $secondRefund = Braintree\Transaction::refund(
            $transactionResult->transaction->id,
            $transactionResult->transaction->amount / 2
        );
        $this->assertTrue($secondRefund->success);
        $secondRefundTransaction = $secondRefund->transaction;


        $updatedOriginalTransaction = Braintree\Transaction::find($originalTransaction->id);
        $expectedRefundIds = [$secondRefundTransaction->id, $firstRefundTransaction->id];

        $updatedRefundIds = $updatedOriginalTransaction->refundIds;

        $this->assertTrue(in_array($expectedRefundIds[0],$updatedRefundIds));
        $this->assertTrue(in_array($expectedRefundIds[1],$updatedRefundIds));
    }

  public function testIncludeProcessorSettlementResponseForSettlementDeclinedTransaction()
    {
        $result = Braintree\Transaction::sale([
            "paymentMethodNonce" => Braintree\Test\Nonces::$visaCheckoutVisa,
            "amount" => "100",
            "options" => [
                "submitForSettlement" => true
            ]
        ]);

        $this->assertTrue($result->success);

        $transaction = $result->transaction;
        Braintree\Test\Transaction::settlementDecline($transaction->id);

        $inline_transaction = Braintree\Transaction::find($transaction->id);
        $this->assertEquals($inline_transaction->status, Braintree\Transaction::SETTLEMENT_DECLINED);
        $this->assertEquals($inline_transaction->processorSettlementResponseCode, "4001");
        $this->assertEquals($inline_transaction->processorSettlementResponseText, "Settlement Declined");
    }

  public function testIncludeProcessorSettlementResponseForSettlementPendingTransaction()
    {
        $result = Braintree\Transaction::sale([
            "paymentMethodNonce" => Braintree\Test\Nonces::$visaCheckoutVisa,
            "amount" => "100",
            "options" => [
                "submitForSettlement" => true
            ]
        ]);

        $this->assertTrue($result->success);

        $transaction = $result->transaction;
        Braintree\Test\Transaction::settlementPending($transaction->id);

        $inline_transaction = Braintree\Transaction::find($transaction->id);
        $this->assertEquals($inline_transaction->status, Braintree\Transaction::SETTLEMENT_PENDING);
        $this->assertEquals($inline_transaction->processorSettlementResponseCode, "4002");
        $this->assertEquals($inline_transaction->processorSettlementResponseText, "Settlement Pending");
    }

  public function testSale_withLodgingIndustryData()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'industry' => [
                'industryType' => Braintree\Transaction::LODGING_INDUSTRY,
                'data' => [
                    'folioNumber' => 'aaa',
                    'checkInDate' => '2014-07-07',
                    'checkOutDate' => '2014-07-09',
                    'roomRate' => '239.00'
                ]
            ]
        ]);
        $this->assertTrue($result->success);
    }

  public function testSale_withLodgingIndustryDataValidation()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'industry' => [
                'industryType' => Braintree\Transaction::LODGING_INDUSTRY,
                'data' => [
                    'folioNumber' => 'aaa',
                    'checkInDate' => '2014-07-07',
                    'checkOutDate' => '2014-06-09',
                    'roomRate' => '239.00'
                ]
            ]
        ]);
        $this->assertFalse($result->success);
        $transaction = $result->transaction;

        $errors = $result->errors->forKey('transaction')->forKey('industry')->onAttribute('checkOutDate');
        $this->assertEquals(Braintree\Error\Codes::INDUSTRY_DATA_LODGING_CHECK_OUT_DATE_MUST_FOLLOW_CHECK_IN_DATE, $errors[0]->code);
    }

  public function testSale_withTravelCruiseIndustryData()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'industry' => [
                'industryType' => Braintree\Transaction::TRAVEL_AND_CRUISE_INDUSTRY,
                'data' => [
                    'travelPackage' => 'flight',
                    'departureDate' => '2014-07-07',
                    'lodgingCheckInDate' => '2014-07-09',
                    'lodgingCheckOutDate' => '2014-07-10',
                    'lodgingName' => 'Disney',
                ]
            ]
        ]);
        $this->assertTrue($result->success);
    }

  public function testSale_withTravelCruiseIndustryDataValidation()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'industry' => [
                'industryType' => Braintree\Transaction::TRAVEL_AND_CRUISE_INDUSTRY,
                'data' => [
                    'travelPackage' => 'invalid',
                    'departureDate' => '2014-07-07',
                    'lodgingCheckInDate' => '2014-07-09',
                    'lodgingCheckOutDate' => '2014-07-10',
                    'lodgingName' => 'Disney',
                ]
            ]
        ]);
        $this->assertFalse($result->success);
        $transaction = $result->transaction;

        $errors = $result->errors->forKey('transaction')->forKey('industry')->onAttribute('travelPackage');
        $this->assertEquals(Braintree\Error\Codes::INDUSTRY_DATA_TRAVEL_CRUISE_TRAVEL_PACKAGE_IS_INVALID, $errors[0]->code);
    }

  public function testSale_withTravelFlightIndustryData()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$paypalOneTimePayment,
            'options' => ['submitForSettlement' => true],
            'industry' => [
                'industryType' => Braintree\Transaction::TRAVEL_AND_FLIGHT_INDUSTRY,
                'data' => [
                    'passengerFirstName' => 'John',
                    'passengerLastName' => 'Doe',
                    'passengerMiddleInitial' => 'M',
                    'passengerTitle' => 'Mr.',
                    'issuedDate' => '2018-01-01',
                    'travelAgencyName' => 'Expedia',
                    'travelAgencyCode' => '12345678',
                    'ticketNumber' => 'ticket-number',
                    'issuingCarrierCode' => 'AA',
                    'customerCode' => 'customer-code',
                    'fareAmount' => '70.00',
                    'feeAmount' => '10.00',
                    'taxAmount' => '20.00',
                    'restrictedTicket' => false,
                    'legs' => [
                        [
                            'conjunctionTicket' => 'CJ0001',
                            'exchangeTicket' => 'ET0001',
                            'couponNumber' => '1',
                            'serviceClass' => 'Y',
                            'carrierCode' => 'AA',
                            'fareBasisCode' => 'W',
                            'flightNumber' => 'AA100',
                            'departureDate' => '2018-01-02',
                            'departureAirportCode' => 'MDW',
                            'departureTime' => '08:00',
                            'arrivalAirportCode' => 'ATX',
                            'arrivalTime' => '10:00',
                            'stopoverPermitted' => false,
                            'fareAmount' => '35.00',
                            'feeAmount' => '5.00',
                            'taxAmount' => '10.00',
                            'endorsementOrRestrictions' => 'NOT REFUNDABLE'
                        ],
                        [
                            'conjunctionTicket' => 'CJ0002',
                            'exchangeTicket' => 'ET0002',
                            'couponNumber' => '1',
                            'serviceClass' => 'Y',
                            'carrierCode' => 'AA',
                            'fareBasisCode' => 'W',
                            'flightNumber' => 'AA200',
                            'departureDate' => '2018-01-03',
                            'departureAirportCode' => 'ATX',
                            'departureTime' => '12:00',
                            'arrivalAirportCode' => 'MDW',
                            'arrivalTime' => '14:00',
                            'stopoverPermitted' => false,
                            'fareAmount' => '35.00',
                            'feeAmount' => '5.00',
                            'taxAmount' => '10.00',
                            'endorsementOrRestrictions' => 'NOT REFUNDABLE'
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertTrue($result->success);
    }

  public function testSale_withTravelFlightIndustryDataValidation()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$paypalOneTimePayment,
            'options' => ['submitForSettlement' => true],
            'industry' => [
                'industryType' => Braintree\Transaction::TRAVEL_AND_FLIGHT_INDUSTRY,
                'data' => [
                    'fareAmount' => '-1.23',
                    'legs' => [
                        [
                            'fareAmount' => '-1.23'
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertFalse($result->success);
        $transaction = $result->transaction;

        $this->assertEquals(
            Braintree\Error\Codes::INDUSTRY_DATA_TRAVEL_FLIGHT_FARE_AMOUNT_CANNOT_BE_NEGATIVE,
            $result->errors->forKey('transaction')->forKey('industry')->onAttribute('fareAmount')[0]->code
        );
        $this->assertEquals(
            Braintree\Error\Codes::INDUSTRY_DATA_LEG_TRAVEL_FLIGHT_FARE_AMOUNT_CANNOT_BE_NEGATIVE,
            $result->errors->forKey('transaction')->forKey('industry')->forKey('legs')->forKey('index0')->onAttribute('fareAmount')[0]->code
        );
    }

  public function testSale_withAmexRewardsSucceeds()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeAmexDirectMerchantAccountId(),
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$amexPayWithPoints['Success'],
                'expirationDate' => '05/12'
            ],
            'options' => [
                'submitForSettlement' => true,
                'amexRewards' => [
                    'requestId' => 'ABC123',
                    'points' => '100',
                    'currencyAmount' => '1.00',
                    'currencyIsoCode' => 'USD'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
    }

  public function testSale_withAmexRewardsSucceedsEvenIfCardIsIneligible()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeAmexDirectMerchantAccountId(),
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$amexPayWithPoints['IneligibleCard'],
                'expirationDate' => '05/12'
            ],
            'options' => [
                'submitForSettlement' => true,
                'amexRewards' => [
                    'requestId' => 'ABC123',
                    'points' => '100',
                    'currencyAmount' => '1.00',
                    'currencyIsoCode' => 'USD'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
    }

  public function testSale_withAmexRewardsSucceedsEvenIfCardBalanceIsInsufficient()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeAmexDirectMerchantAccountId(),
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$amexPayWithPoints['InsufficientPoints'],
                'expirationDate' => '05/12'
            ],
            'options' => [
                'submitForSettlement' => true,
                'amexRewards' => [
                    'requestId' => 'ABC123',
                    'points' => '100',
                    'currencyAmount' => '1.00',
                    'currencyIsoCode' => 'USD'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
    }

  public function testSubmitForSettlement_withAmexRewardsSucceeds()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeAmexDirectMerchantAccountId(),
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$amexPayWithPoints['Success'],
                'expirationDate' => '05/12'
            ],
            'options' => [
                'amexRewards' => [
                    'requestId' => 'ABC123',
                    'points' => '100',
                    'currencyAmount' => '1.00',
                    'currencyIsoCode' => 'USD'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);

        $submitResult = Braintree\Transaction::submitForSettlement($transaction->id, '47.00');
        $submitTransaction = $submitResult->transaction;
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submitTransaction->status);
    }

  public function testSubmitForSettlement_withAmexRewardsSucceedsEvenIfCardIsIneligible()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeAmexDirectMerchantAccountId(),
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$amexPayWithPoints['IneligibleCard'],
                'expirationDate' => '05/12'
            ],
            'options' => [
                'amexRewards' => [
                    'requestId' => 'ABC123',
                    'points' => '100',
                    'currencyAmount' => '1.00',
                    'currencyIsoCode' => 'USD'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);

        $submitResult = Braintree\Transaction::submitForSettlement($transaction->id, '47.00');
        $submitTransaction = $submitResult->transaction;
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submitTransaction->status);
    }

  public function testSubmitForSettlement_withAmexRewardsSucceedsEvenIfCardBalanceIsInsufficient()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'merchantAccountId' => Test\Helper::fakeAmexDirectMerchantAccountId(),
            'creditCard' => [
                'cardholderName' => 'The Cardholder',
                'number' => Braintree\Test\CreditCardNumbers::$amexPayWithPoints['InsufficientPoints'],
                'expirationDate' => '05/12'
            ],
            'options' => [
                'amexRewards' => [
                    'requestId' => 'ABC123',
                    'points' => '100',
                    'currencyAmount' => '1.00',
                    'currencyIsoCode' => 'USD'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);

        $submitResult = Braintree\Transaction::submitForSettlement($transaction->id, '47.00');
        $submitTransaction = $submitResult->transaction;
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submitTransaction->status);
    }

  public function testSubmitForPartialSettlement()
    {
        $authorizedTransaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $authorizedTransaction->status);
        $partialSettlementResult1 = Braintree\Transaction::submitForPartialSettlement($authorizedTransaction->id, '60.00');
        $this->assertTrue($partialSettlementResult1->success);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $partialSettlementResult1->transaction->status);
        $this->assertEquals('60.00', $partialSettlementResult1->transaction->amount);

        $partialSettlementResult2 = Braintree\Transaction::submitForPartialSettlement($authorizedTransaction->id, '40.00');
        $this->assertTrue($partialSettlementResult2->success);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $partialSettlementResult2->transaction->status);
        $this->assertEquals('40.00', $partialSettlementResult2->transaction->amount);

        $refreshedAuthorizedTransaction = Braintree\Transaction::find($authorizedTransaction->id);
        $this->assertEquals(2, count($refreshedAuthorizedTransaction->partialSettlementTransactionIds));
    }

  public function testSubmitForPartialSettlementUnsuccesful()
    {
        $authorizedTransaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $authorizedTransaction->status);
        $partialSettlementResult1 = Braintree\Transaction::submitForPartialSettlement($authorizedTransaction->id, '60.00');
        $this->assertTrue($partialSettlementResult1->success);

        $partialSettlementResult2 = Braintree\Transaction::submitForPartialSettlement($partialSettlementResult1->transaction->id, '10.00');
        $this->assertFalse($partialSettlementResult2->success);
        $baseErrors = $partialSettlementResult2->errors->forKey('transaction')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::TRANSACTION_CANNOT_SUBMIT_FOR_PARTIAL_SETTLEMENT, $baseErrors[0]->code);
    }

  public function testSubmitForPartialSettlement_withOrderId()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $submitResult = Braintree\Transaction::submitForPartialSettlement($transaction->id, '67.00', ['orderId' => 'ABC123']);
        $this->assertEquals(true, $submitResult->success);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submitResult->transaction->status);
        $this->assertEquals('ABC123', $submitResult->transaction->orderId);
        $this->assertEquals('67.00', $submitResult->transaction->amount);
    }

  public function testSubmitForPartialSettlement_withDescriptor()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        $params = [
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555',
                'url' => 'ebay.com'
            ]
        ];

        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
        $submitResult = Braintree\Transaction::submitForPartialSettlement($transaction->id, '67.00', $params);
        $this->assertEquals(true, $submitResult->success);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $submitResult->transaction->status);
        $this->assertEquals('123*123456789012345678', $submitResult->transaction->descriptor->name);
        $this->assertEquals('3334445555', $submitResult->transaction->descriptor->phone);
        $this->assertEquals('ebay.com', $submitResult->transaction->descriptor->url);
    }

  public function testSubmitForPartialSettlement_withInvalidParams()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        $params = ['invalid' => 'invalid'];

        $this->setExpectedException('InvalidArgumentException', 'invalid keys: invalid');
        Braintree\Transaction::submitForPartialSettlement($transaction->id, '67.00', $params);
    }

    public function testFacilitatedAndFacilitatorDetailsAreReturnedOnTransactionsCreatedViaNonceGranting()
    {
        $partnerMerchantGateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_public_id',
            'publicKey' => 'oauth_app_partner_user_public_key',
            'privateKey' => 'oauth_app_partner_user_private_key'
        ]);

        $customer = $partnerMerchantGateway->customer()->create([
            'firstName' => 'Joe',
            'lastName' => 'Brown'
        ])->customer;
        $creditCard = $partnerMerchantGateway->creditCard()->create([
            'customerId' => $customer->id,
            'cardholderName' => 'Adam Davis',
            'number' => '4111111111111111',
            'expirationDate' => '05/2009'
        ])->creditCard;

        $oauthAppGateway = new Braintree\Gateway([
            'clientId' =>  'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret'
        ]);

        $code = Test\Braintree\OAuthTestHelper::createGrant($oauthAppGateway, [
            'merchant_public_id' => 'integration_merchant_id',
            'scope' => 'grant_payment_method'
        ]);

        $credentials = $oauthAppGateway->oauth()->createTokenFromCode([
            'code' => $code,
        ]);

        $grantingGateway = new Braintree\Gateway([
            'accessToken' => $credentials->accessToken
        ]);

        $grantResult = $grantingGateway->paymentMethod()->grant($creditCard->token, false);

        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'paymentMethodNonce' => $grantResult->paymentMethodNonce->nonce
        ]);

        $this->assertEquals(
            $result->transaction->facilitatedDetails->merchantId,
            'integration_merchant_id'
        );
        $this->assertEquals(
            $result->transaction->facilitatedDetails->merchantName,
            '14ladders'
        );
        $this->assertEquals(
            $result->transaction->facilitatedDetails->paymentMethodNonce,
            $grantResult->paymentMethodNonce->nonce
        );

        $this->assertEquals(
            $result->transaction->facilitatorDetails->oauthApplicationClientId,
            "client_id\$development\$integration_client_id"
        );
        $this->assertEquals(
            $result->transaction->facilitatorDetails->oauthApplicationName,
            "PseudoShop"
        );

        $this->assertNull($result->transaction->billing["postalCode"]);
    }

    public function testBillingPostalCodeIsReturnedWhenRequestedOnTransactionsCreatedViaNonceGranting()
    {
        $partnerMerchantGateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_public_id',
            'publicKey' => 'oauth_app_partner_user_public_key',
            'privateKey' => 'oauth_app_partner_user_private_key'
        ]);

        $customer = $partnerMerchantGateway->customer()->create([
            'firstName' => 'Joe',
            'lastName' => 'Brown'
        ])->customer;
        $creditCard = $partnerMerchantGateway->creditCard()->create([
            'customerId' => $customer->id,
            'cardholderName' => 'Adam Davis',
            'number' => '4111111111111111',
            'expirationDate' => '05/2009',
            'billingAddress' => [
                'firstName' => 'Adam',
                'lastName' => 'Davis',
                'postalCode' => '95131'
            ]
        ])->creditCard;

        $oauthAppGateway = new Braintree\Gateway([
            'clientId' =>  'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret'
        ]);

        $code = Test\Braintree\OAuthTestHelper::createGrant($oauthAppGateway, [
            'merchant_public_id' => 'integration_merchant_id',
            'scope' => 'grant_payment_method'
        ]);

        $credentials = $oauthAppGateway->oauth()->createTokenFromCode([
            'code' => $code,
        ]);

        $grantingGateway = new Braintree\Gateway([
            'accessToken' => $credentials->accessToken
        ]);

        $grantResult = $grantingGateway->paymentMethod()->grant($creditCard->token, ['allow_vaulting' => false, 'include_billing_postal_code' => true]);

        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'paymentMethodNonce' => $grantResult->paymentMethodNonce->nonce
        ]);

        $this->assertEquals($result->transaction->billing["postalCode"], "95131");
    }

    public function testTransactionsCanBeCreatedWithSharedPaymentMethodToken()
    {
        $partnerMerchantGateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_public_id',
            'publicKey' => 'oauth_app_partner_user_public_key',
            'privateKey' => 'oauth_app_partner_user_private_key'
        ]);

        $customer = $partnerMerchantGateway->customer()->create([
            'firstName' => 'Joe',
            'lastName' => 'Brown'
        ])->customer;
        $address = $partnerMerchantGateway->address()->create([
            'customerId' => $customer->id,
            'firstName' => 'Dan',
            'lastName' => 'Smith',
        ])->address;
        $creditCard = $partnerMerchantGateway->creditCard()->create([
            'customerId' => $customer->id,
            'cardholderName' => 'Adam Davis',
            'number' => '4111111111111111',
            'expirationDate' => '05/2009'
        ])->creditCard;

        $oauthAppGateway = new Braintree\Gateway([
            'clientId' =>  'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret'
        ]);

        $code = Test\Braintree\OAuthTestHelper::createGrant($oauthAppGateway, [
            'merchant_public_id' => 'integration_merchant_id',
            'scope' => 'read_write,shared_vault_transactions'
        ]);

        $credentials = $oauthAppGateway->oauth()->createTokenFromCode([
            'code' => $code,
        ]);

        $oauthAccesTokenGateway = new Braintree\Gateway([
            'accessToken' => $credentials->accessToken
        ]);

        $result = $oauthAccesTokenGateway->transaction()->sale([
            'amount' => '100.00',
            'sharedPaymentMethodToken' => $creditCard->token,
            'sharedCustomerId' => $customer->id,
            'sharedShippingAddressId' => $address->id,
            'sharedBillingAddressId' => $address->id
        ]);

        $this->assertEquals(
            $result->transaction->shippingDetails->firstName,
            $address->firstName
        );
        $this->assertEquals(
            $result->transaction->billingDetails->firstName,
            $address->firstName
        );
    }

    public function testTransactionsCanBeCreatedWithSharedPaymentMethodNonce()
    {
        $partnerMerchantGateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_public_id',
            'publicKey' => 'oauth_app_partner_user_public_key',
            'privateKey' => 'oauth_app_partner_user_private_key'
        ]);

        $customer = $partnerMerchantGateway->customer()->create([
            'firstName' => 'Joe',
            'lastName' => 'Brown'
        ])->customer;
        $address = $partnerMerchantGateway->address()->create([
            'customerId' => $customer->id,
            'firstName' => 'Dan',
            'lastName' => 'Smith',
        ])->address;
        $creditCard = $partnerMerchantGateway->creditCard()->create([
            'customerId' => $customer->id,
            'cardholderName' => 'Adam Davis',
            'number' => '4111111111111111',
            'expirationDate' => '05/2009'
        ])->creditCard;

        $oauthAppGateway = new Braintree\Gateway([
            'clientId' =>  'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret'
        ]);

        $code = Test\Braintree\OAuthTestHelper::createGrant($oauthAppGateway, [
            'merchant_public_id' => 'integration_merchant_id',
            'scope' => 'read_write,shared_vault_transactions'
        ]);

        $credentials = $oauthAppGateway->oauth()->createTokenFromCode([
            'code' => $code,
        ]);

        $oauthAccesTokenGateway = new Braintree\Gateway([
            'accessToken' => $credentials->accessToken
        ]);

        $sharedNonce = $partnerMerchantGateway->paymentMethodNonce()->create(
            $creditCard->token
        )->paymentMethodNonce->nonce;

        $result = $oauthAccesTokenGateway->transaction()->sale([
            'amount' => '100.00',
            'sharedPaymentMethodNonce' => $sharedNonce,
            'sharedCustomerId' => $customer->id,
            'sharedShippingAddressId' => $address->id,
            'sharedBillingAddressId' => $address->id
        ]);

        $this->assertEquals(
            $result->transaction->shippingDetails->firstName,
            $address->firstName
        );
        $this->assertEquals(
            $result->transaction->billingDetails->firstName,
            $address->firstName
        );
    }

    public function testVisaTransactionReceivesNetworkTransactionId()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
        ]);

        $this->assertTrue($result->success);

        $transaction = $result->transaction;
        $this->assertTrue(strlen($transaction->networkTransactionId) > 0);
    }

    public function testNonVisaTransactionDoesNotReceiveNetworkTransactionId()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$masterCard,
                'expirationDate' => '05/2009',
            ],
        ]);

        $this->assertTrue($result->success);

        $transaction = $result->transaction;
        $this->assertNull($transaction->networkTransactionId);
    }

    public function testTransactionExternalVaultVisaWorksWithStatus()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'externalVault' => [
                'status' => "will_vault",
            ],
        ]);

        $this->assertTrue($result->success);
        $this->assertNotNull($result->transaction->networkTransactionId);
    }

    public function testTransactionExternalVaultNonVisaWorksWithStatus()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$masterCard,
                'expirationDate' => '05/2009',
            ],
            'externalVault' => [
                'status' => "will_vault",
            ],
        ]);

        $this->assertTrue($result->success);
        $this->assertNull($result->transaction->networkTransactionId);
    }

    public function testTransactionExternalVaultNonVisaWorksWithNullPreviousTransactionId()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$masterCard,
                'expirationDate' => '05/2009',
            ],
            'externalVault' => [
                'status' => "will_vault",
                'previousNetworkTransactionId' => null,
            ],
        ]);

        $this->assertTrue($result->success);
        $this->assertNull($result->transaction->networkTransactionId);
    }

    public function testTransactionVisaExternalVaultWorksWithPreviousNetworkTransactionId()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'externalVault' => [
                'status' => "vaulted",
                'previousNetworkTransactionId' => "123456789012345",
            ],
        ]);

        $this->assertTrue($result->success);
        $this->assertNotNull($result->transaction->networkTransactionId);
    }

    public function testTransactionExternalVaultWorksWithStatusVaultedWithoutPreviousNetworkTransactionId()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'externalVault' => [
                'status' => "vaulted",
            ],
        ]);

        $this->assertTrue($result->success);
        $this->assertNotNull($result->transaction->networkTransactionId);
    }

    public function testTransactionExternalVaultValidationErrorUnsupportedPaymentInstrumentType()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$applePayVisa,
            'externalVault' => [
                'status' => "vaulted",
                'previousNetworkTransactionId' => "123456789012345",
            ],
        ]);

        $this->assertFalse($result->success);

        $transaction = $result->transaction;
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_PAYMENT_INSTRUMENT_WITH_EXTERNAL_VAULT_IS_INVALID,
            $result->errors->forKey('transaction')->onAttribute('externalVault')[0]->code
        );
    }

    public function testTransactionExternalVaultValidationErrorInvalidStatus()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'externalVault' => [
                'status' => "bad_status",
            ],
        ]);

        $this->assertFalse($result->success);

        $transaction = $result->transaction;
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_EXTERNAL_VAULT_STATUS_IS_INVALID,
            $result->errors->forKey('transaction')->forKey('externalVault')->onAttribute('status')[0]->code
        );
    }

    public function testTransactionExternalVaultValidationErrorInvalidStatusWithPreviousNetworkTransactionId()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'externalVault' => [
                'status' => "will_vault",
                'previousNetworkTransactionId' => "123456789012345",
            ],
        ]);

        $this->assertFalse($result->success);

        $transaction = $result->transaction;
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_EXTERNAL_VAULT_STATUS_WITH_PREVIOUS_NETWORK_TRANSACTION_ID_IS_INVALID,
            $result->errors->forKey('transaction')->forKey('externalVault')->onAttribute('status')[0]->code
        );
    }

    public function testTransactionExternalVaultValidationErrorInvalidPreviousNetworkTransactionId()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'externalVault' => [
                'status' => "vaulted",
                'previousNetworkTransactionId' => "bad_value",
            ],
        ]);

        $this->assertFalse($result->success);

        $transaction = $result->transaction;
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_EXTERNAL_VAULT_PREVIOUS_NETWORK_TRANSACTION_ID_IS_INVALID,
            $result->errors->forKey('transaction')->forKey('externalVault')->onAttribute('previousNetworkTransactionId')[0]->code
        );
    }

    public function testTransactionExternalVaultValidationErrorInvalidCardType()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$masterCard,
                'expirationDate' => '05/2009',
            ],
            'externalVault' => [
                'status' => "vaulted",
                'previousNetworkTransactionId' => "123456789012345",
            ],
        ]);

        $this->assertFalse($result->success);

        $transaction = $result->transaction;
        $this->assertEquals(
            Braintree\Error\Codes::TRANSACTION_EXTERNAL_VAULT_CARD_TYPE_IS_INVALID,
            $result->errors->forKey('transaction')->forKey('externalVault')->onAttribute('previousNetworkTransactionId')[0]->code
        );
    }
}
