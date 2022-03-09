<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use stdClass;
use DateTime;
use Test\Setup;
use Braintree;

class UtilTest extends Setup
{
    /**
     * @expectedException Braintree\Exception\Authentication
     */
    public function testThrow401Exception()
    {
        Braintree\Util::throwStatusCodeException(401);
    }

    /**
     * @expectedException Braintree\Exception\Authorization
     */
    public function testThrow403Exception()
    {
        Braintree\Util::throwStatusCodeException(403);
    }

    /**
     * @expectedException Braintree\Exception\NotFound
     */
    public function testThrow404Exception()
    {
        Braintree\Util::throwStatusCodeException(404);
    }

    /**
     * @expectedException Braintree\Exception\UpgradeRequired
     */
    public function testThrow426Exception()
    {
        Braintree\Util::throwStatusCodeException(426);
    }

    /**
     * @expectedException Braintree\Exception\TooManyRequests
     */
    public function testThrow429Exception()
    {
        Braintree\Util::throwStatusCodeException(429);
    }

    /**
     * @expectedException Braintree\Exception\ServerError
     */
    public function testThrow500Exception()
    {
        Braintree\Util::throwStatusCodeException(500);
    }

    /**
     * @expectedException Braintree\Exception\DownForMaintenance
     */
    public function testThrow503Exception()
    {
        Braintree\Util::throwStatusCodeException(503);
    }

    /**
     * @expectedException Braintree\Exception\Unexpected
     */
    public function testThrowUnknownException()
    {
        Braintree\Util::throwStatusCodeException(999);
    }

