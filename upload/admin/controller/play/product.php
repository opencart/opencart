<?php
class ControllerPlayProduct extends Controller {
    public function pricingReport(){
        if ($this->checkConfig() == true) {
            //load the language
            $this->data = array_merge($this->data, $this->load->language('play/reportprice'));

            //load the models
            $this->load->model('catalog/product');
            $this->load->model('tool/image');
            $this->load->model('catalog/manufacturer');
            $this->load->model('play/play');
            $this->load->model('play/product');

            //set the title and page info
            $this->document->setTitle($this->data['lang_page_title']);
            $this->document->addScript('view/javascript/openbay/faq.js');
            $this->document->addStyle('view/stylesheet/openbay.css');
            $this->template         = 'play/reportprice.tpl';
            $this->children         = array('common/header','common/footer');
            $this->data['cancel']   = HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token'];
            $this->data['token']    = $this->session->data['token'];
            $this->data['pricing']  = $this->model_play_product->getPricingReport();

            if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
            } else {
                $this->data['error_warning'] = '';
            }

            $this->data['product_id_types']     = $this->play->getProductIdType();

            $this->data['product_conditions']   = $this->play->getItemCondition();

            $this->data['product_dispatch_to']  = $this->play->getDispatchTo();

            if(isset($this->request->get['page'])){
                $page = $this->request->get['page'];
            }else{
                $page = 1;
            }

            $pagination = new Pagination();
            $pagination->total = $this->data['pricing']['total'];
            $pagination->page = $page;
            $pagination->limit = 20;
            //$pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('play/pricingReport', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

            $this->data['pagination'] = $pagination->render();

            $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
        } else {
            $this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
        }
    }

    public function newProduct(){
        if ($this->checkConfig() == true) {

            $this->load->model('play/product');

            //load the language
            $this->data = array_merge($this->data, $this->load->language('play/product'));

            if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                $this->model_play_product->add($this->request->post);

                $this->session->data['success'] = $this->language->get('lang_text_success');

                $this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
            }

            if (!empty($this->request->get['product_id'])) {

                //load the models
                $this->load->model('catalog/product');
                $this->load->model('tool/image');
                $this->load->model('catalog/manufacturer');
                $this->load->model('play/play');

                //set the title and page info
                $this->document->setTitle($this->data['lang_page_title']);
                $this->document->addScript('view/javascript/openbay/faq.js');
                $this->document->addStyle('view/stylesheet/openbay.css');
                $this->template         = 'play/listing.tpl';
                $this->children         = array('common/header','common/footer');
                $this->data['action']   = HTTPS_SERVER . 'index.php?route=play/product/newProduct&token=' . $this->session->data['token'];
                $this->data['cancel']   = HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token'];
                $this->data['token']    = $this->session->data['token'];
                $product_info           = $this->model_catalog_product->getProduct($this->request->get['product_id']);

                if (isset($this->error['warning'])) {
                    $this->data['error_warning'] = $this->error['warning'];
                } else {
                    $this->data['error_warning'] = '';
                }

                $this->data['product']              = $product_info;

                $this->data['actionCode']           = 'a';

                $this->data['product_id_types']     = $this->play->getProductIdType();

                $this->data['product_conditions']   = $this->play->getItemCondition();

                $this->data['product_dispatch_to']  = $this->play->getDispatchTo();

                $this->data['product_dispatch_fr']  = $this->play->getDispatchFrom();

                //check if product has isbn db column
                if($this->openbay->testDbColumn('product', 'isbn') != true){
                    $this->data['product']['isbn'] = '';
                }

                //check if product has ean db column
                $this->data['has_ean'] = false;
                if($this->openbay->testDbColumn('product', 'ean') != true){
                    $this->data['product']['ean'] = '';
                }

                //check if product has upc db column
                if($this->openbay->testDbColumn('product', 'upc') != true){
                    $this->data['product']['upc'] = '';
                }

                $this->data['defaults']['shipfrom'] = $this->config->get('obp_play_def_shipfrom');
                $this->data['defaults']['shipto'] = $this->config->get('obp_play_def_shipto');
                $this->data['defaults']['condition'] = $this->config->get('obp_play_def_itemcond');

                $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
            } else {
                $this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
            }
        } else {
            $this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
        }
    }

    public function editProduct(){
        if ($this->checkConfig() == true) {

            $this->load->model('play/product');

            $this->data = array_merge($this->data, $this->load->language('play/product'));

            if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                $this->model_play_product->edit($this->request->post);
                $this->session->data['success'] = $this->language->get('lang_text_success_updated');
                $this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
            }

            if (!empty($this->request->get['product_id'])) {

                $this->load->model('catalog/product');
                $this->load->model('tool/image');
                $this->load->model('catalog/manufacturer');
                $this->load->model('play/play');

                $this->document->setTitle($this->data['lang_page_title_edit']);
                $this->document->addScript('view/javascript/openbay/faq.js');
                $this->document->addStyle('view/stylesheet/openbay.css');
                $this->template         = 'play/listing.tpl';
                $this->children         = array('common/header','common/footer');
                $this->data['action']   = HTTPS_SERVER . 'index.php?route=play/product/editProduct&token=' . $this->session->data['token'];
                $this->data['delete']   = HTTPS_SERVER . 'index.php?route=play/product/deleteProduct&token=' . $this->session->data['token'] .'&product_id='.$this->request->get['product_id'];
                $this->data['cancel']   = HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token'];
                $this->data['token']    = $this->session->data['token'];

                $product_info           = $this->model_catalog_product->getProduct($this->request->get['product_id']);
                $listing_info           = $this->model_play_product->getListing($this->request->get['product_id']);
                $listing_info['errors'] = $this->model_play_product->getListingErrors($this->request->get['product_id']);

                if (isset($this->error['warning'])) {
                    $this->data['error_warning'] = $this->error['warning'];
                } else {
                    $this->data['error_warning'] = '';
                }

                $this->data['product']              = $product_info;
                $this->data['listing']              = $listing_info;
                $this->data['actionCode']           = '';
                $this->data['product_id_types']     = $this->play->getProductIdType();
                $this->data['product_conditions']   = $this->play->getItemCondition();
                $this->data['product_dispatch_to']  = $this->play->getDispatchTo();
                $this->data['product_dispatch_fr']  = $this->play->getDispatchFrom();

                //check if product has isbn db column
                if($this->openbay->testDbColumn('product', 'isbn') != true){
                    $this->data['product']['isbn'] = '';
                }

                //check if product has ean db column
                $this->data['has_ean'] = false;
                if($this->openbay->testDbColumn('product', 'ean') != true){
                    $this->data['product']['ean'] = '';
                }

                //check if product has upc db column
                if($this->openbay->testDbColumn('product', 'upc') != true){
                    $this->data['product']['upc'] = '';
                }

                $this->data['defaults']['shipfrom'] = $this->config->get('obp_play_def_shipfrom');
                $this->data['defaults']['shipto'] = $this->config->get('obp_play_def_shipto');
                $this->data['defaults']['condition'] = $this->config->get('obp_play_def_itemcond');

                $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
            } else {
                $this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
            }
        } else {
            $this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
        }
    }
    
    public function deleteProduct(){
        if ($this->checkConfig() == true) {
            $this->load->model('play/product');

            $this->data = array_merge($this->data, $this->load->language('play/product'));
            
            $this->model_play_product->delete($this->request->get['product_id']);

            $this->session->data['success'] = $this->language->get('lang_text_success_deleted');

            $this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
        }
    }

    private function checkConfig() {
        $this->token = $this->config->get('obp_play_token');
        $this->secret = $this->config->get('obp_play_secret');

        if ($this->token == '' || $this->secret == '') {
            return false;
        } else {
            return true;
        }
    }
}
