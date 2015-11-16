<?php

class CatalogPayPalExpressTest extends OpenCartSeleniumTest {

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
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "paypal_order");
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "paypal_order_transaction");
			$db->query("DELETE l, lr FROM " . DB_PREFIX . "layout l, " . DB_PREFIX . "layout_route lr WHERE l.layout_id = lr.layout_id AND l.`name` = 'Cart'");

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

				if ($element->text() == 'PayPal Express Checkout') {
					break;
				}
			}

			$this->waitToAppearAndClick(".table-bordered tbody tr:nth-child($i) td:last-child a.btn-success");
			$this->waitToAppearAndClick(".table-bordered tbody tr:nth-child($i) td:last-child a.btn-primary");

			$this->waitToLoad('PayPal Express Checkout');

			$this->clickOnElement('entry-username');
			$this->keys(PP_EXPRESS_API_USERNAME);

			$this->clickOnElement('entry-password');
			$this->keys(PP_EXPRESS_API_PASSWORD);

			$this->clickOnElement('entry-signature');
			$this->keys(PP_EXPRESS_API_SIGNATURE);

			$this->byCssSelector('a[href="#tab-general"]')->click();

			$this->waitToAppearAndClick('#input-live-demo option[value="1"]');

			for ($i = 1; ; $i++) {
				$element = $this->byCssSelector('#input-currency option:nth-child(' . $i . ')');

				if ($element->text() == 'USD') {
					$element->click();
					break;
				}
			}

			$this->clickOnElement('input-total');
			$this->keys('0.00');

			$this->byCssSelector('#input-status option[value="1"]')->click();

			$this->byCssSelector('.pull-right button.btn')->click();

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
