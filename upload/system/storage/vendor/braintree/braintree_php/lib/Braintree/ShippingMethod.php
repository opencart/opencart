<?php

namespace Braintree;

/**
 * Shipping methods module
 * Shipping methods can be assigned to shipping addresses when
 * creating transactions.
 */
class ShippingMethod
{
    const SAME_DAY      = 'same_day';
    const NEXT_DAY      = 'next_day';
    const PRIORITY      = 'priority';
    const GROUND        = 'ground';
    const ELECTRONIC    = 'electronic';
    const SHIP_TO_STORE = 'ship_to_store';
}
