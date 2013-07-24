<?php
class Amazonus {

    private $token;
    private $encPass;
    private $encSalt;

    private $server = 'http://us-amazon.openbaypro.com/';
    
    private $registry;
    
    public function __construct($registry) {
        $this->registry = $registry;
        
        $this->token   = $registry->get('config')->get('openbay_amazonus_token');
        $this->encPass = $registry->get('config')->get('openbay_amazonus_enc_string1');
        $this->encSalt = $registry->get('config')->get('openbay_amazonus_enc_string2');
        
    }
    
    public function __get($name) {
        return $this->registry->get($name);
    }
    
    public function orderNew($orderId) {
        if ($this->config->get('amazonus_status') != 1) {
            return;
        }
        
        /* Is called from front-end? */
        if (!defined('HTTPS_CATALOG')) {
            $this->load->model('amazonus/order');
            $amazonusOrderId = $this->model_amazonus_order->getAmazonusOrderId($orderId);

            $this->load->library('log');
            $logger = new Log('amazonus_stocks.log');
            $logger->write('orderNew() called with order id: ' . $orderId);

            //Stock levels update
            if($this->addonLoad('openstock') == true){
                $logger->write('openStock found installed.');

                $osProducts = $this->osProducts($orderId);
                $logger->write(print_r($osProducts, true));
                $quantityData = array();
                foreach ($osProducts as $osProduct) {
                    $amazonusSkuRows = $this->getLinkedSkus($osProduct['pid'], $osProduct['var']); 
                    foreach($amazonusSkuRows as $amazonusSkuRow) {
                        $quantityData[$amazonusSkuRow['amazonus_sku']] = $osProduct['qty_left'];
                    }
                }
                if(!empty($quantityData)) {
                    $logger->write('Updating quantities with data: ' . print_r($quantityData, true));
                    $this->updateQuantities($quantityData);
                } else {
                    $logger->write('No quantity data need to be posted.');
                }
            } else {
                $orderedProducts = $this->getOrderdProducts($orderId);
                $orderedProductIds = array();
                foreach($orderedProducts as $orderedProduct) {
                    $orderedProductIds[] = $orderedProduct['product_id'];
                }
                $this->putStockUpdateBulk($orderedProductIds);
            }
            $logger->write('orderNew() exiting');
        }
    }
    
    
    public function productUpdateListen($productId, $data) {
        $logger = new Log('amazonus_stocks.log');
        $logger->write('productUpdateListen called for product id: ' . $productId);
        
        if($this->addonLoad('openstock') == true && (isset($data['has_option']) && $data['has_option'] == 1)) {
            $logger->write('openStock found installed and product has options.');
            $quantityData = array();
            foreach($data['product_option_stock'] as $optStock) {
                $amazonusSkuRows = $this->getLinkedSkus($productId, $optStock['var']); 
                foreach($amazonusSkuRows as $amazonusSkuRow) {
                    $quantityData[$amazonusSkuRow['amazonus_sku']] = $optStock['stock'];
                }
            }
            if(!empty($quantityData)) {
                $logger->write('Updating quantities with data: ' . print_r($quantityData, true));
                $this->updateQuantities($quantityData);
            } else {
                $logger->write('No quantity data need to be posted.');
            }
            
        } else {
            $this->putStockUpdateBulk(array($productId));
        }
        $logger->write('productUpdateListen() exiting');
    }
    
