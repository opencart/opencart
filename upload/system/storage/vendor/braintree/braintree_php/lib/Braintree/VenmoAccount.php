<?php
namespace Braintree;

/**
 * Braintree VenmoAccount module
 * Creates and manages Braintree Venmo accounts
 *
 * <b>== More information ==</b>
 *
 * See {@link https://developers.braintreepayments.com/javascript+php}<br />
 *
 * @package    Braintree
 * @category   Resources
 *
 * @property-read \DateTime $createdAt
 * @property-read string $customerId
 * @property-read boolean $default
 * @property-read string $imageUrl
 * @property-read string $sourceDescription
 * @property-read \Braintree\Subscription[] $subscriptions
 * @property-read string $token
 * @property-read \DateTime $updatedAt
 * @property-read string $username
 * @property-read string $venmoUserId
 */
class VenmoAccount extends Base
{
    /* instance methods */
    /**
     * returns false if default is null or false
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     *  factory method: returns an instance of VenmoAccount
     *  to the requesting method, with populated properties
     *
     * @ignore
     * @return VenmoAccount
     */
    public static function factory($attributes)
    {

        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /**
     * sets instance properties from an array of values
     *
     * @access protected
     * @param array $venmoAccountAttribs array of Venmo account properties
     * @return void
     */
    protected function _initialize($venmoAccountAttribs)
    {
        $this->_attributes = $venmoAccountAttribs;

        $subscriptionArray = array();
        if (isset($venmoAccountAttribs['subscriptions'])) {
            foreach ($venmoAccountAttribs['subscriptions'] AS $subscription) {
                $subscriptionArray[] = Subscription::factory($subscription);
            }
        }

        $this->_set('subscriptions', $subscriptionArray);
    }
}
class_alias('Braintree\VenmoAccount', 'Braintree_VenmoAccount');
