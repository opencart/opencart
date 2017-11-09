Divido API
=======

This is the documentation for the Divido API.

Sign up for an account to get instant access to our sandbox environment.

*Current version: v1.9*


Getting started
---------------

There are several distinct parts of a complete integration with the Divido API:

 * Deal Calculator
 * Finances
 * Credit Request
 * Finalize Credit Request
 * Activation
 * Cancellation
 * Refund
 * List all applications
 * Retrieve an application
 * Reporting / List all payment batches
 * Reporting / Retrieve records from a payment batch


### Deal Calculator

Calculate APR, monthly repayments, duration etc

### Finances

List Rate Card and all financial products available for a specific merchant.

### Credit Request

Create a new credit proposal and return an url to application form

### Finalize Credit Request

Finalize an existing accepted credit application, will update the loan agreement and return an url to the contract signing.

### Activation

Activate whole or part of application

### Cancellation

Cancel a deactivated application

### Refund

Refund a part or whole of an activated application

### List all applications

Returns a list of your applications. The applications are returned sorted by creation date, with the most recently created applications appearing first.

### Retrieve an application
	
Retrieves the details of an existing application. Supply the application ID and the API will return the corresponding application.

### Reporting / List all payment batches
	
Retrieves all payment batches.

### Reporting / Retrieve records from a payment batch
	
Retrieves the content of a payment batch. Supply the batch ID and the API will return all records.


Change log
------------

#### 2016-12-20
- Added Deal Calculator
- Added directSign to Credit Request
- Added Finalize Credit Request
- Added activation status

#### 2016-11-28

- Added reference on activate and refund
- Security enhancement: Added support for signing all api calls with a shared secret, contact Divido Support for more info.

#### 2016-11-16

- Added filter on proposal for List all applications
- Updated the webhook response
- Added reference in Credit Request


#### 2016-11-07

- Added product amount for refund and activation

#### 2016-11-02

- Added product lines for refund and activation

#### 2016-11-01

- Renamed fulfillment to activation
- Added reporting


#### 2016-10-30

- Added refunds, fulfillments, fulfillmentStatus to respones

#### 2016-10-18

- Removed Deal Calculator
- Added new statuses
- Changed the product dataset
- Updated the responses from the API


#### 2016-09-27

- Added partial fulfillment
- Added refund
- Added list all applications
- Added retrieve an application
- Added new statuses
- Bug fixes

#### 2016-06-28

- Added cancellation method
- Checkout URL
- Bug fixes
- Added more community libraries

#### 2016-01-28

- Added fulfillment method

#### 2015-06-21

- Added product to credit request
- Campaigns changed to Finances


Offical Libraries
---------------

We strongly encourage use of our official libraries for accessing the Divido API. Our official libraries are listed below, as well as community supported libraries.

#### PHP

Install from source

  `git clone https://github.com/DividoFinancialServices/divido-api-php.git`


Community libraries
---------------

Please submit a pull request to this page to add any missing libraries that should be featured here. Please note that Divido does not offer any support for community libraries.

