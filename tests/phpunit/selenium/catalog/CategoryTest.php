<?php

class CatalogCategoryTest extends OpenCartSeleniumTest {
	
	
	/**
	 * @before
	 */
	protected function setupTest() {
		$this->setBrowser('firefox');
		$this->setBrowserUrl(HTTP_SERVER);
	}
	
	public function testAddToCartButton() {
		$this->url('http://opencart.welfordlocal.co.uk/index.php?route=product/category&path=20');
		
		$addToCartButton = $this->byCssSelector('button[onclick="cart.add(\'28\');"]');
		$addToCartButton->click();
		
		$this->url('index.php?route=checkout/cart');
		$element = $this->byCssSelector('#accordion + br + .row .table-bordered tr:last-child td:last-child');
		$this->assertEquals('$119.50', $element->text());
	}
	
	
	public function testRedirect() {
		$this->url('http://opencart.welfordlocal.co.uk/index.php?route=product/category&path=20');
		
		$addToCartButton = $this->byCssSelector('button[onclick="cart.add(\'42\');"]');
		$addToCartButton->click();
		
		$this->waitUntil(function(){
			if (strpos($this->url(), 'product/product') !== False) {
				return true;
			}
		}, 3000);
	}
	
}
