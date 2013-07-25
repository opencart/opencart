<?php
final class Play {
    
    private $registry;
    private $url    = 'https://playuk.openbaypro.com/';
    private $productIdType = array(
        1 => 'Play ID',
        2 => 'ISBN',
        3 => 'UPC/EAN'
    );
    private $dispatchTo = array(
        1 => 'UK Only',
        2 => 'Europe only (not inc UK)',
        3 => 'UK &amp; Europe'
    );
    private $itemCondition = array(
        11 => 'New',
        1 => 'Used; Like new',
        2 => 'Used; Very good',
        3 => 'Used; Good',
        4 => 'Used; Average',
        5 => 'Collectable; Like new',
        6 => 'Collectable; Very good',
        7 => 'Collectable; Good',
        8 => 'Collectable; Average',
        9 => 'Refurbished',
    );
    private $dispatchFrom = array(
        1 => 'UK',
        2 => 'Andorra',
        3 => 'Austria',
        4 => 'Belgium',
        5 => 'Cyprus',
        6 => 'Denmark',
        7 => 'Finland',
        8 => 'France',
        9 => 'Germany',
        10 => 'Gibraltar',
        11 => 'Greece',
        12 => 'Greenland',
        13 => 'Iceland',
        14 => 'Ireland',
        15 => 'Italy',
        16 => 'Liechtenstein',
        17 => 'Luxembourg',
        18 => 'Malta',
        19 => 'Monaco',
        20 => 'Netherlands',
        21 => 'Norway',
        22 => 'San Marino',
        23 => 'Spain',
        24 => 'Sweden',
        25 => 'Switzerland',
        26 => 'Vatican',
        27 => 'USA',
        28 => 'Canada',
    );
    private $addDelete = array(
        'a' => 'Add',
        'd' => 'Delete'
    );
    private $submitStatus = array(
        1 => 'Pending', //waiting
        2 => 'Processing', //sent
        3 => 'Complete / OK', //sent
        4 => 'Error', //sent
        5 => 'Pending update', //waiting
        6 => 'Complete / Warnings', //sent
        7 => 'Pending delete', //waiting
        8 => 'Stock updating', // waiting
    );
    private $orderStatus = array(
        1 => 'Sale Pending', //purchased not paid
        2 => 'Sold', //purchased and paid, waiting postage
        3 => 'Posted', //posted/shipped
        4 => 'Cancelled', //order cancelled
    );
    private $carriers = array(
        1 => 'Royal Mail',
        2 => 'Parcelforce',
        3 => 'TNT',
        4 => 'DHL',
        5 => 'FedEx'
    );
    private $refundReason = array(
        1 => 'Buyer cancelled',
        2 => 'Item did not arrive',
        3 => 'No inventory',
        4 => 'Customer return',
        5 => 'General adjustment',
        6 => 'Could not dispatch',
        'other' => 'Other reason'
    );

    public function __construct($registry) {
        $this->registry     	= $registry;
        $this->token	    	= $this->config->get('obp_play_token');
        $this->secret	    	= $this->config->get('obp_play_secret');
        $this->logging	    	= $this->config->get('obp_play_logging');
        $this->key              = $this->config->get('obp_play_key');
        $this->key2	            = $this->config->get('obp_play_key2');
        $this->encryptionToken 	= $this->pbkdf2($this->key, $this->key2, 1000, 32);
    }
    
    public function __get($name) {
        return $this->registry->get($name);
    }

