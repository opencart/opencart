<?php
class ControllerAmazonusProduct extends Controller{
    
    public function index() {
        $this->load->language('catalog/product');
        $this->load->language('openbay/amazonus');
        
        $this->load->model('amazonus/amazonus');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        
        $this->load->library('amazonus');

        $this->data = array_merge($this->data, $this->load->language('amazonus/listing'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/openbay.js');
        $this->document->setTitle($this->language->get('lang_title'));

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_price_to'])) {
            $url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }	

        if (isset($this->request->get['filter_quantity_to'])) {
            $url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_sku'])) {
            $url .= '&filter_sku=' . $this->request->get['filter_sku'];
        }

        if (isset($this->request->get['filter_desc'])) {
            $url .= '&filter_desc=' . $this->request->get['filter_desc'];
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }

        if (isset($this->request->get['filter_manufacturer'])) {
            $url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );
        
        $this->data['breadcrumbs'][] = array(
            'text' => 'Products',
            'href' => $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );
        
        if(isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
        } else {
            die('No product id');
        }
        
        if(isset($this->request->get['var'])) {
            $variation = $this->request->get['var'];
        } else {
            $variation = '';
        }
        $this->data['variation'] = $variation;
        $this->data['errors'] = array();
        /* 
         * Perform updates to database if form is posted
         */
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $dataArray = $this->request->post;
            
            $this->model_amazonus_amazonus->saveProduct($product_id, $dataArray);
            
            if($dataArray['upload_after'] === 'true') {
                $uploadResult = $this->uploadSaved();
                if($uploadResult['status'] == 'ok') {
                    $this->session->data['success'] = $this->language->get('uploaded_alert_text');
                    $this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                } else {
                    $this->data['errors'][] = Array('message' => $uploadResult['error_message']);
                }
            } else {
                $this->session->data['success'] = $this->language->get('saved_localy_text');
                $this->redirect($this->url->link('amazonus/product', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL'));
            }
        }
        
        if(isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }
        
        $savedListingData = $this->model_amazonus_amazonus->getProduct($product_id, $variation);
        if(empty($savedListingData)) {
            $listingSaved = false;
        } else {
            $listingSaved = true;
        }
        
        $errors = $this->model_amazonus_amazonus->getProductErrors($product_id);
        foreach($errors as $error) {
            $error['message'] =  'Error for SKU: "' . $error['sku'] . '" - ' . $this->formatUrlsInText($error['message']);
            $this->data['errors'][] = $error;
        }
        if(!empty($errors)) {
            $this->data['has_listing_errors'] = true;
        } else {
            $this->data['has_listing_errors'] = false;
        }
        
        $product_info = $this->model_catalog_product->getProduct($product_id);
        $this->data['listing_name'] = $product_info['name'] . " : " . $product_info['model'];
        $this->data['listing_sku'] = $product_info['sku'];
        $this->data['listing_url'] = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL');
        
        if($listingSaved) {
            $this->data['edit_product_category'] = $savedListingData['category'];
        } else {
            $this->data['edit_product_category'] = '';
        }
        
        /*
         * Load available categories
         */       
        $this->data['amazonus_categories'] = array();
        
        $amazonus_templates = $this->amazonus->getCategoryTemplates();
        
        foreach($amazonus_templates as $template) {
            $template = (array)$template;
            $categoryData = array(
                'friendly_name' => $template['friendly_name'],
                'name' => $template['name'],
                'template' => $template['xml']
            );
            $this->data['amazonus_categories'][] = $categoryData;
        }
        

         if($listingSaved) {
            $this->data['template_parser_url'] = $this->url->link('amazonus/product/parseTemplateAjax&edit_id=' . $product_id, 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $this->data['template_parser_url'] = $this->url->link('amazonus/product/parseTemplateAjax&product_id=' . $product_id, 'token=' . $this->session->data['token'], 'SSL');
        }
        
        $this->data['url_remove_errors'] = $this->url->link('amazonus/product/removeErrors', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL');
        $this->data['cancel_url'] = $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['saved_listings_url'] = $this->url->link('openbay/amazonus/savedListings', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['main_url'] = $this->url->link('amazonus/product', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['token'] = $this->session->data['token'];
        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
         
        if($this->amazonus->addonLoad('openstock') == true) {
            $this->load->model('openstock/openstock');
            $this->data['options'] = $this->model_openstock_openstock->getProductOptionStocks($product_id);
        } else {
            $this->data['options'] = array();
        }
        
        $this->template = 'amazonus/listing_advanced.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }
    
    public function removeErrors() {
        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_price_to'])) {
            $url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_quantity_to'])) {
            $url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_sku'])) {
            $url .= '&filter_sku=' . $this->request->get['filter_sku'];
        }

        if (isset($this->request->get['filter_desc'])) {
            $url .= '&filter_desc=' . $this->request->get['filter_desc'];
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }

        if (isset($this->request->get['filter_manufacturer'])) {
            $url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        if (isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
        } else {
            $this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        $this->load->model('amazonus/amazonus');
        $this->model_amazonus_amazonus->removeAdvancedErrors($product_id);
        $this->session->data['success'] = 'Errors removed';
        $this->redirect($this->url->link('amazonus/product', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL'));
    }
    
    public function uploadSavedAjax() {
        ob_start();
        $result = json_encode($this->uploadSaved());
        ob_clean();
        $this->response->setOutput($result);
    }
    
    private function uploadSaved() {
        $this->load->language('amazonus/listing');
        $this->load->library('amazonus');
        $this->load->model('amazonus/amazonus');
        $logger = new Log('amazonus_product.log');
        
        $logger->write('Uploading process started.');
        
        $savedProducts = $this->model_amazonus_amazonus->getSavedProductsData();
        
        if(empty($savedProducts)) {
            $logger->write('No saved listings found. Uploading canceled.');
            $result['status'] = 'error';
            $result['error_message'] = 'No saved listings. Nothing to upload. Aborting.';
            return $result;
        }
        
        foreach($savedProducts as $savedProduct) {
            $productDataDecoded = (array)json_decode($savedProduct['data']);
            
            $catalog = defined(HTTPS_CATALOG) ? HTTPS_CATALOG : HTTP_CATALOG;
            $response_data = array("response_url" => $catalog . 'index.php?route=amazonus/product/inbound');
            $category_data = array('category' => (string)$savedProduct['category']);
            $fields_data = array('fields' => (array)$productDataDecoded['fields']);
            
            $mpArray = array(); //Amazon US does not have marketplace selection
            $marketplaces_data = array('marketplaces' => $mpArray);
            
            $productData = array_merge($category_data, $fields_data, $response_data, $marketplaces_data);
            $insertion_response = $this->amazonus->insertProduct($productData);
            
            $logger->write("Uploading product with data:" . print_r($productData, true) . "
                Got response:" . print_r($insertion_response, true));
            
            if(!isset($insertion_response['status']) || $insertion_response['status'] == 'error') {
                $details = isset($insertion_response['info']) ? $insertion_response['info'] : 'Unknown';
                $result['error_message'] = sprintf($this->language->get('upload_failed'), $savedProduct['sku'], $details);
                $result['status'] = 'error';
                break;
            }
            $logger->write('Product upload success');
            $this->model_amazonus_amazonus->setProductUploaded($savedProduct['product_id'], $insertion_response['insertion_id'], $savedProduct['var']);
        }
        
        if(!isset($result['status'])) {
            $result['status'] = 'ok';
            $logger->write('Uploading process completed successfully.');
        } else {
            $logger->write('Uploading process failed with message: ' . $result['error_message']);
        }
        return $result;
    }
    
    public function parseTemplateAjax() {
        ob_start();
        $this->load->model('tool/image');
        $this->load->library('log');
        $logger = new Log('amazonus_product.log');
        
        $result = array();
        
        if(isset($this->request->get['xml'])) {
            $templateName = $this->request->get['xml'];
            
            $this->load->library('amazonus_category_template');
            $this->load->library('amazonus');
            
            
            $templateParser = new Amazonus_category_template();
            $data = array('template' => $templateName, 'version' => 2);
            $response = $this->amazonus->callWithResponse("productv2/GetTemplateXml", $data);
            
            if(!$templateParser->load($response)) {
                $logger->write("admin/amazonus/product/parseTemplateAjax failed to load template parses. name=" . $templateName);
                return;
            }
            $category = $templateParser->getCategoryName();
            $fields = $templateParser->getAllFields();
            $tabs = $templateParser->getTabs();
            
            
            $requestedVar = isset($this->request->get['var']) ? $this->request->get['var'] : '';
            
            if(isset($this->request->get['edit_id'])) {
                $fields = $this->fillSavedValues($this->request->get['edit_id'], $fields, $requestedVar);
            }
            elseif(isset($this->request->get['product_id'])) {
                $fields = $this->fillDefaultValues($this->request->get['product_id'], $fields, $requestedVar);         
            }
            
            /* Generating thumbs for image fields */
            $fieldsWithThumbs = array();
            foreach($fields as $field) {
                if($field['accepted']['type'] == 'image') {
                    $field['thumb'] = $this->model_tool_image->resize(str_replace(HTTPS_CATALOG . 'image/', '', $field['value']), 100, 100);
                    if(empty($field['thumb'])) {
                        $field['thumb'] = '';
                    }
                }
                $fieldsWithThumbs[] = $field;
            }
            
            $result = array(
                "category" => $category,
                "fields" => $fieldsWithThumbs,
                "tabs" => $tabs);
        }
        $result = json_encode($result);
        
        ob_clean();
        $this->response->setOutput($result);
    }
    
    private function fillDefaultValues($product_id, $fields_array, $var = '') {
        $this->load->model('catalog/product');
        $this->load->model('setting/setting');
        $this->load->model('amazonus/amazonus');
        
        $openbay_settings = $this->model_setting_setting->getSetting('openbay_amazonus');
        
        $product_info = $this->model_catalog_product->getProduct($product_id);
        $product_info['description'] = trim(utf8_encode(strip_tags(html_entity_decode($product_info['description']), "<br>")));
        $product_info['image'] = HTTPS_CATALOG . 'image/' . $product_info['image'];
        
        $tax_added = isset($openbay_settings['openbay_amazonus_listing_tax_added']) ? $openbay_settings['openbay_amazonus_listing_tax_added'] : 0;
        $default_condition =  isset($openbay_settings['openbay_amazonus_listing_default_condition']) ? $openbay_settings['openbay_amazonus_listing_default_condition'] : '';
        $product_info['price'] = number_format($product_info['price'] + $tax_added / 100 * $product_info['price'], 2, '.', '');
        
        /*Key must be lowecase */
        $defaults = array(
            'sku' => $product_info['sku'],
            'title' => $product_info['name'],
            'quantity' => $product_info['quantity'],
            'standardprice' => $product_info['price'],
            'description' => $product_info['description'],
            'mainimage' => $product_info['image'],
            'currency' => $this->config->get('config_currency'),
            'shippingweight' => number_format($product_info['weight'], 2, '.', ''),
            'conditiontype' => $default_condition,
        );
        
        $this->load->model('localisation/weight_class');
        $weightClass = $this->model_localisation_weight_class->getWeightClass($product_info['weight_class_id']);
        if(!empty($weightClass)) {
            $defaults['shippingweightunitofmeasure'] = $weightClass['unit'];
        }
        
        $this->load->model('catalog/manufacturer');
    	$manufacturer = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
        if(!empty($manufacturer)) {
            $defaults['manufacturer'] = $manufacturer['name'];
        }
        
        $productImages = $this->model_catalog_product->getProductImages($product_id);
        $imageIndex = 1;
        foreach($productImages as $productImage) {
            $defaults['pt' . $imageIndex] = HTTPS_CATALOG . 'image/' . $productImage['image'];
            $imageIndex ++;
        }

        if(!empty($product_info['upc'])) {
            $defaults['type'] = 'UPC';
            $defaults['value'] = $product_info['upc'];
        } else if(!empty($product_info['ean'])) {
            $defaults['type'] = 'EAN';
            $defaults['value'] = $product_info['ean'];
        }
        
        $this->load->library('amazonus');
        if($var !== '' && $this->amazonus->addonLoad('openstock')) {
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
                $defaults['sku'] = $option['sku'];
                $defaults['quantity'] = $option['stock'];
                $defaults['standardprice'] = number_format($option['price'] + $tax_added / 100 * $option['price'], 2, '.', '');
                $defaults['shippingweight'] = number_format($option['weight'], 2, '.', '');
                
                if(!empty($option['image'])) {
                    $defaults['mainimage'] = HTTPS_CATALOG . 'image/' . $option['image'];
                }
            }
        }
        
        if($defaults['shippingweight'] <= 0) {
            unset($defaults['shippingweight']);
            unset($defaults['shippingweightunitofmeasure']);
        }
        
        $filledArray = array();
        
        foreach($fields_array as $field) {
            
            $value_array = array('value' => '');
            
            if(isset($defaults[strtolower($field['name'])])) {
                $value_array = array('value' => $defaults[strtolower($field['name'])]);
            }
            
            $filledItem = array_merge($field, $value_array);
            
            $filledArray[] = $filledItem;
        }
        return $filledArray;
    }
    
    private function fillSavedValues($product_id, $fields_array, $var = '') {
        
        $this->load->model('amazonus/amazonus');
        $savedListing = $this->model_amazonus_amazonus->getProduct($product_id, $var);
        
        $decoded_data = (array)json_decode($savedListing['data']);
        $saved_fields = (array)$decoded_data['fields'];
        
        //Show current quantity instead of last uploaded
        $saved_fields['Quantity'] = $this->model_amazonus_amazonus->getProductQuantity($product_id, $var);
        
        $filledArray = array();
        
        foreach($fields_array as $field) {
            $value_array = array('value' => '');
            
            if(isset($saved_fields[$field['name']])) {
                $value_array = array('value' => $saved_fields[$field['name']]);
            }
            
            $filledItem = array_merge($field, $value_array);
            
            $filledArray[] = $filledItem;
        }

        return $filledArray;
    }
    
    //Only for developer via direct link
    public function resetPending() {
        $this->db->query("UPDATE `" . DB_PREFIX . "amazonus_product` SET `status` = 'saved' WHERE `status` = 'uploaded'");
    }
    
    //TODO if javascript validation is not enough
    private function validateForm() {
        return true;
    }
    
    private function formatUrlsInText($text){
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        preg_match_all($reg_exUrl, $text, $matches);
        $usedPatterns = array();
        foreach($matches[0] as $pattern){
            if(!array_key_exists($pattern, $usedPatterns)){
                $usedPatterns[$pattern]=true;
                $text = str_replace($pattern, "<a target='_blank' href=" .$pattern .">" . $pattern . "</a>", $text);   
            }
        }
        return $text;
    }
}