    /**
     * @expectedException Braintree\Exception\Authentication
     */
    public function testThrowGraphQLAuthenticationException()
    {
        $response = [
            "errors" => [
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "AUTHENTICATION"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    /**
     * @expectedException Braintree\Exception\Authorization
     */
    public function testThrowGraphQLAuthorizationException()
    {
        $response = [
            "errors" => [
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "AUTHORIZATION"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    /**
     * @expectedException Braintree\Exception\NotFound
     */
    public function testThrowGraphQLNotFoundException()
    {
        $response = [
            "errors" => [
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "NOT_FOUND"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    /**
     * @expectedException Braintree\Exception\UpgradeRequired
     */
    public function testThrowGraphQLUnsupportedClientException()
    {
        $response = [
            "errors" => [
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "UNSUPPORTED_CLIENT"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    /**
     * @expectedException Braintree\Exception\TooManyRequests
     */
    public function testThrowGraphQLResourceLimitException()
    {
        $response = [
            "errors" => [
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "RESOURCE_LIMIT"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    /**
     * @expectedException Braintree\Exception\ServerError
     */
    public function testThrowGraphQLInternalException()
    {
        $response = [
            "errors" => [
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "INTERNAL"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    /**
     * @expectedException Braintree\Exception\DownForMaintenance
     */
    public function testThrowGraphQLServiceAvailabilityException()
    {
        $response = [
            "errors" => [
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "SERVICE_AVAILABILITY"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    /**
     * @expectedException Braintree\Exception\Unexpected
     */
    public function testThrowGraphQLUnexpectedException()
    {
        $response = [
            "errors" => [
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "UNDOCUMENTED_ERROR"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    public function testDoesNotThrowGraphQLValidationException()
    {
        $response = [
            "errors" => [
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "VALIDATION"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    /**
     * @expectedException Braintree\Exception\Unexpected
     */
    public function testThrowGraphQLUnexpectedExceptionAndNotValidationExceptionWhenBothArePresent()
    {
        $response = [
            "errors" => [
                [
                    "message" => "validation_error",
                    "extensions" => [
                        "errorClass" => "VALIDATION"
                    ]
                ],
                [
                    "message" => "error_message",
                    "extensions" => [
                        "errorClass" => "UNDOCUMENTED_ERROR"
                    ]
                ]
            ]
        ];
        Braintree\Util::throwGraphQLResponseException($response);
    }

    public function testExtractAttributeAsArrayReturnsEmptyArray()
    {
        $attributes = [];
        $this->assertEquals([], Braintree\Util::extractAttributeAsArray($attributes, "foo"));
    }

    public function testExtractAttributeAsArrayReturnsSingleElementArray()
    {
        $attributes = ['verification' => 'val1'];
        $this->assertEquals(['val1'], Braintree\Util::extractAttributeAsArray($attributes, "verification"));
    }

    public function testExtractAttributeAsArrayReturnsArrayOfObjects()
    {
        $attributes = ['verification' => [['status' => 'val1']]];
        $expected = new Braintree\CreditCardVerification(['status' => 'val1']);
        $this->assertEquals([$expected], Braintree\Util::extractAttributeAsArray($attributes, "verification"));
    }

    public function testDelimeterToUnderscore()
    {
        $this->assertEquals("a_b_c", Braintree\Util::delimiterToUnderscore("a-b-c"));
    }

    public function testCleanClassName()
    {
        $cn = Braintree\Util::cleanClassName('Braintree\Transaction');
        $this->assertEquals('transaction', $cn);
    }

    public function testBuildClassName()
    {
        $cn = Braintree\Util::buildClassName('creditCard');
        $this->assertEquals('Braintree\CreditCard', $cn);
    }

    public function testimplodeAssociativeArray()
    {
        $array = [
            'test1' => 'val1',
            'test2' => 'val2',
            'test3' => new DateTime('2015-05-15 17:21:00'),
        ];
        $string = Braintree\Util::implodeAssociativeArray($array);
        $this->assertEquals('test1=val1, test2=val2, test3=Fri, 15 May 2015 17:21:00 +0000', $string);
    }

    public function testVerifyKeys_withThreeLevels()
    {
        $signature = [
            'firstName',
            ['creditCard' => ['number', ['billingAddress' => ['streetAddress']]]]
        ];
        $data = [
            'firstName' => 'Dan',
            'creditCard' => [
                'number' => '5100',
                'billingAddress' => [
                    'streetAddress' => '1 E Main St'
                ]
            ]
        ];
        Braintree\Util::verifyKeys($signature, $data);
    }

	public function testVerifyKeys_withArrayOfArrays()
	{
        $signature = [
			['addOns' => [['update' => ['amount', 'existingId']]]]
		];

		$goodData = [
            'addOns' => [
                'update' => [
                    [
                        'amount' => '50.00',
                        'existingId' => 'increase_10',
                    ],
                    [
                        'amount' => '60.00',
                        'existingId' => 'increase_20',
                    ]
                ]
            ]
		];

        Braintree\Util::verifyKeys($signature, $goodData);

		$badData = [
            'addOns' => [
                'update' => [
                    [
                        'invalid' => '50.00',
                    ]
                ]
            ]
		];

        $this->setExpectedException('InvalidArgumentException');
        Braintree\Util::verifyKeys($signature, $badData);
	}

    public function testVerifyKeys_arrayAsValue()
    {
        $signature = ['key'];
        $data = ['key' => ['value']];
        $this->setExpectedException('InvalidArgumentException');
        Braintree\Util::verifyKeys($signature, $data);
    }

    public function testVerifyKeys()
    {
        $signature = [
                'amount', 'customerId', 'orderId', 'channel', 'paymentMethodToken', 'type',

                ['creditCard'   =>
                    ['token', 'cvv', 'expirationDate', 'number'],
                ],
                ['customer'      =>
                    [
                        'id', 'company', 'email', 'fax', 'firstName',
                        'lastName', 'phone', 'website'],
                ],
                ['billing'       =>
                    [
                        'firstName', 'lastName', 'company', 'countryName',
                        'extendedAddress', 'locality', 'postalCode', 'region',
                        'streetAddress'],
                ],
                ['shipping'      =>
                    [
                        'firstName', 'lastName', 'company', 'countryName',
                        'extendedAddress', 'locality', 'postalCode', 'region',
                        'streetAddress'],
                ],
                ['options'       =>
                    [
                        'storeInVault', 'submitForSettlement',
                        'addBillingAddressToPaymentMethod'],
                ],
                ['customFields' => ['_anyKey_']
                ],
        ];

        // test valid
        $userKeys = [
                'amount' => '100.00',
                'customFields'   => ['HEY' => 'HO',
                                          'WAY' => 'NO'],
                'creditCard' => [
                    'number' => '5105105105105100',
                    'expirationDate' => '05/12',
                    ],
                ];

        $n = Braintree\Util::verifyKeys($signature, $userKeys);
        $this->assertNull($n);

        $userKeys = [
                'amount' => '100.00',
                'customFields'   => ['HEY' => 'HO',
                                          'WAY' => 'NO'],
                'bogus' => 'FAKE',
                'totallyFake' => 'boom',
                'creditCard' => [
                    'number' => '5105105105105100',
                    'expirationDate' => '05/12',
                    ],
                ];

        // test invalid
        $this->setExpectedException('InvalidArgumentException');

        Braintree\Util::verifyKeys($signature, $userKeys);
    }

    /**
     * @expectedException Braintree\Exception\ValidationsFailed
     */
    public function testReturnException()
    {
        $this->success = false;
        Braintree\Util::returnObjectOrThrowException('Braintree\Transaction', $this);
    }

    public function testReturnObject()
    {
        $this->success = true;
        $this->transaction = new stdClass();
        $t = Braintree\Util::returnObjectOrThrowException('Braintree\Transaction', $this);
        $this->assertInternalType('object', $t);
    }
}
