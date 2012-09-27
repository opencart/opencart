<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$category_id = $this->db->getLastId();
				
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
						
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('category');
	}
	
	public function editCategory($category_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "category SET `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		
		if (isset($data['category_store'])) {		
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
						
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('category');
	}
	
	public function deleteCategory($category_id) {
		
		// Deleting a node
		$category_info = $this->getCategory($data['parent_id']);
		
		$this->db->query("DELETE FROM nested_category WHERE `LEFT` BETWEEN '" . (int)$category_info['left'] . "' AND '" . (int)$category_info['right'] . "'");
		
		$this->db->query("UPDATE nested_category SET rgt = rgt - @myWidth WHERE rgt > @myRight");
		$this->db->query("UPDATE nested_category SET lft = lft - @myWidth WHERE lft > @myRight");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");
		
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}
		
		$this->cache->delete('category');
	} 
	
	public function moveCategory($category_id, $data) {
		// Adding a node
		if ($data['parent_id']) {
			$category_info = $this->getCategory($data['parent_id']);
		
			// Add to the right
			if ($data['position'] == 'next') { 
				$this->db->query("UPDATE " . DB_PREFIX . "category SET `right` = `right` + 2 WHERE `right` > '" . (int)$category_info['right'] . "'");
				$this->db->query("UPDATE " . DB_PREFIX . "category SET `left` = `left` + 2 WHERE `left` > '" . (int)$category_info['right'] . "'");
					
				$this->db->query("UPDATE " . DB_PREFIX . "category SET `left` = '" . (int)($category_info['right'] + 1) . "', `right` = '" . (int)($category_info['right'] + 2) . "' WHERE category_id = '" . (int)$category_id . "'");
			} elseif ($data['position'] == 'child') {
				// Add as a child
				$this->db->query("UPDATE " . DB_PREFIX . "category SET `right` = `right` + 2 WHERE `right` > '" . (int)$category_info['left'] . "'");
				$this->db->query("UPDATE " . DB_PREFIX . "category SET `left` = `left` + 2 WHERE `left` > '" . (int)$category_info['left'] . "'");
					
				$this->db->query("UPDATE " . DB_PREFIX . "category SET `left` = '" . (int)($category_info['left'] + 1) . "', `right` = '" . (int)($category_info['left'] + 2) . "' WHERE category_id = '" . (int)$category_id . "'");
			}
		}	
		
		
		/*
		// cat_b.lft + 1 is the destination. 
		SELECT @destination := (lft + 1)
		FROM category
		WHERE name = cat_b;
		
		SELECT @cat_a_width := ((rgt - lft) + 1)
		FROM category
		WHERE name = cat_a;
		
		// Rip this table a new cat_a sized hole inside cat_b.
		UPDATE category SET rgt = rgt + @cat_a_width WHERE rgt >= @destination;
		UPDATE category SET lft = lft + @cat_a_width WHERE lft >= @destination;
		
		SELECT @cat_a_lft := lft, @cat_a_rgt := rgt
		FROM category
		WHERE name = cat_a;
		
		SELECT @diff := @destination - @cat_a_lft;
		
		// Move cat_a and all inhabitants to new hole  
		UPDATE category SET rgt = rgt + @diff WHERE rgt BETWEEN @cat_a_lft AND @cat_a_rgt;
		UPDATE category SET lft = lft + @diff WHERE lft BETWEEN @cat_a_lft AND @cat_a_rgt;
		
		// Close the gap created when we moved cat_a.
		UPDATE category SET rgt = rgt - @cat_a_width WHERE rgt >= @cat_a_lft;
		UPDATE category SET lft = lft - @cat_a_width WHERE lft >= @cat_a_lft;
		
		
		
$iItemId = 1;
$iItemPosLeft = 0;
$iItemPosRight = 1;
$iParentId = 2;
$iParentPosRight = 4;
$iSize = $iPosRight - $iPosLeft + 1;
$sql = array(

    // step 1: temporary "remove" moving node

    'UPDATE `list_items`
    SET `pos_left` = 0-(`pos_left`), `pos_right` = 0-(`pos_right`)
    WHERE `pos_left` >= "'.$iItemPosLeft.'" AND `pos_right` <= "'.$iItemPosRight.'"',

    // step 2: decrease left and/or right position values of currently 'lower' items (and parents)

    'UPDATE `list_items`
    SET `pos_left` = `pos_left` - '.$iSize.'
    WHERE `pos_left` > "'.$iItemPosRight.'"',
    'UPDATE `list_items`
    SET `pos_right` = `pos_right` - '.$iSize.'
    WHERE `pos_right` > "'.$iItemPosRight.'"',

    // step 3: increase left and/or right position values of future 'lower' items (and parents)

    'UPDATE `list_items`
    SET `pos_left` = `pos_left` + '.$iSize.'
    WHERE `pos_left` >= "'.($iParentPosRight > $iItemPosRight ? $iParentPosRight - $iSize : $iParentPosRight).'"',
    'UPDATE `list_items`
    SET `pos_right` = `pos_right` + '.$iSize.'
    WHERE `pos_right` >= "'.($iParentPosRight > $iItemPosRight ? $iParentPosRight - $iSize : $iParentPosRight).'"',

    // step 4: move node (ant it's subnodes) and update it's parent item id

    'UPDATE `list_items`
    SET
        `pos_left` = 0-(`pos_left`)+'.($iParentPosRight > $iItemPosRight ? $iParentPosRight - $iItemPosRight - 1 : $iParentPosRight - $iItemPosRight - 1 + $iSize).',
        `pos_right` = 0-(`pos_right`)+'.($iParentPosRight > $iItemPosRight ? $iParentPosRight - $iItemPosRight - 1 : $iParentPosRight - $iItemPosRight - 1 + $iSize).'
    WHERE `pos_left` <= "'.(0-$iItemPosLeft).'" AND i.`pos_right` >= "'.(0-$iItemPosRight).'"',
    'UPDATE `list_items`
    SET `parent_item_id` = "'.$iParentItemId.'"
    WHERE `item_id`="'.$iItemId.'"'
);
foreach($sql as $sqlQuery){
    mysql_query($sqlQuery);
}		
		*/
		
	}
	
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	} 
	
	public function getCategories($data) {
		$sql = "SELECT c1.category_id, cd1.name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category c1 LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c1.category_id = cd1.category_id), " . DB_PREFIX . "category c2 LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c2.category_id = cd2.category_id) WHERE c1.`left` BETWEEN c2.`left` AND c2.`right` AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(cd1.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		$sql .= " GROUP BY c1.category_id ORDER BY c1.`left`, c1.sort_order";
		
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
		
	public function getPath($category_id) {
		$query = $this->db->query("SELECT cd2.name FROM " . DB_PREFIX . "category AS c1 LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c1.category_id = cd1.category_id), " . DB_PREFIX . "category AS c2 LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c2.category_id = cd2.category_id) WHERE c1.`left` BETWEEN c2.`left` AND c2.`right` AND c1.category_id = '" . (int)$category_id . "' ORDER BY c2.`left`");			
	
		return $query->rows;
	}
			
	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $category_description_data;
	}	
	
	public function getCategoryStores($category_id) {
		$category_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}
		
		return $category_store_data;
	}

	public function getCategoryLayouts($category_id) {
		$category_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $category_layout_data;
	}
		
	public function getTotalCategories() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");
		
		return $query->row['total'];
	}	
		
	public function getTotalCategoriesByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
	}

	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}		
}
?>