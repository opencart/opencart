<?php
namespace Opencart\Admin\Model\Blog;

/**
 *  Class Article
 *
 * @package Opencart\Admin\Model\Design
 */
class Article extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function add(array $data): int {
		$blog_author_id = intval($data['blog_author_id'] ?? 0);
		if($blog_author_id === 0){
			$blog_author_id = null;
		}

		$status = $this->request->post['status'] ? '1' : '0';

		// Add the article.
		$this->db->query(
			"INSERT INTO `" . DB_PREFIX . "blog_article` SET " .
			"`name` = '" . $this->db->escape((string)$data['name']) . "', " .
			"`image` = '" . $this->db->escape((string)$data['image']) . "', " .
			"`blog_author_id` = '" . $blog_author_id . "', " .
			"`view_count` = 0, " .
			"`status` = '" . $this->db->escape((string)$status) . "', " .
			"`date_added` = NOW(), " .
			"`date_modified` = NOW() "
		);

		$blog_article_id = $this->db->getLastId();

		// Add the contents.
		if(!empty($data['blog_article_content'])){
			foreach($data['blog_article_content'] as $language_id => $blog_content){
				$this->db->query(
					"INSERT INTO `" . DB_PREFIX . "blog_article_content` SET " .
					"`title` = '" . $this->db->escape((string)$blog_content['title']) . "', " .
					"`description` = '" . $this->db->escape((string)$blog_content['description']) . "', " .
					"`content` = '" . $this->db->escape((string)$blog_content['content']) . "', " .
					"`language_id` = '" . intval($language_id) . "', " .
					"`blog_article_id` = '" . intval($blog_article_id) . "' "
				);
			}
		}

		// Add the store relations.
		$article_stores = $data['article_store_id'];
		if(!is_array($article_stores)){
			$article_stores = [$article_stores];
		}

		$article_stores = array_map(function($item){
			return intval($item);
		}, $article_stores);

		$article_stores = array_unique($article_stores, SORT_NUMERIC);

