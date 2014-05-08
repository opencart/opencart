<?php

class AdminControllerExtensionModificationTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {
	}
	
	public function testIndex() {
		$out = $this->dispatchAction("extension/modification")->getOutput();
	}
}