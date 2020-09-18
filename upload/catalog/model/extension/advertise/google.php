<?php

class ModelExtensionAdvertiseGoogle extends Model {
    public function getHumanReadableCategory($product_id, $store_id) {
        $this->load->config('googleshopping/googleshopping');

        $google_category_result = $this->db->query("SELECT google_product_category FROM `" . DB_PREFIX . "googleshopping_product` pag WHERE pag.product_id = " . (int)$product_id . " AND pag.store_id = " . (int)$store_id);

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

    public function getCoupon($order_id) {
        $sql = "SELECT c.code FROM `" . DB_PREFIX . "coupon_history` ch LEFT JOIN `" . DB_PREFIX . "coupon` c ON (c.coupon_id = ch.coupon_id) WHERE ch.order_id=" . (int)$order_id;

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result->row['code'];
        }

        return null;
    }

    public function getRemarketingProductIds($products, $store_id) {
        $ecomm_prodid = array();

        foreach ($products as $product) {
            if (null !== $id = $this->getRemarketingProductId($product, $store_id)) {
                $ecomm_prodid[] = $id;
            }
        }

        return $ecomm_prodid;
    }

    public function getRemarketingItems($products, $store_id) {
        $items = array();

        foreach ($products as $product) {
            if (null !== $id = $this->getRemarketingProductId($product, $store_id)) {
                $items[] = array(
                    'google_business_vertical' => 'retail',
                    'id' => (string)$id,
                    'name' => (string)$product['name'],
                    'quantity' => (int)$product['quantity']
                );
            }
        }

        return $items;
    }

    protected function getRemarketingProductId($product, $store_id) {
        $option_map = $this->getSizeAndColorOptionMap($product['product_id'], $store_id);
        $found_color = "";
        $found_size = "";

        foreach ($product['option'] as $option) {
            if (is_array($option_map['colors'])) {
                foreach ($option_map['colors'] as $product_option_value_id => $color) {
                    if ($option['product_option_value_id'] == $product_option_value_id) {
                        $found_color = $color;
                    }
                }
            }

            if (is_array($option_map['sizes'])) {
                foreach ($option_map['sizes'] as $product_option_value_id => $size) {
                    if ($option['product_option_value_id'] == $product_option_value_id) {
                        $found_size = $size;
                    }
                }
            }
        }

        foreach ($option_map['groups'] as $id => $group) {
            if ($group['color'] === $found_color && $group['size'] === $found_size) {
                return $id;
            }
        }

        return null;
    }

    protected function getOptionId($product_id, $store_id, $type) {
        $sql = "SELECT pag." . $type . " FROM `" . DB_PREFIX . "googleshopping_product` pag WHERE product_id=" . (int)$product_id . " AND store_id=" . (int)$store_id;

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return (int)$result->row[$type];
        }

        return 0;
    }
}