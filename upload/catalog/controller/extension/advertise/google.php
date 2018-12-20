<?php

use \googleshopping\traits\StoreLoader;
use \googleshopping\traits\LibraryLoader;

class ControllerExtensionAdvertiseGoogle extends Controller {
    use StoreLoader;
    use LibraryLoader;

    private $store_id = 0;

    public function __construct($registry) {
        parent::__construct($registry);

        if (getenv("ADVERTISE_GOOGLE_STORE_ID")) {
            $this->store_id = (int)getenv("ADVERTISE_GOOGLE_STORE_ID");
        } else {
            $this->store_id = (int)$this->config->get('config_store_id');
        }

        $this->loadStore($this->store_id);
    }

    public function google_global_site_tag(&$route, &$data, &$output) {
        // In case the extension is disabled, do nothing
        if (!$this->setting->get('advertise_google_status')) {
            return;
        }

        // If there is no tracker, do nothing
        if (!$this->setting->has('advertise_google_conversion_tracker')) {
            return;
        }

        $tracker = $this->setting->get('advertise_google_conversion_tracker');

        // Insert the tags before the closing <head> tag
        $output = str_replace('</head>', $tracker['google_global_site_tag'] . '</head>', $output);
    }

    public function before_checkout_success(&$route, &$data) {
        // In case the extension is disabled, do nothing
        if (!$this->setting->get('advertise_google_status')) {
            return;
        }

        // If there is no tracker, do nothing
        if (!$this->setting->has('advertise_google_conversion_tracker')) {
            return;
        }

        // In case there is no order, do nothing
        if (!isset($this->session->data['order_id'])) {
            return;
        }

        $currency_converter = new Cart\Currency($this->registry);

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $tracker = $this->setting->get('advertise_google_conversion_tracker');
        $currency = $order_info['currency_code'];
        $converted_total = $currency_converter->convert((float)$order_info['total'], $this->config->get('config_currency'), $currency);
        $total = number_format($converted_total, 2, '.', '');

        $search = array(
            '{VALUE}',
            '{CURRENCY}'
        );

        $replace = array(
            $total,
            $currency
        );

        $snippet = str_replace($search, $replace, $tracker['google_event_snippet']);

        // Store the snippet to display it in the order success view
        if (!$this->registry->has('googleshopping')) {
            $this->loadLibrary($this->store_id);
        }

        $this->googleshopping->setEventSnippet($snippet);
        $this->googleshopping->setPurchaseTotal($total);
    }

    public function google_dynamic_remarketing_purchase(&$route, &$data, &$output) {
        // In case the extension is disabled, do nothing
        if (!$this->setting->get('advertise_google_status')) {
            return;
        }

        // If the library has not been loaded, or if there is no snippet, do nothing
        if (!$this->registry->has('googleshopping') || $this->googleshopping->getEventSnippet() === null || $this->googleshopping->getPurchaseTotal() === null) {
            return;
        }

        $data['ecomm_totalvalue'] = $this->googleshopping->getPurchaseTotal();

        $purchase_snippet = $this->load->view('extension/advertise/google_dynamic_remarketing_purchase', $data);

        // Insert the snippet after the output
        $output = str_replace('</body>', $purchase_snippet . $this->googleshopping->getEventSnippet() . '</body>', $output);
    }

    public function google_dynamic_remarketing_home(&$route, &$data, &$output) {
        // In case the extension is disabled, do nothing
        if (!$this->setting->get('advertise_google_status')) {
            return;
        }

        // If we are not on the home page, do nothing
        if (isset($this->request->get['route']) && $this->request->get['route'] != $this->config->get('action_default')) {
            return;
        }

        $snippet = $this->load->view('extension/advertise/google_dynamic_remarketing_home', array());

        // Insert the snippet after the output
        $output = str_replace('</body>', $snippet . '</body>', $output);
    }

    public function google_dynamic_remarketing_searchresults(&$route, &$data, &$output) {
        // In case the extension is disabled, do nothing
        if (!$this->setting->get('advertise_google_status')) {
            return;
        }

        // If we are not on the search page, do nothing
        if (!isset($this->request->get['route']) || $this->request->get['route'] != 'product/search') {
            return;
        }

        $snippet = $this->load->view('extension/advertise/google_dynamic_remarketing_searchresults', array());

        // Insert the snippet after the output
        $output = str_replace('</body>', $snippet . '</body>', $output);
    }

    public function google_dynamic_remarketing_category(&$route, &$data, &$output) {
        // In case the extension is disabled, do nothing
        if (!$this->setting->get('advertise_google_status')) {
            return;
        }

        // If we are not on the search page, do nothing
        if (!isset($this->request->get['route']) || $this->request->get['route'] != 'product/category') {
            return;
        }

        if (isset($this->request->get['path'])) {
            $parts = explode('_', $this->request->get['path']);
            $category_id = (int)end($parts);
        } else if (isset($this->request->get['category_id'])) {
            $category_id = (int)$this->request->get['category_id'];
        } else {
            $category_id = 0;
        }

        $this->load->model('extension/advertise/google');

        $data['ecomm_category'] = str_replace('"', '\\"', $this->model_extension_advertise_google->getHumanReadableOpenCartCategory($category_id));

        $snippet = $this->load->view('extension/advertise/google_dynamic_remarketing_category', $data);

        // Insert the snippet after the output
        $output = str_replace('</body>', $snippet . '</body>', $output);
    }

