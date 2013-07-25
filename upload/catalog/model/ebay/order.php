<?php
class ModelEbayOrder extends Model{
    public function addOrderLine($data, $order_id, $created){
        $orderLine      = $this->getOrderLine($data['txn_id'], $data['item_id']);

        $createdHours   = (int)$this->config->get('openbaypro_created_hours');
        if($createdHours == 0 || $createdHours == ''){ $createdHours = 24; } //This is a fallback value.
        $from           = date("Y-m-d H:i:00", mktime(date("H")-$createdHours, date("i"), date("s"), date("m"), date("d"), date("y")));

        if($orderLine === false){
            if($created >= $from){
                $this->ebay->log('addOrderLine() - New line');
                $product_id = $this->ebay->getProductId($data['item_id']);
                /* add to the transaction table */
                $this->db->query("
                    INSERT INTO `" . DB_PREFIX . "ebay_transaction`
                    SET
                    `order_id`                  = '".(int)$order_id."',
                    `txn_id`                    = '".$this->db->escape($data['txn_id'])."',
                    `item_id`                   = '".$this->db->escape($data['item_id'])."',
                    `product_id`                = '".(int)$product_id."',
                    `containing_order_id`       = '".$data['containing_order_id']."',
                    `order_line_id`             = '".$this->db->escape($data['order_line_id'])."',
                    `qty`                       = '".(int)$data['qty']."',
                    `smp_id`                    = '".(int)$data['smp_id']."',
                    `sku`                       = '".$this->db->escape($data['sku'])."',
                    `created`                   = now(),
                    `modified`                  = now()
                ");

                if(!empty($product_id)){
                    $this->ebay->log('Link found');
                    $this->modifyStock($product_id, $data['qty'], '-', $data['sku']);
                }
            }else{
                $this->ebay->log('addOrderLine() - Transaction is older than '.$this->config->get('openbaypro_created_hours').' hours');
            }
        }else{
            $this->ebay->log('addOrderLine() - Line existed');

            if($order_id != $orderLine['order_id']){
                $this->ebay->log('addOrderLine() - Order ID has changed from "'.$orderLine['order_id'].'" to "'.$order_id.'"');
                $this->db->query("UPDATE `" . DB_PREFIX . "ebay_transaction` SET `order_id` = '".(int)$order_id."', `modified` = now() WHERE `txn_id` = '".$this->db->escape((string)$data['txn_id'])."' AND `item_id` = '".$this->db->escape((string)$data['item_id'])."' LIMIT 1");

                //if the order id has changed then remove the old order details
                $this->delete($orderLine['order_id']);
            }

            if($orderLine['smp_id'] != $data['smp_id']){
                $this->ebay->log('addOrderLine() - SMP ID for orderLine has changed from "'.$orderLine['smp_id'].'" to "'.$data['smp_id'].'"');
                $this->db->query("UPDATE `" . DB_PREFIX . "ebay_transaction` SET `smp_id` = '".$data['smp_id']."', `modified` = now() WHERE `txn_id` = '".$this->db->escape((string)$data['txn_id'])."' AND `item_id` = '".$this->db->escape((string)$data['item_id'])."' LIMIT 1");
            }

            if($orderLine['containing_order_id'] != $data['containing_order_id']){
                $this->ebay->log('addOrderLine() - Containing order ID for orderLine has changed from "'.$orderLine['containing_order_id'].'" to "'.$data['containing_order_id'].'"');
                $this->db->query("UPDATE `" . DB_PREFIX . "ebay_transaction` SET `containing_order_id` = '".$data['containing_order_id']."', `modified` = now() WHERE `txn_id` = '".$this->db->escape((string)$data['txn_id'])."' AND `item_id` = '".$this->db->escape((string)$data['item_id'])."' LIMIT 1");
            }
        }
        $this->ebay->log('addOrderLine() - Done');
    }

    public function addOrderLines($order, $order_id){
        $this->ebay->log('Adding order lines');

        foreach($order->txn as $txn){
            $this->model_ebay_order->addOrderLine(array(
                'txn_id'                => (string)$txn->item->txn,
                'item_id'               => (string)$txn->item->id,
                'containing_order_id'   => (string)$order->order->id,
                'order_line_id'         => (string)$txn->item->line,
                'qty'                   => (int)$txn->item->qty,
                'smp_id'                => (string)$order->smp->id,
                'sku'                   => (string)$txn->item->variantsku
            ), (int)$order_id, $order->order->created);
        }
    }

    public function getOrderLine($txn_id, $item_id){
        $this->ebay->log('getOrderLine() - Testing for order line txn: '.$txn_id.', item: '.$item_id);
        $res = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_transaction` WHERE `txn_id` = '".$this->db->escape($txn_id)."' AND `item_id` = '".$this->db->escape($item_id)."' LIMIT 1");

        if($res->num_rows == 0){
            return false;
        }else{
            return $res->row;
        }
    }

    public function getOrderLines($order_id){
        $this->ebay->log('getOrderLines() - Testing for order lines id: '.$order_id);

        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_transaction` WHERE `order_id` = '".(int)$order_id."'");

        $lines = array();

        foreach ($result->rows as $line){
            $lines[] = $line;
        }

        return $lines;
    }

    public function removeOrderLines($canceling){

        foreach($canceling as $cancel){
            $line = $this->getOrderLine($cancel['txn'], $cancel['id']);

            if($line === false){
                $this->ebay->log('No line needs cancelling');
            }else{
                $this->ebay->log('Found order line to cancel');
                $this->removeOrderLine($cancel['txn'], $cancel['id'], $line);
            }
        }
    }

    private function removeOrderLine($txn_id, $item_id, $line){
        $this->ebay->log('Removing order line, txn: '.$txn_id.',item id: '.$item_id);

        $this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_transaction` WHERE `txn_id` = '".$this->db->escape($txn_id)."' AND `item_id` = '".$this->db->escape($item_id)."' LIMIT 1");

        if($this->db->countAffected() > 0){
            $this->modifyStock($line['product_id'], $line['qty'], '+', $line['sku']);
        }
    }

    public function cancel($order_id){
        $orderLines = $this->getOrderLines($order_id);

        foreach($orderLines as $line) {
            $this->modifyStock($line['product_id'], $line['qty'], '+', $line['sku']);
        }
    }

    public function updatePaymentDetails($order_id, $order){
        $this->db->query("
            UPDATE `" . DB_PREFIX . "order`
            SET
               `payment_method` = '" . $this->db->escape($order->payment->method) . "',
               `total` = '" . (double)$order->order->total . "',
               `date_modified` = NOW()
            WHERE `order_id` = '".(int)$order_id."'
        ");
    }

    public function find($smp_id){
        $this->ebay->log('find() - Finding SMP: '.$smp_id);

        $order_id = $this->orderLinkGet($smp_id);

        /**
         * This is a depreciated method of getting order Id's and will be removed in the future.
         */
        if($order_id == 0){
            $query = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "order_history` WHERE `comment` = '[eBay Import:" . $smp_id . "]' LIMIT 1");

            if($query->num_rows > 0){
                $this->ebay->log('find() (depreciated) - Found: '.$query->row['order_id']);
                return (int)$query->row['order_id'];
            }else{
                $this->ebay->log('find() (depreciated) - Nothing found');
                return false;
            }
        }else{
            $this->ebay->log('find() - Found: '.$order_id);
            return $order_id;
        }
    }

