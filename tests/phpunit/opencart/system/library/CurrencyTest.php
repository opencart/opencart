<?php
require_once dirname ( dirname ( __DIR__ ) ) . '/utils/TestUtils.php';
require_once dirname ( dirname ( dirname ( dirname ( dirname ( __DIR__ ) ) ) ) ) . '/upload/system/library/db.php';
require_once dirname ( dirname ( dirname ( dirname ( dirname ( __DIR__ ) ) ) ) ) . '/upload/system/library/cart/currency.php';
require_once dirname ( dirname ( dirname ( dirname ( dirname ( __DIR__ ) ) ) ) ) . '/upload/system/engine/registry.php';
require_once dirname ( dirname ( dirname ( dirname ( dirname ( __DIR__ ) ) ) ) ) . '/upload/system/library/language.php';

use Cart\Currency;
use TestsUtils\DBUtils;

class CurrencyTest extends PHPUnit_Framework_TestCase {
	private $currency;
	private $registry;
	
	/**
	 * @before
	 */
	public function setUp() {
		$this->registry = new Registry ();
		
		$db = DBUtils::getNewConnection ();
		
		$db->query ( "DELETE FROM " . DB_PREFIX . "currency" );
		$db->query ( "INSERT INTO " . DB_PREFIX . "currency SET currency_id = '1', title = 'Pound Sterling', code = 'GBP', symbol_left = '£', symbol_right = '', decimal_place = '2', value = '0.61979997', status = '1', date_modified = '2011-07-16 10:30:52'" );
		$db->query ( "INSERT INTO " . DB_PREFIX . "currency SET currency_id = '2', title = 'US Dollar', code = 'USD', symbol_left = '$', symbol_right = '', decimal_place = '2', value = '1.00000000', status = '1', date_modified = '2011-07-16 16:55:46'" );
		$db->query ( "INSERT INTO " . DB_PREFIX . "currency SET currency_id = '3', title = 'Euro', code = 'EUR', symbol_left = '', symbol_right = '€', decimal_place = '2', value = '0.70660001', status = '1', date_modified = '2011-07-16 10:30:52'" );
		
		// TODO the currency breaks without this dependencies and do not tests them refactory currency after
		$language = new Language ();
		$language->set ( 'decimal_point', '.' );
		$language->set ( 'thousand_point', ',' );
		
		$this->registry->set ( 'db', $db );
		$this->registry->set ( 'language', $language );
		
		$this->currency = new Currency ( $this->registry );
	}
	public function testCurrencyFormat() {
		$this->assertEquals ( '7.06€', $this->currency->format ( '9.99', 'EUR' ) );
	}
	public function testCurrencyConvert() {
		$value = $this->currency->convert ( '7.06', 'EUR', 'USD' );
		
		// 9.9915084914872843
		$this->assertEquals ( 9.9915, round ( $value, 4 ) );
	}
	public function testCurrencyGetId() {
		$this->assertEquals ( 3, $this->currency->getId ( 'EUR' ) );
	}
	public function testCurrencyGetSymbolLeft() {
		$this->assertEquals ( '£', $this->currency->getSymbolLeft ( 'GBP' ) );
	}
	public function testCurrencyGetSymbolRight() {
		$this->assertEquals ( '€', $this->currency->getSymbolRight ( 'EUR' ) );
	}
	public function testCurrencyGetDecimalPlace() {
		$this->assertEquals ( 2, $this->currency->getDecimalPlace ( 'GBP' ) );
	}
	public function testCurrencyHas() {
		$this->assertTrue ( $this->currency->has ( 'USD' ) );
		$this->assertFalse ( $this->currency->has ( 'AUD' ) );
	}
	public function hasPerformedExpectationsOnOutput() {
		return true;
	}
}