		foreach ($article_stores as $article_store_id){
			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "blog_store_to_article` SET " .
				"`store_id` = '" . $article_store_id. "', " .
				"`blog_article_id` = '" . intval($blog_article_id) . "', " .
				"`view_count` = 0 "
			);
		}

		// Add the tags.
		if(!empty($data['tags'])) {
			foreach ($data['tags'] as $language_id => $tags) {
				foreach($tags as $tag){
					$this->db->query(
						"INSERT INTO `" . DB_PREFIX . "blog_tag_to_article` SET " .
						"`tag` = '" . $this->db->escape((string)$tag) . "', " .
						"`language_id` = '" . $this->db->escape((int)$language_id) . "', " .
						"`blog_article_id` = '" . intval($blog_article_id) . "' "
					);
				}
			}
		}

		return $blog_article_id;
	}

	/**
	 * @param int   $blog_article_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function edit(int $blog_article_id, array $data): void {
		$blog_author_id = intval($data['blog_author_id'] ?? 0);
		if($blog_author_id === 0){
			$blog_author_id = null;
		}

		$status = intval($data['status']) === 1 ? '1' : '0';

		// Update the article.
		$this->db->query(
			"UPDATE `" . DB_PREFIX . "blog_article` SET " .
			"`name` = '" . $this->db->escape((string)$data['name']) . "', " .
			"`image` = '" . $this->db->escape((string)$data['image']) . "', " .
			"`blog_author_id` = '" . $blog_author_id . "', " .
			"`status` = '" .  $status . "', " .
			"`date_modified` = NOW() ".
			" WHERE blog_article_id = '" . $blog_article_id . "' LIMIT 1"
		);

		// Update the contents.
		if(!empty($data['blog_article_content'])){
			foreach($data['blog_article_content'] as $language_id => $blog_content){
				$query = $this->db->query("SELECT blog_article_id FROM `" . DB_PREFIX . "blog_article_content` WHERE `language_id` = '" . intval($language_id) . "' AND `blog_article_id` = '" . intval($blog_article_id) . "' LIMIT 1" );
				if($query->num_rows > 0){
					$this->db->query(
						"UPDATE `" . DB_PREFIX . "blog_article_content` SET " .
						"`title` = '" . $this->db->escape((string)$blog_content['title']) . "', " .
						"`description` = '" . $this->db->escape((string)$blog_content['description']) . "', " .
						"`content` = '" . $this->db->escape((string)$blog_content['content']) . "' " .
						" WHERE `language_id` = '" . intval($language_id) . "' AND " .
						"`blog_article_id` = '" . intval($blog_article_id) . "' "
					);
				}else{
					$this->db->query(
						"INSERT INTO `" . DB_PREFIX . "blog_article_content` SET " .
						"`title` = '" . $this->db->escape((string)$blog_content['title']) . "', " .
						"`description` = '" . $this->db->escape((string)$blog_content['description']) . "', " .
						"`content` = '" . $this->db->escape((string)$blog_content['content']) . "', " .
						"`language_id` = '" . intval($language_id) . "', " .
						"`blog_article_id` = '" . intval($blog_article_id) . "' "
					);
				}
			}
		}

		// Add the store relations if not exists.
		$article_stores = $data['article_store_id'];
		if(!is_array($article_stores)){
			$article_stores = [$article_stores];
		}

		$article_stores = array_map(function($item){
			return intval($item);
		}, $article_stores);

		$article_stores = array_unique($article_stores, SORT_NUMERIC);

		foreach ($article_stores as $article_store_id){
			$this->db->query(
				"INSERT IGNORE INTO `" . DB_PREFIX . "blog_store_to_article` SET " .
				"`store_id` = '" . $article_store_id. "', " .
				"`blog_article_id` = '" . intval($blog_article_id) . "', " .
				"`view_count` = 0 "
			);
		}

		// Add the tags if not exists.
		if(!empty($data['tags'])) {
			$available_tags = [];

			foreach ($data['tags'] as $language_id => $tags) {
				foreach($tags as $tag) {
					$available_tags[] = "'" . $this->db->escape((string)$tag) . "'";

					$this->db->query(
						"INSERT IGNORE INTO `" . DB_PREFIX . "blog_tag_to_article` SET " .
						"`tag` = '" . $this->db->escape((string)$tag) . "', " .
						"`language_id` = '" . $this->db->escape((int)$language_id) . "', " .
						"`blog_article_id` = '" . intval($blog_article_id) . "' "
					);
				}
			}

			// Delete the tags that are not used anymore.
			$this->db->query(
				"DELETE FROM `" . DB_PREFIX . "blog_tag_to_article` WHERE " .
				"`tag` NOT IN (" . implode(', ', $available_tags) . "), " .
				"`language_id` = '" . $this->db->escape((int)$language_id) . "', " .
				"`blog_article_id` = '" . intval($blog_article_id) . "' "
			);
		}
	}

	/**
	 * @param int $blog_article_id
	 *
	 * @return void
	 */
	public function delete(int $blog_article_id): void {
		// Delete its tags.
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_tag_to_article` WHERE `blog_article_id` = '" . (int)$blog_article_id . "'");
		// Delete its store relations.
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_store_to_article` WHERE `blog_article_id` = '" . (int)$blog_article_id . "'");
		// Delete the contents.
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_article_content` WHERE `blog_article_id` = '" . (int)$blog_article_id . "'");
		// Delete the article.
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_article` WHERE `blog_article_id` = '" . (int)$blog_article_id . "'");
	}

	/**
	 * @param int $blog_article_id
	 *
	 * @return array
	 */
	public function getArticle(int $blog_article_id): array {
		$query = $this->db->query("SELECT ba.*, bat.fullname as author_name  FROM `" . DB_PREFIX . "blog_article` ba LEFT JOIN `" . DB_PREFIX . "blog_author` bat ON (ba.blog_author_id =  bat.blog_author_id) WHERE `blog_article_id` = '" . (int)$blog_article_id . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getArticles(array $data = []): array {
		$sql = "SELECT ba.*, bat.fullname as author_name  FROM `" . DB_PREFIX . "blog_article` ba";

		if (!empty($data['tag'])) {
			$sql .= " INNER JOIN `" . DB_PREFIX . "blog_tag_to_article` btta ON (btta.blog_article_id =  ba.blog_article_id and btta.tag = " . $this->db->escape((string)$data['tag']) . ") ";
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "blog_author` bat ON (ba.blog_author_id =  bat.blog_author_id) ";

		// Sorting fields.
		$sort_data = [
			'blog_article_id'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY ba.`" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY ba.`blog_article_id`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * @param array $data
	 * @return int
	 */
	public function getTotalArticles(array $data = []): int {
		$query = $this->db->query("SELECT COUNT(ba.blog_article_id) AS `total` FROM `" . DB_PREFIX . "blog_article` ba");

		if (!empty($data['tag'])) {
			$query .= " INNER JOIN `blog_tag_to_article` btta ON (btta.blog_article_id =  ba.blog_article_id and btta.tag = " . $this->db->escape((string)$data['tag']) . ") ";
		}

		return (int)$query->row['total'];
	}

	/**
	 * @param int $blog_article_id
	 * @return array
	 */
	public function getAllTags(int $blog_article_id): array{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_tag_to_article` WHERE blog_article_id = " . $blog_article_id);
		$result = [];
		foreach ($query->rows as $row){
			$language_id = intval($row['language_id']);
			if(!isset($result[$language_id])){
				$result[$language_id] = [];
			}
			$result[$language_id][] = $row['tag'];
		}
		return $result;
	}

	/**
	 * @param int $blog_article_id
	 * @return array
	 */
	public function getStores(int $blog_article_id): array{
		$query = $this->db->query("SELECT bsta.*, s.name as store_name FROM `" . DB_PREFIX . "blog_store_to_article` bsta LEFT JOIN `" . DB_PREFIX . "store` s ON (bsta.store_id = s.store_id) WHERE bsta.blog_article_id = " . $blog_article_id);
		$result = [];
		foreach ($query->rows as $row){
			$result[] = [
				'store_id' => $row['store_id'],
				'store_name' => $row['store_name'] === null ? $this->language->get('text_default') : $row['store_name'],
				'view_count' => $row['view_count']
			];
		}
		return $result;
	}

	/**
	 * @param int $blog_article_id
	 * @return array
	 */
	public function getContents(int $blog_article_id): array{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_article_content` WHERE blog_article_id = " . $blog_article_id);
		$result = [];
		foreach ($query->rows as $row){
			$result[$row['language_id']] = [
				'blog_article_id' 	=> $row['blog_article_id'],
				'language_id' 		=> $row['language_id'],
				'title' 			=> $row['title'],
				'description' 		=> $row['description'],
				'content'			=> $row['content'],
			];
		}
		return $result;
	}
}
