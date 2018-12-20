<?php

use \googleshopping\exception\Connection as ConnectionException;
use \googleshopping\Googleshopping;

class ModelExtensionAdvertiseGoogle extends Model {
    private $events = array(
        'admin/view/common/column_left/before' => array(
            'extension/advertise/google/admin_link',
        ),
        'admin/model/catalog/product/addProduct/after' => array(
            'extension/advertise/google/addProduct',
        ),
        'admin/model/catalog/product/copyProduct/after' => array(
            'extension/advertise/google/copyProduct',
        ),
        'admin/model/catalog/product/deleteProduct/after' => array(
            'extension/advertise/google/deleteProduct',
        ),
        'catalog/controller/checkout/success/before' => array(
            'extension/advertise/google/before_checkout_success'
        ),
        'catalog/view/common/header/after' => array(
            'extension/advertise/google/google_global_site_tag'
        ),
        'catalog/view/common/success/after' => array(
            'extension/advertise/google/google_dynamic_remarketing_purchase'
        ),
        'catalog/view/product/product/after' => array(
            'extension/advertise/google/google_dynamic_remarketing_product'
        ),
        'catalog/view/product/search/after' => array(
            'extension/advertise/google/google_dynamic_remarketing_searchresults'
        ),
        'catalog/view/product/category/after' => array(
            'extension/advertise/google/google_dynamic_remarketing_category'
        ),
        'catalog/view/common/home/after' => array(
            'extension/advertise/google/google_dynamic_remarketing_home'
        ),
        'catalog/view/checkout/cart/after' => array(
            'extension/advertise/google/google_dynamic_remarketing_cart'
        )
    );

    public function isAppIdUsed($app_id, $store_id) {
        $sql = "SELECT `store_id` FROM `" . DB_PREFIX . "setting` WHERE `key`='advertise_google_app_id' AND `value`='" . $this->db->escape($store_id) . "' AND `store_id`!=" . (int)$store_id . " LIMIT 1";

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            try {
                $googleshopping = new Googleshopping($this->registry, (int)$result->row['store_id']);

                return $googleshopping->isConnected();
            } catch (\RuntimeException $e) {
                return false;
            }
        }

