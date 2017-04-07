<?php

class CatalogModelCataloManufacturerTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModel('catalog/manufacturer');
	}
	
	public function testGetManufacturer() {
		$manufacturer = $this->model_catalog_manufacturer->getManufacturer(5);
		$this->assertNotEmpty($manufacturer);
		
		$manufacturer = $this->model_catalog_manufacturer->getManufacturer(0);
		$this->assertEmpty($manufacturer);
	}
	
	public function testGetManufacturers() {
		$filters = array(
			'sort' => 'name',
		);
		
		$manufacturers = $this->model_catalog_manufacturer->getManufacturers($filters);
		$manufacturerIds = array(8, 9, 7, 5, 6, 10);
		$actualManufacturerIds = array();
		
		foreach ($manufacturers as $manufacturer) {
			$actualManufacturerIds[] = $manufacturer['manufacturer_id'];
		}
		
		$this->assertEquals($manufacturerIds, $actualManufacturerIds);
	}
}
