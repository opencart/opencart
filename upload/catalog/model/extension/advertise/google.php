<?php

class ModelExtensionAdvertiseGoogle extends Model {
    public function getHumanReadableCategory($product_id, $store_id) {
        $this->load->config('googleshopping/googleshopping');

        $google_category_result = $this->db->query("SELECT google_product_category FROM `" . DB_PREFIX . "product_advertise_google` pag WHERE pag.product_id = " . (int)$product_id . " AND pag.store_id = " . (int)$store_id);

        if ($google_category_result->num_rows > 0) {
            $google_category_id = $google_category_result->row['google_product_category'];
            $google_categories = $this->config->get('advertise_google_google_product_categories');

            if (!empty($google_category_id) && isset($google_categories[$google_category_id])) {
                return $google_categories[$google_category_id];
            }
        }

        $oc_category_result = $this->db->query("SELECT c.category_id FROM `" . DB_PREFIX . "product_to_category` p2c LEFT JOIN `" . DB_PREFIX . "category` c ON (c.category_id = p2c.category_id) WHERE p2c.product_id=" . (int)$product_id . " LIMIT 0,1");

        if ($oc_category_result->num_rows > 0) {
            return $this->getHumanReadableOpenCartCategory((int)$oc_category_result->row['category_id']);
        }

        return "";
    }

    public function getHumanReadableOpenCartCategory($category_id) {
        $sql = "SELECT GROUP_CONCAT(cd.name ORDER BY cp.level SEPARATOR ' &gt; ') AS path FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd ON (cp.path_id = cd.category_id) WHERE cd.language_id=" . (int)$this->config->get('config_language_id') . " AND cp.category_id=" . (int)$category_id;

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result->row['path'];
        }

        return "";
    }

    public function getSizeAndColorOptionMap($product_id, $store_id) {
        $color_id = $this->getOptionId($product_id, $store_id, 'color');
        $size_id = $this->getOptionId($product_id, $store_id, 'size');

        $groups = $this->googleshopping->getGroups($product_id, $this->config->get('config_language_id'), $color_id, $size_id);

        $colors = $this->googleshopping->getProductOptionValueNames($product_id, $this->config->get('config_language_id'), $color_id);
        $sizes = $this->googleshopping->getProductOptionValueNames($product_id, $this->config->get('config_language_id'), $size_id);

        $map = array(
            'groups' => $groups,
            'colors' => count($colors) > 1 ? $colors : null,
            'sizes' => count($sizes) > 1 ? $sizes : null,
        );

        return $map;
    }

    protected function getOptionId($product_id, $store_id, $type) {
        $sql = "SELECT pag." . $type . " FROM `" . DB_PREFIX . "product_advertise_google` pag WHERE product_id=" . (int)$product_id . " AND store_id=" . (int)$store_id;

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return (int)$result->row[$type];
        }

        return 0;
    }
}