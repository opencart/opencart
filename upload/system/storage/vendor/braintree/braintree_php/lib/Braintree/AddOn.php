<?php
namespace Braintree;

/**
 * @property-read string $amount
 * @property-read \DateTime $createdAt
 * @property-read int|null $currentBillingCycle
 * @property-read string $description
 * @property-read string $id
 * @property-read string|null $kind
 * @property-read string $merchantId
 * @property-read string $name
 * @property-read boolean $neverExpires
 * @property-read int|null $numberOfBillingCycles
 * @property-read int|null $quantity
 * @property-read \DateTime $updatedAt
 */
class AddOn extends Modification
{
    /**
     *
     * @param array $attributes
     * @return AddOn
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }


    /**
     * static methods redirecting to gateway
     *
     * @return AddOn[]
     */
    public static function all()
    {
        return Configuration::gateway()->addOn()->all();
    }
}
class_alias('Braintree\AddOn', 'Braintree_AddOn');
