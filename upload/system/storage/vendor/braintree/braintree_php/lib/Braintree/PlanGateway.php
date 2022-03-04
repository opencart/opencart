<?php

namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree PlanGateway module
 * Creates and manages Plans
 */
class PlanGateway
{
    private $_gateway;
    private $_config;
    private $_http;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasAccessTokenOrKeys();
        $this->_http = new Http($gateway->config);
    }

    /*
     * Retrieve all plans
     *
     * @return array of Plan objects
     */
    public function all()
    {
        $path = $this->_config->merchantPath() . '/plans';
        $response = $this->_http->get($path);
        if (key_exists('plans', $response)) {
            $plans = ["plan" => $response['plans']];
        } else {
            $plans = ["plan" => []];
        }

        return Util::extractAttributeAsArray(
            $plans,
            'plan'
        );
    }

    /*
     * Request a new plan be created
     *
     * @param array $attributes containing request params
     *
     * @return Result\Sucessful|Result\Error
     */
    public function create($attributes)
    {
        Util::verifyKeys(self::_createSignature(), $attributes);
        $path = $this->_config->merchantPath() . '/plans';
        $response = $this->_http->post($path, ['plan' => $attributes]);
        return $this->_verifyGatewayResponse($response);
    }

    /*
     * Look up a single plan
     *
     * @param string $id of the plan to find
     *
     * @return plan|Exception\NotFound
     */
    public function find($id)
    {
        $this->_validateId($id);

        try {
            $path = $this->_config->merchantPath() . '/plans/' . $id;
            $response = $this->_http->get($path);
            return Plan::factory($response['plan']);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound('plan with id ' . $id . ' not found');
        }
    }

    /*
     * Updates a specific plan with given details
     *
     * @param string $planId the ID of the plan to be updated
     * @param mixed $attributes
     *
     * @return plan|Exception\NotFound
     */
    public function update($planId, $attributes)
    {
        Util::verifyKeys(self::_updateSignature(), $attributes);
        $path = $this->_config->merchantPath() . '/plans/' . $planId;
        $response = $this->_http->put($path, ['plan' => $attributes]);
        return $this->_verifyGatewayResponse($response);
    }

    private static function _createSignature()
    {
        return array_merge(
            [
                "billingDayOfMonth",
                "billingFrequency",
                "currencyIsoCode",
                "description",
                "id",
                "merchantId",
                "name",
                "numberOfBillingCycles",
                "price",
                "trialDuration",
                "trialDurationUnit",
                "trialPeriod"
            ],
            self::_addOnDiscountSignature()
        );
    }

    private static function _updateSignature()
    {
        return array_merge(
            [
                "billingDayOfMonth",
                "billingFrequency",
                "currencyIsoCode",
                "description",
                "id",
                "merchantId",
                "name",
                "numberOfBillingCycles",
                "price",
                "trialDuration",
                "trialDurationUnit",
                "trialPeriod"
            ],
            self::_addOnDiscountSignature()
        );
    }

    private static function _addOnDiscountSignature()
    {
        return [
            [
                'addOns' => [
                    ['add' => ['amount', 'inheritedFromId', 'neverExpires', 'numberOfBillingCycles', 'quantity']],
                    ['update' => ['amount', 'existingId', 'neverExpires', 'numberOfBillingCycles', 'quantity']],
                    ['remove' => ['_anyKey_']],
                ]
            ],
            [
                'discounts' => [
                    ['add' => ['amount', 'inheritedFromId', 'neverExpires', 'numberOfBillingCycles', 'quantity']],
                    ['update' => ['amount', 'existingId', 'neverExpires', 'numberOfBillingCycles', 'quantity']],
                    ['remove' => ['_anyKey_']],
                ]
            ]
        ];
    }

    /**
     * * @ignore
     * */
    private function _validateId($id = null)
    {
        if (empty($id)) {
            throw new InvalidArgumentException(
                'expected plan id to be set'
            );
        }
        if (!preg_match('/^[0-9A-Za-z_-]+$/', $id)) {
            throw new InvalidArgumentException(
                $id . ' is an invalid plan id.'
            );
        }
    }

    /**
     * * @ignore
     * */
    private function _verifyGatewayResponse($response)
    {
        if (isset($response['plan'])) {
            return new Result\Successful(
                Plan::factory($response['plan'])
            );
        } elseif (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected(
                "Expected plan, or apiErrorResponse"
            );
        }
    }
}
