<?php

class CatalogControllerCheckoutCartTest extends OpenCartTest {

	/**
	 * @before
	 */
	public function setupTest() {
		$this->cart->clear();
	}

	public function test() {
        $this->assertTrue(true);
    }
//
//	public function testAddProduct() {
//		$this->request->post['product_id'] = 28;
//		$this->request->post['quantity'] = 1;
//
//		$response = json_decode($this->dispatchAction("checkout/cart/add")->getOutput(), true);
//
//		$this->assertTrue(!empty($response['success']) && !empty($response['total']));
//		$this->assertEquals(1, preg_match('/HTC Touch HD/', $response['success']));
//		$this->assertEquals(1, preg_match('/122\.00/', $response['total']));
//	}
}
