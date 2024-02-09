<?php
namespace googleshopping;

use \googleshopping\Exception\Connection as ConnectionException;
use \googleshopping\Exception\AccessForbidden as AccessForbiddenException;
use \googleshopping\traits\StoreLoader;

class Googleshopping extends Library {
    use StoreLoader;

    const API_URL = 'https://campaigns.opencart.com/';
    const CACHE_CAMPAIGN_REPORT = 21600; // In seconds
    const CACHE_PRODUCT_REPORT = 21600; // In seconds
    const ROAS_WAIT_INTERVAL = 1209600; // In seconds
    const MICROAMOUNT = 1000000;
    const DEBUG_LOG_FILENAME = 'googleshopping.%s.log';
    const ENDPOINT_ACCESS_TOKEN = 'api/access_token';
    const ENDPOINT_ACCESS_TOKEN_TEST = 'api/access_token/test';
    const ENDPOINT_CAMPAIGN_DELETE = 'api/campaign/delete';
    const ENDPOINT_CAMPAIGN_STATUS = 'api/campaign/status';
    const ENDPOINT_CAMPAIGN_TEST = 'api/campaign/test';
    const ENDPOINT_CAMPAIGN_UPDATE = 'api/campaign/update';
    const ENDPOINT_CONVERSION_TRACKER = 'api/conversion_tracker';
    const ENDPOINT_DATAFEED_CLOSE = 'api/datafeed/close';
    const ENDPOINT_DATAFEED_INIT = 'api/datafeed/init';
    const ENDPOINT_DATAFEED_PUSH = 'api/datafeed/push';
    const ENDPOINT_MERCHANT_AUTH_URL = 'api/merchant/authorize_url';
    const ENDPOINT_MERCHANT_AVAILABLE_CARRIERS = 'api/merchant/available_carriers';
    const ENDPOINT_MERCHANT_DISCONNECT = 'api/merchant/disconnect';
    const ENDPOINT_MERCHANT_PRODUCT_STATUSES = 'api/merchant/product_statuses';
    const ENDPOINT_MERCHANT_SHIPPING_TAXES = 'api/merchant/shipping_taxes';
    const ENDPOINT_REPORT_AD = 'api/report/ad&interval=%s';
    const ENDPOINT_REPORT_CAMPAIGN = 'api/report/campaign&interval=%s';
    const ENDPOINT_VERIFY_IS_CLAIMED = 'api/verify/is_claimed';
    const ENDPOINT_VERIFY_SITE = 'api/verify/site';
    const ENDPOINT_VERIFY_TOKEN = 'api/verify/token';
    const SCOPES = 'OC_FEED REPORT ADVERTISE';

    private $event_snippet;
    private $purchase_data;
    private $store_url;
    private $store_name;
    private $endpoint_url;
    private $store_id = 0;
    private $debug_log;

    public function __construct($registry, $store_id) {
        parent::__construct($registry);

        $this->store_id = $store_id;

        $this->load->model('setting/setting');

        if ($this->store_id === 0) {
            $this->store_url = basename(DIR_TEMPLATE) == 'template' ? HTTPS_CATALOG : HTTPS_SERVER;
            $this->store_name = $this->config->get('config_name');
        } else {
            $this->store_url = $this->model_setting_setting->getSettingValue('config_ssl', $store_id);
            $this->store_name = $this->model_setting_setting->getSettingValue('config_name', $store_id);
        }

        $this->endpoint_url = self::API_URL . 'index.php?route=%s';

        $this->loadStore($this->store_id);

        $this->debug_log = new Log(sprintf(self::DEBUG_LOG_FILENAME, $this->store_id));
    }

    public function getStoreUrl() {
        return $this->store_url;
    }

    public function getStoreName() {
        return $this->store_name;
    }

    public function getSupportedLanguageId($code) {
        $this->load->model('localisation/language');

        foreach ($this->model_localisation_language->getLanguages() as $language) {
            $language_code = current(explode("-", $language['code']));

            if ($this->compareTrimmedLowercase($code, $language_code) === 0) {
                return (int)$language['language_id'];
            }
        }

        return 0;
    }

    public function getSupportedCurrencyId($code) {
        $this->load->model('localisation/currency');

        foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
            if ($this->compareTrimmedLowercase($code, $currency['code']) === 0) {
                return (int)$currency['currency_id'];
            }
        }