    public function updateOrder($orderId, $orderStatusString, $courier_id = '', $courierFromList = true, $tracking_no = '') {   
        
        if ($this->config->get('amazonus_status') != 1) {
            return;
        }
        
        /* Is called from admin? */
        if (!defined('HTTPS_CATALOG')) {
            return;
        }
        
        $amazonusOrder = $this->getOrder($orderId);
        
        if(!$amazonusOrder) {
            return;
        }
        
        $amazonusOrderId = $amazonusOrder['amazonus_order_id'];

        
        $log = new Log('amazonus.log');
        $log->write("Order's $amazonusOrderId status changed to $orderStatusString");
        
        
        $this->load->model('amazonus/amazonus');
        $amazonusOrderProducts = $this->model_amazonus_amazonus->getAmazonusOrderedProducts($orderId);
        
        
        $requestNode = new SimpleXMLElement('<Request/>');
        
        $requestNode->addChild('AmazonusOrderId', $amazonusOrderId);
        $requestNode->addChild('Status', $orderStatusString);
       
        if(!empty($courier_id)) {
            if($courierFromList) {
                $requestNode->addChild('CourierId', $courier_id);
            } else {
                $requestNode->addChild('CourierOther', $courier_id);
            }
            $requestNode->addChild('TrackingNo', $tracking_no);   
        }
        
        $orderItemsNode = $requestNode->addChild('OrderItems');
        
        foreach ($amazonusOrderProducts as $product) {
            $newOrderItem = $orderItemsNode->addChild('OrderItem');
            $newOrderItem->addChild('ItemId', htmlspecialchars($product['amazonus_order_item_id']));
            $newOrderItem->addChild('Quantity', (int) $product['quantity']);
        }
        
        $doc = new DOMDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($requestNode->asXML());
        $doc->formatOutput = true;

        $this->model_amazonus_amazonus->updateAmazonusOrderTracking($orderId, $courier_id, $courierFromList, !empty($courier_id) ? $tracking_no : '');
        $log->write('Request: ' . $doc->saveXML());        
        $response = $this->callWithResponse('order/update2', $doc->saveXML(), false);
        $log->write("Response for Order's status update: $response");
    }
    
    public function getCategoryTemplates() {
        $result = $this->callWithResponse("productv2/RequestTemplateList");
        if(isset($result)) {
            return (array)json_decode($result);
        } else {
            return array();
        }
    }
    
    public function registerInsertion($data) {
        $result = $this->callWithResponse("productv2/RegisterInsertionRequest", $data);
        if(isset($result)) {
            return (array)json_decode($result);
        } else {
            return array();
        }
    }
    
    public function insertProduct($data) {
        $result = $this->callWithResponse("productv2/InsertProductRequest", $data);
        if(isset($result)) {
            return (array)json_decode($result);
        } else {
            return array();
        }
    }
    
    public function updateQuantities($data) {
        $result = $this->callWithResponse("product/UpdateQuantityRequest", $data);
        if(isset($result)) {
            return (array)json_decode($result);
        } else {
            return array();
        }    
    }
    
     public function getStockUpdatesStatus($data) {
        $result = $this->callWithResponse("status/StockUpdates", $data);
        if(isset($result)) {
            return $result;
        } else {
            return false;
        }    
    }
    
    public function callNoResponse($method, $data = array(), $isJson = true) {
        if  ($isJson) {
            $argString = json_encode($data);
        } else {
            $argString = $data;
        }
        
        $token = $this->pbkdf2($this->encPass, $this->encSalt, 1000, 32);
        $crypt = $this->encrypt($argString, $token, true);
        
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $this->server . $method,
            CURLOPT_USERAGENT => 'OpenBay Pro for Amazonus/Opencart', 
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 2,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_POSTFIELDS => 'token=' . $this->token . '&data=' . rawurlencode($crypt),
        );
        $ch = curl_init();

        curl_setopt_array($ch, $defaults);
        
        curl_exec($ch);
        
