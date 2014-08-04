<?php

class CatalogAmazonPaymentsTest extends OpenCartSeleniumTest {
	
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
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "order_amazon");
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "order_amazon_product");
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "order_amazon_report");
			$db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "order_total_tax");
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
			
			$this->waitToAppearAndClick('.table-bordered tbody tr:first-child td:last-child a.btn-success');
			$this->waitToAppearAndClick('.table-bordered tbody tr:first-child td:last-child a.btn-primary');
			
			$this->waitToLoad('Amazon Payments');
			
			$this->clickOnElement('amazon_checkout_merchant_id');
			$this->keys(AMAZON_PAYMENTS_SELLER_ID);
			
			$this->clickOnElement('amazon_checkout_access_key');
			$this->keys(AMAZON_PAYMENTS_ACCESS_KEY);
			
			$this->clickOnElement('amazon_checkout_access_secret');
			$this->keys(AMAZON_PAYMENTS_ACCESS_SECRET);
			
			$this->byCssSelector('#amazon_checkout_status option[value="1"]')->click();
			
			$this->byCssSelector('#amazon_checkout_marketplace option[value="' . AMAZON_PAYMENTS_COUNTRY . '"]')->click();
			
			$this->byCssSelector('#amazon_checkout_order_default_status option[value="1"]')->click();
			$this->byCssSelector('#amazon_checkout_order_ready_status option[value="2"]')->click();
			$this->byCssSelector('#amazon_checkout_order_shipped_status option[value="3"]')->click();
			$this->byCssSelector('#amazon_checkout_order_canceled_status option[value="7"]')->click();
			
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
			$this->waitToAppearAndClick('.table-bordered tbody tr:nth-child(3) td:last-child a.btn-success');
			$this->waitToAppearAndClick('.table-bordered tbody tr:nth-child(3) td:last-child a.btn-primary');
			
			$this->waitToLoad('Amazon Payments button'); 
			$this->byCssSelector('.fa-plus-circle')->click();
			
			for ($i = 1; ; $i++) {
				$element = $this->byCssSelector('select[name="amazon_checkout_layout_module[0][layout_id]"] option:nth-child(' . $i . ')');
				
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
		
		$this->clickOnElement('CBAWidgets0');

		$windowHandles = $this->windowHandles();
		
		$this->window($windowHandles[1]);
		
		$this->waitToLoad("Amazon Payments", 5000);
		
		$this->clickOnElement('ap_email');
		$this->keys(AMAZON_PAYMENTS_USERNAME);
		
		$this->clickOnElement('ap_password');
		$this->keys(AMAZON_PAYMENTS_PASSWORD);
		
		$this->clickOnElement('signInSubmit');
		
		$this->window($windowHandles[0]);
		
		$this->waitToLoad('Amazon Payments'); 
		
		// Address' Page
		sleep(3);
		
		$this->frame('CBAWidgets0IFrame');
		
		$this->byCssSelector('.cba-v2-widget-list div:nth-child(' . AMAZON_PAYMENTS_ADDRESS_POSITION . ')')->click();
		
		sleep(3);
		
		$this->window($windowHandles[0]);
		
		$this->byCssSelector("#continue-button")->click();
		
		sleep(3);
		// Payment method's page
		$this->frame('CBAWidgets0IFrame');
		
		$this->byCssSelector('.cba-v2-widget-list div:nth-child(' . AMAZON_PAYMENTS_CARDS_POSITION . ')')->click();
		
		sleep(3);
		
		$this->window($windowHandles[0]);
		
		$this->byCssSelector("#continue-button")->click();
		
		sleep(3);
		
		$this->byCssSelector("#confirm-button")->click();
		
		sleep(3);
		
		$element = $this->byCssSelector('h2');
		
		$this->assertEquals('Your order has been placed!', $element->text());
		
	}
}