#### Magento 1.*

  - [Divido for Magento 1.*](https://github.com/DividoFinancialServices/divido-magento)

#### Magento 2

  - [Divido for Magento 2](https://github.com/DividoFinancialServices/magento2-module)

#### WooCommerce

  - [Divido for WooCommerce](https://wordpress.org/plugins/divido-for-woocommerce/)

#### OpenCart

 - [Divido for OpenCart](https://github.com/DividoFinancialServices/divido-opencart)


Using the API
===========

API endpoints
---------------
To use the Divido API to query data, you will need to send a request to the correct endpoint. Request endpoints should depend on whether you wish to query the live or sandbox environment:

 - Sandbox: `https://secure.sandbox.divido.com/v1/`
 - Live: `https://secure.divido.com/v1/`

HTTP response codes
---------------
You may encounter the following response codes. Any unsuccessful response codes will contain more information to help you identify the cause of the problem.

 - `200` The request has succeeded.

 - `201` The request has been fulfilled and resulted in a new resource being created. The newly created resource can be referenced by the URI(s) returned in the entity of the response, with the most specific URI for the resource given by a Location header field.

 - `404` Not Found. The requested resource was not found. The response body will explain which resource was not found.

 - `500` Internal Server Error. The server encountered an error while processing your request and failed. Please report this to the Divido support team.



Webhooks
===========

To alert you of any changes in the status of your resources, Divido provides webhooks. These are POST requests to your server that are sent as soon as a resource changes status. The body of the request contains details of the change.

Request
---------------
The API server will send a POST request to the `response_url` associated with the application or `webhook_url` set by Divido support team.


#### Parameters


`application`   Application ID

`proposal`   Proposal ID

`reference`   Third party reference (if supplied as part of the application) 

`status`   New status

`metadata`   Metadata (if supplied as part of the application)


#### Example Request

JSON example

``` json
{
    "application": "C84047A6D-89B2-FECF-D2B4-168444F5178C",
    "event": "application-status-update",
    "metadata": {
        "Invoice Number": "844001",
        "Order Number": "100019"
    },
    "name": "John Doe",
    "proposal": "PAA717844-EE9D-78AF-D11C-EDCC1D180F87",
    "reference": "100019",
    "status": "ACCEPTED"
}
```

Possible statuses
---------------

  - `DRAFT` - Proposal send to Underwriter, waiting for decision

  - `ACCEPTED` - Application accepted by Underwriter

  - `DECLINED` - Applicaiton declined by Underwriter

  - `REFERRED` - Application referred by Underwriter, waiting for new status
  
  - `INFO-NEEDED` - More information is required before decision

  - `ACTION-CUSTOMER` - Waiting for more information from Customer

  - `ACTION-RETAILER` - Waiting for more information from Merchant
 
  - `ACTION-LENDER` - Waiting for more information from Underwriter

  - `DEPOSIT-PAID` - Deposit paid by customer

  - `SIGNED` - Customer has signed all contracts

  - `AWAITING-ACTIVATION` - Waiting for confirmation from Underwriter

  - `AWAITING-CANCELLATION` - Waiting for confirmation from Underwriter  

  - `PARTIALLY-ACTIVATED` - Application partially activated by merchant

  - `ACTIVATED` - Application activated and confirmed by Underwriter

  - `CANCELLED` - Application cancelled

  - `REFUNDED` - Whole Application refunded

  - `DISPUTED` - Dispute raised by Merchant or Underwriter

  - `LOAN-REVERSAL` - Loan reversal in progress

  - `COMPLETED` - Application completed (after cool down period)



Resources
===========

Finances
------------------

Returns an array with all finance options available for merchant

#### Example Request
   `GET` https://secure.divido.com/v1/finances?merchant={MERCHANT}&country={COUNTRY} `HTTP/1.1`

#### Example Response

JSON example

``` json
{
    "finances": [
        {
            "agreement_duration": 6,
            "country": "GB",
            "deferral_period": 0,
            "id": "F06895E17-EE96-926E-7137-37BCABB9DCF7",
            "interest_rate": 0,
            "max_deposit": 50,
            "min_amount": 150,
            "min_deposit": 0,
            "text": "6 Month 0% Interest Free"
        },
        {
            "agreement_duration": 12,
            "country": "GB",
            "deferral_period": 0,
            "id": "F284D5F1D-E8AF-D4B7-E1AF-A352F6087352",
            "interest_rate": 0,
            "max_deposit": 50,
            "min_amount": 150,
            "min_deposit": 0,
            "text": "12 Month 0% Interest Free"
        }
    ],
    "status": "ok"
}
```


#### Parameters

`merchant` 
    -  Your unique account identifier (*Required, String*)
  
```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102`
```

`country` - The country code (*Optional, String*)

``` 
Example `GB`
```
  


Deal Calculator
------------------

The deal calculator calculates the payment terms for various terms and deposits.

#### Example Request
   `GET` https://secure.divido.com/v1/dealcalculator?merchant={MERCHANT}&amount={AMOUNT}&deposit={deposit}&country={country}&finance={FINANCE} `HTTP/1.1`

#### Example Response

JSON example

``` json
{  
    "status": 'ok',  
    "purchase_price": 2000,  
    "deposit_amount": 200,  
    "credit_amount": 1800,  
    "monthly_payment_amount": 150,  
    "total_repayable_amount": 1800,  
    "agreement_duration": 12,  
    "interest_rate": 0.0,  
    "interest_type": "APR"
 }  
```

#### Parameters

`merchant` 
    -  Your unique account identifier (*Required, String*)
  
```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102`
```

`amount`
  - The total value of the order (*Required, Float*)

``` 
Example `2000.00`
```

  `deposit` - The value of the deposit. (*Required, Float*)

``` 
Example `200`
```

  `country` - The country code (*Required, String*)

``` 
Example `GB`
```

  `finance` - The finance code (*Required, String*)

``` 
Example `FA48EC74D-D95D-73A9-EC99-004FBE14A027`
```



Credit Request
------------------

The credit request creates a new proposal and return a URL to the Divido application form.

#### Example Request
   `POST` https://secure.divido.com/v1/creditrequest `HTTP/1.1`


``` javascript
curl https://secure.divido.com/v1/creditrequest \
-d merchant=live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102 \
-d deposit=100 \
-d finance=F06895E17-EE96-926E-7137-37BCABB9DCF7 \
-d directSign=true \
-d country=GB \
-d language=EN \
-d currency=GBP \
-d amount=1197.5 \
-d reference=100019 \
-d "customer[firstName]=John" \
-d "customer[middleNames]=L" \
-d "customer[lastName]=Doe" \
-d "customer[country]=GB" \
-d "customer[address][postcode]=EC2A 4BX" \
-d "customer[address][street]=High street" \
-d "customer[address][flat]=B" \
-d "customer[address][buildingNumber]=115" \
-d "customer[address][buildingName]=Amanda apartments" \
-d "customer[address][town]=London" \
-d "customer[address][monthsAtAddress]=60" \
-d "customer[gender]=male" \
-d "customer[email]=john.doe@domain.com" \
-d "customer[phoneNumber]=+44201234567" \
-d "customer[dateOfBirthYear]=1967" \
-d "customer[dateOfBirthMonth]=07" \
-d "customer[dateOfBirthDay]=01" \
-d "customer[bank][sortCode]=123456" \
-d "customer[bank][accountNumber]=12345678" \
-d "metadata[Invoice Number]=844001" \
-d "metadata[Order Number]=100019" \
-d "products[1][sku]=GIB100" \
-d "products[1][name]=Gibson Les Paul Studio Raw Guitar" \
-d "products[1][quantity]=1" \
-d "products[1][price]=1153.00" \
-d "products[1][vat]=20" \
-d "products[1][unit]=pcs" \
-d "products[1][image]=http://www.webshop.com/images/GIB100.png" \
-d "products[2][sku]=H10" \
-d "products[2][name]=Restring Upgrade" \
-d "products[2][quantity]=0.5" \
-d "products[2][price]=89" \
-d "products[2][vat]=20" \
-d "products[2][unit]=hour" \
-d "products[2][attributes]=1" \
-d "responseUrl=http://www.webshop.com/response.php" \
-d "checkoutUrl=http://www.webshop.com/checkout" \
-d "redirectUrl=http://www.webshop.com/success.html"
```


#### Example Response

JSON example

``` json
{
    "id": "PD66DF728-5646-22E3-EF6F-CD3D2D094170",
    "status": "ok",
    "token": "bcaa33546495965c4c8b3dc41d8582a1",
    "url": "https://secure.divido.com/token/bcaa33546495965c4c8b3dc41d8582a1"
}
```


#### Parameters

`merchant` 
    -  Your unique account identifier (*Required, String*)
    -  
  
```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102`
```

`deposit` - The value of the deposit. (*Required, Float*)

``` 
Example `100`
```

`finance` - The finance code (*Required, String*)

``` 
Example `F23B150D4-9D00-724A-6DFA-A1E726F6761A`
```

`directSign` - Whether or not to immediately go to signing after approval (*Optional, Boolean*, Default is `true`)

``` 
Example `true`
```


`country` - The country code (*Required, String*)

``` 
Example `GB`
```

`language` - The country code (*Required, String*)

``` 
Example `EN`
```

`currency` - The currency code (*Required, String*)

``` 
Example `GBP`
```

`amount` - Order total in same currency as proposal. Used to validate the total from product line items. (*Optional, Float*)

``` 
Example `1197.5`
```

`reference` - Your intenral reference, will be returned in webhooks. (*Optional, Float*)

``` 
Example `100019`
```

`customer['firstName']` - Customer first name (*Optional, String*)

``` 
Example `Jane`
```

`customer['middleNames']` - Customer middle names (*Optional, String*)

``` 
Example `L`
```

`customer['lastName']` - Customer last name (*Optional, String*)

``` 
Example `Doe`
```

`customer['country']` - Customer country (*Optional, String*)

``` 
Example `GB`
```

`customer['address']['postcode']` - Customer postcode (*Optional, String*

``` 
Example `EC2A 4BX`
```

`customer['address']['street']` - Customer street (*Optional, String*)

``` 
Example `High street`
```

`customer['address']['flat']` - Customer flat (*Optional, String*)

``` 
Example `B`
```

`customer['address']['buildingNumber']` - Customer building number (*Optional, String*)

``` 
Example `115`
```

`customer['address']['buildingName']` - Customer building name (*Optional, String*)

``` 
Example `Amanda apartments`
```

`customer['address']['town']` - Customer town (*Optional, String*)

``` 
Example `London`
```

`customer['address']['monthsAtAddress']` - Customer months at address (*Optional, String*)

``` 
Example `60`
```

`customer['gender']` - Customer gender (*Optional, String*)

``` 
Example `male`
```

`customer['email']` - Customer email (*Optional, String*)

``` 
Example `jane.doe@email.com`
```

`customer['phoneNumber']` - Customer phone number (*Optional, String*)

``` 
Example `0201234567`
```

`customer['dateOfBirthYear']` - Customer year of birth (*Optional, String*)

``` 
Example `1967`
```

`customer['dateOfBirthMonth']` - Customer month of birth (*Optional, String*)

``` 
Example `07`
```

`customer['dateOfBirthDay']` - Customer day of birth (*Optional, String*)

``` 
Example `01`
```

`customer['bank']['sortCode']` - Customer bank sort code (*Optional, String*)

``` 
Example `123456`
```

`customer['bank']['accountNumber']` - Customer bank account number (*Optional, String*)

``` 
Example `12345678`
```

`metadata['key']` - metadata key (*Optional, String*)

``` 
Example `Invoice Number`
```

`metadata['value']` - metadata value (*Optional, String*)

``` 
Example `844001`
```

`products['1']['sku']` - Product SKU (*Optional, String*)

``` 
Example `GIB100`
```

`products['1']['name']` - Product name/description (*Optional, String*)

``` 
Example `Gibson Les Paul Studio Raw Guitar`
```

`products['1']['quantity']` - Product quantity (*Optional, String*)

``` 
Example `1`
```

`products['1']['price']` - Product price in same currency as proposal (*Optional, String*)

``` 
Example `1153.00`
```
`products['1']['vat']` - Product VAT percentage (*Optional, String*)

``` 
Example `20`
```

`products['1']['unit']` - Product unit (*Optional, String*)
``` 
Example `pcs`
```

`products['1']['image']` - Product image (*Optional, String*)
``` 
Example `http://www.webshop.com/images/GIB100.png`
```

`products['1']['attributes']` - Product attributes (1=Service fee,2=Shipping fee,3=Payment fee,6=Discount, 10=Price is without VAT, 20=Line item with order VAT sum) (*Optional, String*)

``` 
Example `1,2`
```

`responseUrl` - The URL where we send notification about the payment (*Optional, String*)

``` 
Example `http://www.webshop.com/response.php`
```

`checkoutUrl` - A URL which Divido redirects the customer to if they get declined or wish to cancel their application (*Optional, String*)

``` 
Example `http://www.webshop.com/checkout`
```

`redirectUrl` - The URL the customer will get redirected to after a successful application (*Optional, String*)

``` 
Example `http://www.webshop.com/success.html`
```

Finalize Credit Request
------------------

Finalize an existing accepted credit application, will update the loan agreement and return an url to the contract signing. This only applies to Credit Requests created directSign = false.

#### Example Request
   `POST` https://secure.divido.com/v1/creditrequest/finalize `HTTP/1.1`


``` javascript
curl https://secure.divido.com/v1/creditrequest \
-d merchant=live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102 \
-d application=CAAC243AC-499A-84AF-DBBA-F58B9F7E798C \
-d deposit=100 \
-d finance=F06895E17-EE96-926E-7137-37BCABB9DCF7 \
-d amount=1197.5 \
-d "products[1][sku]=GIB100" \
-d "products[1][name]=Gibson Les Paul Studio Raw Guitar" \
-d "products[1][quantity]=1" \
-d "products[1][price]=1153.00" \
-d "products[1][vat]=20" \
-d "products[1][unit]=pcs" \
-d "products[1][image]=http://www.webshop.com/images/GIB100.png" \
-d "products[2][sku]=H10" \
-d "products[2][name]=Restring Upgrade" \
-d "products[2][quantity]=0.5" \
-d "products[2][price]=89" \
-d "products[2][vat]=20" \
-d "products[2][unit]=hour" \
-d "products[2][attributes]=1" \
-d "redirectUrl=http://www.webshop.com/success.html"
```

#### Example Response

JSON example

``` json
{
    "id": "CAAC243AC-499A-84AF-DBBA-F58B9F7E798C",
    "status": "ok",
    "token": "bcaa33546495965c4c8b3dc41d8582a1",
    "url": "https://secure.divido.com/token/bcaa33546495965c4c8b3dc41d8582a1"
}
```


Activation
------------------

Activate whole or part of an application and initialise a payout from the underwriter. Activate part of the application by specifing the products that should be activated. If no product data is submitted, the whole application will be activated.

#### Example Request
   `POST` https://secure.divido.com/v1/activation `HTTP/1.1`


``` javascript
curl https://secure.divido.com/v1/activation \
-d merchant="live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102" \
-d application="CAAC243AC-499A-84AF-DBBA-F58B9F7E798C" \
-d "products[1][sku]=GIB100" \
-d "products[1][name]=Gibson Les Paul Studio Raw Guitar" \
-d "products[1][quantity]=1" \
-d "products[1][price]=1153.00" \
-d "products[1][vat]=20" \
-d "products[2][sku]=H10" \
-d "products[2][name]=Restring Upgrade" \
-d "products[2][quantity]=0.5" \
-d "products[2][price]=89" \
-d "products[2][vat]=20" \
-d "products[2][attributes]=1" \
-d amount=1197.5 \
-d deliveryMethod="delivery" \
-d trackingNumber="DHL291824419F" \
-d reference="9482471" \
-d comment="Order was delivered to the customer by DHL" \
```


#### Example Response

JSON example

``` json
{
    "result": {
        "creditAmount": 1097.5,
        "depositAmount": 100,
        "depositStatus": "PAID-BY-CARD",
        "activatedAmount": 1197.5,
        "activationStatus": "AWAITING-ACTIVATION",
        "activations": [
            {
                "amount": 1197.5,
                "comment": "Order was delivered to the customer by DHL",
                "date": "2016-10-26 04:11",
                "deliveryMethod": "delivery",
                "reference": "9482471",
                "status": "AWAITING-ACTIVATION",
                "trackingNumber": "DHL291824419F"
            }
        ],
        "id": "C8A05742F-3040-44EC-C252-050FD8869F79",
        "purchasePrice": 1197.5,
        "refundedAmount": 0,
        "refunds": [],
        "status": "AWAITING-ACTIVATION"
    },
    "status": "ok"
}
```


#### Parameters

`merchant` - Your unique account identifier (*Required, String*)
  
```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102 `
```

`application` - The application or proposal identifier. (*Required, String*)

```
Example `CAAC243AC-499A-84AF-DBBA-F58B9F7E798C`
```

`products['1']['sku']` - Product SKU (*Optional, String*)

``` 
Example `GIB100`
```

`products['1']['name']` - Product name/description (*Optional, String*)

``` 
Example `Gibson Les Paul Studio Raw Guitar`
```

`products['1']['quantity']` - Product quantity (*Optional, String*)

``` 
Example `1`
```

`products['1']['price']` - Product price in same currency as proposal (*Optional, String*)

``` 
Example `1153.00`
```
`products['1']['vat']` - Product VAT percentage (*Optional, String*)

``` 
Example `20`
```

`amount` - Sum of the activated products (*Optional, String*)

```
Example `1197.5`
```

`deliveryMethod` - How the goods were delivered, can be either "store" or "delivery" (*Required, String*)

```
Example `delivery`
```

`trackingNumber` - If the deliveryMethod is delivery and you have a tracking number (*Optional, String*)

```
Example `DHL291824419F`
```

`reference` - Your reference to identify the activation (*Optional, String*)

``` 
Example `9482471 `
```

`comment` - Comment to the underwriter, can be order number or other information (*Optional, String*)

```
Example `Order was delivered to the customer by DHL`
```

Cancellation
------------------

Mark an application as cancelled and notify the underwriter, only possible if application is DRAFT, REFERRED, INFO-NEEDED, ACTION-CUSTOMER, ACTION-RETAILER, ACTION-LENDER, ACCEPTED, DEPOSIT-PAID, SIGNED.

#### Example Request
   `POST` https://secure.divido.com/v1/cancellation `HTTP/1.1`


``` javascript
curl https://secure.divido.com/v1/cancellation \
-d merchant="live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102" \
-d application="CAAC243AC-499A-84AF-DBBA-F58B9F7E798C" \
-d comment="Customer requested to cancelled the order"
```


#### Example Response

JSON example

``` json
{
    "result": {
        "creditAmount": 1097.5,
        "depositAmount": 100,
        "depositStatus": "UNPAID",
        "activatedAmount": 0,
        "activationStatus": "AWAITING-ACTIVATION",
        "activations": [],
        "id": "CAAC243AC-499A-84AF-DBBA-F58B9F7E798C",
        "purchasePrice": 1197.5,
        "refundedAmount": 0,
        "refunds": [],
        "status": "AWAITING-CANCELLATION"
    },
    "status": "ok"
}
```


#### Parameters

`merchant` -  Your unique account identifier (*Required, String*)

```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102 `
```

`application` - The application or proposal identifier. (*Required, String*)

``` 
Example `CAAC243AC-499A-84AF-DBBA-F58B9F7E798C `
```

`comment` - Comment to the underwriter, can be order number or other information (*Optional, String*)

``` 
Example `Customer requested to cancelled the order`
```

Refund
------------------

Refund whole or part of an application, if the application is AWAITING-ACTIVATION, PARTIALLY-ACTIVATED, ACTIVATED or COMPLETED. For partial refund, specify the products that have been refunded. If no product data is submitted the whole application will be refunded.

#### Example Request
   `POST` https://secure.divido.com/v1/refund `HTTP/1.1`


``` javascript
curl https://secure.divido.com/v1/refund \
-d merchant="live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102" \
-d application="CAAC243AC-499A-84AF-DBBA-F58B9F7E798C" \
-d "products[1][sku]=H10" \
-d "products[1][name]=Restring Upgrade" \
-d "products[1][quantity]=0.5" \
-d "products[1][price]=89" \
-d "products[1][vat]=20" \
-d "products[1][attributes]=1" \
-d amount=44.5 \
-d reference="7321834" \
-d comment="Customer returned part of order"
```


#### Example Response

JSON example

``` json
{
    "result": {
        "creditAmount": 1097.5,
        "depositAmount": 100,
        "depositStatus": "PAID-BY-CARD",
        "activatedAmount": 1197.5,
        "activationStatus": "AWAITING-ACTIVATION",
        "activations": [
            {
                "amount": 1197.5,
                "comment": "Order was delivered to the customer by DHL",
                "date": "2016-10-26 04:11",
                "deliveryMethod": "delivery",
                "reference": "9482471",
                "status": "AWAITING-ACTIVATION",
                "trackingNumber": "DHL291824419F"
            }
        ],
        "id": "C8A05742F-3040-44EC-C252-050FD8869F79",
        "purchasePrice": 1197.5,
        "refundedAmount": 44.5,
        "refunds": [
            {
                "amount": 44.5,
                "comment": "Customer returned part of order",
                "date": "2016-10-26 04:14",
                "reference": "7321834",
                "status": "PENDING"
            }
        ],
        "status": "AWAITING-ACTIVATION"
    },
    "status": "ok"
}
```


#### Parameters

`merchant`     -  Your unique account identifier (*Required, String*)

```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102 `
```

`application` - The application or proposal identifier. (*Required, String*)

``` 
Example `CAAC243AC-499A-84AF-DBBA-F58B9F7E798C`
```

`products['1']['sku']` - Product SKU (*Optional, String*)

``` 
Example `H10`
```

`products['1']['name']` - Product name/description (*Optional, String*)

``` 
Example `Restring Upgrade`
```

`products['1']['quantity']` - Product quantity (*Optional, String*)

``` 
Example `0.5`
```

`products['1']['price']` - Product price in same currency as proposal (*Optional, String*)

``` 
Example `89`
```
`products['1']['vat']` - Product VAT percentage (*Optional, String*)

``` 
Example `20`
```

`products['1']['attributes']` - Product attributes (1=Service,2=Shipping fee,3=Payment fee, 10=Price is without VAT) (*Optional, String*)

``` 
Example `1,2`
```

`amount` - Sum of the refunded items (*Optional, String*)

```
Example `44.5`
```

`reference` - Your reference to identify the refund (*Optional, String*)

``` 
Example `7321834 `
```

`comment` - Comment to the underwriter, can be order number or other information (*Optional, String*)

``` 
Example `Customer returned part of order`
```



List all applications
------------------

Returns a list of your applications. The applications are returned sorted by creation date, with the most recently created applications appearing first.

#### Example Request
   `GET` https://secure.divido.com/v1/applications?merchant={MERCHANT}&proposal={PROPOSAL}&status={STATUS}&page={PAGE} `HTTP/1.1`

#### Example Response

JSON example

``` json
{
    "itemsPerPage": 30,
    "page": 1,
    "records": [
        {
            "_creditAmount": "£ 1197.50",
            "_depositAmount": "£ 0",
            "_activatedAmount": "£ 1197.50",
            "_monthlyPaymentAmount": "£ 199.58",
            "_purchasePrice": "£ 1197.50",
            "_refundedAmount": "£ 0",
            "agreementDuration": 6,
            "channel": {
                "id": "CDDB70595-BFE6-0B7D-EE5B-B09FFC89F98C",
                "name": "Webshop.com",
                "type": "webshop"
            },
            "country": "GB",
            "createdDate": "2016-10-26 04:18",
            "creditAmount": 1197.5,
            "currency": "GBP",
            "deferralPeriod": 0,
            "depositAmount": 0,
            "depositReference": "",
            "depositStatus": "NO-DEPOSIT",
            "email": "john.doe@domain.com",
            "finance": {
                "id": "F06895E17-EE96-926E-7137-37BCABB9DCF7",
                "maxDeposit": 50,
                "minAmount": 150,
                "minDeposit": 0,
                "text": "6 Month 0% Interest Free"
            },
            "firstName": "John",
            "activatedAmount": 1197.5,
            "activations": [
                {
                    "_amount": "£ 1197.50",
                    "amount": 1197.5,
                    "comment": "",
                    "date": "2016-10-26 04:20",
                    "deliveryMethod": "delivery",
                    "reference": "9482471",
                    "trackingNumber": ""
                }
            ],
            "history": [
                {
                    "date": "2016-10-26 04:20",
                    "status": "AWAITING-ACTIVATION",
                    "text": "",
                    "type": "status",
                    "user": "James Weston"
                },
                {
                    "date": "2016-10-26 04:18",
                    "status": "SIGNED",
                    "text": "",
                    "type": "status",
                    "user": ""
                },
                {
                    "date": "2016-10-26 04:18",
                    "status": "ACCEPTED",
                    "text": "",
                    "type": "status",
                    "user": ""
                },
                {
                    "date": "2016-10-26 04:18",
                    "status": "",
                    "text": "Customer entered gateway from 10.11.12.1",
                    "type": "",
                    "user": ""
                }
            ],
            "id": "C92F7C65B-5C2D-6544-BB13-3E54243B9875",
            "interestRate": 0,
            "interestType": "simple",
            "lastName": "Doe",
            "lender": "Demo",
            "lenderReference": "",
            "metadata": {
                "Invoice Number": "844001",
                "Order Number": "100019"
            },
            "modifiedDate": "2016-10-26 04:20",
            "monthlyPaymentAmount": 199.58,
            "products": [
                {
                    "_price": "£ 1153",
                    "_sum": "£ 1153",
                    "image": "http://www.webshop.com/images/GIB100.png",
                    "name": "Gibson Les Paul Studio Raw Guitar",
                    "price": "1153.00",
                    "quantity": 1,
                    "sku": "GIB100",
                    "sum": "1153.00",
                    "unit": "pcs"
                },
                {
                    "_price": "£ 89",
                    "_sum": "£ 44.50",
                    "image": "",
                    "name": "Restring Upgrade",
                    "price": "89.00",
                    "quantity": 0.5,
                    "sku": "H10",
                    "sum": "44.50",
                    "unit": "hour"
                }
            ],
            "proposal": "PD56030F0-845C-ECF1-6118-0B26EFDCB273",
            "proposalCreator": null,
            "purchasePrice": 1197.5,
            "refundedAmount": 0,
            "refunds": [],
            "status": "AWAITING-ACTIVATION",
            "totalRepayableAmount": 1197.5
        },
        {
        	...
        },
        {
        	...
        },
        {
        	...
        }
    ],
    "status": "ok",
    "totalItems": 4
}
```


#### Parameters

`merchant` 
    -  Your unique account identifier (*Required, String*)
  
```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102`
```

`country` - Filter by country code (*Optional, String*)

``` 
Example `GB`
```

`status` - Filter by status (*Optional, String*)

``` 
Example `SIGNED`
```

`proposal` - Filter by proposal (*Optional, String*)

``` 
Example `PAA717844-EE9D-78AF-D11C-EDCC1D180F87`
```

`page` - Show page, default 1 (*Optional, String*)

``` 
Example `2`
```

Retrieve an applications
------------------

Retrieves the details of an existing application. Supply the application ID and the API will return the corresponding application.

#### Example Request
   `GET` https://secure.divido.com/v1/applications?merchant={MERCHANT}&id={id} `HTTP/1.1`

#### Example Response

JSON example

``` json
{
    "record": {
        "_creditAmount": "£ 1197.50",
        "_depositAmount": "£ 0",
        "_activatedAmount": "£ 1197.50",
        "_monthlyPaymentAmount": "£ 199.58",
        "_purchasePrice": "£ 1197.50",
        "_refundedAmount": "£ 0",
        "agreementDuration": 6,
        "channel": {
            "id": "CDDB70595-BFE6-0B7D-EE5B-B09FFC89F98C",
            "name": "Webshop.com",
            "type": "webshop"
        },
        "country": "GB",
        "createdDate": "2016-10-26 04:18",
        "creditAmount": 1197.5,
        "currency": "GBP",
        "deferralPeriod": 0,
        "depositAmount": 0,
        "depositReference": "",
        "depositStatus": "NO-DEPOSIT",
        "email": "john.doe@domain.com",
        "finance": {
            "id": "F06895E17-EE96-926E-7137-37BCABB9DCF7",
            "maxDeposit": 50,
            "minAmount": 150,
            "minDeposit": 0,
            "text": "6 Month 0% Interest Free"
        },
        "firstName": "John",
        "activatedAmount": 1197.5,
        "activations": [
            {
                "_amount": "£ 1197.50",
                "amount": 1197.5,
                "comment": "",
                "date": "2016-10-26 04:20",
                "deliveryMethod": "delivery",
                "reference": "9482471",
                "trackingNumber": ""
            }
        ],
        "history": [
            {
                "date": "2016-10-26 04:20",
                "status": "AWAITING-ACTIVATION",
                "text": "",
                "type": "status",
                "user": "James Weston"
            },
            {
                "date": "2016-10-26 04:18",
                "status": "SIGNED",
                "text": "",
                "type": "status",
                "user": ""
            },
            {
                "date": "2016-10-26 04:18",
                "status": "ACCEPTED",
                "text": "",
                "type": "status",
                "user": ""
            },
            {
                "date": "2016-10-26 04:18",
                "status": "",
                "text": "Customer entered gateway from 10.11.12.1",
                "type": "",
                "user": ""
            }
        ],
        "id": "C92F7C65B-5C2D-6544-BB13-3E54243B9875",
        "interestRate": 0,
        "interestType": "simple",
        "lastName": "Doe",
        "lender": "Demo",
        "lenderReference": "",
        "metadata": {
            "Invoice Number": "844001",
            "Order Number": "100019"
        },
        "modifiedDate": "2016-10-26 04:20",
        "monthlyPaymentAmount": 199.58,
        "products": [
            {
                "_price": "£ 1153",
                "_sum": "£ 1153",
                "image": "http://www.webshop.com/images/GIB100.png",
                "name": "Gibson Les Paul Studio Raw Guitar",
                "price": "1153.00",
                "quantity": 1,
                "sku": "GIB100",
                "sum": "1153.00",
                "unit": "pcs"
            },
            {
                "_price": "£ 89",
                "_sum": "£ 44.50",
                "image": "",
                "name": "Restring Upgrade",
                "price": "89.00",
                "quantity": 0.5,
                "sku": "H10",
                "sum": "44.50",
                "unit": "hour"
            }
        ],
        "proposal": "PD56030F0-845C-ECF1-6118-0B26EFDCB273",
        "proposalCreator": null,
        "purchasePrice": 1197.5,
        "reference": "100019",
        "refundedAmount": 0,
        "refunds": [],
        "status": "AWAITING-ACTIVATION",
        "totalRepayableAmount": 1197.5
    },
    "status": "ok"
}
```


#### Parameters

`merchant` 
    -  Your unique account identifier (*Required, String*)
  
```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102`
```

`id` - Application id (*Required, String*)

``` 
Example `C84047A6D-89B2-FECF-D2B4-168444F5178C`
```

Reporting / List all payment batches
------------------

Retrieves all payment batches.

#### Example Request
   `GET` https://secure.divido.com/v1/payments?merchant={MERCHANT}&currency={CURRENCY}&lender={LENDER}&page={PAGE} `HTTP/1.1`

#### Example Response

JSON example

``` json
{
    "itemsPerPage": 30,
    "page": 1,
    "records": [
        {
            "currency": "GBP",
            "date": "2016-04-14",
            "id": "PB0506EBA-870B-FFC2-FCAB-250D1B1291BD",
            "lender": {
                "id": "L07F46CDF-5296-D190-1F2D-A1B5FD869B72",
                "name": "Demo"
            },
            "merchant": {
                "id": "M7470B82C-B1EE-158F-D965-CFFD3158992A",
                "name": "Sofa & Table"
            },
            "records": 1,
            "totalCreditAmount": 989,
            "totalNetPayment": 1074.165,
            "totalSubsidyAmount": 4.945
        },
        ...,
        ...
    ],
    "status": "ok",
    "totalItems": 3
}
```


#### Parameters

`merchant` 
    -  Your unique account identifier (*Required, String*)
  
```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102`
```

`currency` - Filter by currency code (*Optional, String*)

``` 
Example `GBP`
```

`lender` - Filter by lender ID (*Optional, String*)

``` 
Example `L07F46CDF-5296-D190-1F2D-A1B5FD869B72 `
```

`page` - Show page, default 1 (*Optional, String*)

``` 
Example `2`
```

Reporting / Retrieve records from a payment batch
------------------

Retrieves the content of a payment batch. Supply the batch ID and the API will return all records.

#### Example Request
   `GET` https://secure.divido.com/v1/payments?merchant={MERCHANT}&id={id} `HTTP/1.1`

#### Example Response

JSON example

``` json
{
    "record": {
        "currency": "GBP",
        "date": "2016-04-14",
        "id": "PB0506EBA-870B-FFC2-FCAB-250D1B1291BD",
        "lender": {
            "id": "L07F46CDF-5296-D190-1F2D-A1B5FD869B72",
            "name": "Demo"
        },
        "totalCreditAmount": 989,
        "totalNetPayment": 1074.165,
        "totalSubsidyAmount": 4.945,
        "transactions": [
            {
                "application": "CDD6CCE0C-8DC5-6EB4-9A08-45520484EB19",
                "creditAmount": 989,
                "customer": "HESELDEN, ANN",
                "depositAmount": 100,
                "finance": {
                    "id": "F927FA596-6C9C-8376-A99B-70AE9A020F6B",
                    "text": "0% 12 month - Demo Bank"
                },
                "netPaymentAmount": 1074.165,
                "purchasePrice": 1089,
                "reference": "267381",
                "subsidyAmount": 4.945
            }
        ]
    },
    "status": "ok"
}
```


#### Parameters

`merchant` 
    -  Your unique account identifier (*Required, String*)
  
```
Example `live_c31be25be.fb2ee4bc8a66e1ecd797c56f03621102`
```

`id` - Batch id (*Required, String*)

``` 
Example `PB0506EBA-870B-FFC2-FCAB-250D1B1291BD `
```