        curl_close($ch);
    }
    
    public function callWithResponse($method, $data = array(), $isJson = true) {
        if  ($isJson) {
            $argString = json_encode($data);
        } else {
            $argString = $data;
        }
        
        $token = $this->pbkdf2($this->encPass, $this->encSalt, 1000, 32);
        $crypt = $this->encrypt($argString, $token, true);
        
        $defaults = array(
            CURLOPT_POST            => 1,
            CURLOPT_HEADER          => 0,
            CURLOPT_URL             => $this->server . $method,
            CURLOPT_USERAGENT       => 'OpenBay Pro for Amazonus/Opencart', 
            CURLOPT_FRESH_CONNECT   => 1,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_FORBID_REUSE    => 1,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_SSL_VERIFYPEER  => 0,
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_POSTFIELDS      => 'token=' . $this->token . '&data=' . rawurlencode($crypt),
        );
        $ch = curl_init();

        curl_setopt_array($ch, $defaults);
        
        $response = curl_exec($ch);
        
        curl_close($ch);
        
        return $response;
    }
    
    public function decryptArgs($crypt, $isBase64 = true) {
        if ($isBase64) {
            $crypt = base64_decode($crypt, true);
            if (!$crypt) {
                return false;
            }
        }
        
        $token = $this->pbkdf2($this->encPass, $this->encSalt, 1000, 32);
        $data = $this->decrypt($crypt, $token);        
        
        return $data;
    }

    private function encrypt($msg, $k, $base64 = false) {
        if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', ''))
            return false;

        $iv = mcrypt_create_iv(32, MCRYPT_RAND);

        if (mcrypt_generic_init($td, $k, $iv) !== 0)
            return false;

        $msg = mcrypt_generic($td, $msg);
        $msg = $iv . $msg;
        $mac = $this->pbkdf2($msg, $k, 1000, 32);
        $msg .= $mac;

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        if ($base64) {
            $msg = base64_encode($msg);
        }

        return $msg;
    }

    private function decrypt($msg, $k, $base64 = false) {
        if ($base64) {
            $msg = base64_decode($msg);
        }

        if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', '')) {
            return false;
        }

        $iv = substr($msg, 0, 32);
        $mo = strlen($msg) - 32;
        $em = substr($msg, $mo);
        $msg = substr($msg, 32, strlen($msg) - 64);
        $mac = $this->pbkdf2($iv . $msg, $k, 1000, 32);

        if ($em !== $mac) {
            return false;
        }

        if (mcrypt_generic_init($td, $k, $iv) !== 0) {
            return false;
        }

        $msg = mdecrypt_generic($td, $msg);
        $msg = unserialize($msg);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $msg;
    }

    private function pbkdf2($p, $s, $c, $kl, $a = 'sha256') {
        $hl = strlen(hash($a, null, true));
        $kb = ceil($kl / $hl);
        $dk = '';

        for ($block = 1; $block <= $kb; $block++) {

            $ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);

            for ($i = 1; $i < $c; $i++)
                $ib ^= ($b = hash_hmac($a, $b, $p, true));

            $dk .= $ib;
        }

        return substr($dk, 0, $kl);
    }
    
    public function getServer() {
        return $this->server;
    }

    /*
     *
     * Takes an array of product id's that stock has just been modified for.
     * If the endInactive flag is true then it should end items that the status is 0
     *
     */
    public function putStockUpdateBulk($productIdArray, $endInactive = false){
        $this->load->library('log');
        $logger = new Log('amazonus_stocks.log');
        $logger->write('Updating stock using putStockUpdateBulk()');
        $quantityData = array();
        foreach($productIdArray as $productId) {
            $amazonusRows = $this->getLinkedSkus($productId);
            foreach($amazonusRows as $amazonusRow) {
                $productRow = $this->db->query("SELECT quantity, status FROM `" . DB_PREFIX . "product`
                    WHERE `product_id` = '" . (int) $productId . "'")->row;
                
                if(!empty($productRow)) {
                    if($endInactive && $productRow['status'] == '0') {
                        $quantityData[$amazonusRow['amazonus_sku']] = 0;
                    } else {
                        $quantityData[$amazonusRow['amazonus_sku']] = $productRow['quantity'];
                    }
                }
            }
        }
        if(!empty($quantityData)) {
            $logger->write('Quantity data to be sent:' . print_r($quantityData, true));
            $response = $this->amazonus->updateQuantities($quantityData);
            $logger->write('Submit to API. Response: ' . print_r($response, true));
        } else {
            $logger->write('No quantity data need to be posted.');
        }
    }
    
    /*
     * stock control
     */
     public function getLinkedSkus($productId, $var='') {
        return $this->db->query("SELECT `amazonus_sku`
            FROM `" . DB_PREFIX . "amazonus_product_link`
            WHERE `product_id` = '" . (int)$productId . "' AND `var` = '" . $var . "'
            ")->rows;
     }

     public function getOrderdProducts($orderId) {
         return $this->db->query("SELECT `op`.`product_id`, `p`.`quantity` as `quantity_left`
            FROM `" . DB_PREFIX . "order_product` as `op`
            LEFT JOIN `" . DB_PREFIX . "product` as `p` 
            ON `p`.`product_id` = `op`.`product_id`
            WHERE `op`.`order_id` = '" . (int) $orderId . "'
            ")->rows; 
    }
    
    
    /*
     * osProducts
     *
     * Gets the products in an order (with quantities left) when OpenStock is installed.
     *
     * @param $order_id
     * @return array
     */
    public function osProducts($order_id){
     //   $this->log('Getting products from osProducts()');
        $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

        $passArray = array();
        foreach ($order_product_query->rows as $order_product) {
            $product_query = $this->db->query("
                SELECT *
                FROM " . DB_PREFIX . "product
                WHERE `product_id` = '" . (int) $order_product['product_id'] . "'
                LIMIT 1");

            if (isset($product_query->row['has_option']) && ($product_query->row['has_option'] == 1)) {
                $pOption_query = $this->db->query("
                        SELECT `" . DB_PREFIX . "order_option`.`product_option_value_id`
                        FROM `" . DB_PREFIX . "order_option`, `" . DB_PREFIX . "product_option`, `" . DB_PREFIX . "option`
                        WHERE `" . DB_PREFIX . "order_option`.`order_product_id` = '" . (int) $order_product['order_product_id'] . "'
                        AND `" . DB_PREFIX . "order_option`.`order_id` = '" . (int) $order_id . "'
                        AND `" . DB_PREFIX . "order_option`.`product_option_id` = `" . DB_PREFIX . "product_option`.`product_option_id`
                        AND `" . DB_PREFIX . "product_option`.`option_id` = `" . DB_PREFIX . "option`.`option_id`
                        AND ((`" . DB_PREFIX . "option`.`type` = 'radio') OR (`" . DB_PREFIX . "option`.`type` = 'select'))
                        ORDER BY `" . DB_PREFIX . "order_option`.`order_option_id`
                        ASC");

                if ($pOption_query->num_rows != 0) {
                    $pOptions = array();
                    foreach ($pOption_query->rows as $pOptionRow) {
                        $pOptions[] = $pOptionRow['product_option_value_id'];
                    }

                    $var = implode(':', $pOptions);
                    $qtyLeftRow = $this->db->query("SELECT `stock` FROM `" . DB_PREFIX . "product_option_relation` WHERE `product_id` = '" . (int)$order_product['product_id'] . "' AND `var` = '" . $this->db->escape($var) . "'")->row;
                    if(empty($qtyLeftRow)) {
                        $qtyLeftRow['stock'] = 0;
                    }
                    $passArray[] = array('pid' => $order_product['product_id'], 'qty_left' => $qtyLeftRow['stock'], 'var' => $var);
                }
            } else {
                $passArray[] = array('pid' => $order_product['product_id'], 'qty_left' => $product_query->row['quantity'], 'var' => '');
            }
        }

        return $passArray;
    }
    
    /*
     * addonLoad (copy from ebay library)
     *
     * Loads a 3rd party module for OpenBay to use.
     * @param $addon
     * @return bool
     */
    public function addonLoad($addon){
        $addon = (string)$addon; //ensure the addon name is a string value.


        if(file_exists(DIR_SYSTEM."ebay_addon/".$addon.".php"))
        {
            if($addon == "openstock") {
                $isInstalled = $this->db->query("
                    SELECT COUNT(*) as count FROM `" . DB_PREFIX . "extension`
                    WHERE `code` = 'openstock'")->row;
                if($isInstalled['count'] == 0) {
                    return false;
                }
            }
            
            include_once(DIR_SYSTEM."ebay_addon/".$addon.".php");
            
            if(empty($this->addon) || !is_object($this->addon))
            {
                $this->addon = new stdClass();
            }
        
            $this->addon->$addon = new $addon;
            return true;
        }else{
            return false;
        }
    }

    public function validate(){
        if($this->config->get('amazonus_status') != 0 &&
            $this->config->get('openbay_amazonus_token') != '' &&
            $this->config->get('openbay_amazonus_enc_string1') != '' &&
            $this->config->get('openbay_amazonus_enc_string2') != ''){
            return true;
        }else{
            return false;
        }
    }    
    
    public function deleteProduct($product_id){
        $this->db->query("DELETE FROM `" . DB_PREFIX . "amazonus_product_link` WHERE `product_id` = '" . $this->db->escape($product_id) . "'");
    }

    public function deleteOrder($order_id){
        /**
         * @todo
         */
    }
    
    public function getOrder($orderId) {
        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "amazonus_order` WHERE `order_id` = '".(int)$orderId."' LIMIT 1");

        if($qry->num_rows > 0){
            return $qry->row;
        }else{
            return false;
        }
    }

    public function getCarriers(){
        return array(
            "USPS",
            "UPS",
            "FedEx",
            "DHL",
            "Fastway",
            "GLS",
            "GO!",
            "Hermes Logistik Gruppe",
            "Royal Mail",
            "Parcelforce",
            "City Link",
            "TNT",
            "Target",
            "SagawaExpress",
            "NipponExpress",
            "YamatoTransport",
            "DHL Global Mail",
            "UPS Mail Innovations",
            "FedEx SmartPost",
            "OSM",
            "OnTrac",
            "Streamlite",
            "Newgistics",
            "Canada Post",
            "Blue Package",
        );
    }
    
}
