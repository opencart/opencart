<?php

class CatalogAccountTest extends OpenCartSeleniumTest {


	/**
	 * @before
	 */
	protected function setupTest() {
		$this->setBrowser('firefox');
		$this->setBrowserUrl(HTTP_SERVER);
	}

	/**
	 * @after
	 */
	protected function completeTest() {
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
		$db->query("DELETE FROM " . DB_PREFIX . "customer");
		$db->query("DELETE FROM " . DB_PREFIX . "address");
	}

	public function testNewsletterSubscription() {
		$this->doRegistration();

		$this->url("index.php?route=account/newsletter");
		$this->byCssSelector('input[value="1"]')->click();
		$this->byCssSelector('input[value="Continue"]')->click();
		$this->url("index.php?route=account/newsletter");
		$element = $this->byCssSelector('input[value="1"]');
		$this->assertEquals('true', $element->attribute('checked'));

		$this->byCssSelector('input[value="0"]')->click();
		$this->byCssSelector('input[value="Continue"]')->click();
		$this->url("index.php?route=account/newsletter");
		$element = $this->byCssSelector('input[value="0"]');
		$this->assertEquals('true', $element->attribute('checked'));
	}

	public function testAddAddress() {
		$this->doRegistration();
		$this->url("index.php?route=account/address/add");

		$this->clickOnElement('input-firstname');
		$this->keys('Firstname');

		$this->clickOnElement('input-lastname');
		$this->keys('Lastname');

		$this->clickOnElement('input-company');
		$this->keys('Company');

		$this->clickOnElement('input-address-1');
		$this->keys('Address 1');

		$this->clickOnElement('input-address-2');
		$this->keys('Address 2');

		$this->clickOnElement('input-city');
		$this->keys('City');

		$this->clickOnElement('input-postcode');
		$this->keys('000 000');

		$this->byCssSelector('#input-country option[value="222"]')->click();
		$this->byCssSelector('#input-zone option[value="3608"]')->click();

		$this->byCssSelector('input[value="Continue"]')->click();

		$this->waitUntil(function() {
			if (strpos($this->url(), 'account/address') !== False) {
				return true;
			}
		}, 3000);

		$this->byCssSelector('table.table-hover tr:last-child td:last-child .btn-info')->click();

		$this->waitUntil(function() {
			if (strpos($this->url(), 'account/address/edit') !== False) {
				return true;
			}
		}, 3000);

		$this->byId('input-firstname')->clear();
		$this->clickOnElement('input-firstname');
		$this->keys('Firstname2');

		$this->byId('input-lastname')->clear();
		$this->clickOnElement('input-lastname');
		$this->keys('Lastname2');

		$this->byId('input-company')->clear();
		$this->clickOnElement('input-company');
		$this->keys('Company2');

		$this->byId('input-address-1')->clear();
		$this->clickOnElement('input-address-1');
		$this->keys('Address 12');

		$this->byId('input-address-2')->clear();
		$this->clickOnElement('input-address-2');
		$this->keys('Address 22');

		$this->byId('input-city')->clear();
		$this->clickOnElement('input-city');
		$this->keys('City2');

		$this->byId('input-postcode')->clear();
		$this->clickOnElement('input-postcode');
		$this->keys('999 999');

		$this->byCssSelector('#input-country option[value="223"]')->click();

		$this->waitUntil(function() {
			if ($this->byCssSelector('#input-zone option[value="3624"]')) {
				return true;
			}
		}, 3000);

		$this->byCssSelector('#input-zone option[value="3624"]')->click();

		$this->byCssSelector('input[value="Continue"]')->click();

		$this->waitUntil(function() {
			if (strpos($this->url(), 'account/address') !== False) {
				return true;
			}
		}, 3000);

		$this->byCssSelector('table.table-hover tr:last-child td:last-child .btn-info')->click();

		$firstname = $this->byId('input-firstname')->value();
		$this->assertEquals('Firstname2', $firstname);

		$lastname = $this->byId('input-lastname')->value();
		$this->assertEquals('Lastname2', $lastname);

		$company = $this->byId('input-company')->value();
		$this->assertEquals('Company2', $company);

		$address1 = $this->byId('input-address-1')->value();
		$this->assertEquals('Address 12', $address1);

		$address2 = $this->byId('input-address-2')->value();
		$this->assertEquals('Address 22', $address2);

		$city = $this->byId('input-city')->value();
		$this->assertEquals('City2', $city);

		$postcode = $this->byId('input-postcode')->value();
		$this->assertEquals('999 999', $postcode);

		$country = $this->byId('input-country')->value();
		$this->assertEquals('223', $country);

		$zone = $this->byId('input-zone')->value();
		$this->assertEquals('3624', $zone);
	}

