<?php

class CatalogSagePayExpressTest extends OpenCartSeleniumTest {

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
			$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "sagepay_direct_order");
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "sagepay_direct_order_transaction");
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "sagepay_direct_order_recurring");
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "sagepay_direct_card");

			$this->url("admin/");

			$this->byCssSelector('input[name="username"]')->click();
			$this->keys(ADMIN_USERNAME);

			$this->byCssSelector('input[name="password"]')->click();
			$this->keys(ADMIN_PASSWORD);

			$this->byCssSelector('button[type="submit"]')->click();

			$this->moduleInstalled = true;

			$this->waitToLoad('Dashboard');

			// Installing the payment module
			$this->clickOnElement('button-menu');

			$this->waitToAppearAndClick('#extension a');
			$this->waitToAppearAndClick('#extension li:nth-child(5) a');

			$this->waitToLoad('Payment');

			$i = 1;

			for ( ; ; $i++) {
				$element = $this->byCssSelector(".table-bordered tbody tr:nth-child($i) td:first-child");

				if ($element->text() == 'SagePay Direct') {
					break;
				}
			}

			$this->waitToAppearAndClick(".table-bordered tbody tr:nth-child($i) td:last-child a.btn-success");
			$this->waitToAppearAndClick(".table-bordered tbody tr:nth-child($i) td:last-child a.btn-primary");

			$this->waitToLoad('SagePay Direct');

			$this->clickOnElement('sagepay_direct_vendor');
			$this->keys(SAGEPAY_DIRECT_VENDOR);

			$this->byCssSelector('#input-test option[value="test"]')->click();

			$this->clickOnElement('sagepay_direct_total');
			$this->keys('0.00');

			// Adding the Cart Layout
			$this->waitToAppearAndClick('#system a');
			$this->waitToAppearAndClick('#system li:nth-child(2) a');
			$this->waitToAppearAndClick('#system li:nth-child(2) li:first-child a');

			$this->waitToLoad('Layouts');
			$this->byCssSelector('.fa-plus-circle')->click();

			$this->waitToAppearAndClick('#input-name');
			$this->keys('Cart');

			$this->byCssSelector('.fa-plus-circle')->click();

			$this->byCssSelector('input[name="layout_route[0][route]"]')->click();
			$this->keys('checkout/cart');

			$this->byCssSelector('.fa-check-circle')->click();

			// Installing the payment button
			$this->waitToAppearAndClick('#extension a');
			$this->waitToAppearAndClick('#extension li:nth-child(3) a');

			$i = 1;

			for ( ; ; $i++) {
				$element = $this->byCssSelector(".table-bordered tbody tr:nth-child($i) td:first-child");

				if ($element->text() == 'PayPal Express Checkout button') {
					break;
				}
			}

			$this->waitToAppearAndClick(".table-bordered tbody tr:nth-child($i) td:last-child a.btn-success");
			$this->waitToAppearAndClick(".table-bordered tbody tr:nth-child($i) td:last-child a.btn-primary");

			$this->waitToLoad('PayPal Express Checkout button');
			$this->byCssSelector('.fa-plus-circle')->click();

			for ($i = 1; ; $i++) {
				$element = $this->byCssSelector("select[name=\"pp_button_module[0][layout_id]\"] option:nth-child($i)");

				if ($element->text() == 'Cart') {
					$element->click();
					break;
				}
			}

			$this->byCssSelector('button[title="Save"]')->click();
		}
	}

	public function testOneProduct() {
		$this->url('index.php?route=product/product&product_id=43');
		$this->clickOnElement('button-cart');

		$this->url('index.php?route=checkout/cart');
		$this->waitToLoad('Shopping Cart');

		$this->byCssSelector('.pp-express-button')->click();

		$this->waitToLoad("Pay with a PayPal", 30000);

		$this->clickOnElement('login_email');
		$this->keys(PP_EXPRESS_USERNAME);

		$this->clickOnElement('login_password');
		$this->keys(PP_EXPRESS_PASSWORD);

		$this->clickOnElement('submitLogin');

		$this->waitToLoad("Review your information", 30000);

		$this->clickOnElement('continue_abovefold');

		$this->waitToLoad("Confirm order", 30000);

		$this->byCssSelector('.pull-right .btn-primary')->click();

		$this->waitToLoad("Your order has been placed!");

		$element = $this->byCssSelector('#content h1');

		$this->assertEquals('Your order has been placed!', $element->text());
	}

}
