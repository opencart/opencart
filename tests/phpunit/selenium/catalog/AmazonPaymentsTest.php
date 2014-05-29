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
			
			$this->byCssSelector('i.fa-check-circle')->click();
			
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
			
			
			$this->waitToLoad('Modules');
			
			$this->byCssSelector('.table-bordered tbody tr:nth-child(3) td:last-child a.btn-primary')->click();
			
			$this->byCssSelector('button.btn-primary')->click();
		}
	}
	
	public function testOneProduct() {
		
	}
	
	private function waitToAppearAndClick($cssSelector, $timeout = 3000) {
		$this->waitUntil(function() use ($cssSelector) {
			$element = $this->byCssSelector($cssSelector);
			
			if ($element->displayed()) {
				return true;
			}
		}, $timeout);
		
		$this->byCssSelector($cssSelector)->click();
	}
	
	private function waitToLoad($title, $timeout = 3000) {
		$this->waitUntil(function() use ($title) {
			if ($this->title() == $title) {
				return true;
			}
		}, $timeout);
	}
	
}