    public function getHistory($order_id){
        $this->ebay->log('Getting order history for ID: '.$order_id);

        $query = $this->db->query("SELECT `order_status_id` FROM `" . DB_PREFIX . "order_history` WHERE `order_id` = '".(int)$order_id."'");

        $status = array();

        if($query->num_rows){
            foreach($query->rows as $row){
                $status[] = $row['order_status_id'];
            }
        }

        return $status;
    }

    public function hasUser($order_id){
        $query = $this->db->query("SELECT `customer_id` FROM `" . DB_PREFIX . "order` WHERE `order_id` = '".(int)$order_id."'");

        if($query->num_rows == 0 || (isset($query->row['customer_id']) && $query->row['customer_id'] == 0)){
            return false;
        }else{
            return true;
        }
    }

    public function update($order_id, $order_status_id, $comment = '') {
        $order_info = $this->model_checkout_order->getOrder($order_id);

        $notify = $this->config->get('openbaypro_update_notify');

        if ($order_info) {

            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

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
                $message .= 'eBay, Amazon and Play.com order management - http://www.openbaypro.com/';

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

    public function confirm($order_id, $order_status_id, $comment = '') {

        $order_info     = $this->model_checkout_order->getOrder($order_id);
        $notify         = $this->config->get('openbaypro_confirm_notify');

        if ($order_info && !$order_info['order_status_id']) {
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '".(int)$notify."', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

            $order_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

            $this->cache->delete('product');

            // Downloads
            $order_download_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_download` WHERE `order_id` = '" . (int)$order_id . "'");

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

            $order_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$order_info['language_id'] . "'");

            $order_status = '';
            if ($order_status_query->num_rows) {
                $order_status = $order_status_query->row['name'];
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

            if($this->config->get('openbaypro_email_brand_disable') == 1){
                $template->data['text_powered'] = '';
            }else{
                $template->data['text_powered'] = '<a href="http://www.openbaypro.com/">OpenBay Pro - eBay, Amazon and Play.com order management for OpenCart</a>.';
            }

            $template->data['logo'] = HTTPS_SERVER .'image/' . $this->config->get('config_logo');
            $template->data['store_name'] = $order_info['store_name'];
            $template->data['store_url'] = $order_info['store_url'];
            $template->data['customer_id'] = $order_info['customer_id'];
            $template->data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;

            if ($order_download_query->num_rows) {
                $template->data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
            } else {
                $template->data['download'] = '';
            }

            $template->data['order_id'] = $order_id;
            $template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
            $template->data['payment_method'] = $order_info['payment_method'];
            $template->data['shipping_method'] = $order_info['shipping_method'];
            $template->data['email'] = $order_info['email'];
            $template->data['telephone'] = $order_info['telephone'];
            $template->data['ip'] = $order_info['ip'];

            $template->data['comment'] = '';
            if ($comment && $notify) {
                $template->data['comment'] = nl2br($comment);
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
            $template->data['products']         = array();

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

            if ($order_download_query->num_rows) {
                $text .= $language->get('text_new_download') . "\n";
                $text .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
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
        }
    }

    private function modifyStock($product_id, $qty, $symbol = '-', $sku = ''){
        $this->ebay->log('modifyStock() - Updating stock. Product id: '.$product_id.' qty: '.$qty.', symbol: '.$symbol.' sku: '.$sku);

        $item_id = $this->ebay->getEbayItemId($product_id);

        if($this->ebay->addonLoad('openstock') == true && !empty($sku)){
            $this->db->query("UPDATE `" . DB_PREFIX . "product_option_relation` SET `stock` = (`stock` ".(string)$symbol." " . (int)$qty . ") WHERE `var` = '" . (string)$sku . "' AND `product_id` = '".(int)$product_id."' AND `subtract` = '1'");

            $stock = $this->ebay->getProductStockLevel($product_id, $sku);

            $this->ebay->log('modifyStock() /variant  - Stock is now set to: '.$stock['quantity']);

            $this->ebay->putStockUpdate($item_id, $stock['quantity'], $sku);
        }else{
            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` ".(string)$symbol." " . (int)$qty . ") WHERE `product_id` = '" . (int)$product_id . "' AND `subtract` = '1'");

            $stock = $this->ebay->getProductStockLevel($product_id);

            $this->ebay->log('modifyStock() - Stock is now set to: '.$stock['quantity']);

            //send back stock update to eBay incase of a reserve product level
            $this->ebay->putStockUpdate($item_id, $stock['quantity']);
        }
    }

    public function getCountryAddressFormat($iso2) {
        $this->ebay->log('getCountryAddressFormat() - Getting country from ISO2: '.$iso2);

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '".$this->db->escape($iso2)."' LIMIT 1");

        if(!isset($query->row['address_format']) || $query->row['address_format'] == ''){
            $this->ebay->log('getCountryAddressFormat() - No country found, default');
            return false;
        }else{
            $this->ebay->log('getCountryAddressFormat() - found country: '.$query->row['address_format']);
            return $query->row['address_format'];
        }
    }

    public function orderLinkCreate($order_id, $smp_id){
        $this->ebay->log('orderLinkCreate() - order_id: '.$order_id.', smp_id: '.$smp_id);

        $this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_order` SET `order_id` = '" . (int)$order_id . "', `smp_id` = '".(int)$smp_id."', `parent_ebay_order_id` = 0");

        return $this->db->getLastId();
    }

    public function orderLinkGet($smp_id){
        $query = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "ebay_order` WHERE `smp_id` = '".(int)$smp_id."' LIMIT 1");

        if($query->num_rows > 0){
            return (int)$query->row['order_id'];
        }else{
            return 0;
        }
    }

    public function delete($order_id){
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE `order_id` = '".(int)$order_id."' LIMIT 1");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '".(int)$order_id."'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE `order_id` = '".(int)$order_id."'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE `order_id` = '".(int)$order_id."'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '".(int)$order_id."'");
    }

    public function lockAdd($smp_id){
        $this->ebay->log('lockAdd() - Added lock, smp_id: '.$smp_id);
        $this->db->query("INSERT INTO`" . DB_PREFIX . "ebay_order_lock` SET `smp_id` = '".(int)$smp_id."'");
    }

    public function lockDelete($smp_id){
        $this->ebay->log('lockDelete() - Delete lock, smp_id: '.$smp_id);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_order_lock` WHERE `smp_id` = '".(int)$smp_id."'");
    }

    public function lockExists($smp_id){
        $this->ebay->log('lockExists() - Check lock, smp_id: '.(int)$smp_id);
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_order_lock` WHERE `smp_id` = '".(int)$smp_id."' LIMIT 1");

        if($query->num_rows > 0){
            $this->ebay->log('lockExists() - Lock found, stopping order.');
            return true;
        }else{
            $this->lockAdd($smp_id);
            return false;
        }
    }
}