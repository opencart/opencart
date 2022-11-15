<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade9 extends \Opencart\System\Engine\Controller {
    public function index(): void {
        $this->load->language('upgrade/upgrade');

        $json = [];

        // Fix: https://github.com/opencart/opencart/issues/11971
        // Previous SEO URL from OC v3.x releases
        $query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "seo_url' AND COLUMN_NAME = 'sort_order'");

        if (!$query->num_rows) {
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "seo_url`");

            // It makes mass changes to the DB by creating tables that are not in the current db, changes the charset and DB engine to the SQL schema.
            try {
                // Structure
                $this->load->helper('db_schema');

                $tables = oc_db_schema();

                foreach ($tables as $table) {
                    if ($table['name'] == 'seo_url') {
                        $table_query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table['name'] . "'");

                        if (!$table_query->num_rows) {
                            $sql = "CREATE TABLE `" . DB_PREFIX . $table['name'] . "` (" . "\n";

                            foreach ($table['field'] as $field) {
                                $sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . $this->db->escape($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
                            }

                            if (isset($table['primary'])) {
                                $primary_data = [];

                                foreach ($table['primary'] as $primary) {
                                    $primary_data[] = "`" . $primary . "`";
                                }

                                $sql .= " PRIMARY KEY (" . implode(",", $primary_data) . "),\n";
                            }

                            if (isset($table['index'])) {
                                foreach ($table['index'] as $index) {
                                    $index_data = [];

                                    foreach ($index['key'] as $key) {
                                        $index_data[] = "`" . $key . "`";
                                    }

                                    $sql .= " KEY `" . $index['name'] . "` (" . implode(",", $index_data) . "),\n";
                                }
                            }

                            $sql = rtrim($sql, ",\n") . "\n";
                            $sql .= ") ENGINE=" . $table['engine'] . " CHARSET=" . $table['charset'] . " COLLATE=" . $table['collate'] . ";\n";

                            $this->db->query($sql);
                        }
                    }
                }

                $seo_urls = [];

                // product_id
                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 47,
                    'keyword'     => 'hp-lp3065',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 48,
                    'keyword'     => 'ipod-classic',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 28,
                    'keyword'     => 'htc-touch-hd',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 43,
                    'keyword'     => 'macbook',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 44,
                    'keyword'     => 'macbook-air',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 45,
                    'keyword'     => 'macbook-pro',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 30,
                    'keyword'     => 'canon-eos-5d',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 31,
                    'keyword'     => 'nikon-d300',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 29,
                    'keyword'     => 'palm-treo-pro',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 35,
                    'keyword'     => 'product-8',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 49,
                    'keyword'     => 'samsung-galaxy-tab-10-1',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 33,
                    'keyword'     => 'samsung-syncmaster-941bw',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 46,
                    'keyword'     => 'sony-vaio',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 41,
                    'keyword'     => 'imac',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 40,
                    'keyword'     => 'iphone',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 36,
                    'keyword'     => 'ipod-nano',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 34,
                    'keyword'     => 'ipod-shuffle',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 32,
                    'keyword'     => 'ipod-touch',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 50,
                    'keyword'     => 'apple-4',
                    'sort_order'  => 1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'product_id',
                    'value'       => 42,
                    'keyword'     => 'apple-cinema',
                    'sort_order'  => 1
                ];

                // manufacturer_id
                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'manufacturer_id',
                    'value'       => 5,
                    'keyword'     => 'htc',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'manufacturer_id',
                    'value'       => 7,
                    'keyword'     => 'hewlett-packard',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'manufacturer_id',
                    'value'       => 6,
                    'keyword'     => 'palm',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'manufacturer_id',
                    'value'       => 10,
                    'keyword'     => 'sony',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'manufacturer_id',
                    'value'       => 9,
                    'keyword'     => 'canon',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'manufacturer_id',
                    'value'       => 8,
                    'keyword'     => 'apple',
                    'sort_order'  => 0
                ];

                // path
                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => 30,
                    'keyword'     => 'printer',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '20_27',
                    'keyword'     => 'desktops/mac',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '20_26',
                    'keyword'     => 'desktops/pc',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => 25,
                    'keyword'     => 'component',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '25_29',
                    'keyword'     => 'component/mouse',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => 33,
                    'keyword'     => 'cameras',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '25_28',
                    'keyword'     => 'component/monitor',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '25_28_35',
                    'keyword'     => 'component/monitor/test-1',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '25_28_36',
                    'keyword'     => 'component/monitor/test-2',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '25_30',
                    'keyword'     => 'component/printers',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '25_31',
                    'keyword'     => 'component/scanners',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '25_32',
                    'keyword'     => 'component/web-camera',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => 20,
                    'keyword'     => 'desktops',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => 18,
                    'keyword'     => 'laptop-notebook',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '18_46',
                    'keyword'     => 'laptop-notebook/macs',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '18_45',
                    'keyword'     => 'laptop-notebook/windows',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => 34,
                    'keyword'     => 'mp3-players',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_43',
                    'keyword'     => 'mp3-players/test-11',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_44',
                    'keyword'     => 'mp3-players/test-12',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_47',
                    'keyword'     => 'mp3-players/test-15',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_48',
                    'keyword'     => 'mp3-players/test-16',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_49',
                    'keyword'     => 'mp3-players/test-17',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_50',
                    'keyword'     => 'mp3-players/test-18',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_51',
                    'keyword'     => 'mp3-players/test-19',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_52',
                    'keyword'     => 'mp3-players/test-20',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_52_58',
                    'keyword'     => 'mp3-players/test-20/test-25',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_53',
                    'keyword'     => 'mp3-players/test-21',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_54',
                    'keyword'     => 'mp3-players/test-22',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_55',
                    'keyword'     => 'mp3-players/test-23',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_56',
                    'keyword'     => 'mp3-players/test-24',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_38',
                    'keyword'     => 'mp3-players/test-4',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_37',
                    'keyword'     => 'mp3-players/test-5',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_39',
                    'keyword'     => 'mp3-players/test-6',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_40',
                    'keyword'     => 'mp3-players/test-7',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_41',
                    'keyword'     => 'mp3-players/test-8',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => '34_42',
                    'keyword'     => 'mp3-players/test-9',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => 24,
                    'keyword'     => 'smartphone',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => 17,
                    'keyword'     => 'software',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'path',
                    'value'       => 57,
                    'keyword'     => 'tablet',
                    'sort_order'  => 0
                ];

                // information_id
                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'information_id',
                    'value'       => 1,
                    'keyword'     => 'about-us',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'information_id',
                    'value'       => 2,
                    'keyword'     => 'terms',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'information_id',
                    'value'       => 4,
                    'keyword'     => 'delivery',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'information_id',
                    'value'       => 3,
                    'keyword'     => 'privacy',
                    'sort_order'  => 0
                ];

                // language
                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'language',
                    'value'       => 'en-gb',
                    'keyword'     => 'en-gb',
                    'sort_order'  => -2
                ];

                // route
                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'route',
                    'value'       => 'information/information.info',
                    'keyword'     => 'info',
                    'sort_order'  => 0
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'route',
                    'value'       => 'information/information',
                    'keyword'     => 'information',
                    'sort_order'  => -1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'route',
                    'value'       => 'product/product',
                    'keyword'     => 'product',
                    'sort_order'  => -1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'route',
                    'value'       => 'product/category',
                    'keyword'     => 'catalog',
                    'sort_order'  => -1
                ];

                $seo_urls[] = [
                    'store_id'    => 0,
                    'language_id' => 1,
                    'key'         => 'route',
                    'value'       => 'product/manufacturer',
                    'keyword'     => 'brands',
                    'sort_order'  => -1
                ];

                foreach ($seo_urls as $seo_url) {
                    $value = preg_replace('/[^a-zA-Z0-9_|\/\.]/', '', str_replace('|', '.', $seo_url['value']));

                    $this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$seo_url['store_id'] . "', `language_id` = '" . (int)$seo_url['language_id'] . "', `key` = '" . $this->db->escape($seo_url['key']) . "', `value` = '" . $value . "', `keyword` = '" . $this->db->escape($seo_url['keyword']) . "', `sort_order` = '" . (int)$seo_url['sort_order'] . "'");
                }
            } catch (\ErrorException $exception) {
                $json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
            }
        }

        // Fix https://github.com/opencart/opencart/issues/11594
        $this->db->query("UPDATE `" . DB_PREFIX . "layout_route` SET `route` = REPLACE(`route`, '|', '.')");
        $this->db->query("UPDATE `" . DB_PREFIX . "seo_url` SET `value` = REPLACE(`value`, '|', '.') WHERE `key` = 'route'");
        $this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = REPLACE(`trigger`, '|', '.'), `action` = REPLACE(`action`, '|', '.')");
        $this->db->query("UPDATE `" . DB_PREFIX . "banner_image` SET `link` = REPLACE(`link`, '|', '.')");

        if (!$json) {
            $json['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['admin'])) {
                $url .= '&admin=' . $this->request->get['admin'];
            }

            $json['redirect'] = $this->url->link('install/step_4', $url, true);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