        return 0;
    }

    public function getCountryName($code) {
        $this->load->config('googleshopping/googleshopping');

        $this->load->model('localisation/country');

        $countries = $this->config->get('advertise_google_countries');

        // Default value
        $result = $countries[$code];

        // Override with store value, if present
        foreach ($this->model_localisation_country->getCountries() as $store_country) {
            if ($this->compareTrimmedLowercase($store_country['iso_code_2'], $code) === 0) {
                $result = $store_country['name'];
                break;
            }
        }

        return $result;
    }

    public function compareTrimmedLowercase($text1, $text2) {
        return strcmp(strtolower(trim($text1)), strtolower(trim($text2)));
    }

    public function getTargets($store_id) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "googleshopping_target` WHERE store_id=" . $store_id;

        return array_map(array($this, 'target'), $this->db->query($sql)->rows);
    }    

    public function getTarget($advertise_google_target_id) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "googleshopping_target` WHERE advertise_google_target_id=" . (int)$advertise_google_target_id;

        return $this->target($this->db->query($sql)->row);
    }

    public function editTarget($target_id, $target) {
        $sql = "UPDATE `" . DB_PREFIX . "googleshopping_target` SET `campaign_name`='" . $this->db->escape($target['campaign_name']) . "', `country`='" . $this->db->escape($target['country']) . "', `budget`='" . (float)$target['budget'] . "', `feeds`='" . $this->db->escape(json_encode($target['feeds'])) . "', `roas`='" . (int)$target['roas'] . "', `status`='" . $this->db->escape($target['status']) . "' WHERE `advertise_google_target_id`='" . (int)$target_id . "'";

        $this->db->query($sql);

        return $target;
    }

    public function deleteTarget($target_id) {
        $sql = "DELETE FROM `" . DB_PREFIX . "googleshopping_target` WHERE `advertise_google_target_id`='" . (int)$target_id . "'";

        $this->db->query($sql);

        $sql = "DELETE FROM `" . DB_PREFIX . "googleshopping_product_target` WHERE `advertise_google_target_id`='" . (int)$target_id . "'";

        $this->db->query($sql);

        return true;
    }

    public function doJob($job) {
        $product_count = 0;

        // Initialize push
        $init_request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_DATAFEED_INIT,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => array(
                'work_id' => $job['work_id']
            )
        );

        $response = $this->api($init_request);

        // At this point, the job has been initialized and we can start pushing the datafeed
        $page = 0;

        while (null !== $products = $this->getFeedProducts(++$page, $job['language_id'], $job['currency'])) {
            $post = array();

            $post_data = array(
                'product' => $products,
                'work_id' => $job['work_id'],
                'work_step' => $response['work_step']
            );

            $this->curlPostQuery($post_data, $post);

            $push_request = array(
                'type' => 'POST',
                'endpoint' => self::ENDPOINT_DATAFEED_PUSH,
                'use_access_token' => true,
                'content_type' => 'multipart/form-data',
                'data' => $post
            );

            $response = $this->api($push_request);

            $product_count += count($products);
        }

        // Finally, close the file to finish the job
        $close_request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_DATAFEED_CLOSE,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => array(
                'work_id' => $job['work_id'],
                'work_step' => $response['work_step']
            )
        );

        $this->api($close_request);

        return $product_count;
    }

    public function getProductVariationIds($page) {
        $this->load->config('googleshopping/googleshopping');

        $sql = "SELECT DISTINCT pag.product_id, pag.color, pag.size FROM `" . DB_PREFIX . "googleshopping_product` pag LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = pag.product_id) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p2s.product_id = p.product_id AND p2s.store_id=" . (int)$this->store_id . ") WHERE p2s.store_id IS NOT NULL AND p.status = 1 AND p.date_available <= NOW() AND p.price > 0 ORDER BY p.product_id ASC LIMIT " . (int)(($page - 1) * $this->config->get('advertise_google_report_limit')) . ', ' . (int)$this->config->get('advertise_google_report_limit');

        $result = array();

        $this->load->model('localisation/language');

        foreach ($this->db->query($sql)->rows as $row) {
            foreach ($this->model_localisation_language->getLanguages() as $language) {
                $groups = $this->getGroups($row['product_id'], $language['language_id'], $row['color'], $row['size']);

                foreach (array_keys($groups) as $id) {
                    if (!in_array($id, $result)) {
                        $result[] = $id;
                    }
                }
            }
        }

        return !empty($result) ? $result : null;
    }

    // A copy of the OpenCart SEO URL rewrite method.
    public function rewrite($link) {
        $url_info = parse_url(str_replace('&amp;', '&', $link));

        $url = '';

        $data = array();

        parse_str($url_info['query'], $data);

        foreach ($data as $key => $value) {
            if (isset($data['route'])) {
                if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

                    if ($query->num_rows && $query->row['keyword']) {
                        $url .= '/' . $query->row['keyword'];

                        unset($data[$key]);
                    }
                } elseif ($key == 'path') {
                    $categories = explode('_', $value);

                    foreach ($categories as $category) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = 'category_id=" . (int)$category . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

                        if ($query->num_rows && $query->row['keyword']) {
                            $url .= '/' . $query->row['keyword'];
                        } else {
                            $url = '';

                            break;
                        }
                    }

                    unset($data[$key]);
                }
            }
        }

        if ($url) {
            unset($data['route']);

            $query = '';

            if ($data) {
                foreach ($data as $key => $value) {
                    $query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
                }

                if ($query) {
                    $query = '?' . str_replace('&', '&amp;', trim($query, '&'));
                }
            }

            return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
        } else {
            return $link;
        }
    }

    protected function convertedTaxedPrice($value, $tax_class_id, $currency) {
        return number_format($this->currency->convert($this->tax->calculate($value, $tax_class_id, $this->config->get('config_tax')), $this->config->get('config_currency'), $currency), 2, '.', '');
    }

    protected function getFeedProducts($page, $language_id, $currency) {
        $sql = $this->getFeedProductsQuery($page, $language_id);

        $result = array();

        $this->setRuntimeExceptionErrorHandler();

        foreach ($this->db->query($sql)->rows as $row) {
            try {
                if (!empty($row['image']) && is_file(DIR_IMAGE . $row['image']) && is_readable(DIR_IMAGE . $row['image'])) {
                    $image = $this->resize($row['image'], 250, 250);
                } else {
                    throw new \RuntimeException("Image does not exist or cannot be read.");
                }
            } catch (\RuntimeException $e) {
                $this->output(sprintf("Error for product %s: %s", $row['model'], $e->getMessage()));

                $image = $this->resize('no_image.png', 250, 250);
            }

            $url = new \Url($this->store_url, $this->store_url);

            if ($this->config->get('config_seo_url')) {
                $url->addRewrite($this);
            }

            $price = $this->convertedTaxedPrice($row['price'], $row['tax_class_id'], $currency);

            $special_price = null;

            if ($row['special_price'] !== null) {
                $parts = explode('<[S]>', $row['special_price']);

                $special_price = array(
                    'value' => $this->convertedTaxedPrice($parts[0], $row['tax_class_id'], $currency),
                    'currency' => $currency
                );

                if ($parts[1] >= '1970-01-01') {
                    $special_price['start'] = $parts[1];
                }

                if ($parts[2] >= '1970-01-01') {
                    $special_price['end'] = $parts[2];
                }
            }

            $campaigns = array();
            $custom_label_0 = '';
            $custom_label_1 = '';
            $custom_label_2 = '';
            $custom_label_3 = '';
            $custom_label_4 = '';

            if (!empty($row['campaign_names'])) {
                $campaigns = explode('<[S]>', $row['campaign_names']);
                $i = 0;

                do {
                    ${'custom_label_' . ($i++)} = trim(strtolower(array_pop($campaigns)));
                } while ($campaigns);
            }

            $mpn = !empty($row['mpn']) ? $row['mpn'] : '';

            if (!empty($row['upc'])) {
                $gtin = $row['upc'];
            } else if (!empty($row['ean'])) {
                $gtin = $row['ean'];
            } else if (!empty($row['jan'])) {
                $gtin = $row['jan'];
            } else if (!empty($row['isbn'])) {
                $gtin = $row['isbn'];
            } else {
                $gtin = '';
            }

            $base_row = array(
                'adult' => !empty($row['adult']) ? 'yes' : 'no',
                'age_group' => !empty($row['age_group']) ? $row['age_group'] : '',
                'availability' => (int)$row['quantity'] > 0 && !$this->config->get('config_maintenance') ? 'in stock' : 'out of stock',
                'brand' => $this->sanitizeText($row['brand'], 70),
                'color' => '',
                'condition' => !empty($row['condition']) ? $row['condition'] : '',
                'custom_label_0' => $this->sanitizeText($custom_label_0, 100),
                'custom_label_1' => $this->sanitizeText($custom_label_1, 100),
                'custom_label_2' => $this->sanitizeText($custom_label_2, 100),
                'custom_label_3' => $this->sanitizeText($custom_label_3, 100),
                'custom_label_4' => $this->sanitizeText($custom_label_4, 100),
                'description' => $this->sanitizeText($row['description'], 5000),
                'gender' => !empty($row['gender']) ? $row['gender'] : '',
                'google_product_category' => !empty($row['google_product_category']) ? $row['google_product_category'] : '',
                'id' => $this->sanitizeText($row['product_id'], 50),
                'identifier_exists' => !empty($row['brand']) && !empty($mpn) ? 'yes' : 'no',
                'image_link' => $this->sanitizeText($image, 2000),
                'is_bundle' => !empty($row['is_bundle']) ? 'yes' : 'no',
                'item_group_id' => $this->sanitizeText($row['product_id'], 50),
                'link' => $this->sanitizeText(html_entity_decode($url->link('product/product', 'product_id=' . $row['product_id'], true), ENT_QUOTES, 'UTF-8'), 2000),
                'mpn' => $this->sanitizeText($mpn, 70),
                'gtin' => $this->sanitizeText($gtin, 14),
                'multipack' => !empty($row['multipack']) && (int)$row['multipack'] >= 2 ? (int)$row['multipack'] : '', // Cannot be 1!!!
                'price' => array(
                    'value' => $price,
                    'currency' => $currency
                ),
                'size' => '',
                'size_system' => !empty($row['size_system']) ? $row['size_system'] : '',
                'size_type' => !empty($row['size_type']) ? $row['size_type'] : '',
                'title' => $this->sanitizeText($row['name'], 150)
            );

            // Provide optional special price
            if ($special_price !== null) {
                $base_row['special_price'] = $special_price;
            }

            $groups = $this->getGroups($row['product_id'], $language_id, $row['color'], $row['size']);

            foreach ($groups as $id => $group) {
                $base_row['id'] = $id;
                $base_row['color'] = $this->sanitizeText($group['color'], 40);
                $base_row['size'] = $this->sanitizeText($group['size'], 100);

                $result[] = $base_row;
            }
        }

        $this->restoreErrorHandler();

        return !empty($result) ? $result : null;
    }

    public function getGroups($product_id, $language_id, $color_id, $size_id) {
        $options = array(
            'color' => $this->getProductOptionValueNames($product_id, $language_id, $color_id),
            'size' => $this->getProductOptionValueNames($product_id, $language_id, $size_id)
        );

        $result = array();

        foreach ($this->combineOptions($options) as $group) {
            $key = $product_id . '-' . md5(json_encode(array('color' => $group['color'], 'size' => $group['size'])));

            $result[$key] = $group;
        }

        return $result;
    }

    public function getProductOptionValueNames($product_id, $language_id, $option_id) {
        $sql = "SELECT DISTINCT pov.product_option_value_id, ovd.name FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (ovd.option_value_id = pov.option_value_id) WHERE pov.product_id=" . (int)$product_id . " AND pov.option_id=" . (int)$option_id . " AND ovd.language_id=" . (int)$language_id;

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            $return = array();

            foreach ($result->rows as $row) {
                $text = $this->sanitizeText($row['name'], 100);
                $name = implode('/', array_slice(array_filter(array_map('trim', preg_split('~[,/;]+~i', $text))), 0, 3));

                $return[$row['product_option_value_id']] = $name;
            }

            return $return;
        }

        return array('');
    }

    public function applyFilter(&$sql, &$data) {
        if (!empty($data['filter_product_name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product_name']) . "%'";
        }

        if (!empty($data['filter_product_model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_product_model']) . "%'";
        }

        if (!empty($data['filter_category_id'])) {
            $sql .= " AND p.product_id IN (SELECT p2c_t.product_id FROM `" . DB_PREFIX . "category_path` cp_t LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c_t ON (p2c_t.category_id=cp_t.category_id) WHERE cp_t.path_id=" . (int)$data['filter_category_id'] . ")";
        }

        if (isset($data['filter_is_modified']) && $data['filter_is_modified'] !== "") {
            $sql .= " AND p.product_id IN (SELECT pag_t.product_id FROM `" . DB_PREFIX . "googleshopping_product` pag_t WHERE pag_t.is_modified=" . (int)$data['filter_is_modified'] . ")";
        }

        if (!empty($data['filter_store_id'])) {
            $sql .= " AND p.product_id IN (SELECT p2s_t.product_id FROM `" . DB_PREFIX . "product_to_store` p2s_t WHERE p2s_t.store_id=" . (int)$data['filter_store_id'] . ")";
        }
    }

    public function getProducts($data, $store_id) {
        $sql = "SELECT pag.*, p.product_id, p.image, pd.name, p.model FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) LEFT JOIN `" . DB_PREFIX . "googleshopping_product` pag ON (pag.product_id = p.product_id AND pag.store_id = " . (int)$store_id . ") WHERE pag.store_id IS NOT NULL AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $this->applyFilter($sql, $data);

        $sql .= " GROUP BY p.product_id";

        $sort_data = array(
            'name',
            'model',
            'impressions',
            'clicks',
            'cost',
            'conversions',
            'conversion_value',
            'has_issues',
            'destination_status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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

        return $this->db->query($sql)->rows;
    }

    public function getTotalProducts($data, $store_id) {
        $sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) LEFT JOIN `" . DB_PREFIX . "googleshopping_product` pag ON (pag.product_id = p.product_id AND pag.store_id = " . (int)$store_id . ") WHERE pag.store_id IS NOT NULL AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $this->applyFilter($sql, $data);

        return (int)$this->db->query($sql)->row['total'];
    }

    public function getProductIds($data, $store_id) {
        $result = array();

        $this->load->model('localisation/language');

        foreach ($this->getProducts($data, $store_id) as $row) {
            $product_id = (int)$row['product_id'];

            if (!in_array($product_id, $result)) {
                $result[] = $product_id;
            }
        }

        return $result;
    }

    public function clearProductStatuses($product_ids, $store_id) {
        $sql = "UPDATE `" . DB_PREFIX . "googleshopping_product_status` SET `destination_statuses`='', `data_quality_issues`='', `item_level_issues`='', `google_expiration_date`=0 WHERE `product_id` IN (" . $this->productIdsToIntegerExpression($product_ids) . ") AND `store_id`=" . (int)$store_id;

        $this->db->query($sql);

        $sql = "UPDATE `" . DB_PREFIX . "googleshopping_product` SET `has_issues`=0, `destination_status`='pending' WHERE `product_id` IN (" . $this->productIdsToIntegerExpression($product_ids) . ") AND `store_id`=" . (int)$store_id;

        $this->db->query($sql);
    }

    public function productIdsToIntegerExpression($product_ids) {
        return implode(",", array_map(array($this, 'integer'), $product_ids));
    }

    public function integer($product_id) {
        if (!is_numeric($product_id)) {
            return 0;
        } else {
            return (int)$product_id;
        }
    }

    public function cron() {
        $this->enableErrorReporting();

        $this->load->config('googleshopping/googleshopping');

        $report = array();

        $report[] = $this->output("Starting CRON task for " . $this->getStoreUrl());

        try {
            $report[] = $this->output("Refreshing access token.");

            $this->isConnected();
        } catch (\RuntimeException $e) {
            $report[] = $this->output($e->getMessage());
        }

        $default_config_tax = $this->config->get("config_tax");
        $default_config_store_id = $this->config->get("config_store_id");
        $default_config_language_id = $this->config->get("config_language_id");
        $default_config_seo_url = $this->config->get("config_seo_url");

        // Do product feed uploads
        foreach ($this->getJobs() as $job) {
            try {
                $report[] = $this->output("Uploading product feed. Work ID: " . $job['work_id']);

                // Set the tax context for the job
                if (in_array("US", $job['countries'])) {
                    // In case the feed is for the US, disable taxes because they are already configured on the merchant level by the extension
                    $this->config->set("config_tax", 0);
                }

                // Set the store and language context for the job
                $this->config->set("config_store_id", $this->store_id);
                $this->config->set("config_language_id", $job['language_id']);
                $this->config->set("config_seo_url", $this->model_setting_setting->getSettingValue("config_seo_url", $this->store_id));

                // Do the CRON job
                $count = $this->doJob($job);

                // Reset the taxes, store, and language to their original state
                $this->config->set("config_tax", $default_config_tax);
                $this->config->set("config_store_id", $default_config_store_id);
                $this->config->set("config_language_id", $default_config_language_id);
                $this->config->set("config_seo_url", $default_config_seo_url);

                $report[] = $this->output("Uploaded count: " . $count);
            } catch (\RuntimeException $e) {
                $report[] = $this->output($e->getMessage());
            }
        }

        // Reset the taxes, store, and language to their original state
        $this->config->set("config_tax", $default_config_tax);
        $this->config->set("config_store_id", $default_config_store_id);
        $this->config->set("config_language_id", $default_config_language_id);
        $this->config->set("config_seo_url", $default_config_seo_url);

        // Pull product reports
        $report[] = $this->output("Fetching product reports.");

        try {
            $report_count = 0;

            $page = 0;

            $this->clearReports();

            while (null !== $product_variation_ids = $this->getProductVariationIds(++$page)) {
                foreach (array_chunk($product_variation_ids, (int)$this->config->get('advertise_google_report_limit')) as $chunk) {
                    $product_reports = $this->getProductReports($chunk);

                    if (!empty($product_reports)) {
                        $this->updateProductReports($product_reports);
                        $report_count += count($product_reports);
                    }
                }
            }
        } catch (\RuntimeException $e) {
            $report[] = $this->output($e->getMessage());
        }

        $report[] = $this->output("Fetched report count: " . $report_count);

        // Pull product statuses
        $report[] = $this->output("Fetching product statuses.");

        $page = 1;
        $status_count = 0;

        do {
            $filter_data = array(
                'start' => ($page - 1) * $this->config->get('advertise_google_product_status_limit'),
                'limit' => $this->config->get('advertise_google_product_status_limit')
            );

            $page++;

            $product_variation_target_specific_ids = $this->getProductVariationTargetSpecificIds($filter_data);

            try {
                // Fetch latest statuses from the API
                if (!empty($product_variation_target_specific_ids)) {
                    $product_ids = $this->getProductIds($filter_data, $this->store_id);

                    $this->clearProductStatuses($product_ids, $this->store_id);

                    foreach (array_chunk($product_variation_target_specific_ids, (int)$this->config->get('advertise_google_product_status_limit')) as $chunk) {
                        $product_statuses = $this->getProductStatuses($chunk);

                        if (!empty($product_statuses)) {
                            $this->updateProductStatuses($product_statuses);
                            $status_count += count($product_statuses);
                        }
                    }
                }
            } catch (\RuntimeException $e) {
                $report[] = $this->output($e->getMessage());
            }
        } while (!empty($product_variation_target_specific_ids));

        $report[] = $this->output("Fetched status count: " . $status_count);

        $report[] = $this->output("CRON finished!");

        $this->applyNewSetting('advertise_google_cron_last_executed', time());

        $this->sendEmailReport($report);
    }

    public function getProductVariationTargetSpecificIds($data) {
        $result = array();

        $targets = $this->getTargets($this->store_id);

        foreach ($this->getProducts($data, $this->store_id) as $row) {
            foreach ($targets as $target) {
                foreach ($target['feeds'] as $feed) {
                    $language_code = $feed['language'];

                    $language_id = $this->getSupportedLanguageId($language_code);

                    $groups = $this->getGroups($row['product_id'], $language_id, $row['color'], $row['size']);

                    foreach (array_keys($groups) as $id) {
                        $id_parts = array();
                        $id_parts[] = 'online';
                        $id_parts[] = $language_code;
                        $id_parts[] = $target['country']['code'];
                        $id_parts[] = $id;

                        $result_id = implode(':', $id_parts);

                        if (!in_array($result_id, $result)) {
                            $result[] = $result_id;
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function updateProductReports($reports) {
        $values = array();

        foreach ($reports as $report) {
            $entry = array();
            $entry['product_id'] = $this->getProductIdFromOfferId($report['offer_id']);
            $entry['store_id'] = (int)$this->store_id;
            $entry['impressions'] = (int)$report['impressions'];
            $entry['clicks'] = (int)$report['clicks'];
            $entry['conversions'] = (int)$report['conversions'];
            $entry['cost'] = ((int)$report['cost']) / self::MICROAMOUNT;
            $entry['conversion_value'] = (float)$report['conversion_value'];
            
            $values[] = "(" . implode(",", $entry) . ")";
        }

        $sql = "INSERT INTO `" . DB_PREFIX . "googleshopping_product` (`product_id`, `store_id`, `impressions`, `clicks`, `conversions`, `cost`, `conversion_value`) VALUES " . implode(',', $values) . " ON DUPLICATE KEY UPDATE `impressions`=`impressions` + VALUES(`impressions`), `clicks`=`clicks` + VALUES(`clicks`), `conversions`=`conversions` + VALUES(`conversions`), `cost`=`cost` + VALUES(`cost`), `conversion_value`=`conversion_value` + VALUES(`conversion_value`)";

        $this->db->query($sql);
    }

    public function updateProductStatuses($statuses) {
        $product_advertise_google = array();
        $product_advertise_google_status = array();
        $product_level_entries = array();
        $entry_statuses = array();

        foreach ($statuses as $status) {
            $product_id = $this->getProductIdFromTargetSpecificId($status['productId']);
            $product_variation_id = $this->getProductVariationIdFromTargetSpecificId($status['productId']);

            if (!isset($product_level_entries[$product_id])) {
                $product_level_entries[$product_id] = array(
                    'product_id' => (int)$product_id,
                    'store_id' => (int)$this->store_id,
                    'has_issues' => 0,
                    'destination_status' => 'pending'
                );
            }

            foreach ($status['destinationStatuses'] as $destination_status) {
                if (!$destination_status['approvalPending']) {
                    switch ($destination_status['approvalStatus']) {
                        case 'approved' :
                            if ($product_level_entries[$product_id]['destination_status'] == 'pending') {
                                $product_level_entries[$product_id]['destination_status'] = 'approved';
                            }
                        break;
                        case 'disapproved' :
                            $product_level_entries[$product_id]['destination_status'] = 'disapproved';
                        break;
                    }
                }
            }

            if (!$product_level_entries[$product_id]['has_issues']) {
                if (!empty($status['dataQualityIssues']) || !empty($status['itemLevelIssues'])) {
                    $product_level_entries[$product_id]['has_issues'] = 1;
                }
            }

            if (!isset($entry_statuses[$product_variation_id])) {
                $entry_statuses[$product_variation_id] = array();

                $entry_statuses[$product_variation_id]['product_id'] = (int)$product_id;
                $entry_statuses[$product_variation_id]['store_id'] = (int)$this->store_id;
                $entry_statuses[$product_variation_id]['product_variation_id'] = "'" . $this->db->escape($product_variation_id) . "'";
                $entry_statuses[$product_variation_id]['destination_statuses'] = array();
                $entry_statuses[$product_variation_id]['data_quality_issues'] = array();
                $entry_statuses[$product_variation_id]['item_level_issues'] = array();
                $entry_statuses[$product_variation_id]['google_expiration_date'] = (int)strtotime($status['googleExpirationDate']);
            }

            $entry_statuses[$product_variation_id]['destination_statuses'] = array_merge(
                $entry_statuses[$product_variation_id]['destination_statuses'],
                !empty($status['destinationStatuses']) ? $status['destinationStatuses'] : array()
            );

            $entry_statuses[$product_variation_id]['data_quality_issues'] = array_merge(
                $entry_statuses[$product_variation_id]['data_quality_issues'],
                !empty($status['dataQualityIssues']) ? $status['dataQualityIssues'] : array()
            );

            $entry_statuses[$product_variation_id]['item_level_issues'] = array_merge(
                $entry_statuses[$product_variation_id]['item_level_issues'],
                !empty($status['itemLevelIssues']) ? $status['itemLevelIssues'] : array()
            );
        }

        foreach ($entry_statuses as &$entry_status) {
            $entry_status['destination_statuses'] = "'" . $this->db->escape(json_encode($entry_status['destination_statuses'])) . "'";
            $entry_status['data_quality_issues'] = "'" . $this->db->escape(json_encode($entry_status['data_quality_issues'])) . "'";
            $entry_status['item_level_issues'] = "'" . $this->db->escape(json_encode($entry_status['item_level_issues'])) . "'";
            
            $product_advertise_google_status[] = "(" . implode(",", $entry_status) . ")";
        }

        $sql = "INSERT INTO `" . DB_PREFIX . "googleshopping_product_status` (`product_id`, `store_id`, `product_variation_id`, `destination_statuses`, `data_quality_issues`, `item_level_issues`, `google_expiration_date`) VALUES " . implode(',', $product_advertise_google_status) . " ON DUPLICATE KEY UPDATE `destination_statuses`=VALUES(`destination_statuses`), `data_quality_issues`=VALUES(`data_quality_issues`), `item_level_issues`=VALUES(`item_level_issues`), `google_expiration_date`=VALUES(`google_expiration_date`)";

        $this->db->query($sql);

        foreach ($product_level_entries as $entry) {
            $entry['destination_status'] = "'" . $this->db->escape($entry['destination_status']) . "'";

            $product_advertise_google[] = "(" . implode(",", $entry) . ")";
        }

        $sql = "INSERT INTO `" . DB_PREFIX . "googleshopping_product` (`product_id`, `store_id`, `has_issues`, `destination_status`) VALUES " . implode(',', $product_advertise_google) . " ON DUPLICATE KEY UPDATE `has_issues`=VALUES(`has_issues`), `destination_status`=VALUES(`destination_status`)";

        $this->db->query($sql);
    }

    protected function memoryLimitInBytes() {
        $memory_limit = ini_get('memory_limit');

        if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
            if ($matches[2] == 'G') {
                $memory_limit = (int)$matches[1] * 1024 * 1024 * 1024; // nnnG -> nnn GB
            } else if ($matches[2] == 'M') {
                $memory_limit = (int)$matches[1] * 1024 * 1024; // nnnM -> nnn MB
            } else if ($matches[2] == 'K') {
                $memory_limit = (int)$matches[1] * 1024; // nnnK -> nnn KB
            }
        }

        return (int)$memory_limit;
    }

    protected function enableErrorReporting() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    protected function getProductIdFromTargetSpecificId($target_specific_id) {
        return (int)preg_replace('/^online:[a-z]{2}:[A-Z]{2}:(\d+)-[a-f0-9]{32}$/', '$1', $target_specific_id);
    }

    protected function getProductVariationIdFromTargetSpecificId($target_specific_id) {
        return preg_replace('/^online:[a-z]{2}:[A-Z]{2}:(\d+-[a-f0-9]{32})$/', '$1', $target_specific_id);
    }

    protected function getProductIdFromOfferId($offer_id) {
        return (int)preg_replace('/^(\d+)-[a-f0-9]{32}$/', '$1', $offer_id);
    }

    protected function clearReports() {
        $sql = "UPDATE `" . DB_PREFIX . "googleshopping_product` SET `impressions`=0, `clicks`=0, `conversions`=0, `cost`=0.0000, `conversion_value`=0.0000 WHERE `store_id`=" . (int)$this->store_id;

        $this->db->query($sql);
    }

    protected function getJobs() {
        $jobs = array();

        if ($this->setting->has('advertise_google_work') && is_array($this->setting->get('advertise_google_work'))) {
            $this->load->model('extension/advertise/google');

            foreach ($this->setting->get('advertise_google_work') as $work) {
                $supported_language_id = $this->getSupportedLanguageId($work['language']);
                $supported_currency_id = $this->getSupportedCurrencyId($work['currency']);

                if (!empty($supported_language_id) && !empty($supported_currency_id)) {
                    $currency_info = $this->getCurrency($supported_currency_id);

                    $jobs[] = array(
                        'work_id' => $work['work_id'],
                        'countries' => isset($work['countries']) && is_array($work['countries']) ? $work['countries'] : array(),
                        'language_id' => $supported_language_id,
                        'currency' => $currency_info['code']
                    );
                }
            }
        }

        return $jobs;
    }

    protected function output($message) {
        $log_message = date('Y-m-d H:i:s - ') . $message;

        if (defined('STDOUT')) {
            fwrite(STDOUT, $log_message . PHP_EOL);
        } else {
            echo $log_message . '<br /><hr />';
        }

        return $log_message;
    }

    protected function sendEmailReport($report) {
        if (!$this->setting->get('advertise_google_cron_email_status')) {
            return; //Do nothing
        }

        $this->load->language('extension/advertise/google');

        $subject = $this->language->get('text_cron_email_subject');
        $message = sprintf($this->language->get('text_cron_email_message'), implode('<br/>', $report));

        $mail = new \Mail();

        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');

        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, "UTF-8");
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($this->setting->get('advertise_google_cron_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, "UTF-8"));
        $mail->setText(strip_tags($message));
        $mail->setHtml($message);
        
        $mail->send();
    }

    protected function getOptionValueName($row) {
        $text = $this->sanitizeText($row['name'], 100);

        return implode('/', array_slice(array_filter(array_map('trim', preg_split('~[,/;]+~i', $text))), 0, 3));
    }

    protected function combineOptions($arrays) {
        // Based on: https://gist.github.com/cecilemuller/4688876
        $result = array(array());

        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }

        return $result;
    }

    protected function resize($filename, $width, $height) {
        if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
            throw new \RuntimeException("Invalid image filename: " . DIR_IMAGE . $filename);
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $image_old = $filename;
        $image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

        if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
            list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
            
            if ($width_orig * $height_orig * 4 > $this->memoryLimitInBytes() * 0.4) {
                throw new \RuntimeException("Image too large, skipping: " . $image_old);
            }

            if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                throw new \RuntimeException("Unexpected image type, skipping: " . $image_old);
            }
                        
            $path = '';

            $directories = explode('/', dirname($image_new));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            if ($width_orig != $width || $height_orig != $height) {
                $image = new \Image(DIR_IMAGE . $image_old);
                $image->resize($width, $height);
                $image->save(DIR_IMAGE . $image_new);
            } else {
                copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
            }
        }
        
        $image_new = str_replace(array(' ', ','), array('%20', '%2C'), $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +
        
        return $this->store_url . 'image/' . $image_new;
    }

    protected function sanitizeText($text, $limit) {
        return utf8_substr(
            trim(
                preg_replace(
                    '~\s+~', 
                    ' ', 
                    strip_tags(
                        html_entity_decode(htmlspecialchars_decode($text, ENT_QUOTES), ENT_QUOTES, 'UTF-8')
                    )
                )
            ),
            0,
            $limit
        );
    }

    protected function setRuntimeExceptionErrorHandler() {
        set_error_handler(function($code, $message, $file, $line) {
            if (!(error_reporting() & $code)) {
                return false;
            }

            switch ($code) {
                case E_NOTICE:
                case E_USER_NOTICE:
                    $error = 'Notice';
                    break;
                case E_WARNING:
                case E_USER_WARNING:
                    $error = 'Warning';
                    break;
                case E_ERROR:
                case E_USER_ERROR:
                    $error = 'Fatal Error';
                    break;
                default:
                    $error = 'Unknown';
                    break;
            }

            $message = 'PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line;

            throw new \RuntimeException($message);
        });
    }

    protected function restoreErrorHandler() {
        restore_error_handler();
    }

    protected function getFeedProductsQuery($page, $language_id) {
        $this->load->config('googleshopping/googleshopping');

        $sql = "SELECT p.product_id, pd.name, pd.description, p.image, p.quantity, p.price, p.mpn, p.ean, p.jan, p.isbn, p.upc, p.model, p.tax_class_id, IFNULL((SELECT m.name FROM `" . DB_PREFIX . "manufacturer` m WHERE m.manufacturer_id = p.manufacturer_id), '') as brand, (SELECT GROUP_CONCAT(agt.campaign_name SEPARATOR '<[S]>') FROM `" . DB_PREFIX . "googleshopping_product_target` pagt LEFT JOIN `" . DB_PREFIX . "googleshopping_target` agt ON (agt.advertise_google_target_id = pagt.advertise_google_target_id) WHERE pagt.product_id = p.product_id AND pagt.store_id = p2s.store_id GROUP BY pagt.product_id) as campaign_names, (SELECT CONCAT_WS('<[S]>', ps.price, ps.date_start, ps.date_end) FROM `" . DB_PREFIX . "product_special` ps WHERE ps.product_id=p.product_id AND ps.customer_group_id=" . (int)$this->config->get('config_customer_group_id') . " AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) as special_price, pag.google_product_category, pag.condition, pag.adult, pag.multipack, pag.is_bundle, pag.age_group, pag.color, pag.gender, pag.size_type, pag.size_system, pag.size FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p2s.product_id = p.product_id AND p2s.store_id=" . (int)$this->store_id . ") LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "googleshopping_product` pag ON (pag.product_id = p.product_id AND pag.store_id = p2s.store_id) WHERE p2s.store_id IS NOT NULL AND pd.language_id=" . (int)$language_id . " AND pd.name != '' AND pd.description != '' AND pd.name IS NOT NULL AND pd.description IS NOT NULL AND p.image != '' AND p.status = 1 AND p.date_available <= NOW() AND p.price > 0 ORDER BY p.product_id ASC LIMIT " . (int)(($page - 1) * $this->config->get('advertise_google_push_limit')) . ', ' . (int)$this->config->get('advertise_google_push_limit');

        return $sql;
    }

    public function setEventSnippet($snippet) {
        $this->event_snippet = $snippet;
    }

    public function getEventSnippet() {
        return $this->event_snippet;
    }

    public function getEventSnippetSendTo() {
        $tracker = $this->setting->get('advertise_google_conversion_tracker');

        if (!empty($tracker['google_event_snippet'])) {
            $matches = array();

            preg_match('~send_to\': \'([a-zA-Z0-9-]*).*\'~', $tracker['google_event_snippet'], $matches);

            return $matches[1];
        }

        return null;
    }

    public function setPurchaseData($total) {
        $this->purchase_data = $total;
    }

    public function getPurchaseData() {
        return $this->purchase_data;
    }

    public function convertAndFormat($price, $currency) {
        $currency_converter = new \Cart\Currency($this->registry);
        $converted_price = $currency_converter->convert((float)$price, $this->config->get('config_currency'), $currency);
        return (float)number_format($converted_price, 2, '.', '');
    }

    public function getMerchantAuthUrl($data) {
        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_MERCHANT_AUTH_URL,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => $data
        );

        $response = $this->api($request);

        return $response['url'];
    }

    public function isConnected() {
        $settings_exist =
            $this->setting->has('advertise_google_access_token') &&
            $this->setting->has('advertise_google_refresh_token') &&
            $this->setting->has('advertise_google_app_id') &&
            $this->setting->has('advertise_google_app_secret');

        if ($settings_exist) {
            if ($this->testAccessToken() || $this->getAccessToken()) {
                return true;
            }
        }

        throw new ConnectionException("Access unavailable. Please re-connect.");
    }

    public function isStoreUrlClaimed() {
        // No need to check the connection here - this method is called immediately after checking it

        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_VERIFY_IS_CLAIMED,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => array(
                'url_website' => $this->store_url
            )
        );

        $response = $this->api($request);

        return $response['is_claimed'];
    }

    public function currencyFormat($value) {
        return '$' . number_format($value, 2, '.', ',');
    }

    public function getCampaignReports() {
        $targets = array();
        $statuses = array();
        
        foreach ($this->getTargets($this->store_id) as $target) {
            $targets[] = $target['campaign_name'];
            $statuses[$target['campaign_name']] = $target['status'];
        }
        $targets[] = 'Total';

        $cache = new \Cache($this->config->get('cache_engine'), self::CACHE_CAMPAIGN_REPORT);
        $cache_key = 'advertise_google.' . $this->store_id . '.campaign_reports.' . md5(json_encode(array_keys($statuses)) . $this->setting->get('advertise_google_reporting_interval'));

        $cache_result = $cache->get($cache_key);

        if (empty($cache_result['result']) || (isset($cache_result['timestamp']) && $cache_result['timestamp'] >= time() + self::CACHE_CAMPAIGN_REPORT)) {
            $request = array(
                'endpoint' => sprintf(self::ENDPOINT_REPORT_CAMPAIGN, $this->setting->get('advertise_google_reporting_interval')),
                'use_access_token' => true
            );

            $csv = $this->api($request);

            $lines = explode("\n", trim($csv['campaign_report']));

            $result = array(
                'date_range' => null,
                'reports' => array()
            );

            // Get date range
            $matches = array();
            preg_match('~CAMPAIGN_PERFORMANCE_REPORT \((.*?)\)~', $lines[0], $matches);
            $result['date_range'] = $matches[1];

            $header = explode(',', $lines[1]);
            $data = array();
            $total = array();
            $value_keys = array();

            $campaign_keys = array_flip($targets);

            $expected = array(
                'Campaign' => 'campaign_name',
                'Impressions' => 'impressions',
                'Clicks' => 'clicks',
                'Cost' => 'cost',
                'Conversions' => 'conversions',
                'Total conv. value' => 'conversion_value'
            );

            foreach ($header as $i => $title) {
                if (!in_array($title, array_keys($expected))) {
                    continue;
                }

                $value_keys[$i] = $expected[$title];
            }

            // Fill blank values
            foreach ($campaign_keys as $campaign_name => $l) {
                foreach ($value_keys as $i => $key) {
                    $result['reports'][$l][$key] = $key == 'campaign_name' ? $campaign_name : '&ndash;';
                }
            }

            // Fill actual values
            for ($j = 2; $j < count($lines); $j++) {
                $line_items = explode(',', $lines[$j]);
                $l = null;

                // Identify campaign key
                foreach ($line_items as $k => $line_item_value) {
                    if (array_key_exists($k, $value_keys) && array_key_exists($line_item_value, $campaign_keys) && $value_keys[$k] == 'campaign_name') {
                        $l = $campaign_keys[$line_item_value];
                    }
                }

                // Fill campaign values
                if (!is_null($l)) {
                    foreach ($line_items as $k => $line_item_value) {
                        if (!array_key_exists($k, $value_keys)) {
                            continue;
                        }

                        if (in_array($value_keys[$k], array('cost'))) {
                            $line_item_value = $this->currencyFormat((float)$line_item_value / self::MICROAMOUNT);
                        } else if (in_array($value_keys[$k], array('conversion_value'))) {
                            $line_item_value = $this->currencyFormat((float)$line_item_value);
                        } else if ($value_keys[$k] == 'conversions') {
                            $line_item_value = (int)$line_item_value;
                        }

                        $result['reports'][$l][$value_keys[$k]] = $line_item_value;
                    }
                }
            }

            $cache->set($cache_key, array(
                'timestamp' => time(),
                'result' => $result
            ));
        } else {
            $result = $cache_result['result'];
        }

        // Fill campaign statuses
        foreach ($result['reports'] as &$report) {
            if ($report['campaign_name'] == 'Total') {
                $report['status'] = '';
            } else {
                $report['status'] = $statuses[$report['campaign_name']];
            }
        }

        $this->applyNewSetting('advertise_google_report_campaigns', $result);
    }

    public function getProductReports($product_ids) {
        $cache = new \Cache($this->config->get('cache_engine'), self::CACHE_PRODUCT_REPORT);
        $cache_key = 'advertise_google.' . $this->store_id . '.product_reports.' . md5(json_encode($product_ids) . $this->setting->get('advertise_google_reporting_interval'));

        $cache_result = $cache->get($cache_key);

        if (!empty($cache_result['result']) && isset($cache_result['timestamp']) && (time() - self::CACHE_PRODUCT_REPORT <= $cache_result['timestamp'])) {
            return $cache_result['result'];
        }

        $post = array();
        $post_data = array(
            'product_ids' => $product_ids
        );

        $this->curlPostQuery($post_data, $post);

        $request = array(
            'type' => 'POST',
            'endpoint' => sprintf(self::ENDPOINT_REPORT_AD, $this->setting->get('advertise_google_reporting_interval')),
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => $post
        );

        $response = $this->api($request);

        $result = array();

        if (!empty($response['ad_report'])) {
            $lines = explode("\n", trim($response['ad_report']));

            $header = explode(',', $lines[1]);
            $data = array();
            $keys = array();
            
            $expected = array(
                'Item Id' => 'offer_id',
                'Impressions' => 'impressions',
                'Clicks' => 'clicks',
                'Cost' => 'cost',
                'Conversions' => 'conversions',
                'Total conv. value' => 'conversion_value'
            );

            foreach ($header as $i => $title) {
                if (!in_array($title, array_keys($expected))) {
                    continue;
                }

                $data[$i] = 0.0;
                $keys[$i] = $expected[$title];
            }

            // We want to omit the last line because it does not include the total number of impressions for all campaigns
            for ($j = 2; $j < count($lines) - 1; $j++) {
                $line_items = explode(',', $lines[$j]);

                $result[$j] = array();

                foreach ($line_items as $k => $line_item) {
                    if (in_array($k, array_keys($data))) {
                        $result[$j][$keys[$k]] = (float)$line_item;
                    }
                }
            }
        }

        $cache->set($cache_key, array(
            'result' => $result,
            'timestamp' => time()
        ));

        return $result;
    }

    public function getProductStatuses($product_ids) {
        $post_data = array(
            'product_ids' => $product_ids
        );

        $this->curlPostQuery($post_data, $post);

        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_MERCHANT_PRODUCT_STATUSES,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => $post
        );

        $response = $this->api($request);

        return $response['statuses'];
    }

    public function getConversionTracker() {
        $request = array(
            'endpoint' => self::ENDPOINT_CONVERSION_TRACKER,
            'use_access_token' => true
        );

        $result = $this->api($request);

        // Amend the conversion snippet by replacing the default values with placeholders.
        $search = array(
            "'value': 0.0",
            "'currency': 'USD'"
        );

        $replace = array(
            "'value': {VALUE}",
            "'currency': '{CURRENCY}'"
        );

        $result['conversion_tracker']['google_event_snippet'] = str_replace($search, $replace, $result['conversion_tracker']['google_event_snippet']);

        return $result['conversion_tracker'];
    }

    public function testCampaigns() {
        $request = array(
            'endpoint' => self::ENDPOINT_CAMPAIGN_TEST,
            'use_access_token' => true
        );
        
        $result = $this->api($request);

        return $result['status'] === true;
    }

    public function testAccessToken() {
        $request = array(
            'endpoint' => self::ENDPOINT_ACCESS_TOKEN_TEST,
            'use_access_token' => true
        );
        
        try {
            $result = $this->api($request);

            return $result['status'] === true;
        } catch (AccessForbiddenException $e) {
            throw $e;
        } catch (\RuntimeException $e) {
            // Do nothing
        }

        return false;
    }

    public function getAccessToken() {
        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_ACCESS_TOKEN,
            'use_access_token' => false,
            'content_type' => 'multipart/form-data',
            'data' => array(
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->setting->get('advertise_google_refresh_token'),
                'client_id' => $this->setting->get('advertise_google_app_id'),
                'client_secret' => $this->setting->get('advertise_google_app_secret'),
                'scope' => self::SCOPES
            )
        );

        $access = $this->api($request);

        $this->applyNewSetting('advertise_google_access_token', $access['access_token']);
        $this->applyNewSetting('advertise_google_refresh_token', $access['refresh_token']);

        return true;
    }

    public function access($data, $code) {
        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_ACCESS_TOKEN,
            'use_access_token' => false,
            'content_type' => 'multipart/form-data',
            'data' => array(
                'grant_type' => 'authorization_code',
                'client_id' => $data['app_id'],
                'client_secret' => $data['app_secret'],
                'redirect_uri' => $data['redirect_uri'],
                'code' => $code
            )
        );

        return $this->api($request);
    }

    public function authorize($data) {
        $query = array();

        $query['response_type'] = 'code';
        $query['client_id'] = $data['app_id'];
        $query['redirect_uri'] = $data['redirect_uri'];
        $query['scope'] = self::SCOPES;
        $query['state'] = $data['state'];

        return sprintf($this->endpoint_url, 'api/authorize/login') . '&' . http_build_query($query);
    }

    public function verifySite() {
        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_VERIFY_TOKEN,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => array(
                'url_website' => $this->store_url
            )
        );

        $response = $this->api($request);

        $token = $response['token'];

        $this->createVerificationToken($token);

        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_VERIFY_SITE,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => array(
                'url_website' => $this->store_url
            )
        );

        try {
            $this->api($request);

            $this->deleteVerificationToken($token);
        } catch (\RuntimeException $e) {
            $this->deleteVerificationToken($token);
            
            throw $e;
        }
    }

    public function deleteCampaign($name) {
        $post = array();
        $data = array(
            'delete' => array(
                $name
            )
        );

        $this->curlPostQuery($data, $post);

        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_CAMPAIGN_DELETE,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => $post
        );

        $this->api($request);
    }

    public function pushTargets() {
        $post = array();
        $targets = array();

        foreach ($this->getTargets($this->store_id) as $target) {
            $targets[] = array(
                'campaign_name' => $target['campaign_name_raw'],
                'country' => $target['country']['code'],
                'status' => $this->setting->get('advertise_google_status') ? $target['status'] : 'paused',
                'budget' => (float)$target['budget']['value'],
                'roas' => ((int)$target['roas']) / 100,
                'feeds' => $target['feeds_raw']
            );
        }

        $data = array(
            'target' => $targets
        );

        $this->curlPostQuery($data, $post);

        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_CAMPAIGN_UPDATE,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => $post
        );

        $response = $this->api($request);

        $this->applyNewSetting('advertise_google_work', $response['work']);
    }

    public function pushShippingAndTaxes() {
        $post = array();
        $data = $this->setting->get('advertise_google_shipping_taxes');

        $this->curlPostQuery($data, $post);

        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_MERCHANT_SHIPPING_TAXES,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => $post
        );

        $this->api($request);
    }

    public function disconnect() {
        $request = array(
            'type' => 'GET',
            'endpoint' => self::ENDPOINT_MERCHANT_DISCONNECT,
            'use_access_token' => true
        );

        $this->api($request);
    }

    public function pushCampaignStatus() {
        $post = array();
        $targets = array();

        foreach ($this->getTargets($this->store_id) as $target) {
            $targets[] = array(
                'campaign_name' => $target['campaign_name_raw'],
                'status' => $this->setting->get('advertise_google_status') ? $target['status'] : 'paused'
            );
        }

        $data = array(
            'target' => $targets
        );

        $this->curlPostQuery($data, $post);

        $request = array(
            'type' => 'POST',
            'endpoint' => self::ENDPOINT_CAMPAIGN_STATUS,
            'use_access_token' => true,
            'content_type' => 'multipart/form-data',
            'data' => $post
        );

        $this->api($request);
    }

    public function getAvailableCarriers() {
        $request = array(
            'type' => 'GET',
            'endpoint' => self::ENDPOINT_MERCHANT_AVAILABLE_CARRIERS,
            'use_access_token' => true
        );

        $result = $this->api($request);

        return $result['available_carriers'];
    }

    public function getLanguages($language_codes) {
        $this->load->config('googleshopping/googleshopping');

        $result = array();

        foreach ($this->config->get('advertise_google_languages') as $code => $name) {
            if (in_array($code, $language_codes)) {
                $supported_language_id = $this->getSupportedLanguageId($code);

                $result[] = array(
                    'status' => $supported_language_id !== 0,
                    'language_id' => $supported_language_id,
                    'code' => $code,
                    'name' => $this->getLanguageName($supported_language_id, $name)
                );
            }
        }

        return $result;
    }

    public function getLanguageName($language_id, $default) {
        $this->load->model('localisation/language');

        $language_info = $this->model_localisation_language->getLanguage($language_id);

        if (isset($language_info['name']) && trim($language_info['name']) != "") {
            return $language_info['name'];
        }

        // We do not expect to get to this point, but just in case...
        return $default;
    }

    public function getCurrencies($currency_codes) {
        $result = array();

        $this->load->config('googleshopping/googleshopping');

        $result = array();

        foreach ($this->config->get('advertise_google_currencies') as $code => $name) {
            if (in_array($code, $currency_codes)) {
                $supported_currency_id = $this->getSupportedCurrencyId($code);

                $result[] = array(
                    'status' => $supported_currency_id !== 0,
                    'code' => $code,
                    'name' => $this->getCurrencyName($supported_currency_id, $name) . ' (' . $code . ')'
                );
            }
        }

        return $result;
    }

    public function getCurrencyName($currency_id, $default) {
        $this->load->model('extension/advertise/google');

        $currency_info = $this->getCurrency($currency_id);

        if (isset($currency_info['title']) && trim($currency_info['title']) != "") {
            return $currency_info['title'];
        }

        // We do not expect to get to this point, but just in case...
        return $default;
    }

    public function getCurrency($currency_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

        return $query->row;
    }

    public function debugLog($text) {
        if ($this->setting->get('advertise_google_debug_log')) {
            $this->debug_log->write($text);
        }
    }

    protected function target($target) {
        $feeds_raw = json_decode($target['feeds'], true);

        $feeds = array_map(function($feed) {
            $language = current($this->getLanguages(array($feed['language'])));
            $currency = current($this->getCurrencies(array($feed['currency'])));

            return array(
                'text' => $language['name'] . ', ' . $currency['name'],
                'language' => $feed['language'],
                'currency' => $feed['currency']
            );
        }, $feeds_raw);

        return array(
            'target_id' => $target['advertise_google_target_id'],
            'campaign_name' => str_replace('&#44;', ',', trim($target['campaign_name'])),
            'campaign_name_raw' => $target['campaign_name'],
            'country' => array(
                'code' => $target['country'],
                'name' => $this->getCountryName($target['country'])
            ),
            'budget' => array(
                'formatted' => sprintf($this->language->get('text_per_day'), number_format((float)$target['budget'], 2)),
                'value' => (float)$target['budget']
            ),
            'feeds' => $feeds,
            'status' => $target['status'],
            'roas' => $target['roas'],
            'roas_status' => $target['date_added'] <= date('Y-m-d', time() - self::ROAS_WAIT_INTERVAL),
            'roas_available_on' => strtotime($target['date_added']) + self::ROAS_WAIT_INTERVAL,
            'feeds_raw' => $feeds_raw
        );
    }

    private function curlPostQuery($arrays, &$new = array(), $prefix = null) {
        foreach ($arrays as $key => $value) {
            $k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
            if (is_array($value)) {
                $this->curlPostQuery($value, $new, $k);
            } else {
                $new[$k] = $value;
            }
        }
    }

    private function createVerificationToken($token) {
        $dir = dirname(DIR_SYSTEM);

        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \RuntimeException("Not a directory, or no permissions to write to: " . $dir);
        }

        if (!file_put_contents($dir . '/' . $token, 'google-site-verification: ' . $token)) {
            throw new \RuntimeException("Could not write to: " . $dir . '/' . $token);
        }
    }

    private function deleteVerificationToken($token) {
        $dir = dirname(DIR_SYSTEM);

        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \RuntimeException("Not a directory, or no permissions to write to: " . $dir);
        }

        $file = $dir . '/' . $token;

        if (is_file($file) && is_writable($file)) {
            @unlink($file);
        }
    }

    private function applyNewSetting($key, $value) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code`='advertise_google' AND `key`='" . $this->db->escape($key) . "'";
        $result = $this->db->query($sql);

        if (is_array($value)) {
            $encoded = json_encode($value);
            $serialized = 1;
        } else {
            $encoded = $value;
            $serialized = 0;
        }

        if ($result->num_rows == 0) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value`='" . $this->db->escape($encoded) . "', `code`='advertise_google', `key`='" . $this->db->escape($key) . "', serialized='" . $serialized . "', store_id='0'");

            $this->setting->set($key, $value);
        } else {
            $this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`='" . $this->db->escape($encoded) . "', serialized='" . $serialized . "' WHERE `code`='advertise_google' AND `key`='" . $this->db->escape($key) . "'");

            $this->setting->set($key, $value);
        }
    }

    private function api($request) {
        $this->debugLog("REQUEST: " . json_encode($request));

        $url = sprintf($this->endpoint_url, $request['endpoint']);

        $headers = array();

        if (isset($request['content_type'])) {
            $headers[] = 'Content-Type: ' . $request['content_type'];
        } else {
            $headers[] = 'Content-Type: application/json';
        }

        if (!empty($request['use_access_token'])) {
            $headers[] = 'Authorization: Bearer ' . $this->setting->get('advertise_google_access_token');
        }

        $curl_options = array();

        if (isset($request['type']) && $request['type'] == 'POST') {
            $curl_options[CURLOPT_POST] = true;
            $curl_options[CURLOPT_POSTFIELDS] = $request['data'];
        }

        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HTTPHEADER] = $headers;

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $this->debugLog("RESPONSE: " . $result);

        if (!empty($result) && $info['http_code'] == 200) {
            $return = json_decode($result, true);

            if ($return['error']) {
                throw new \RuntimeException($return['message']);
            } else {
                return $return['result'];
            }
        } else if (in_array($info['http_code'], array(400, 401, 403))) {
            $return = json_decode($result, true);

            if ($info['http_code'] != 401 && $return['error']) {
                throw new \RuntimeException($return['message']);
            } else {
                throw new ConnectionException("Access unavailable. Please re-connect.");
            }
        } else if ($info['http_code'] == 402) {
            $return = json_decode($result, true);

            if ($return['error']) {
                throw new AccessForbiddenException($return['message']);
            } else {
                throw new ConnectionException("Access unavailable. Please re-connect.");
            }
        } else {
            $this->debugLog("CURL ERROR! CURL INFO: " . print_r($info, true));

            throw new \RuntimeException("A temporary error was encountered. Please try again later.");
        }
    }
}
