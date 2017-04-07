<?php

class CatalogModelCataloCategoryTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModel('catalog/category');
	}
	
	public function testGetCategory() {
		$category = $this->model_catalog_category->getCategory(25);
		$this->assertEquals($category['category_id'], 25);
		
		$category = $this->model_catalog_category->getCategory(0);
		$this->assertEmpty($category);
	}
	
	public function testGetCategories() {
		$categories = $this->model_catalog_category->getCategories(0);
		$this->assertCount(8, $categories);
		
		$categories = $this->model_catalog_category->getCategories(20);
		$this->assertCount(2, $categories);
	}
}
