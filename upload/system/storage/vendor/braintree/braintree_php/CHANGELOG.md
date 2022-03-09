## 3.40.0
* Deprecate `GRANTED_PAYMENT_INSTRUMENT_UPDATE` and add `GRANTOR_UPDATED_GRANTED_PAYMENT_METHOD` and `RECIPIENT_UPDATED_GRANTED_PAYMENT_METHOD`
* Add `accountType` field to `TransactionGateway`, `CreditCardGateway`, `PaymentMethodGateway`, and `CreditCardVerification`.
* Add Hiper and Hipercard test numbers.

## 3.39.0
* Add `bin` field to `paymentMethodNonceDetails`
* Add Error indicating pdf uploads too long for dispute evidence.
* Add `GrantedPaymentMethodRevoked` webhook response objects

## 3.38.0
* Add `fraudServiceProvider` field in `riskData`
* Fix issue where merchant reporting calls would throw an exception in PHP 7 (#236)

## 3.37.0
* Add `processorResponseType` to `Transaction`, `AuthorizationAdjustment`, and `CreditCardVerification`.
* Add `authorizationExpiresAt` to `Transaction`
* Add support for additional PayPal options when vaulting a PayPal Order during customer update
* Allow PayPal payment ID and payer ID to be passed during transaction create
* Add `travel_flight` support to industry-specific data

## 3.36.0
* Fix dispute results in transactions not showing the correct status sometimes
* Add `externalVault` option to transaction sale
* Add `LocalPaymentCompleted` webhook

## 3.35.0
* Add subscription charged unsuccessfully sample webhook to webhook testing gateway
* Add `processor_response_code` and `processor_response_text` to authorization adjustments subfield in transaction response.
* Fix issue where result objects could not be printed with `echo` (thanks @cmeon)
* Add Samsung Pay support

## 3.34.0
* Allow payee ID to be passed in options params for transaction create
* Add `merchant_id` to `ConnectedMerchantStatusTransitioned` and `ConnectedMerchantPayPalStatusChanged` Auth webhooks
* Fix webhook testing sample xml for dispute webhooks to include `amount-won` and `amount-disputed` (closes #225)

## 3.33.0
* Fix WebhookTestingGateway to use local configuration
* Add Disbursement type field and methods

## 3.32.0
* Add support for US Bank Account verifications API

## 3.31.0
* Fix issue where webhook verification would fail due to missing global public key configuration value
* Fix issue where webhook testing did not work on instantiated gateway
* Add support for VCR compelling evidence dispute representment

## 3.30.0
* Add `oauthAccessRevocation` to `WebhookNotification`s
* Add support for `profileId` in Transaction#create options for VenmoAccounts
* Add support for dispute search by `customerId`, `disbursementDate`, and `effectiveDate`
* Make `CustomerGateway::find` backward compatible
* Remove `sepaMandateType` and `sepaMandateAcceptanceLocation` params from `ClientTokenGateway`

## 3.29.0
* Add support for `association_filter_id` in `Customer#find`
* Add support for setting `timeout` and `acceptGzipEncoding` values on construction of `Configuration` instances

## 3.28.0
* Add support for Level 3 summary parameters: `shippingAmount`, `discountAmount`, and `shipsFromPostalCode`
* Add support for `tax_amount` field on transaction `line_items`
* Add `sourceMerchantId` property to `WebhookNotification`s if present
* Deprecate `TRANSACTION_LINE_ITEM_DISCOUNT_AMOUNT_MUST_BE_GREATER_THAN_ZERO` error in favor of `TRANSACTION_LINE_ITEM_DISCOUNT_AMOUNT_CANNOT_BE_NEGATIVE`.
* Deprecate `TRANSACTION_LINE_ITEM_UNIT_TAX_AMOUNT_MUST_BE_GREATER_THAN_ZERO` error in favor of `TRANSACTION_LINE_ITEM_UNIT_TAX_AMOUNT_CANNOT_BE_NEGATIVE`.
* Deprecate `Braintree\Transaction\LineItem` in favor of `Braintree\TransactionLineItem`.
* Add `findAll` static method to `TransactionLineItem` class
* Add support for `profile_id` in Transaction#create options for VenmoAccounts

## 3.27.0
* Add support for Level 3 summary parameters: `shippingAmount`, `discountAmount`, and `shipsFromPostalCode`
* Add support for transaction line items
* Add support for tagged evidence in `DisputeGateway::addTextEvidence` (Beta release)
* Update https certificate bundle

## 3.26.1
* Deprecate `OAuthGateway::computeSignature`
* Fix spec to expect PayPal transactions to move to settling rather than settled
* Fix AchMandate acceptedAt attribute parsing

## 3.26.0
* Add support for upgrading a PayPal future payment refresh token to a billing agreement
* Address deprecation warnings for create_function with PHP 7 (#193, thanks @chrisdeeming)
* Add `cardHolderName` to `CreditCardDetails` (#201, thanks @Samistine)
* Add GrantedPaymentInstrumentUpdate webhook support
* Allow paypal => options params to be passed in camel case
* Add ability to create a transaction from a shared nonce
* Implement JsonSerializable on Braintree Objects for easier logging (#200, thanks @hfmikep)
* Fix spec to expect PayPal transaction to settle immediately after successful capture
* Add `options` -> `paypal` -> `shipping` for creating & updating customers as well as creating payment methods
* Add `options` -> `paypal` -> `description` for creating and updating subscriptions
* Add `binData` to `PaymentMethodNonce`
* Add `bin` to `ApplePayCard`
* Add `deviceDataCaptured` field in `riskData`

## 3.25.0
* Add `submitForSettlement` option to `Subscription::retryCharge`
* Support `eci_indicator` for Transaction sale with raw Apple Pay parameters
* Add `accept` method for the Dispute API
* Add `addTextEvidence` method for the Dispute API
* Add `addFileEvidence` method for the Dispute API
* Add `finalize` method for the Dispute API
* Add `find` method for the Dispute API
* Add `removeEvidence` method for the Dispute API
* Add `search` method for the Dispute API
* Add DocumentUpload

## 3.24.0
* Add AuthorizationAdjustment class and `authorizationAdjustments` to Transaction
* Add iDEAL webhook support
* Add `IDEAL_PAYMENT` to `PaymentInstrumentType`
* Create Braintree class to be PSR compliant
* Coinbase is no longer a supported payment method. `PAYMENT_METHOD_NO_LONGER_SUPPORTED` will be returned for Coinbase operations.
* Add `ApplePay` for web domain registration.
* Add facilitated details to Transaction if present
* Update link to transaction api documentation (thanks @qoheleth-tech!).
* Fix TransactionGateway return types (thanks @jjok!).
* Update return type for client token (thanks @jlaswell!).

## 3.23.1
* Fix token generator return type - thanks @jjok!
* Improve error reporting for connection issues - thanks @montymxb!
* Add support for additional PayPal options when vaulting a PayPal Order

## 3.23.0
* Add Visa Checkout support
* Add ConnectedMerchantStatusTransitioned and ConnectedMerchantPayPalStatusChanged Auth webhooks
* Add new properties to `CreditCardVerification` and `Customer`
* Add SDK support for skip AVS and skip CVV

## 3.22.0
* Add option to disable Accept-Encoding: gzip header for Google App Engine
* Fix a bug where `merchantAccount->all` would attempt to fetch too many pages of merchant accounts

## 3.21.1
* Add back in `options->three_d_secure` to transaction params that was accidentally removed in v3.14.0

## 3.21.0
* Allow optional configuration of SSL version
* Replace `var_dump` with `print_r`. Thanks, @mnlg
* Add functionality to list all merchant accounts for a merchant with `merchantAccount->all`
* Stop sending account_description field from us bank accounts

## 3.20.0
* Add option `skip_advanced_fraud_check` for transaction flows

## 3.19.0
* Add multi-currency updates to merchants onboarded through Braintree Auth
* Raise an exception if fetching pages of results times out during a transaction search

## 3.18.0
* Fix `UsBankAccount` support for `Customer`s
* Update `Grant` api to support options hash

## 3.17.0
* Add 'UsBankAccount' payment method

## 3.16.0
* Add authenticated proxy functionality
* Add constant for Venmo Account payment instrument type
* Add validation error for verifications with submerchants

## 3.15.0
* Add 'default_payment_method' option for Customer

## 3.14.0
**Note: This version introduced an unintentional breaking change where the `options->three_d_secure` transaction parameter was changed to `options->threeDSecure`. Starting in v3.21.1, both case conventions are supported for backwards compatibility.**

* Add OrderId to refund
* Add 3DS Pass thru support
* Expose IDs in resource collections
* Add leading slash to the namespace. Thanks, @bocharsky-bw
* Stop modifying DateTime parameters during XML generation. Thanks, @jodarove

## 3.13.0
* Add method of revoking OAuth access tokens.

## 3.12.0
* Add Transaction `update_details`
* Support for Too Many Requests response codes
* Add ability to count errors in ErrorCollection object. Thanks, @bocharsky-bw
* Improve Type Hinting

## 3.11.0
* Remove final from classes. Thanks, @ibrahimlawal!
* Add currency to Transaction search

## 3.10.0
* Add timeout attribute
* Add start-date and end-date to SUBSCRIPTION_CHARGED_SUCCESSFULLY test webhook response

## 3.9.0
* Add AccountUpdaterDailyReport webhook parsing

## 3.8.0
* Add payment method revoke
* Add support for options in `submit_for_settlement` transaction flows
* Add verification create API
* Update https certificate bundle

## 3.7.0
* Add VenmoAccount
* Allow order_id and descriptor to be passed in for Transaction submit_for_settlement
* Add facilitator details onto transactions
* Add check webhook constant

## 3.6.1
* Fix PSR-0 style namespacing when using Symfony

## 3.6.0
* Add support for proxy servers
* Add PSR-4 namespacing support
* Add support for AMEX Express Checkout
* Add support for new fields in dispute webhooks (`dateWon`, `dateOpened`, `kind`)
* Add transaction data to sucessful subscription webhook

## 3.5.0
* Add support for raw ApplePay params on Transaction create

## 3.4.0
* Add sourceDescription method to Android Pay and Apple Pay payment methods
* Add new Android Pay test nonces
* Add billing agreement ID to PayPal Account
* Support amex rewards transactions

## 3.3.0
* Add new test payment method nonces
* Allow passing description on PayPal transactions
* Add methods to change transaction settlement status in sandbox
* Fix issue where customer with an id of 0 could not be found
* Add Europe Bank Account functionality

## 3.2.0
* Add additional search criteria

## 3.1.0
* Add support for HHVM
* Validate that configuration is valid before verifying webhooks
* Make OAuth methods conform more to existing API
* Expose customer paymentMethods as an attribute

## 3.0.1
* Add support for Android Pay

## 3.0.0
* Deprecate PHP 5.2 and 5.3
* Validate webhook challenge payload
* Bugfix for calling `__toString()` on objects that contain a `\DateTime`

## 2.40.0
* Add missing criteria to credit card verification search
* Bugfix for autoloading files with Composer

## 2.39.0
* Add oauth functionality
* Add 3DS info to the server side

## 2.38.0
* Update payment instrument types and test nonces
* Add missing valid params to PaymentMethodGateway

## 2.37.0
* Add 3D Secure transaction fields
* Add ability to create nonce from vaulted payment methods

## 2.36.0
* Surface Apple Pay payment instrument name in responses
* Support Coinbase payment instruments

## 2.35.2
* Fix E_STRICT errors
* Expose subscription status details

## 2.35.1
* Bugfix for auto loading files

## 2.35.0
* Allow PayPal fields in transaction.options.paypal
* Add error code constants
* Internal refactoring

## 2.34.0
* Add risk_data to Transaction and Verification with Kount decision and id
* Add verification_amount an option when creating a credit card
* Add TravelCruise industry type to Transaction
* Add room_rate to Lodging industry type
* Add CreditCard#verification as the latest verification on that credit card
* Add ApplePay support to all endpoints that may return ApplePayCard objects
* Add prefix to sample Webhook to simulate webhook query params

## 2.33.0
* Allow descriptor to be passed in Funding Details options params for Merchant Account create and update.

## 2.32.0
* Add additionalProcessorResponse to Transaction

## 2.31.1
* Allow payee_email to be passed in options params for Transaction create

## 2.31.0
* Added paypal specific fields to transaction calls
* Added SettlementPending, SettlementDeclined transaction statuses

## 2.30.0
* Add descriptor url support

## 2.29.0
* Allow credit card verification options to be passed outside of the nonce for PaymentMethod.create
* Allow billing_address parameters and billing_address_id to be passed outside of the nonce for PaymentMethod.create
* Add Subscriptions to paypal accounts
* Add PaymentMethod.update
* Add fail_on_duplicate_payment_method option to PaymentMethod.create

## 2.28.0
* Adds support for v.zero SDKs.

## 2.27.2

* Make webhook parsing more robust with newlines
* Add messages to InvalidSignature exceptions

## 2.27.1

* Updated secureCompare to correctly compare strings in consistent time
* Add better error messages around webhook verification

## 2.27.0

* Include Dispute information on Transaction
* Search for Transactions disputed on a certain date

## 2.26.0

* Disbursement Webhooks

## 2.25.1

* Fix factories on AddOn and Discount (thanks [stewe](https://github.com/stewe))
* Allow billingAddressId on transaction create

## 2.25.0

* Merchant account find API

## 2.24.0

* Merchant account update API
* Merchant account create API v2

## 2.23.1

* Update configuration URLs

## 2.23.0

* Official Partnership support

## 2.22.2

* Add Partner Merchant Declined webhook
* use preg_callback_replace instead of preg_replace (thanks [jonthornton](https://github.com/jonthornton)!)

## 2.22.1

* Adds missing test contstant to library namespace

## 2.22.0

* Adds holdInEscrow method
* Add error codes for verification not supported error
* Add companyName and taxId to merchant account create
* Adds cancelRelease method
* Adds releaseFromEscrow functionality
* Adds phone to merchant account signature.
* Adds merchant account phone error code.
* Fix casing issues with Braintree\_Http and Braintree\_Util references (thanks [steven-hadfield](https://github.com/steven-hadfield)!)
* Fixed transaction initialization arguments to be optional (thanks [karolsojko](https://github.com/karolsojko)!)

## 2.21.0

* Enable device data.

## 2.20.0

* Fixed getting custom fields with valueForHtmlField. [Thanks to Miguel Manso for the fix.](https://github.com/mumia)
* Adds disbursement details to transactions.
* Adds image url to transactions.

## 2.19.0

* Adds channel field to transactions.

## 2.18.0

* Adds country of issuance and issuing bank bin database fields

## 2.17.0

* Adds verification search

## 2.16.0

* Additional card information, such as prepaid, debit, commercial, Durbin regulated, healthcare, and payroll, are returned on credit card responses
* Allows transactions to be specified as recurring

## 2.15.0

* Adds prepaid field to credit cards (possible values include Yes, No, Unknown)

## 2.14.1

* Adds composer support (thanks [till](https://github.com/till))
* Fixes erroneous version number
* Braintree_Plan::all() returns empty array if no plans exist

## 2.14.0

* Adds webhook gateways for parsing, verifying, and testing notifications

## 2.13.0

* Adds search for duplicate credit cards given a payment method token
* Adds flag to fail saving credit card to vault if card is duplicate

## 2.12.5

* Exposes plan_id on transactions

## 2.12.4

* Added error code for invalid purchase order number

## 2.12.3

* Fixed problematic case in ResourceCollection when no results are returned from a search.

## 2.12.2

* Fixed customer search, which returned customers when no customers matched search criteria

## 2.12.1

* Added new error message for merchant accounts that do not support refunds

## 2.12.0

* Added ability to retrieve all Plans, AddOns, and Discounts
* Added Transaction cloning

## 2.11.0

* Added Braintree_SettlementBatchSummary

## 2.10.1

* Wrap dependency requirement in a function, to prevent pollution of the global namespace

## 2.10.0

* Added subscriptionDetails to Transaction
* Added flag to store in vault only when a transaction is successful
* Added new error code

## 2.9.0

* Added a new transaction state, AUTHORIZATION_EXPIRED.
* Enabled searching by authorizationExpiredAt.

## 2.8.0

* Added next_billing_date and transaction_id to subscription search
* Added address_country_name to customer search
* Added new error codes

## 2.7.0

* Added Customer search
* Added dynamic descriptors to Subscriptions and Transactions
* Added level 2 fields to Transactions:
  * tax_amount
  * tax_exempt
  * purchase_order_number

## 2.6.1

* Added billingAddressId to allowed parameters for credit cards create and update
* Allow searching on subscriptions that are currently in a trial period using inTrialPeriod

## 2.6.0

* Added ability to perform multiple partial refunds on Braintree_Transactions
* Allow passing expirationMonth and expirationYear separately when creating Braintree_Transactions
* Added revertSubscriptionOnProrationFailure flag to Braintree_Subscription update that specifies how a Subscription should react to a failed proration charge
* Deprecated Braintree_Subscription nextBillAmount in favor of nextBillingPeriodAmount
* Deprecated Braintree_Transaction refundId in favor of refundIds
* Added new fields to Braintree_Subscription:
  * balance
  * paidThroughDate
  * nextBillingPeriodAmount

## 2.5.0

* Added Braintree_AddOns/Braintree_Discounts
* Enhanced Braintree_Subscription search
* Enhanced Braintree_Transaction search
* Added constants for Braintree_Result_CreditCardVerification statuses
* Added EXPIRED and PENDING statuses to Braintree_Subscription
* Allowed prorateCharges to be specified on Braintree_Subscription update
* Added Braintree_AddOn/Braintree_Discount details to Braintree_Transactions that were created from a Braintree_Subscription
* Removed 13 digit Visa Sandbox Credit Card number and replaced it with a 16 digit Visa
* Added new fields to Braintree_Subscription:
  * billingDayOfMonth
  * daysPastDue
  * firstBillingDate
  * neverExpires
  * numberOfBillingCycles

## 2.4.0

* Added ability to specify country using countryName, countryCodeAlpha2, countryCodeAlpha3, or countryCodeNumeric (see [ISO_3166-1](https://en.wikipedia.org/wiki/ISO_3166-1))
* Added gatewayRejectionReason to Braintree_Transaction and Braintree_Verification
* Added unified message to result objects

## 2.3.0

* Added unified Braintree_TransparentRedirect url and confirm methods and deprecated old methods
* Added functions to Braintree_CreditCard to allow searching on expiring and expired credit cards
* Allow card verification against a specified merchant account
* Added ability to update a customer, credit card, and billing address in one request
* Allow updating the paymentMethodToken on a subscription

## 2.2.0

* Prevent race condition when pulling back collection results -- search results represent the state of the data at the time the query was run
* Rename ResourceCollection's approximate_size to maximum_size because items that no longer match the query will not be returned in the result set
* Correctly handle HTTP error 426 (Upgrade Required) -- the error code is returned when your client library version is no long compatible with the gateway
* Add the ability to specify merchant_account_id when verifying credit cards
* Add subscription_id to transactions created from subscriptions

## 2.1.0

* Added transaction advanced search
* Added ability to partially refund transactions
* Added ability to manually retry past-due subscriptions
* Added new transaction error codes
* Allow merchant account to be specified when creating transactions
* Allow creating a transaction with a vault customer and new payment method
* Allow existing billing address to be updated when updating credit card
* Correctly handle xml with nil=true

## 2.0.0

* Updated success? on transaction responses to return false on declined transactions
* Search results now include Enumerable and will automatically paginate data
* Added credit_card[cardholder_name] to allowed transaction params and CreditCardDetails (thanks [chrismcc](https://github.com/chrismcc))
* Fixed a bug with Customer::all
* Added constants for error codes

## 1.2.1

* Added methods to get both shallow and deep errors from a Braintree_ValidationErrorCollection
* Added the ability to make a credit card the default card for a customer
* Added constants for transaction statuses
* Updated Quick Start in README.md to show a workflow with error checking

## 1.2.0

* Added subscription search
* Provide access to associated subscriptions from CreditCard
* Switched from using Zend framework for HTTP requests to using curl extension
* Fixed a bug in Transparent Redirect when arg_separator.output is configured as &amp; instead of &
* Increased http request timeout
* Fixed a bug where ForgedQueryString exception was being raised instead of DownForMaintenance
* Updated SSL CA files

## 1.1.1

* Added Braintree_Transaction::refund
* Added Braintree_Transaction::submitForSettlementNoValidate
* Fixed a bug in errors->onHtmlField when checking for errors on custom fields when there are none
* Added support for passing merchantAccountId for Transaction and Subscription

## 1.1.0

* Added recurring billing support

## 1.0.1

* Fixed bug with Braintree_Error_ErrorCollection.deepSize
* Added methods for accessing validation errors and params by html field name

## 1.0.0

* Initial release
