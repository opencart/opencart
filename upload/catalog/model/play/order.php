<?php
class ModelPlayOrder extends Model{
    public function createOrder($customer_id, $order_info){
        //total of order
        $total = 0;

        if($order_info['sale-value-gbp'] != ''){
            $total = $order_info['sale-value-gbp'];
            $currency = $this->getCurrency('GBP');
        }

        if($order_info['sale-value-euro'] != ''){
            $total = $order_info['sale-value-euro'];
            $currency = $this->getCurrency('EUR');
        }

        if($order_info['exchange-rate'] != ''){
            $exchange_rate = $order_info['exchange-rate'];
        }else{
            $exchange_rate = 1;
        }
        
        //get default customer group
        /* @todo */
        $customer_group = $this->config->get('obp_play_def_customer_grp');
        
        //customer formatted name
        $name_parts     = $this->openbay->splitName((string)$order_info['recipient-name']);
        $fname          = $name_parts['firstname'];
        $lname          = $name_parts['surname'];
        
        $this->db->query("
                INSERT INTO `" . DB_PREFIX . "order` 
                SET 
                   invoice_prefix           = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "',
                   store_id                 = '" . (int)$this->config->get('config_store_id') . "', 
                   store_name               = '" . $this->db->escape($this->config->get('config_name') . ' / Play.com') . "',
                   store_url                = '" . $this->db->escape($this->config->get('config_url')) . "',  
                   customer_id              = '" . (int)$customer_id . "', 
                   customer_group_id        = '" . (int)$customer_group . "', 
                   firstname                = '" . $this->db->escape($fname) . "', 
                   lastname                 = '" . $this->db->escape($lname) . "', 
                   email                    = '" . $this->db->escape($order_info['buyer-email']) . "', 
                   payment_firstname        = '" . $this->db->escape($fname) . "', 
                   payment_lastname         = '" . $this->db->escape($lname) . "',
                   payment_address_1        = '" . $this->db->escape($order_info['ship-address-1']) . "',
                   payment_address_2        = '" . $this->db->escape($order_info['ship-address-2']) . "',
                   payment_city             = '" . $this->db->escape($order_info['ship-city']) . "',
                   payment_postcode         = '" . $this->db->escape($order_info['ship-postcode']) . "',
                   payment_country          = '" . $this->db->escape($order_info['ship-country']) . "',
                   payment_country_id       = '',
                   payment_zone             = '',
                   payment_zone_id          = '',
                   payment_address_format   = '',
                   payment_method           = 'Play Funds',
                   payment_code             = '',
                   shipping_firstname       = '" . $this->db->escape($fname) . "',
                   shipping_lastname        = '" . $this->db->escape($lname) . "',
                   shipping_address_1       = '" . $this->db->escape($order_info['ship-address-1']) . "',
                   shipping_address_2       = '" . $this->db->escape($order_info['ship-address-2']) . "',
                   shipping_city            = '" . $this->db->escape($order_info['ship-city']) . "',
                   shipping_postcode        = '" . $this->db->escape($order_info['ship-postcode']) . "',
                   shipping_country         = '" . $this->db->escape($order_info['ship-country']) . "',
                   shipping_country_id      = '',
                   shipping_zone            = '',
                   shipping_zone_id         = '',
                   shipping_address_format  = '',
                   shipping_method          = '',
                   shipping_code            = '',
                   `total`                  = '" . (double)$total . "',
                   affiliate_id             = '0', 
                   commission               = '0', 
                   language_id              = '".(int)$this->config->get('config_language_id')."', 
                   currency_id              = '" . (int)$currency['currency_id']."', 
                   currency_code            = '" . $this->db->escape($currency['code']) . "', 
                   currency_value           = '" . (double)$exchange_rate . "',
                   date_added               = NOW(),
                   date_modified            = NOW()");

        $this->play->log('createOrder() - Order ID created: '.$this->db->getLastId());
        
        return $this->db->getLastId();
    }
    
    public function getCurrency($code){
        $qry = $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "currency` 
            WHERE `code` = '".$this->db->escape($code)."' LIMIT 1");
        
        if($qry->num_rows > 0){
            $this->play->log('getCurrency() - '.$code. ' currency found');
            return $qry->row;
        }else{
            $this->play->log('getCurrency() - '.$code. ' currency not found in DB - please add it');
            return 0;
        }
    }

    public function update($order_id, $order_status_id, $comment = '') {
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $notify = $this->config->get('obp_play_order_update_notify');

        if ($order_info && ($this->play->isModified($order_id) == false)) {

            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
            
            $old_status_id = $this->play->getOldOrderStatus($order_id);
            
            $this->db->query("UPDATE `" . DB_PREFIX . "play_order` SET `old_status` = '".$old_status_id."' WHERE `order_id` = '".(int)$order_id."'");
            
            if ($notify) {
                $language = new Language($order_info['language_directory']);
                $language->load($order_info['language_filename']);
                $language->load('mail/order');

                $subject = sprintf($language->get('text_update_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

                $message  = $language->get('text_update_order') . ' ' . $order_id . "\n";
                $message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

                $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

                if ($order_status_query->num_rows) {
                    $message .= $language->get('text_update_order_status') . "\n\n";
                    $message .= $order_status_query->row['name'] . "\n\n";
                }

                if ($order_info['customer_id']) {
                    $message .= $language->get('text_update_link') . "\n";
                    $message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
                }

                if ($comment) {
                    $message .= $language->get('text_update_comment') . "\n\n";
                    $message .= $comment . "\n\n";
                }

                $message .= $language->get('text_update_footer');

                $message .= "\n\n";
                $message .= 'OpenBay Pro - eBay, Amazon and Play.com order management - http://www.openbaypro.com/';

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');
                $mail->setTo($order_info['email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($order_info['store_name']);
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                $mail->send();
            }
        }
    }

    public function addProduct($order_id, $product_id, $product_name, $model, $quantity, $price, $total, $tax){

        $this->play->log('addProduct() - Price: '.$price);
        $this->play->log('addProduct() - Total: '.$total);

        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_product`
        SET
            `order_id`          = '".(int)$order_id."',
            `product_id`        = '".(int)$product_id."',
            `quantity`          = '".(int)$quantity."',
            `name`              = '".$this->db->escape($product_name)."',
            `model`             = '".$this->db->escape($model)."',
            `price`             = '".$price."',
            `total`             = '".$price."',
            `tax`               = '".(double)$tax."'");
        
        if($product_id >0){
            $this->updateStock($product_id, $quantity, '-');
        }
    }
    
    public function updateStock($product_id, $quantity, $modifier){
        $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity ".$modifier." " . (int)$quantity . ") WHERE product_id = '" . (int)$product_id . "' AND subtract = '1'");

        $this->play->stockModified($product_id);
    }
    
    public function restockProducts($order_id){
        
        $order_products = $this->getOrderProducts($order_id);
        
        foreach($order_products as $product){
            if($product['product_id'] > 0){
                $this->updateStock($product['product_id'], $product['quantity'], '+');
            }
        }
    }
    
    public function getOrderProducts($order_id){
        $qry = $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "order_product` 
            WHERE `order_id` = '".(int)$order_id."'");
        
        $products = array();
        
        if($qry->num_rows > 0){
            foreach($qry->rows as $row){
                $products[] = $row;
            }
        }
        
        return $products;
    }

    public function addPlayOrder($order_id, $play_order_id, $gbp, $eur){
        $this->db->query("
            INSERT INTO `" . DB_PREFIX . "play_order` 
            SET 
                `order_id`          = '".(int)$order_id."', 
                `play_order_id`     = '".(int)$play_order_id."',
                `paid_gbp`          = '".(double)$gbp."',
                `paid_euro`         = '".(double)$eur."'
        ");
    }

    public function confirm($order_id, $order_status_id, $comment = '') {
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $notify         = $this->config->get('obp_play_order_new_notify');
        $notify_admin   = $this->config->get('obp_play_order_new_notify_admin');

        if ($order_info && $order_info['order_status_id'] == 0) {

            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET `order_status_id` = '" . (int)$order_status_id . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");

            $this->db->query("INSERT INTO `" . DB_PREFIX . "order_history` SET `order_id` = '" . (int)$order_id . "', `order_status_id` = '" . (int)$order_status_id . "', `notify` = '".(int)$notify."', `comment` = '" . $this->db->escape($comment) . "', `date_added` = NOW()");
            
            $order_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

            $this->cache->delete('product');

            // Order Totals
            $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY `sort_order` ASC");

            foreach ($order_total_query->rows as $order_total) {
                $this->load->model('total/' . $order_total['code']);

                if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
                    $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
                }
            }

            // Send out order confirmation mail
            $language = new Language($order_info['language_directory']);
            $language->load($order_info['language_filename']);
            $language->load('mail/order');

            $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

            if ($order_status_query->num_rows) {
                $order_status = $order_status_query->row['name'];
            } else {
                $order_status = '';
            }

            $subject = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);

            // HTML Mail
            $template = new Template();

            $template->data['title'] = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

            $template->data['text_greeting'] = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
            $template->data['text_link'] = $language->get('text_new_link');
            $template->data['text_download'] = $language->get('text_new_download');
            $template->data['text_order_detail'] = $language->get('text_new_order_detail');
            $template->data['text_instruction'] = $language->get('text_new_instruction');
            $template->data['text_order_id'] = $language->get('text_new_order_id');
            $template->data['text_date_added'] = $language->get('text_new_date_added');
            $template->data['text_payment_method'] = $language->get('text_new_payment_method');
            $template->data['text_shipping_method'] = $language->get('text_new_shipping_method');
            $template->data['text_email'] = $language->get('text_new_email');
            $template->data['text_telephone'] = $language->get('text_new_telephone');
            $template->data['text_ip'] = $language->get('text_new_ip');
            $template->data['text_payment_address'] = $language->get('text_new_payment_address');
            $template->data['text_shipping_address'] = $language->get('text_new_shipping_address');
            $template->data['text_product'] = $language->get('text_new_product');
            $template->data['text_model'] = $language->get('text_new_model');
            $template->data['text_quantity'] = $language->get('text_new_quantity');
            $template->data['text_price'] = $language->get('text_new_price');
            $template->data['text_total'] = $language->get('text_new_total');
            $template->data['text_footer'] = $language->get('text_new_footer');
            $template->data['text_powered'] = '<a href="http://www.openbaypro.com/">OpenBay Pro - eBay, Amazon and Play.com order management for OpenCart</a>.';

            $template->data['logo'] = HTTPS_SERVER.'image/' . $this->config->get('config_logo');
            $template->data['store_name'] = $order_info['store_name'];
            $template->data['store_url'] = $order_info['store_url'];
            $template->data['customer_id'] = $order_info['customer_id'];
            $template->data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;

            $template->data['download'] = '';

            $template->data['order_id'] = $order_id;
            $template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
            $template->data['payment_method'] = $order_info['payment_method'];
            $template->data['shipping_method'] = $order_info['shipping_method'];
            $template->data['email'] = $order_info['email'];
            $template->data['telephone'] = $order_info['telephone'];
            $template->data['ip'] = $order_info['ip'];

            if ($comment && $notify) {
                $template->data['comment'] = nl2br($comment);
            } else {
                $template->data['comment'] = '';
            }

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'lastname'  => $order_info['payment_lastname'],
                'company'   => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city'      => $order_info['payment_city'],
                'postcode'  => $order_info['payment_postcode'],
                'zone'      => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country'   => $order_info['payment_country']
            );

            $template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname'  => $order_info['shipping_lastname'],
                'company'   => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city'      => $order_info['shipping_city'],
                'postcode'  => $order_info['shipping_postcode'],
                'zone'      => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country'   => $order_info['shipping_country']
            );

            $template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            // Products
            $template->data['products'] = array();

            foreach ($order_product_query->rows as $product) {
                $option_data = array();

                $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

                foreach ($order_option_query->rows as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                    }

                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                $template->data['products'][] = array(
                    'name'     => $product['name'],
                    'model'    => $product['model'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            $template->data['vouchers'] = array();

            $template->data['totals'] = $order_total_query->rows;

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
                $html = $template->fetch($this->config->get('config_template') . '/template/mail/order.tpl');
            } else {
                $html = $template->fetch('default/template/mail/order.tpl');
            }

            // Text Mail
            $text  = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
            $text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
            $text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
            $text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";

            if ($comment && $notify) {
                $text .= $language->get('text_new_instruction') . "\n\n";
                $text .= $comment . "\n\n";
            }

            // Products
            $text .= $language->get('text_new_products') . "\n";

            foreach ($order_product_query->rows as $product) {
                $text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

                $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

                foreach ($order_option_query->rows as $option) {
                    $text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($option['value']) > 20 ? utf8_substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
                }
            }

            $text .= "\n";

            $text .= $language->get('text_new_order_total') . "\n";

            foreach ($order_total_query->rows as $total) {
                $text .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
            }

            $text .= "\n";

            if ($order_info['customer_id']) {
                $text .= $language->get('text_new_link') . "\n";
                $text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
            }

            if ($order_info['comment']) {
                $text .= $language->get('text_new_comment') . "\n\n";
                $text .= $order_info['comment'] . "\n\n";
            }

            $text .= $language->get('text_new_footer') . "\n\n";

            if($notify == 1){
                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');
                $mail->setTo($order_info['email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($order_info['store_name']);
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setHtml($html);
                $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
                $mail->send();
            }

            // Admin Alert Mail
            if($notify_admin == 1) {
                $this->openbay->newOrderAdminNotify($order_id, $order_status_id);
            }
        }
    }
    
    public function getModifiedOrders(){
        $qry = $this->db->query("
            SELECT * 
            FROM 
                `" . DB_PREFIX . "play_order` 
            WHERE
                `modified`  = '1'
        ");
        
        if($qry->num_rows){
            $orders = array();
            
            foreach($qry->rows as $row){
                $orders[] = $row;
            }
            
            return $orders;
        }else{
            return 0;
        }
    }
}