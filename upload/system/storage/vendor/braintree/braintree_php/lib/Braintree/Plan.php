<?php

namespace Braintree;

/**
 * Plan class object. A plan is a template for subscriptions.
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/plan developer docs} for information on attributes
 */
class Plan extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Plan
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);

        return $instance;
    }

    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;

        $addOnArray = [];
        if (isset($attributes['addOns'])) {
            foreach ($attributes['addOns'] as $addOn) {
                $addOnArray[] = AddOn::factory($addOn);
            }
        }
        $this->_attributes['addOns'] = $addOnArray;

        $discountArray = [];
        if (isset($attributes['discounts'])) {
            foreach ($attributes['discounts'] as $discount) {
                $discountArray[] = Discount::factory($discount);
            }
        }
        $this->_attributes['discounts'] = $discountArray;

        $planArray = [];
        if (isset($attributes['plans'])) {
            foreach ($attributes['plans'] as $plan) {
                $planArray[] = self::factory($plan);
            }
        }
        $this->_attributes['plans'] = $planArray;
    }

    /**
     * static methods redirecting to gateway class
     *
     * @see PlanGateway::all()
     *
     * @return Plan[]
     */
    public static function all()
    {
        return Configuration::gateway()->plan()->all();
    }

    /**
     * static methods redirecting to gateway class
     *
     * @param array $attributes response object attributes
     *
     * @return Plan
     */
    public static function create($attributes)
    {
        return Configuration::gateway()->plan()->create($attributes);
    }

    /**
     * static methods redirecting to gateway class
     *
     * @param $id int planId
     *
     * @return Plan
     */
    public static function find($id)
    {
        return Configuration::gateway()->plan()->find($id);
    }

    /**
     * static methods redirecting to gateway class
     *
     * @param $planId     int planId
     * @param array $attributes response object attributes
     *
     * @return Plan
     */
    public static function update($planId, $attributes)
    {
        return Configuration::gateway()->plan()->update($planId, $attributes);
    }
}