    public function google_dynamic_remarketing_product(&$route, &$data, &$output) {
        // In case the extension is disabled, do nothing
        if (!$this->setting->get('advertise_google_status')) {
            return;
        }

        // If we do not know the viewed product, do nothing
        if (!isset($this->request->get['product_id']) || !isset($this->request->get['route']) || $this->request->get['route'] != 'product/product') {
            return;
        }

        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct((int)$this->request->get['product_id']);

        // If product does not exist, do nothing
        if (!$product_info) {
            return;
        }

        $this->loadLibrary($this->store_id);

        $this->load->model('extension/advertise/google');

        $category_name = $this->model_extension_advertise_google->getHumanReadableCategory($product_info['product_id'], $this->store_id);

        $price = (float)$product_info['price'];
        $special = (float)$product_info['special'];

        if (!is_null($product_info['special']) && $special < $price) {
            $price = $special;
        }

        if ($this->config->get('config_tax')) {
            $price = $this->tax->calculate($price, $product_info['tax_class_id']);
        }

        $option_map = $this->model_extension_advertise_google->getSizeAndColorOptionMap($product_info['product_id'], $this->store_id);

        $data = array();
        $data['option_map'] = json_encode($option_map);
        $data['ecomm_totalvalue'] = (float)$price;
        $data['ecomm_category'] = str_replace('"', '\\"', $category_name);

        $snippet = $this->load->view('extension/advertise/google_dynamic_remarketing_product', $data);

        // Insert the snippet after the output
        $output = str_replace('</body>', $snippet . '</body>', $output);
    }

    public function google_dynamic_remarketing_cart(&$route, &$data, &$output) {
        // In case the extension is disabled, do nothing
        if (!$this->setting->get('advertise_google_status')) {
            return;
        }

        // If we are not on the cart page, do nothing
        if (!isset($this->request->get['route']) || $this->request->get['route'] != 'checkout/cart') {
            return;
        }

        $this->loadLibrary($this->store_id);

        $this->load->model('catalog/product');
        $this->load->model('extension/advertise/google');

        $data['ecomm_totalvalue'] = $this->cart->getTotal();

        $ecomm_prodid = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_map = $this->model_extension_advertise_google->getSizeAndColorOptionMap($product['product_id'], $this->store_id);
            $found_color = "";
            $found_size = "";

            foreach ($product['option'] as $option) {
                if (is_array($option_map['colors'])) {
                    foreach ($option_map['colors'] as $product_option_value_id => $color) {
                        if ($option['product_option_value_id'] == $product_option_value_id) {
                            $found_color = $color;
                        }
                    }
                }

                if (is_array($option_map['sizes'])) {
                    foreach ($option_map['sizes'] as $product_option_value_id => $size) {
                        if ($option['product_option_value_id'] == $product_option_value_id) {
                            $found_size = $size;
                        }
                    }
                }
            }

            foreach ($option_map['groups'] as $id => $group) {
                if ($group['color'] === $found_color && $group['size'] === $found_size) {
                    $ecomm_prodid[] = $id;
                }
            }
        }

        $data['ecomm_prodid'] = json_encode($ecomm_prodid);

        $snippet = $this->load->view('extension/advertise/google_dynamic_remarketing_cart', $data);

        // Insert the snippet after the output
        $output = str_replace('</body>', $snippet . '</body>', $output);
    }

    public function cron($cron_id = null, $code = null, $cycle = null, $date_added = null, $date_modified = null) {
        $this->loadLibrary($this->store_id);

        if (!$this->validateCRON()) {
            // In case this is not a CRON task
            return;
        }

        $this->load->language('extension/advertise/google');

        $this->googleshopping->cron();
    }

    protected function validateCRON() {
        if (!$this->setting->get('advertise_google_status')) {
            // In case the extension is disabled, do nothing
            return false;
        }

        if (!$this->setting->get('advertise_google_gmc_account_selected')) {
            return false;
        }

        if (!$this->setting->get('advertise_google_gmc_shipping_taxes_configured')) {
            return false;
        }

        try {
            if (!$this->googleshopping->isConnected()) {
                return false;
            }

            if (count($this->googleshopping->getTargets($this->store_id)) === 0) {
                return false;
            }
        } catch (\RuntimeException $e) {
            return false;
        }

        if (isset($this->request->get['cron_token']) && $this->request->get['cron_token'] == $this->config->get('advertise_google_cron_token')) {
            return true;
        }

        if (defined('ADVERTISE_GOOGLE_ROUTE')) {
            return true;
        }

        return false;
    }
}