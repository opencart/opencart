<?php

class CatalogModelCatalogInformationTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModel('catalog/information');
	}
	
	public function testGetInformation() {
		$information = $this->model_catalog_information->getInformation(3);
		$this->assertNotEmpty($information);
	}
	
	public function testGetInformations() {
		$information = $this->model_catalog_information->getInformations();
		
		$this->assertNotEmpty($information);
	}
	
	public function testGetInformationLayoutId() {
		$information = $this->model_catalog_information->getInformationLayoutId(0);
		$this->assertEmpty($information);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "information_to_layout SET information_id = 3, layout_id = 1");
		$layoutId = $this->model_catalog_information->getInformationLayoutId(3);
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_layout");
		$this->assertEquals(1, $layoutId);
	}
	
}
