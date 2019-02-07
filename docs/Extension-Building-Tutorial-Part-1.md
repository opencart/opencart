Creating a Blog CMS within OpenCart.

* [DB](#db)
* [Administration](#db)
  * [Creating the Controller](#db)
  * [Creating the Model](#db)
  * [Creating the View](#db)
* [Store Front](#db)
  * [Creating the Controller](#db)
  * [Creating the Model](#db)
  * [Creating the View](#db)

## 1. DB

Whenever I start building a new application i normally start with the DB structure then work around that to create the controllers, models and views. 

###### Blog Table

```
CREATE TABLE `oc_blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(3) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime,
  PRIMARY KEY (`blog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

###### Blog Description Table

```
CREATE TABLE `oc_blog_description` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` mediumtext NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`blog_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
```
 
Admin Controller
 
Admin Model
```
<?php
class ModelCatalogBlog extends Model {
	public function addBlog($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "blog SET sort_order = '" . (int)$data['sort_order'] . "', image = '" . (isset($data['image']) ? (int)$data['image'] : 0) . "', status = '" . (int)$data['status'] . "'");

		$blog_id = $this->db->getLastId();

		foreach ($data['blog_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "blog_description SET blog_id = '" . (int)$blog_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->cache->delete('blog');

		return $blog_id;
	}

	public function editBlog($blog_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "blog SET sort_order = '" . (int)$data['sort_order'] . "', image = '" . (isset($data['image']) ? $data['image'] : '') . "', status = '" . (int)$data['status'] . "' WHERE blog_id = '" . (int)$blog_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "blog_description WHERE blog_id = '" . (int)$blog_id . "'");

		foreach ($data['blog_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "blog_description SET blog_id = '" . (int)$blog_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->cache->delete('blog');
	}

	public function deleteBlog($blog_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog` WHERE blog_id = '" . (int)$blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_description` WHERE blog_id = '" . (int)$blog_id . "'");

		$this->cache->delete('blog');
	}

	public function getBlog($blog_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog WHERE blog_id = '" . (int)$blog_id . "'");

		return $query->row;
	}

	public function getBlogs($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "blog i LEFT JOIN " . DB_PREFIX . "blog_description id ON (i.blog_id = id.blog_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'id.title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
		} else {
			$blog_data = $this->cache->get('blog.' . (int)$this->config->get('config_language_id'));

			if (!$blog_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog i LEFT JOIN " . DB_PREFIX . "blog_description id ON (i.blog_id = id.blog_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$blog_data = $query->rows;

				$this->cache->set('blog.' . (int)$this->config->get('config_language_id'), $blog_data);
			}

			return $blog_data;
		}
	}

	public function getBlogDescriptions($blog_id) {
		$blog_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_description WHERE blog_id = '" . (int)$blog_id . "'");

		foreach ($query->rows as $result) {
			$blog_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $blog_description_data;
	}

	public function getTotalBlogs() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog");

		return $query->row['total'];
	}

	public function install() {
		$this->db->query("CREATE TABLE `" . DB_PREFIX . "_blog` (
		  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
		  `image` varchar(255) DEFAULT NULL,
		  `status` tinyint(1) NOT NULL,
		  PRIMARY KEY (`blog_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		
		$this->db->query("CREATE TABLE `oc_blog_description` (
		  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
		  `language_id` int(11) NOT NULL,
		  `title` varchar(64) NOT NULL,
		  `description` mediumtext NOT NULL,
		  `meta_title` varchar(255) NOT NULL,
		  `meta_description` varchar(255) NOT NULL,
		  `meta_keyword` varchar(255) NOT NULL,
		  PRIMARY KEY (`blog_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
	}

	public function uninstall(){
		$this->db->query("DROP TABLE `" . DB_PREFIX . "blog`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "blog_description`");
	}
}
```

 
Admin View
 
Catalog
 
Creating the Controller
 
Creating the Model


 
Creating the View
 
Creating the installer
