<?php

final class Openbay {

    private $registry;

    public function __construct($registry) {
        $this->registry = $registry;
    }

    public function __get($name) {
        return $this->registry->get($name);
    }

    public function orderNew($orderId) {
        /**
         * Once and order has been imported from external marketplace and
         * and order_id has been created, this method should be called.
         * 
         * $src is the variable controlling where the order has been created.
         */
        
        // eBay Module
        if ($this->config->get('openbay_status') == 1) {
            $this->ebay->orderNew($orderId);
        }

        // Amazon EU Module
        if ($this->config->get('amazon_status') == 1) {
            $this->amazon->orderNew($orderId);
        }
        
        // Amazon US Module
        if ($this->config->get('amazonus_status') == 1) {
            $this->amazonus->orderNew($orderId);
        }

        // Play.com Module
        if ($this->config->get('play_status') == 1) {
            $this->play->orderNew($orderId);
        }

        /**
         * If a 3rd party module needs to be notified about a new order
         * so it can update the stock then they should add a method to their
         * application here with the order id so they can get the info about it.
         * i.e. $this->mylibraryfile->newOrderMethod($orderId);
         */
    }

    public function productUpdateListen($productId, $data) {
        /**
         * This call is performed after the product has been updated.
         * The $data variable holds all of the information that has
         * been sent through the $_POST.
         */
        
        // eBay Module
        if ($this->config->get('openbay_status') == 1) {
            $this->ebay->productUpdateListen($productId, $data);
        }

        // Amazon Module
        if ($this->config->get('amazon_status') == 1) {
            $this->amazon->productUpdateListen($productId, $data);
        }
        
        // Amazon US Module
        if ($this->config->get('amazonus_status') == 1) {
            $this->amazonus->productUpdateListen($productId, $data);
        }

        // Play.com Module
        if ($this->config->get('play_status') == 1) {
            $this->play->productUpdateListen($productId, $data);
        }
    }

    public function putStockUpdateBulk($productIdArray, $productSkuArray = null, $endInactive = false){
        /**
         * putStockUpdateBulk
         *
         * Takes an array of product id's where stock has been modified
         *
         * @param $productIdArray
         */

        // eBay Module
        if ($this->config->get('openbay_status') == 1) {
            $this->ebay->putStockUpdateBulk($productIdArray, $productSkuArray, $endInactive);
        }

        // Amazon EU Module
        if ($this->config->get('amazon_status') == 1) {
            $this->amazon->putStockUpdateBulk($productIdArray, $endInactive);
        }
        
        // Amazon US Module
        if ($this->config->get('amazonus_status') == 1) {
            $this->amazonus->putStockUpdateBulk($productIdArray, $endInactive);
        }

        // Play.com Module
        if ($this->config->get('play_status') == 1) {
            $this->play->putStockUpdateBulk($productIdArray, $endInactive);
        }
    }

