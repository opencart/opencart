<?php

class CatalogCheckoutTest extends OpenCartSeleniumTest {
	
	
	/**
	 * @before
	 */
	protected function setupTest() {
		$this->setBrowser('firefox');
		$this->setBrowserUrl(HTTP_SERVER);
	}
	
	public function testUpdateQuantity() {
		$this->addProductsToCart();
		
		$element = $this->byCssSelector('.table-bordered tbody tr:last-child input');
		$this->assertEquals('3', $element->value());
		
		$element = $this->byCssSelector('.table-bordered tbody tr:first-child input');
		$this->assertEquals('1', $element->value());
		
		$element->clear();
		$element->click();
		$this->keys('2');
		
		$this->byCssSelector('.table-bordered tbody tr:first-child button.btn-primary')->click();
		
		sleep(3);
				
		$element = $this->byCssSelector('.table-bordered tbody tr:first-child input');
		$this->assertEquals('2', $element->value());
				
		$element = $this->byCssSelector('.table-bordered tbody tr:last-child input');
		$this->assertEquals('3', $element->value());
	}
	
	public function testRemoveProduct() {
		$this->addProductsToCart();
		
		$element = $this->byCssSelector('form .table-bordered tbody tr:first-child td:nth-child(2)');
		$this->assertStringStartsWith('MacBook', $element->text());
		
		$this->byCssSelector('.table-bordered tbody tr:first-child button.btn-danger')->click();
		
		sleep(3);
		
		$element = $this->byCssSelector('form .table-bordered tbody tr:first-child td:nth-child(2)');
		$this->assertStringStartsWith('Sony VAIO', $element->text());
	}
	
	private function addProductsToCart() {
		$this->url('index.php?route=product/product&product_id=43');
		$this->byId('button-cart')->click();
		
		$this->url('index.php?route=product/product&product_id=46');
		$this->byId('input-quantity')->clear();
		$this->clickOnElement('input-quantity');
		$this->keys('3');
		$this->byId('button-cart')->click();
		
		$this->url('index.php?route=checkout/cart');
	}
}
