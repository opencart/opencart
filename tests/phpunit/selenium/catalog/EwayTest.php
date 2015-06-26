<?php

class CatalogEwayTest extends OpenCartSeleniumTest {

	private $moduleInstalled = false;

	/**
	 * @before
	 */
	protected function before() {
		$this->setBrowser('firefox');
		$this->setBrowserUrl(HTTP_SERVER);
	}

	public function setUpPage() {
		if (!$this->moduleInstalled) {
			$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "eway_order");
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "eway_transactions");
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "eway_card");

			// Log into the admin area
			$this->url("admin/");

			$this->byCssSelector('input[name="username"]')->click();
			$this->keys(ADMIN_USERNAME);

			$this->byCssSelector('input[name="password"]')->click();
			$this->keys(ADMIN_PASSWORD);

			$this->byCssSelector('button[type="submit"]')->click();

			$this->waitToLoad('Dashboard');

			$this->setAdminCurrency();

			$this->setStoreEmail();

			// Installing the payment module
			$this->clickOnElement('button-menu');

			$this->waitToAppearAndClick('#extension a');
			$this->waitToAppearAndClick('#extension li:nth-child(5) a');

			$this->waitToLoad('Payment');

			$i = 1;

			for ( ; ; $i++) {
				$element = $this->byCssSelector(".table-bordered tbody tr:nth-child($i) td:first-child");

				if ($element->text() == 'eWAY Payment') {
					break;
				}
			}

			$this->waitToAppearAndClick(".table-bordered tbody tr:nth-child($i) td:last-child a.btn-success");

			$this->moduleInstalled = true;

			// Configure eWAY
			$this->waitToAppearAndClick(".table-bordered tbody tr:nth-child($i) td:last-child a.btn-primary");

			$this->waitToLoad('eWAY Payment');

			$this->byCssSelector('#input-test option[value="1"]')->click();

			$this->byCssSelector('input[name="eway_username"]')->clear();
			$this->byCssSelector('input[name="eway_username"]')->click();
			$this->keys(EWAY_API_KEY);

			$this->byCssSelector('input[name="eway_password"]')->clear();
			$this->byCssSelector('input[name="eway_password"]')->click();
			$this->keys(EWAY_API_PASSWORD);

			$this->byCssSelector('#input-status option[value="1"]')->click();

			$this->byCssSelector('input[name="eway_payment_type[visa]"][value="1"]')->click();

			$this->byCssSelector('#input-order-status option[value="15"]')->click();
			$this->byCssSelector('#input-order-status-refund option[value="11"]')->click();
			$this->byCssSelector('#input-order-status-auth option[value="1"]')->click();
			$this->byCssSelector('#input-order-status-fraud option[value="2"]')->click();

			$this->byCssSelector('.pull-right button.btn')->click();
			sleep(2);

			$this->moduleInstalled = true;
		}
	}

	public function testOneProduct() {
		// Add to cart
		$this->url('index.php?route=product/product&product_id=43');

		$this->setCatalogCurrency();

		$this->waitToAppearAndClick('#button-cart');

		// To the checkout!
		$this->url('index.php?route=checkout/checkout');
		$this->waitToLoad('Checkout');

		sleep(2);

		// Sometimes this step is here
		try {
			$this->byCssSelector('input[value="guest"]')->click();
			$this->clickOnElement('button-account');
			sleep(3);
		} catch (Exception $e) {

		}

		$this->completePersonalDetails();

		// Payment selection
		$this->waitToAppearAndClick('input[value="eway"]');
		$this->byCssSelector('input[name="agree"]')->click();

		$this->clickOnElement('button-payment-method');

		// Card details
		$this->waitToAppearAndClick('#eway-cardname');
		$this->keys('Bob Smith');

		$this->byCssSelector('#eway-cardnumber')->click();
		$this->keys('4444333322221111');
		
		$this->byCssSelector('#eway-card-expiry-year option[value="2025"]')->click();
		
		$this->byCssSelector('#eway-cardcvn')->click();
		$this->keys('321');

		// Pay and confirmation
		$this->clickOnElement('button-confirm');
		$this->waitToLoad("Your order has been placed!");

		$element = $this->byCssSelector('#content h1');
		$this->assertEquals('Your order has been placed!', $element->text());

	}

	/**
	 * Adds AUD currency for eWAY testing
	 */
	public function setAdminCurrency() {
		// Add the currency
		$this->clickOnElement('button-menu');

		$this->waitToAppearAndClick('#system a');
		$this->waitToAppearAndClick('#system li:nth-child(4) a');
		$this->waitToAppearAndClick('#system li:nth-child(4) ul li:nth-child(3) a');

		$this->waitToLoad('Currencies');

		$this->byCssSelector('.pull-right a[data-original-title="Add New"]')->click();
		sleep(2);

		$this->clickOnElement('input-title');
		$this->keys('Australian Dollar');

		$this->clickOnElement('input-code');
		$this->keys('AUD');

		$this->clickOnElement('input-symbol-left');
		$this->keys('$');

		$this->byCssSelector('#input-status option[value="1"]')->click();

		$this->byCssSelector('.pull-right button.btn')->click();

		sleep(3);
	}

	/**
	 * Set the store email and encryption key so checkout doesn't fail
	 */
	function setStoreEmail() {
		$this->clickOnElement('button-menu');

		$this->waitToAppearAndClick('#system a');
		$this->waitToAppearAndClick('#system li:nth-child(1) a');

		$this->waitToAppearAndClick('td.text-right a.btn');
		$this->waitToLoad('Settings');

		$this->clickOnElement('input-email');
		$this->keys('test@example.org');

		$this->byCssSelector("a[href='#tab-server']")->click();

		$this->byCssSelector('#input-encryption')->click();
		$this->keys('iamakey');

		$this->byCssSelector('.pull-right button.btn')->click();

		sleep(3);
	}

	/**
	 * Switchs the store frontend to AUD for eWAY testing
	 */
	function setCatalogCurrency() {

		$this->byCssSelector("div.btn-group button.dropdown-toggle")->click();
		$this->byCssSelector('ul.dropdown-menu button[name="AUD"]')->click();

	}

	/**
	 * Fill in the personal details in the checkout (Austrlian address)
	 */
	function completePersonalDetails() {
		// Customer details
		$this->clickOnElement('input-payment-firstname');
		$this->keys('Firstname');

		$this->clickOnElement('input-payment-lastname');
		$this->keys('Lastname');

		$this->clickOnElement('input-payment-email');
		$this->keys('john.smith@example.com');

		$this->clickOnElement('input-payment-telephone');
		$this->keys('1800999999');

		$this->clickOnElement('input-payment-address-1');
		$this->keys('Address 1');

		$this->clickOnElement('input-payment-city');
		$this->keys('City');

		$this->clickOnElement('input-payment-postcode');
		$this->keys('2000');

		// Australia
		$this->byCssSelector('#input-payment-country option[value="13"]')->click();

		// NSW
		$this->waitToAppearAndClick('#input-payment-zone option[value="192"]');

		$this->clickOnElement('button-guest');
	}
}
