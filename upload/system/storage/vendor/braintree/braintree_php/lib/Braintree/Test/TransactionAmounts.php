<?php
namespace Braintree\Test;

/**
 * Transaction amounts used for testing purposes
 *
 * The constants in this class can be used to create transactions with
 * the desired status in the sandbox environment.
 *
 * @package    Braintree
 * @subpackage Test
 */
class TransactionAmounts
{
   public static $authorize   = '1000.00';
   public static $decline     = '2000.00';
   public static $hardDecline = '2015.00';
}
class_alias('Braintree\Test\TransactionAmounts', 'Braintree_Test_TransactionAmounts');
