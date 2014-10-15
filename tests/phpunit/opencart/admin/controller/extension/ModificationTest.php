<?php

class AdminControllerExtensionModificationTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {
	}
	
	public function testIndex() {
		$this->request->server['REQUEST_METHOD'] = 'GET';
		$out = $this->dispatchAction("extension/modification")->getOutput();
	}
}