    public function call($call, array $post = NULL, array $options = array(), $content_type = 'json', $statusOverride = false){
        $this->log('call() - Call: '.$call);
        $this->log('call() - $post: '.json_encode($post));
        $this->log('call() - $options: '.json_encode($options));
        $this->log('call() - $content_type: '.$content_type);
        $this->log('call() - $statusOverride: '.$statusOverride);





        if($this->config->get('play_status') == 1 || $statusOverride == true){

            if(defined("HTTPS_CATALOG")){
                $domain = HTTPS_CATALOG;
            }else{
                $domain = HTTPS_SERVER;
            }

            $data = array(
                'token'             => $this->token,
                'secret'            => $this->secret,
                'key'               => $this->key,
                'server'            => $this->server, 
                'domain'            => $domain,
                'language'          => $this->config->get('openbay_language'),
                'openbay_version'   => (int)$this->config->get('openbay_version'),
                'data'              => $this->encrypt($post, true),
                'content_type'      => $content_type
            );

            $useragent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";

            $defaults = array(
                CURLOPT_POST            => 1,
                CURLOPT_HEADER          => 0,
                CURLOPT_URL             => $this->url.$call,
                CURLOPT_USERAGENT       => $useragent, 
                CURLOPT_FRESH_CONNECT   => 1,
                CURLOPT_RETURNTRANSFER  => 1,
                CURLOPT_FORBID_REUSE    => 1,
                CURLOPT_TIMEOUT         => 0,
                CURLOPT_SSL_VERIFYPEER  => 0,
                CURLOPT_SSL_VERIFYHOST  => 0,
                CURLOPT_POSTFIELDS      => http_build_query($data, '', "&")
            );

            $ch = curl_init();
            curl_setopt_array($ch, ($options + $defaults));
            if( ! $result = curl_exec($ch)){
            }
            curl_close($ch);
            
            /* JSON RESPONSE */
            if($content_type == 'json'){
                $encoding = mb_detect_encoding($result);

                /* some json data may have BOM due to php not handling types correctly */
                if($encoding == 'UTF-8') {
                  $result = preg_replace('/[^(\x20-\x7F)]*/','', $result);    
                } 

                $result = json_decode($result, 1);

                if(!empty($result)){
                    return $result;
                }else{
                    return false;
                }
            }elseif($content_type == 'xml'){
                $result = simplexml_load_string($result);

                if(!empty($result)){
                    return $result;
                }else{
                    return false;
                }
            }
        }else{
        }
    }

    public function getCarriers(){
        $this->log('getCarriers()');
        return $this->carriers;
    }

    public function getRefundReason(){
        $this->log('getRefundReason()');
        return $this->refundReason;
    }

    public function getProductIdType(){
        $this->log('getProductIdType()');
        return $this->productIdType;
    }

    public function getItemCondition(){
        $this->log('getItemCondition()');
        return $this->itemCondition;
    }

    public function getDispatchTo(){
        $this->log('getDispatchTo()');
        return $this->dispatchTo;
    }

    public function getDispatchFrom(){
        $this->log('getDispatchFrom()');
        return $this->dispatchFrom;
    }

    public function getAddDelete(){
        $this->log('getAddDelete()');
        return $this->addDelete;
    }

    public function getSubmitStatus(){
        $this->log('getSubmitStatus()');
        return $this->submitStatus;
    }

    public function getOrderStatus(){
        $this->log('getOrderStatus()');
        return $this->orderStatus;
    }

    public function getLinkedProducts($changed = false){
        $this->log('getLinkedProducts() - changed: '.$changed);
        $data = array();

        $sql_status = '';
        if($changed == true){
            $sql_status = ' WHERE `pi`.`status` = 1 OR `pi`.`status` = 5 OR `pi`.`status` = 8';
        }

        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "play_product_insert` `pi` LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `pi`.`product_id`)".$sql_status);

