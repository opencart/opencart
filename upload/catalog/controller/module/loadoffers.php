<?php
class ControllerModuleLoadoffers extends Controller {
    public function index() {
        $this->load->language('module/loadoffers'); // loads the language file of loadoffers
         
        $data['heading_title'] = $this->language->get('heading_title'); // set the heading_title of the module
         
        $data['loadoffers_value'] = html_entity_decode($this->config->get('loadoffers_text_field')); // gets the saved value of loadoffers text field and parses it to a variable to use this inside our module view
        $group = '';
        if($this->config->get('loadoffers_text_field') == 'yes'){
            $group = ' GROUP BY pd.product_id ';
        }
        $data['loadoffers_limit'] = $this->config->get('loadoffers_limit_field');
        $limit = $this->config->get('loadoffers_limit_field');
        if(!empty($limit)) {
            $limit = ' LIMIT '.$limit;
            $data['loadoffers_limit'] = 6;
        }
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $pidQuery = $this->db->query("SELECT *,(SELECT image FROM " . DB_PREFIX . "product p WHERE p.product_id = pd.product_id) as image, (SELECT price FROM " . DB_PREFIX . "product p WHERE p.product_id = pd.product_id) as realprice FROM " . DB_PREFIX . "product_discount pd WHERE pd.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd.quantity > 1 AND ((pd.date_start = '0000-00-00' OR pd.date_start < NOW()) AND (pd.date_end = '0000-00-00' OR pd.date_end > NOW())) ".$group." ORDER BY rand() ".$limit."");
        
        $results = $pidQuery->rows;


        $data['productsonoffer'] = $results; //$this->model_catalog_product->getProductsDiscounts();
        

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/loadoffers.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/loadoffers.tpl', $data);
        } else {
            return $this->load->view('default/template/module/loadoffers.tpl', $data);
        }
    }
}