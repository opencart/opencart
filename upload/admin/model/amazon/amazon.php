<?php

class ModelAmazonAmazon extends Model {
    
    public function scheduleOrders($data) {
        $log = new Log('amazon.log');

        $requestXml = '<Request>
  <ResponseURL>' . HTTPS_CATALOG . 'index.php?route=amazon/order' . '</ResponseURL>
  <MarketplaceIDs>';
        
        foreach ($data['openbay_amazon_orders_marketplace_ids'] as $marketplaceId) {
            $requestXml .= '    <MarketplaceID>' . $marketplaceId . '</MarketplaceID>';
        }
        
        $requestXml .= '
  </MarketplaceIDs>
</Request>';
        
        $response = $this->amazon->callWithResponse('order/scheduleOrders', $requestXml, false);

        libxml_use_internal_errors(true);
        $responseXml = simplexml_load_string($response);
        libxml_use_internal_errors(false);

        if ($responseXml && $responseXml->Status == '0') {
            $log->write('Scheduling orders call was successful');
            return true;
        }

        $log->write('Failed to schedule orders. Response: ' . $response);

        return false;
    }
    
    public function saveProduct($product_id, $dataArray) {
        if(isset($dataArray['fields']['item-price'])) {
            $price = $dataArray['fields']['item-price'];
        } else if(isset($dataArray['fields']['price'])) {
            $price = $dataArray['fields']['price'];
        } else if(isset($dataArray['fields']['StandardPrice'])) {
            $price = $dataArray['fields']['StandardPrice'];
        }   else {
            $price = 0;
        }
        
        $category = (isset($dataArray['category'])) ? $dataArray['category'] : "";
        $sku = (isset($dataArray['fields']['sku'])) ? $dataArray['fields']['sku'] : "";
        if(isset($dataArray['fields']['sku'])) {
            $sku = $dataArray['fields']['sku']; 
        } else if(isset($dataArray['fields']['SKU'])) {
            $sku = $dataArray['fields']['SKU']; 
        }

        $var = isset($dataArray['optionVar']) ? $dataArray['optionVar'] : '';
        
        $marketplaces = isset($dataArray['marketplace_ids']) ? serialize($dataArray['marketplace_ids']) : serialize(array());
        
        //Todo: better serialize
        $dataEncoded = json_encode(array('fields' => $dataArray['fields']));
        
        $this->db->query("
            REPLACE INTO `" . DB_PREFIX . "amazon_product`
            SET `product_id` = '" . (int)$product_id . "', 
                `sku` = '" . $this->db->escape($sku) . "', 
                `category` = '" . $this->db->escape($category) . "', 
                `data` = '" . $this->db->escape($dataEncoded) . "', 
                `status` = 'saved', 
                `insertion_id` = '', 
                `price` = '" . $price . "',
                `var` = '" . $this->db->escape($var) . "',
                `marketplaces` = '" . $this->db->escape($marketplaces) . "'");
    }
    
    public function deleteSaved($product_id, $var = '') {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND `var` = '" . $this->db->escape($var) . "'");
    }
    
    public function getSavedProducts() {
        return $this->db->query("
            SELECT `ap`.`status`, `ap`.`product_id`, `ap`.`sku` as `amazon_sku`, `pd`.`name` as `product_name`, `p`.`model` as `product_model`, `p`.`sku` as `product_sku`, `ap`.`var` as `var`  
            FROM `" . DB_PREFIX . "amazon_product` as `ap`
            LEFT JOIN `" . DB_PREFIX . "product_description` as `pd` 
            ON `ap`.`product_id` = `pd`.`product_id`
            LEFT JOIN `" . DB_PREFIX . "product` as `p`
            ON `ap`.`product_id` = `p`.`product_id`
            WHERE `ap`.`status` = 'saved'
            AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'")->rows;
    }
    
    public function getSavedProductsData() {
        return $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "amazon_product`
            WHERE `status` = 'saved' AND `version` = 2")->rows;
    }
    
    public function getProduct($product_id, $var = '') {
        return $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND `var` = '" . $this->db->escape($var) . "' AND `version` = 2")->row;
    }
    
    public function getProductCategory($product_id, $var = '') {
        $row = $this->db->query("
            SELECT `category` FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND `var` = '" . $this->db->escape($var) . "' AND `version` = 2")->row;
        if(isset($row['category'])) {
            return $row['category'];
        } else {
            return "";
        }
    }
    
    public function setProductUploaded($product_id, $insertion_id, $var = '') {
        $this->db->query(
            "UPDATE `" . DB_PREFIX . "amazon_product`
            SET `status` = 'uploaded', `insertion_id` = '" . $this->db->escape($insertion_id) . "'
            WHERE `product_id` = '" . (int)$product_id . "' AND `var` = '" . $this->db->escape($var) . "' AND `version` = 2");
    }
    
    public function resetUploaded($insertion_id) {
        $this->db->query(
            "UPDATE `" . DB_PREFIX . "amazon_product`
            SET `status` = 'saved', `insertion_id` = ''
            WHERE `insertion_id` = '" . $this->db->escape($insertion_id) . "' AND `version` = 2");
    }
    
    public function getProductStatus($product_id) {
        
        $rowsUploaded = $this->db->query("
            SELECT COUNT(*) count
            FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND status = 'uploaded'")->row;
        $rowsUploaded = $rowsUploaded['count'];
        
        $rowsOk = $this->db->query("
            SELECT COUNT(*) count
            FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND status = 'ok'")->row;
        $rowsOk = $rowsOk['count'];
                
        $rowsError = $this->db->query("
            SELECT COUNT(*) count
            FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND status = 'error'")->row;
        $rowsError = $rowsError['count'];
        
        $rowsSaved = $this->db->query("
            SELECT COUNT(*) count
            FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND status = 'saved'")->row;
        $rowsSaved = $rowsSaved['count'];
        
        $rowsTotal = $rowsUploaded + $rowsOk + $rowsError + $rowsSaved;
        
        $links = $this->db->query("
            SELECT COUNT(*) as count
            FROM `" . DB_PREFIX . "amazon_product_link`
            WHERE `product_id` = '" . (int)$product_id . "'")->row;
        $links = $links['count'];
        
        
        if($rowsTotal === 0 && $links > 0) {
            return 'linked';
        } else if($rowsTotal == 0) {
            return false;
        }
        
        if($rowsUploaded > 0) {
            return 'processing';
        }
        
        if($rowsUploaded == 0 && $rowsOk > 0 && $rowsError == 0) {
            return 'ok';
        }
        
        if($rowsSaved > 0) {
            return 'saved';
        }
        
        if($rowsUploaded == 0 && $rowsError > 0 && $rowsOk == 0) {
            $quick = $this->db->query("
                SELECT *
                FROM `" . DB_PREFIX . "amazon_product`
                WHERE `product_id` = " . (int) $product_id . " AND `version` = 3")->row;
            
            if($quick) {
                return 'error_quick';
            } else {
                return 'error_advanced';
            }
        } else {
            return 'error_few';
        }
        
        return false;
    }
    
    public function getProductErrors($product_id, $version = 2) {
        if($version == 3) {
            $messageRow = $this->db->query("
            SELECT `messages` FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND `version` = 3")->row;
            
            return json_decode($messageRow['messages']);
        }
        
        
        $result = array();
        
        $insertionRows = $this->db->query("
            SELECT `sku`, `insertion_id` FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND `version` = 2")->rows;
        
        if(!empty($insertionRows)) {
            foreach($insertionRows as $insertionRow) {
                $errorRows = $this->db->query("
                    SELECT * FROM `" . DB_PREFIX . "amazon_product_error`
                    WHERE `sku` = '" . $this->db->escape($insertionRow['sku']) . "' AND `insertion_id` = '" . $this->db->escape($insertionRow['insertion_id']) . "'")->rows;
                foreach($errorRows as $errorRow) {
                    $result[] = $errorRow;
                }
            }
        }
        return $result;
    }
    
    public function getProductsWithErrors() {
        return $this->db->query("
            SELECT `product_id`, `sku` FROM `" . DB_PREFIX . "amazon_product`
            WHERE `status` = 'error' AND `version` = 2")->rows;
    }
    
    public function deleteProduct($product_id) {
        $this->db->query(
            "DELETE FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "'");
    }
    
    public function linkProduct($amazon_sku, $product_id, $var = '') {
        $count = $this->db->query("SELECT COUNT(*) as 'count' FROM `" . DB_PREFIX . "amazon_product_link` WHERE `product_id` = '" . (int)$product_id . "' AND `amazon_sku` = '" . $this->db->escape($amazon_sku) . "' AND `var` = '" . $this->db->escape($var) . "' LIMIT 1")->row;
        if($count['count'] == 0) {
            $this->db->query(
                "INSERT INTO `" . DB_PREFIX . "amazon_product_link`
                SET `product_id` = '" . (int)$product_id . "', `amazon_sku` = '" . $this->db->escape($amazon_sku) . "', `var` = '" . $this->db->escape($var) . "'");
        }
    }
    
    public function removeProductLink($amazon_sku) {
        $this->db->query(
            "DELETE FROM `" . DB_PREFIX . "amazon_product_link`
            WHERE `amazon_sku` = '" . $this->db->escape($amazon_sku) . "'");
    }
    
    public function removeAdvancedErrors($product_id) {
        $product_rows = $this->db->query("
            SELECT `insertion_id` FROM `" . DB_PREFIX . "amazon_product`
            WHERE `product_id` = '" . (int)$product_id . "' AND `version` = 2")->rows;
        
        foreach ($product_rows as $product) {
            $this->db->query(
                "DELETE FROM `" . DB_PREFIX . "amazon_product_error`
                WHERE `insertion_id` = '" . $this->db->escape($product['insertion_id']) . "'");
        }
        
        $this->db->query(
            "UPDATE `" . DB_PREFIX . "amazon_product`
            SET `status` = 'saved', `insertion_id` = ''
            WHERE `product_id` = '" . (int)$product_id . "' AND `status` = 'error' AND `version` = 2");
    }
    
    public function getProductLinks($product_id = 'all') {
        $query = "SELECT `apl`.`amazon_sku`, `apl`.`product_id`, `pd`.`name` as `product_name`, `p`.`model`, `p`.`sku`, `apl`.`var`, '' as `combi`
            FROM `" . DB_PREFIX . "amazon_product_link` as `apl`
            LEFT JOIN `" . DB_PREFIX . "product_description` as `pd` 
            ON `apl`.`product_id` = `pd`.`product_id`
            LEFT JOIN `" . DB_PREFIX . "product` as `p`
            ON `apl`.`product_id` = `p`.`product_id`";
        if($product_id != 'all') {
            $query .= " WHERE `apl`.`product_id` = '" . (int) $product_id . "' AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";
        }else{
            $query .= "WHERE `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";
        }
        
        $rows = $this->db->query($query)->rows;
        
        $this->load->library('amazon');
        if($this->amazon->addonLoad('openstock') == true) {
            $this->load->model('openstock/openstock');
            $this->load->model('tool/image');
            $rowsWithVar = array();
            foreach($rows as $row) {
                $stockOpts = $this->model_openstock_openstock->getProductOptionStocks($row['product_id']);
                foreach($stockOpts as $opt) {
                    if($opt['var'] == $row['var']) {
                        $row['combi'] = $opt['combi'];
                        $row['sku'] = $opt['sku'];
                        break;
                    }
                }
                $rowsWithVar[] = $row;
            }
            return $rowsWithVar;
        } else {
            return $rows;
        }
    }
    
    public function getUnlinkedProducts() {
        $this->load->library('amazon');
        if($this->amazon->addonLoad('openstock') == true) {
            
            $rows = $this->db->query("
                SELECT `p`.`product_id`, `p`.`model`, `p`.`sku`, `pd`.`name` as `product_name`, '' as `var`, '' as `combi`, `p`.`has_option`
                FROM `" . DB_PREFIX . "product` as `p`
                LEFT JOIN `" . DB_PREFIX . "product_description` as `pd` 
                ON `p`.`product_id` = `pd`.`product_id`
                AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'")->rows;
            
            $result = array();
            $this->load->model('openstock/openstock');
            $this->load->model('tool/image');
            foreach($rows as $row) {
                 if(!$this->productLinkExists($row['product_id'], $row['var'])) {
                    $result[] = $row;
                }
                $stockOpts = $this->model_openstock_openstock->getProductOptionStocks($row['product_id']);
                foreach($stockOpts as $opt) {
                    if($this->productLinkExists($row['product_id'], $opt['var'])) {
                        continue;
                    }
                    $row['var'] = $opt['var'];
                    $row['combi'] = $opt['combi'];
                    $row['sku'] = $opt['sku'];
                    $result[] = $row;
                }
            }
        } else {
            $result = $this->db->query("
                SELECT `p`.`product_id`, `p`.`model`, `p`.`sku`, `pd`.`name` as `product_name`, '' as `var`, '' as `combi`
                FROM `" . DB_PREFIX . "product` as `p`
                LEFT JOIN `" . DB_PREFIX . "product_description` as `pd` 
                ON `p`.`product_id` = `pd`.`product_id`
                LEFT JOIN `" . DB_PREFIX . "amazon_product_link` as `apl`
                ON `apl`.`product_id` = `p`.`product_id`
                WHERE `apl`.`amazon_sku` IS NULL
                AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'")->rows;
        }
        
        return $result;
    }
    
    private function productLinkExists($product_id, $var) {
         $link = $this->db->query("SELECT * FROM `" . DB_PREFIX . "amazon_product_link` WHERE `product_id` = " . (int)$product_id . " AND var = '" . $this->db->escape($var) . "'")->row;
         if(empty($link)) {
             return false;
         } else {
             return true;
         }
    }
    
    public function getOrderStatusString($orderId) {
        $row = $this->db->query("
            SELECT `s`.`key`
            FROM `" . DB_PREFIX . "order` `o`
            JOIN `" . DB_PREFIX . "setting` `s` ON `o`.`order_id` = " . (int) $orderId . " AND `s`.`value` = `o`.`order_status_id`
            WHERE `s`.`key` = 'openbay_amazon_order_status_shipped' OR `s`.`key` = 'openbay_amazon_order_status_canceled'
            LIMIT 1")->row;
        
        if (!isset($row['key']) || empty($row['key'])) {
            return null;
        }
        
        $key = $row['key'];
        
        switch ($key) {
            case 'openbay_amazon_order_status_shipped':
                $orderStatus = 'shipped';
                break;
            case 'openbay_amazon_order_status_canceled':
                $orderStatus = 'canceled';
                break;
            
            default:
                $orderStatus = null;
                break;
        }
        
        return $orderStatus;
    }
    
    public function updateAmazonOrderTracking($orderId, $courierId, $courierFromList, $trackingNo) {
        $this->db->query("
            UPDATE `" . DB_PREFIX . "amazon_order`
            SET `courier_id` = '" . $courierId . "',
                `courier_other` = " . (int)!$courierFromList . ",
                `tracking_no` = '" . $trackingNo . "'
            WHERE `order_id` = " . (int) $orderId . "");
    }
    
    public function getAmazonOrderId($orderId) {
        $row = $this->db->query("
            SELECT `amazon_order_id`
            FROM `" . DB_PREFIX . "amazon_order`
            WHERE `order_id` = " . (int) $orderId . "
            LIMIT 1")->row;
        
        if (isset($row['amazon_order_id']) && !empty($row['amazon_order_id'])) {
            return $row['amazon_order_id'];
        }
        
        return NULL;
    }
    
    public function getAmazonOrderedProducts($orderId) {
        return $this->db->query("
            SELECT `aop`.`amazon_order_item_id`, `op`.`quantity`
            FROM `" . DB_PREFIX . "amazon_order_product` `aop`
            JOIN `" . DB_PREFIX . "order_product` `op` ON `op`.`order_product_id` = `aop`.`order_product_id`
                AND `op`.`order_id` = " . (int) $orderId)->rows;
    }
    
    public function getProductQuantity($product_id, $var = '') {
        $this->load->library('amazon');
        
        $result = null;
        
        if($var !== '' && $this->amazon->addonLoad('openstock')) {
            $this->load->model('tool/image');
            $this->load->model('openstock/openstock');
            $optionStocks = $this->model_openstock_openstock->getProductOptionStocks($product_id);
            
            $option = null;
            foreach ($optionStocks as $optionIterator) {
                if($optionIterator['var'] === $var) {
                    $option = $optionIterator;
                    break;
                }
            }
            
            if($option != null) {
                $result = $option['stock'];
            }
        } else {
            $this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($product_id);
        
            if (isset($product_info['quantity'])) {
                $result = $product_info['quantity'];
            }
        }
        return $result;
    }
    
    public function install(){
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazon_order` (
              `order_id` int(11) NOT NULL ,
              `amazon_order_id` char(19) NOT NULL ,
              `courier_id` varchar(255) NOT NULL ,
              `courier_other` tinyint(1) NOT NULL,
              `tracking_no` varchar(255) NOT NULL ,
              PRIMARY KEY (`order_id`, `amazon_order_id`)
        ) DEFAULT COLLATE=utf8_general_ci;");
        
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazon_order_product` (
                `order_product_id` int(11) NOT NULL ,
                `amazon_order_item_id` varchar(255) NOT NULL,
                PRIMARY KEY(`order_product_id`, `amazon_order_item_id`)
        );");
        
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazon_product_unshipped` (
                `order_id` int(11) NOT NULL,
                `product_id` int(11) NOT NULL,
                `quantity` int(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`order_id`,`product_id`)
            ) DEFAULT COLLATE=utf8_general_ci;;");
        
        $this->db->query("
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazon_product` (
          `version` int(11) NOT NULL DEFAULT 2,
          `product_id`  int(11) NOT NULL ,
          `category`  varchar(255) NOT NULL ,
          `sku`  varchar(255) NOT NULL ,
          `insertion_id` varchar(255) NOT NULL ,
          `data`  text NOT NULL ,
          `status` enum('saved','uploaded','ok','error') NOT NULL ,
          `price`  decimal(15,4) NOT NULL COMMENT 'Price on Amazon' ,
          `var` char(100) NOT NULL DEFAULT '',
          `marketplaces` text NOT NULL ,
          `messages` text NOT NULL,
          PRIMARY KEY (`product_id`, `var`)
        );");
        
        $this->db->query("
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazon_product_error` (
          `error_id` int(11) NOT NULL AUTO_INCREMENT,
          `sku` varchar(255) NOT NULL ,
          `insertion_id` varchar(255) NOT NULL ,
          `error_code` int(11) NOT NULL ,
          `message` text NOT NULL ,
          PRIMARY KEY (`error_id`)
        );");
        
        $this->db->query("
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazon_product_link` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `amazon_sku` varchar(255) NOT NULL,
          `var` char(100) NOT NULL DEFAULT '',
          `product_id` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT COLLATE=utf8_general_ci;;");
    }
    
    public function uninstall(){
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_order`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_order_product`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_product2`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_product`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_product_link`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_product_unshipped`");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` = 'openbay_amazon'");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_product_error`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_process`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_product_unshipped`");
    }
}