        if($qry->num_rows > 0){
            foreach($qry->rows as $p){

                if($p['quantity'] == 0){
                    $this->db->query("UPDATE `" . DB_PREFIX . "play_product_insert` SET `status` = 7 WHERE `product_id` = '".$p['product_id']."' LIMIT 1");
                }

                $data[] = $p;
            }

            return $data;
        }else{
            return 0;
        }
    }

    public function getRemovedProducts(){
        $this->log('getRemovedProducts()');
        $data = array();

        $qry = $this->db->query("
            SELECT * 
            FROM `" . DB_PREFIX . "play_product_insert` `pi` 
            LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `pi`.`product_id`)  WHERE `pi`.`status` = 7"
        );

        if($qry->num_rows > 0){
            $this->log('getRemovedProducts() - found: '.$qry->num_rows);
            foreach($qry->rows as $p){
                $data[] = $p;
            }

            return $data;
        }else{
            return 0;
        }
    }

    public function encrypt($msg,$base64 = false){

        $k = $this->encryptionToken;

        if ( ! $td = mcrypt_module_open('rijndael-256', '', 'ctr', '') )
            return false;

        $msg = serialize($msg);
        $iv  = mcrypt_create_iv(32, MCRYPT_RAND);

        if ( mcrypt_generic_init($td, $k, $iv) !== 0 )
            return false;

        $msg  = mcrypt_generic($td, $msg);
        $msg  = $iv . $msg;
        $mac  = $this->pbkdf2($msg, $k, 1000, 32);
        $msg .= $mac;

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        if ( $base64 ) $msg = base64_encode($msg);

        return $msg;
    }

    public function decrypt($msg,$base64 = false){

        $k = $this->encryptionToken;

        if ( $base64 ){
            $msg = base64_decode($msg);
        }

        if ( ! $td = mcrypt_module_open('rijndael-256', '', 'ctr', '') ){
            $this->log->write('Failed to open cipher');
            return false;
        }

        $iv  = substr($msg, 0, 32);
        $mo  = strlen($msg) - 32;
        $em  = substr($msg, $mo);
        $msg = substr($msg, 32, strlen($msg)-64);
        $mac = $this->pbkdf2($iv . $msg, $k, 1000, 32);

        if ( $em !== $mac ){
            $this->log->write('Mac authenticate failed');
            return false;
        }

        if ( mcrypt_generic_init($td, $k, $iv) !== 0 ){
            $this->log->write('Buffer init failed');
            return false;
        }

        $msg = mdecrypt_generic($td, $msg);
        $msg = unserialize($msg);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $msg;
    }

    private function pbkdf2( $p, $s, $c, $kl, $a = 'sha256' ) {
        $hl = strlen(hash($a, null, true));
        $kb = ceil($kl / $hl);
        $dk = '';

        for ( $block = 1; $block <= $kb; $block ++ ) {

            $ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);

            for ( $i = 1; $i < $c; $i ++ )

                $ib ^= ($b = hash_hmac($a, $b, $p, true));

            $dk .= $ib;
        }

        return substr($dk, 0, $kl);
    }

    public function orderNew($order_id){
        $this->log('orderNew() - $order_id: '.$order_id);
        if($this->isPlayOrder($order_id) == false){
            $order_qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

            foreach ($order_qry->rows as $product){
                $this->log('orderNew() - product_id: '.(int)$product['product_id']);
                $this->stockModified((int)$product['product_id']);
            }
        }
    }

    public function productUpdateListen($product_id, $data){
        $this->log('productUpdateListen() - $product_id: '.$product_id);
        $this->stockModified($product_id);
    }

    public function putStockUpdateBulk($productIdArray, $endInactive){
        $this->log('putStockUpdateBulk()');

        foreach($productIdArray as $product_id){
            $this->stockModified($product_id);
        }
    }

    public function isPlayOrder($order_id){
        $this->log('isPlayOrder() - $order_id: '.$order_id);

        $order = $this->getPlayOrder($order_id);

        if($order != false){
            $this->log('isPlayOrder() - return true');
            return true;
        }else{
            $this->log('isPlayOrder() - return false');
            return false;
        }
    }

    public function getPlayOrder($order_id){
        $this->log('getPlayOrder() - $order_id: '.$order_id);

        if($this->openbay->testDbTable(DB_PREFIX . "play_order") == true){
            $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "play_order` WHERE `order_id` = '".(int)$order_id."' LIMIT 1");

            if($qry->num_rows > 0){
                $this->log('getPlayOrder() - return true');
                return $qry->row;
            }else{
                $this->log('getPlayOrder() - return false');
                return false;
            }
        }else{
            $this->log('getPlayOrder() - return false');
            return false;
        }
    }
    
    public function getOrderId($play_order_id){
        $this->log('getOrderId() - $play_order_id:'.$play_order_id);

        if($this->openbay->testDbTable(DB_PREFIX . "play_order") == true){
            $qry = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "play_order` WHERE `play_order_id` = '".(int)$play_order_id."' LIMIT 1");

            if($qry->num_rows > 0){
                $this->log('getOrderId() - returning order_id:'.$qry->row['order_id']);
                return $qry->row['order_id'];
            }else{
                $this->log('getOrderId() - return false');
                return false;
            }
        }else{
            return false;
        }
    }

    public function isPlayProduct($product_id){
        $this->log('isPlayProduct() - product_id: '.$product_id);

        if($this->openbay->testDbTable(DB_PREFIX . "play_product_insert") == true){
            $qry = $this->db->query("SELECT `pi`.`status` AS `status`, `p`.`quantity` AS `quantity`  FROM `" . DB_PREFIX . "play_product_insert` `pi` LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `pi`.`product_id`) WHERE `pi`.`product_id` = '".(int)$product_id."' LIMIT 1");

            if($qry->num_rows > 0){
                $this->log('isPlayProduct() - return true');
                return $qry->row;
            }else{
                $this->log('isPlayProduct() - return false');
                return false;
            }
        }else{
            
        }
    }

    public function stockModified($product_id){
        $product = $this->isPlayProduct($product_id);

        $this->log('stockModified() - product_id: '.$product_id.', status: '.$product['status']);

        if(isset($product['status']) && ($product['status'] == 2 || $product['status'] == 3 || $product['status'] == 6 || $product['status'] == 4)){
            if($product['quantity'] <= 0){
                $this->log('stockModified() - set to status 7 (remove - no stock)');
                $this->db->query("UPDATE `" . DB_PREFIX . "play_product_insert` SET `status` = '7', `action` = 'd' WHERE `product_id` = '".(int)$product_id."' LIMIT 1");
            }else{
                $this->log('stockModified() - set to status 8 (still has stock)');
                $this->db->query("UPDATE `" . DB_PREFIX . "play_product_insert` SET `status` = '8' WHERE `product_id` = '".(int)$product_id."' LIMIT 1");
            }
        }
    }

    public function orderStatusModified($order_id, $old_status_id){
        $this->log('orderStatusModified() - $order_id: '.$order_id.', old status: '.$old_status_id);

        if($this->isPlayOrder($order_id)){
            $this->log('orderStatusModified() - modified');
            $this->db->query("UPDATE `" . DB_PREFIX . "play_order` SET `old_status` = '".(int)$old_status_id."', `modified` = '1' WHERE `order_id` = '".(int)$order_id."' LIMIT 1");
        }
    }
    
    public function getOldOrderStatus($order_id){
        $qry = $this->db->query("SELECT `order_status_id` FROM `" . DB_PREFIX . "order` WHERE `order_id` = '".(int)$order_id."' LIMIT 1");

        if($qry->num_rows > 0){
            $this->log('getOldOrderStatus() - $order_id: '.$order_id.', returning: '.$qry->row['order_status_id']);
            return $qry->row['order_status_id'];
        }else{
            $this->log('getOldOrderStatus() - no record found');
            return false;
        }
    }
    
    public function isModified($order_id){
        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "play_order` WHERE `order_id` = '".(int)$order_id."' AND `modified` = 1 LIMIT 1");

        if($qry->num_rows > 0){
            $this->log('isModified() - $order_id: '.$order_id.', return true');
            return true;
        }else{
            $this->log('isModified() - $order_id: '.$order_id.', return false');
            return false;
        }
    }

    public function deleteProduct($product_id){
        $this->log('deleteProduct() - $product_id: '.$product_id);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "play_product_insert` WHERE `product_id` = '".(int)$product_id."' LIMIT 1");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "play_product_insert_error` WHERE `product_id` = '".(int)$product_id."'");
    }

    public function deleteOrder($order_id){
        /**
         * @todo
         */
    }

    public function log($data){
        $logger = new Log('play.log');
        $logger->write($data);
    }
}
?>