	public function testChangePassword() {
		$this->doRegistration();

		$this->url("index.php?route=account/password");

		$this->clickOnElement('input-password');
		$this->keys('new-password');

		$this->clickOnElement('input-confirm');
		$this->keys('new-password');

		$this->byCssSelector('input[value="Continue"]')->click();

		$this->url('index.php?route=account/logout');
		$this->url('index.php?route=account/login');

		$this->clickOnElement('input-email');
		$this->keys('john.smith@example.com');

		$this->clickOnElement('input-password');
		$this->keys('new-password');

		$this->byCssSelector('input[value="Login"]')->click();

		$this->waitUntil(function(){
			if (strpos($this->url(), 'account/account') !== False) {
				return true;
			}
		}, 3000);
	}

	public function testInformationEditing() {
		$this->doRegistration();

		$this->url("index.php?route=account/edit");

		$this->byId('input-firstname')->clear();
		$this->clickOnElement('input-firstname');
		$this->keys('John-New');

		$this->byId('input-lastname')->clear();
		$this->clickOnElement('input-lastname');
		$this->keys('Smith-New');

		$this->byId('input-email')->clear();
		$this->clickOnElement('input-email');
		$this->keys('john.smith.new@example.com');

		$this->byId('input-telephone')->clear();
		$this->clickOnElement('input-telephone');
		$this->keys('000000000');

		$this->byCssSelector('input[value="Continue"]')->click();

		$this->url("index.php?route=account/edit");

		$firstname = $this->byId('input-firstname')->value();
		$this->assertEquals('John-New', $firstname);

		$lastname = $this->byId('input-lastname')->value();
		$this->assertEquals('Smith-New', $lastname);

		$email = $this->byId('input-email')->value();
		$this->assertEquals('john.smith.new@example.com', $email);

		$telephone = $this->byId('input-telephone')->value();
		$this->assertEquals('000000000', $telephone);
	}

	public function testLogin() {
		$this->doRegistration();
		$this->url('index.php?route=account/logout');
		$this->url('index.php?route=account/login');

		$this->clickOnElement('input-email');
		$this->keys('john.smith@example.com');

		$this->clickOnElement('input-password');
		$this->keys('password123456');

		$this->byCssSelector('input[value="Login"]')->click();

		$this->waitUntil(function(){
			if (strpos($this->url(), 'account/account') !== False) {
				return true;
			}
		}, 3000);
	}

	public function testFailedLogin() {
		$this->doRegistration();
		$this->url('index.php?route=account/logout');
		$this->url('index.php?route=account/login');

		$this->clickOnElement('input-email');
		$this->keys('john.smith@example.com');

		$this->clickOnElement('input-password');
		$this->keys('incorrect password');

		$this->byCssSelector('input[value="Login"]')->click();

		$this->waitUntil(function(){
			if (strpos($this->url(), 'account/login') !== False) {
				return true;
			}
		}, 3000);

		$this->byCssSelector('.alert-danger');
	}

	private function doRegistration() {
		$this->url('index.php?route=account/register');

		$this->clickOnElement('input-firstname');
		$this->keys('John');

		$this->clickOnElement('input-lastname');
		$this->keys('Smith');

		$this->clickOnElement('input-email');
		$this->keys('john.smith@example.com');

		$this->clickOnElement('input-telephone');
		$this->keys('0123456789');

		$this->clickOnElement('input-address-1');
		$this->keys('Address 1');

		$this->clickOnElement('input-address-2');
		$this->keys('Address 2');

		$this->clickOnElement('input-city');
		$this->keys('City');

		$this->clickOnElement('input-postcode');
		$this->keys('000 000');

		$countryElement = $this->byCssSelector('#input-country option[value="222"]');
		$countryElement->click();

		$countyElement = $this->byCssSelector('#input-zone option[value="3608"]');
		$countyElement->click();

		$this->clickOnElement('input-password');
		$this->keys('password123456');

		$this->clickOnElement('input-confirm');
		$this->keys('password123456');

		$this->byCssSelector('input[name="agree"]')->click();

		$this->byCssSelector('input[value="Continue"]')->click();

		$this->waitUntil(function(){
			if (strpos($this->url(), 'account/success') !== False) {
				return true;
			}
		}, 3000);
	}
}
