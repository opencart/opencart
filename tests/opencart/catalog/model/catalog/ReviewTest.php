<?php

class CatalogModelCatalogReviewTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModelByRoute('catalog/review');
		$this->db->query("DELETE FROM " . DB_PREFIX . "review");
	}
	
	/**
	 * @after
	 */
	public function completeTest() {
		$this->loadModelByRoute('catalog/review');
		$this->db->query("DELETE FROM " . DB_PREFIX . "review");
	}
	
	public function testAddReviews() {		
		$productId = 0;
		$data = array(
			'name' => "Reviewer's name",
			'text' => 'Review',
			'rating' => 0,
		);
		
		for ($i = 0; $i < 5; $i++) {
			$this->model_catalog_review->addReview($productId, $data);
		}
		
		$reviewCount = (int)$this->db->query("SELECT COUNT(*) AS review_num FROM " . DB_PREFIX . "review")->row['review_num'];
		$this->assertEquals(5, $reviewCount);
	}
	
}
