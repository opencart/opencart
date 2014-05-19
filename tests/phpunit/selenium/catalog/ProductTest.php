<?php

class CatalogProductTest extends OpenCartSeleniumTest {
	
	
	/**
	 * @before
	 */
	protected function setupTest() {
		$this->setBrowser('firefox');
		$this->setBrowserUrl(HTTP_SERVER);
	}
	
	public function testAddToCartButton() {
		$this->url('index.php?route=product/product&product_id=43');
		$this->clickOnElement('button-cart');

		$this->url('index.php?route=checkout/cart');
		$element = $this->byCssSelector('#accordion + br + .row .table-bordered tr:last-child td:last-child');
		$this->assertEquals('$589.50', $element->text());
	}
	
	public function testQuantityField() {
		$this->url('index.php?route=product/product&product_id=43');
		$inputElement = $this->byId('input-quantity');
		$inputElement->clear();
		
		$this->clickOnElement('input-quantity');
		$this->keys('3');
		
		$this->clickOnElement('button-cart');

		$this->url('index.php?route=checkout/cart');
		$element = $this->byCssSelector('#accordion + br + .row .table-bordered tr:last-child td:last-child');
		$this->assertEquals('$1,768.50', $element->text());
	}
	
	public function testWishListButton() {
		$this->url('index.php?route=product/product&product_id=43');
		$element = $this->byCssSelector('i.fa-heart:last-child');
		$element->click();
	
		$this->waitUntil(function() {
			if ($this->byCssSelector('.alert-success')) {
				return true;
			}
		}, 2000);
	}
	
	public function testCompareButton() {
		$this->url('index.php?route=product/product&product_id=43');
		$element = $this->byCssSelector('i.fa-exchange');
		$element->click();
	
		$this->waitUntil(function() {
			if ($this->byCssSelector('.alert-success')) {
				return true;
			}
		}, 2000);
	}

}