    public function testDbColumn($table, $column){
        //check profile table for default column
        $res = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX.$table."` LIKE '".$column."'");
        if($res->num_rows != 0){
            return true;
        }else{
            return false;
        }
    }

    public function testDbTable($table){
        //check profile table for default column
        $res = $this->db->query("SELECT `table_name` AS `c` FROM `information_schema`.`tables` WHERE `table_schema` = DATABASE()");
        
        $tables = array();
		
        foreach($res->rows as $row){
           $tables[] = $row['c'];
        }
        
        if(in_array($table, $tables)){
            return true;
        }else{
            return false;
        }
    }

    public function splitName($name) {
        $name = explode(' ', $name);
        $fname = $name[0];
        unset($name[0]);
        $lname = implode(' ', $name);

        return array(
            'firstname' => $fname,
            'surname'   => $lname
        );
    }

    public function getTaxRates($tax_class_id) {
        $tax_rates = array();

        $tax_query = $this->db->query("SELECT
                    tr2.tax_rate_id,
                    tr2.name,
                    tr2.rate,
                    tr2.type,
                    tr1.priority
                FROM " . DB_PREFIX . "tax_rule tr1
                LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id)
                INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id)
                LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id)
                LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id)
                WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "'
                AND tr1.based = 'shipping'
                AND tr2cg.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
                AND z2gz.country_id = '" . (int)$this->config->get('config_country_id') . "'
                AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$this->config->get('config_zone_id') . "')
                ORDER BY tr1.priority ASC");

        foreach ($tax_query->rows as $result) {
            $tax_rates[$result['tax_rate_id']] = array(
                'tax_rate_id' => $result['tax_rate_id'],
                'name'        => $result['name'],
                'rate'        => $result['rate'],
                'type'        => $result['type'],
                'priority'    => $result['priority']
            );
        }

        return $tax_rates;
    }

    public function getTaxRate($class_id){
        $rates = $this->getTaxRates($class_id);
        $percentage = 0.00;
        foreach($rates as $rate){
            if($rate['type'] == 'P'){
                $percentage += $rate['rate'];
            }
        }

        return $percentage;
    }

    public function getZoneId($name, $country_id){
        $query = $this->db->query("SELECT `zone_id` FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "' AND status = '1' AND `name` = '".$this->db->escape($name)."'");

        if($query->num_rows > 0){
            return $query->row['zone_id'];
        }else{
            return 0;
        }
    }

    public function newOrderAdminNotify($order_id, $order_status_id){

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($order_id);

        $language = new Language($order_info['language_directory']);
        $language->load($order_info['language_filename']);
        $language->load('mail/order');

        $order_status = $this->db->query("SELECT `name` FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1")->row['name'];

        // Order Totals
        $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY `sort_order` ASC");

        //Order contents
        $order_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

        $subject = sprintf($language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);

        // Text
        $text  = $language->get('text_new_received') . "\n\n";
        $text .= $language->get('text_new_order_id') . ' ' . $order_info['order_id'] . "\n";
        $text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
        $text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
        $text .= $language->get('text_new_products') . "\n";

        foreach ($order_product_query->rows as $product) {
            $text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

            $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

            foreach ($order_option_query->rows as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                }

                $text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
            }
        }

        if(isset($order_voucher_query) && is_array($order_voucher_query)){
            foreach ($order_voucher_query->rows as $voucher) {
                $text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
            }
        }

        $text .= "\n";
        $text .= $language->get('text_new_order_total') . "\n";

        foreach ($order_total_query->rows as $total) {
            $text .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
        }

        $text .= "\n";

        if ($order_info['comment']) {
            $text .= $language->get('text_new_comment') . "\n\n";
            $text .= $order_info['comment'] . "\n\n";
        }

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($this->config->get('config_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($order_info['store_name']);
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
        $mail->send();

        // Send to additional alert emails
        $emails = explode(',', $this->config->get('config_alert_emails'));

        foreach ($emails as $email) {
            if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                $mail->setTo($email);
                $mail->send();
            }
        }
    }

    public function deleteProduct($product_id){
        // eBay Module
        if ($this->config->get('openbay_status') == 1) {
            $this->ebay->deleteProduct($product_id);
        }

        // Amazon Module
        if ($this->config->get('amazon_status') == 1) {
            $this->amazon->deleteProduct($product_id);
        }
        
        // Amazon US Module
        if ($this->config->get('amazonus_status') == 1) {
            $this->amazonus->deleteProduct($product_id);
        }

        // Play.com Module
        if ($this->config->get('play_status') == 1) {
            $this->play->deleteProduct($product_id);
        }
    }

    public function deleteOrder($order_id){
        /**
         * Called when an order is deleted - usually by the admin. Helpful to loop over the products to add the stock back to the markets.
         */
        // eBay Module
        if ($this->config->get('openbay_status') == 1) {
            $this->ebay->deleteOrder($order_id);
        }

        // Amazon Module
        if ($this->config->get('amazon_status') == 1) {
            $this->amazon->deleteOrder($order_id);
        }

        // Amazon US Module
        if ($this->config->get('amazonus_status') == 1) {
            $this->amazonus->deleteOrder($order_id);
        }

        // Play.com Module
        if ($this->config->get('play_status') == 1) {
            $this->play->deleteOrder($order_id);
        }
    }

    public function getProductModelNumber($product_id, $sku = null){
        if($sku != null){
            $qry = $this->db->query("SELECT `sku` FROM `" . DB_PREFIX . "product_option_relation` WHERE `product_id` = '".(int)$product_id."' AND `var` = '".$this->db->escape($sku)."'");

            if($qry->num_rows > 0){
                return $qry->row['sku'];
            }else{
                return false;
            }

        }else{
            $qry = $this->db->query("SELECT `model` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '".(int)$product_id."' LIMIT 1");

            if($qry->num_rows > 0){
                return $qry->row['model'];
            }else{
                return false;
            }
        }
    }
}
