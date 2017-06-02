<?php

class CatalogModelCatalogReviewTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModel('catalog/review');
		$this->db->query("DELETE FROM " . DB_PREFIX . "review");
	}
	
	/**
	 * @after
	 */
	public function completeTest() {
		$this->loadModel('catalog/review');
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
	
	public function testGetReviews() {
		$productId = 28;
		$data = array(
			'name' => "Reviewer's name",
			'text' => 'Review',
			'rating' => 0,
		);
		
		for ($i = 0; $i < 5; $i++) {
			$this->model_catalog_review->addReview($productId, $data);
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "review SET `status` = 1");
		
		$reviews = $this->model_catalog_review->getReviewsByProductId($productId);
		
		$this->assertCount(5, $reviews);
	}
	
	public function testGetReviewCount() {
		$productId = 28;
		$data = array(
			'name' => "Reviewer's name",
			'text' => 'Review',
			'rating' => 0,
		);
		
		for ($i = 0; $i < 5; $i++) {
			$this->model_catalog_review->addReview($productId, $data);
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "review SET `status` = 1");
		
		$reviewCount = $this->model_catalog_review->getTotalReviewsByProductId($productId);
		
		$this->assertEquals(5, $reviewCount);
	}
}