        return false;
    }

    public function getFinalProductId() {
        $sql = "SELECT product_id FROM `" . DB_PREFIX . "product` ORDER BY product_id DESC LIMIT 1";

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return (int)$result->row['product_id'];
        }

        return null;
    }

    public function isAnyProductCategoryModified($store_id) {
        $sql = "SELECT pag.is_modified FROM `" . DB_PREFIX . "product_advertise_google` pag WHERE pag.google_product_category IS NOT NULL AND pag.store_id=" . (int)$store_id . " LIMIT 0,1";

        return $this->db->query($sql)->num_rows > 0;
    }

    public function getMapping($store_id) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "category_to_google_product_category` WHERE store_id=" . (int)$store_id;

        return $this->db->query($sql)->rows;
    }

    public function setCategoryMapping($google_product_category, $store_id, $category_id) {
        $sql = "INSERT INTO `" . DB_PREFIX . "category_to_google_product_category` SET `google_product_category`='" . $this->db->escape($google_product_category) . "', `store_id`=" . (int)$store_id . ", `category_id`=" . (int)$category_id . " ON DUPLICATE KEY UPDATE `category_id`=" . (int)$category_id;

        $this->db->query($sql);
    }

    public function getMappedCategory($google_product_category, $store_id) {
        $sql = "SELECT GROUP_CONCAT(cd.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, cp.category_id FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd ON (cp.path_id = cd.category_id) LEFT JOIN `" . DB_PREFIX . "category_to_google_product_category` c2gpc ON (c2gpc.category_id = cp.category_id) WHERE cd.language_id=" . (int)$this->config->get('config_language_id') . " AND c2gpc.google_product_category='" . $this->db->escape($google_product_category) . "' AND c2gpc.store_id=" . (int)$store_id;

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result->row;
        }

        return null;
    }

    public function getProductByProductAdvertiseGoogleId($product_advertise_google_id) {
        $sql = "SELECT pag.product_id FROM `" . DB_PREFIX . "product_advertise_google` pag WHERE pag.product_advertise_google_id=" . (int)$product_advertise_google_id;

        $result = $this->db->query($sql);

        if ($result->num_rows) {
            $this->load->model('catalog/product');

            return $this->model_catalog_product->getProduct($result->row['product_id']);
        }
    }

    public function getProductAdvertiseGoogle($product_advertise_google_id) {
        $sql = "SELECT pag.* FROM `" . DB_PREFIX . "product_advertise_google` pag WHERE pag.product_advertise_google_id=" . (int)$product_advertise_google_id;

        return $this->db->query($sql)->row;
    }

    public function hasActiveTarget($store_id) {
        $sql = "SELECT agt.advertise_google_target_id FROM `" . DB_PREFIX . "advertise_google_target` agt WHERE agt.store_id=" . (int)$store_id . " AND agt.status='active' LIMIT 1";

        return $this->db->query($sql)->num_rows > 0;
    }

    public function getRequiredFieldsByProductIds($product_ids, $store_id) {
        $this->load->config('googleshopping/googleshopping');

        $result = array();
        $countries = $this->getTargetCountriesByProductIds($product_ids, $store_id);

        foreach ($countries as $country) {
            foreach ($this->config->get('advertise_google_country_required_fields') as $field => $requirements) {
                if (
                    (!empty($requirements['countries']) && in_array($country, $requirements['countries']))
                        ||
                    (is_array($requirements['countries']) && empty($requirements['countries']))
                ) {
                    $result[$field] = $requirements;
                }
            }
        }

        return $result;
    }

    public function getRequiredFieldsByFilter($data, $store_id) {
        $this->load->config('googleshopping/googleshopping');

        $result = array();
        $countries = $this->getTargetCountriesByFilter($data, $store_id);

        foreach ($countries as $country) {
            foreach ($this->config->get('advertise_google_country_required_fields') as $field => $requirements) {
                if (
                    (!empty($requirements['countries']) && in_array($country, $requirements['countries']))
                        ||
                    (is_array($requirements['countries']) && empty($requirements['countries']))
                ) {
                    $result[$field] = $requirements;
                }
            }
        }

        return $result;
    }

    public function getTargetCountriesByProductIds($product_ids, $store_id) {
        $sql = "SELECT DISTINCT agt.country FROM `" . DB_PREFIX . "product_advertise_google_target` pagt LEFT JOIN `" . DB_PREFIX . "advertise_google_target` agt ON (agt.advertise_google_target_id = pagt.advertise_google_target_id AND agt.store_id = pagt.store_id) WHERE pagt.product_id IN (" . $this->googleshopping->productIdsToIntegerExpression($product_ids) . ") AND pagt.store_id=" . (int)$store_id;

        return array_map(array($this, 'country'), $this->db->query($sql)->rows);
    }

    public function getTargetCountriesByFilter($data, $store_id) {
        $sql = "SELECT DISTINCT agt.country FROM `" . DB_PREFIX . "product_advertise_google_target` pagt LEFT JOIN `" . DB_PREFIX . "advertise_google_target` agt ON (agt.advertise_google_target_id = pagt.advertise_google_target_id AND agt.store_id = pagt.store_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (pagt.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = pagt.product_id) WHERE pagt.store_id=" . (int)$store_id . " AND pd.language_id=" . (int)$this->config->get('config_language_id');

        $this->googleshopping->applyFilter($sql, $data);

        return array_map(array($this, 'country'), $this->db->query($sql)->rows);
    }

    public function getProductOptionsByProductIds($product_ids) {
        $sql = "SELECT po.option_id, od.name FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option_description` od ON (od.option_id=po.option_id AND od.language_id=" . (int)$this->config->get('config_language_id') . ") LEFT JOIN `" . DB_PREFIX . "option` o ON (o.option_id = po.option_id) WHERE o.type IN ('select', 'radio') AND po.product_id IN (" . $this->googleshopping->productIdsToIntegerExpression($product_ids) . ")";

        return $this->db->query($sql)->rows;
    }

    public function getProductOptionsByFilter($data) {
        $sql = "SELECT DISTINCT po.option_id, od.name FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option_description` od ON (od.option_id=po.option_id AND od.language_id=" . (int)$this->config->get('config_language_id') . ") LEFT JOIN `" . DB_PREFIX . "option` o ON (o.option_id = po.option_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (po.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = po.product_id) WHERE o.type IN ('select', 'radio') AND pd.language_id=" . (int)$this->config->get('config_language_id');

        $this->googleshopping->applyFilter($sql, $data);

        return $this->db->query($sql)->rows;
    }

    public function addTarget($target, $store_id) {
        $sql = "INSERT INTO `" . DB_PREFIX . "advertise_google_target` SET `store_id`=" . (int)$store_id . ", `campaign_name`='" . $this->db->escape($target['campaign_name']) . "', `country`='" . $this->db->escape($target['country']) . "', `budget`='" . (float)$target['budget'] . "', `feeds`='" . $this->db->escape(json_encode($target['feeds'])) . "', `status`='" . $this->db->escape($target['status']) . "'";

        $this->db->query($sql);

        return $this->db->getLastId();
    }

    public function deleteProducts($product_ids) {
        $sql = "DELETE FROM `" . DB_PREFIX . "product_advertise_google` WHERE `product_id` IN (" . $this->googleshopping->productIdsToIntegerExpression($product_ids) . ")";

        $this->db->query($sql);

        $sql = "DELETE FROM `" . DB_PREFIX . "product_advertise_google_target` WHERE `product_id` IN (" . $this->googleshopping->productIdsToIntegerExpression($product_ids) . ")";

        $this->db->query($sql);

        $sql = "DELETE FROM `" . DB_PREFIX . "product_advertise_google_status` WHERE `product_id` IN (" . $this->googleshopping->productIdsToIntegerExpression($product_ids) . ")";

        $this->db->query($sql);

        return true;
    }

    public function setAdvertisingBySelect($post_product_ids, $post_target_ids, $store_id) {
        if (!empty($post_product_ids)) {
            $product_ids = array_map(array($this->googleshopping, 'integer'), $post_product_ids);

            $product_ids_expression = implode(',', $product_ids);

            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_advertise_google_target` WHERE product_id IN (" . $product_ids_expression . ") AND store_id=" . (int)$store_id);

            if (!empty($post_target_ids)) {
                $target_ids = array_map(array($this->googleshopping, 'integer'), $post_target_ids);

                $values = array();

                foreach ($product_ids as $product_id) {
                    foreach ($target_ids as $target_id) {
                        $values[] = '(' . $product_id . ',' . $store_id . ',' . $target_id . ')';
                    }
                }

                $sql = "INSERT INTO `" . DB_PREFIX . "product_advertise_google_target` (`product_id`, `store_id`, `advertise_google_target_id`) VALUES " . implode(',', $values);

                $this->db->query($sql);
            }
        }
    }

    public function setAdvertisingByFilter($data, $post_target_ids, $store_id) {
        $sql = "DELETE pagt FROM `" . DB_PREFIX . "product_advertise_google_target` pagt LEFT JOIN `" . DB_PREFIX . "product` p ON (pagt.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = p.product_id) WHERE pd.language_id=" . (int)$this->config->get('config_language_id');

        $this->googleshopping->applyFilter($sql, $data);

        $this->db->query($sql);

        if (!empty($post_target_ids)) {
            $target_ids = array_map(array($this->googleshopping, 'integer'), $post_target_ids);

            $insert_sql = "SELECT p.product_id, " . (int)$store_id . " as store_id, '%s' as advertise_google_target_id FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = p.product_id) WHERE pd.language_id=" . (int)$this->config->get('config_language_id');

            $this->googleshopping->applyFilter($insert_sql, $data);

            foreach ($target_ids as $target_id) {
                $sql = "INSERT INTO `" . DB_PREFIX . "product_advertise_google_target` (`product_id`, `store_id`, `advertise_google_target_id`) " . sprintf($insert_sql, $target_id);

                $this->db->query($sql);
            }
        }
    }

    public function insertNewProducts($product_ids = array(), $store_id) {
        $sql = "INSERT INTO `" . DB_PREFIX . "product_advertise_google` (`product_id`, `store_id`, `google_product_category`) SELECT p.product_id, p2s.store_id, (SELECT c2gpc.google_product_category FROM `" . DB_PREFIX . "product_to_category` p2c LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (p2c.category_id = cp.category_id) LEFT JOIN `" . DB_PREFIX . "category_to_google_product_category` c2gpc ON (c2gpc.category_id = cp.path_id AND c2gpc.store_id = " . (int)$store_id . ") WHERE p2c.product_id = p.product_id AND c2gpc.google_product_category IS NOT NULL ORDER BY cp.level DESC LIMIT 0,1) as `google_product_category` FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p2s.product_id = p.product_id AND p2s.store_id = " . (int)$store_id . ") LEFT JOIN `" . DB_PREFIX . "product_advertise_google` pag ON (pag.product_id = p.product_id AND pag.store_id=p2s.store_id) WHERE pag.product_id IS NULL AND p2s.store_id IS NOT NULL";

        if (!empty($product_ids)) {
            $sql .= " AND p.product_id IN (" . $this->googleshopping->productIdsToIntegerExpression($product_ids) . ")";
        }

        $this->db->query($sql);
    }

    public function updateGoogleProductCategoryMapping($store_id) {
        $sql = "INSERT INTO `" . DB_PREFIX . "product_advertise_google` (`product_id`, `store_id`, `google_product_category`) SELECT p.product_id, " . (int)$store_id . " as store_id, (SELECT c2gpc.google_product_category FROM `" . DB_PREFIX . "product_to_category` p2c LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (p2c.category_id = cp.category_id) LEFT JOIN `" . DB_PREFIX . "category_to_google_product_category` c2gpc ON (c2gpc.category_id = cp.path_id AND c2gpc.store_id = " . (int)$store_id . ") WHERE p2c.product_id = p.product_id AND c2gpc.google_product_category IS NOT NULL ORDER BY cp.level DESC LIMIT 0,1) as `google_product_category` FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_advertise_google` pag ON (pag.product_id = p.product_id) WHERE pag.product_id IS NOT NULL ON DUPLICATE KEY UPDATE `google_product_category`=VALUES(`google_product_category`)";

        $this->db->query($sql);
    }

    public function updateSingleProductFields($data) {
        $values = array();

        $entry = array();
        $entry['product_id'] = (int)$data['product_id'];
        $entry = array_merge($entry, $this->makeInsertData($data));

        $values[] = "(" . implode(",", $entry) . ")";

        $sql = "INSERT INTO `" . DB_PREFIX . "product_advertise_google` (`product_id`, `store_id`, `google_product_category`, `condition`, `adult`, `multipack`, `is_bundle`, `age_group`, `color`, `gender`, `size_type`, `size_system`, `size`, `is_modified`) VALUES " . implode(',', $values) . " ON DUPLICATE KEY UPDATE " . $this->makeOnDuplicateKeyData();

        $this->db->query($sql);
    }

    public function updateMultipleProductFields($filter_data, $data) {
        $insert_sql = "SELECT p.product_id, %s FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = p.product_id) WHERE pd.language_id=" . (int)$this->config->get('config_language_id');

        $this->googleshopping->applyFilter($insert_sql, $filter_data);

        $insert_data = array();
        $keys[] = "`product_id`";

        foreach ($this->makeInsertData($data) as $key => $value) {
            $insert_data[] = $value . " as `" . $key . "`";
            $keys[] = "`" . $key . "`";
        }

        $sql = "INSERT INTO `" . DB_PREFIX . "product_advertise_google` (" . implode(", ", $keys) . ") " . sprintf($insert_sql, implode(", ", $insert_data)) . " ON DUPLICATE KEY UPDATE " . $this->makeOnDuplicateKeyData();

        $this->db->query($sql);
    }

    protected function makeInsertData($data) {
        $insert_data = array();

        $insert_data['store_id'] = (int)$data['store_id'];
        $insert_data['google_product_category'] = "'" . $this->db->escape($data['google_product_category']) . "'";
        $insert_data['condition'] = "'" . $this->db->escape($data['condition']) . "'";
        $insert_data['adult'] = (int)$data['adult'];
        $insert_data['multipack'] = (int)$data['multipack'];
        $insert_data['is_bundle'] = (int)$data['is_bundle'];
        $insert_data['age_group'] = "'" . $this->db->escape($data['age_group']) . "'";
        $insert_data['color'] = (int)$data['color'];
        $insert_data['gender'] = "'" . $this->db->escape($data['gender']) . "'";
        $insert_data['size_type'] = "'" . $this->db->escape($data['size_type']) . "'";
        $insert_data['size_system'] = "'" . $this->db->escape($data['size_system']) . "'";
        $insert_data['size'] = (int)$data['size'];
        $insert_data['is_modified'] = 1;

        return $insert_data;
    }

    protected function makeOnDuplicateKeyData() {
        return "`google_product_category`=VALUES(`google_product_category`), `condition`=VALUES(`condition`), `adult`=VALUES(`adult`), `multipack`=VALUES(`multipack`), `is_bundle`=VALUES(`is_bundle`), `age_group`=VALUES(`age_group`), `color`=VALUES(`color`), `gender`=VALUES(`gender`), `size_type`=VALUES(`size_type`), `size_system`=VALUES(`size_system`), `size`=VALUES(`size`), `is_modified`=VALUES(`is_modified`)";
    }

    public function getCategories($data = array(), $store_id) {
        $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN `" . DB_PREFIX . "category_to_store` c2s ON (c2s.category_id = cp.category_id AND c2s.store_id=" . (int)$store_id . ") LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE c2s.store_id IS NOT NULL AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sql .= " GROUP BY cp.category_id";

        $sort_data = array(
            'name',
            'sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY sort_order";
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

    public function getProductCampaigns($product_id, $store_id) {
        $sql = "SELECT agt.advertise_google_target_id, agt.campaign_name FROM `" . DB_PREFIX . "product_advertise_google_target` pagt LEFT JOIN `" . DB_PREFIX . "advertise_google_target` agt ON (pagt.advertise_google_target_id = agt.advertise_google_target_id) WHERE pagt.product_id=" . (int)$product_id . " AND pagt.store_id=" . (int)$store_id;

        return $this->db->query($sql)->rows;
    }

    public function getProductIssues($product_id, $store_id) {
        $this->load->model('localisation/language');

        $sql = "SELECT pag.color, pag.size, pd.name, p.model FROM `" . DB_PREFIX . "product_advertise_google` pag LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = pag.product_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = pag.product_id AND pd.language_id=" . (int)$this->config->get('config_language_id') . ") WHERE pag.product_id=" . (int)$product_id . " AND pag.store_id=" . (int)$store_id;

        $product_info = $this->db->query($sql)->row;

        if (!empty($product_info)) {
            $result = array();
            $result['name'] = $product_info['name'];
            $result['model'] = $product_info['model'];
            $result['entries'] = array();

            foreach ($this->model_localisation_language->getLanguages() as $language) {
                $language_id = $language['language_id'];
                $groups = $this->googleshopping->getGroups($product_id, $language_id, $product_info['color'], $product_info['size']);

                $result['entries'][$language_id] = array(
                    'language_name' => $language['name'],
                    'issues' => array()
                );

                foreach ($groups as $id => $group) {
                    $issues = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_advertise_google_status` WHERE product_id=" . (int)$product_id . " AND store_id=" . (int)$store_id . " AND product_variation_id='" . $this->db->escape($id) . "'")->row;

                    $destination_statuses = !empty($issues['destination_statuses']) ? json_decode($issues['destination_statuses'], true) : array();
                    $data_quality_issues = !empty($issues['data_quality_issues']) ? json_decode($issues['data_quality_issues'], true) : array();
                    $item_level_issues = !empty($issues['item_level_issues']) ? json_decode($issues['item_level_issues'], true) : array();
                    $google_expiration_date = !empty($issues['google_expiration_date']) ? date($this->language->get('datetime_format'), $issues['google_expiration_date']) : $this->language->get('text_na');

                    $result['entries'][$language_id]['issues'][] = array(
                        'color' => $group['color'] != "" ? $group['color'] : $this->language->get('text_na'),
                        'size' => $group['size'] != "" ? $group['size'] : $this->language->get('text_na'),
                        'destination_statuses' => $destination_statuses,
                        'data_quality_issues' => $data_quality_issues,
                        'item_level_issues' => $item_level_issues,
                        'google_expiration_date' => $google_expiration_date
                    );
                }
            }

            return $result;
        }

        return null;
    }

    public function createTables() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_advertise_google` (
            `product_advertise_google_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `product_id` INT(11),
            `store_id` INT(11) NOT NULL DEFAULT '0',
            `has_issues` TINYINT(1),
            `destination_status` ENUM('pending','approved','disapproved') NOT NULL DEFAULT 'pending',
            `impressions` INT(11) NOT NULL DEFAULT '0',
            `clicks` INT(11) NOT NULL DEFAULT '0',
            `conversions` INT(11) NOT NULL DEFAULT '0.0000',
            `cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
            `conversion_value` decimal(15,4) NOT NULL DEFAULT '0.0000',
            `google_product_category` VARCHAR(10),
            `condition` ENUM('new','refurbished','used'),
            `adult` TINYINT(1),
            `multipack` INT(11),
            `is_bundle` TINYINT(1),
            `age_group` ENUM('newborn','infant','toddler','kids','adult'),
            `color` INT(11),
            `gender` ENUM('male','female','unisex'),
            `size_type` ENUM('regular','petite','plus','big and tall','maternity'),
            `size_system` ENUM('AU','BR','CN','DE','EU','FR','IT','JP','MEX','UK','US'),
            `size` INT(11),
            `is_modified` TINYINT(1) NOT NULL DEFAULT '0',
            PRIMARY KEY (`product_advertise_google_id`),
            UNIQUE `product_id_store_id` (`product_id`, `store_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_advertise_google_status` (
            `product_id` INT(11),
            `store_id` INT(11) NOT NULL DEFAULT '0',
            `product_variation_id` varchar(64),
            `destination_statuses` TEXT NOT NULL,
            `data_quality_issues` TEXT NOT NULL,
            `item_level_issues` TEXT NOT NULL,
            `google_expiration_date` INT(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (`product_id`, `store_id`, `product_variation_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_advertise_google_target` (
            `product_id` INT(11) NOT NULL,
            `store_id` INT(11) NOT NULL DEFAULT '0',
            `advertise_google_target_id` INT(11) UNSIGNED NOT NULL,
            PRIMARY KEY (`product_id`, `advertise_google_target_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "category_to_google_product_category` (
            `google_product_category` VARCHAR(10) NOT NULL,
            `store_id` INT(11) NOT NULL DEFAULT '0',
            `category_id` INT(11) NOT NULL,
            INDEX `category_id_store_id` (`category_id`, `store_id`),
            PRIMARY KEY (`google_product_category`, `store_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "advertise_google_target` (
            `advertise_google_target_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `store_id` INT(11) NOT NULL DEFAULT '0',
            `campaign_name` varchar(255) NOT NULL DEFAULT '',
            `country` varchar(2) NOT NULL DEFAULT '',
            `budget` decimal(15,4) NOT NULL DEFAULT '0.0000',
            `feeds` text NOT NULL,
            `status` ENUM('paused','active') NOT NULL DEFAULT 'paused',
            INDEX `store_id` (`store_id`),
            PRIMARY KEY (`advertise_google_target_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    }

    public function dropTables() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "advertise_google_target`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "category_to_google_product_category`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_advertise_google_status`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_advertise_google_target`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_advertise_google`");
    }

    public function deleteEvents() {
        $this->load->model('setting/event');

        $this->model_setting_event->deleteEventByCode('advertise_google');
    }

    public function createEvents() {
        $this->load->model('setting/event');

        foreach ($this->events as $trigger => $actions) {
            foreach ($actions as $action) {
                $this->model_setting_event->addEvent('advertise_google', $trigger, $action, 1, 0);
            }
        }
    }

    public function getAllowedTargets() {
        $this->load->config('googleshopping/googleshopping');

        $result = array();

        foreach ($this->config->get('advertise_google_targets') as $target) {
            $result[] = array(
                'country' => array(
                    'code' => $target['country'],
                    'name' => $this->googleshopping->getCountryName($target['country'])
                ),
                'languages' => $this->googleshopping->getLanguages($target['languages']),
                'currencies' => $this->googleshopping->getCurrencies($target['currencies'])
            );
        }

        return $result;
    }

    protected function country($row) {
        return $row['country'];
    }
}