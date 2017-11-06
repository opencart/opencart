<?php
class ControllerExtensionModuleEbayListing extends Controller {
	public function index() {
		if ($this->config->get('module_ebay_listing_status') == 1) {
			$this->load->language('extension/module/ebay_listing');

			$this->load->model('tool/image');
			$this->load->model('extension/openbay/ebay_product');

            $request_data                   = array();
            $request_data['search_keyword'] = $this->config->get('module_ebay_listing_keywords');
            $request_data['seller_id']      = $this->config->get('module_ebay_listing_username');
            $request_data['limit']          = $this->config->get('module_ebay_listing_limit');
            $request_data['sort']           = $this->config->get('module_ebay_listing_sort');
            $request_data['search_desc']    = $this->config->get('module_ebay_listing_description');

            $cache_key = implode(".", $request_data);

			$data['products'] = array();

			$product_data = $this->cache->get('module_ebay_listing.' . $cache_key);

			if (!$product_data) {
				$product_data = $this->openbay->ebay->call('item/searchListingsForDisplay', $request_data);

                $this->cache->set('module_ebay_listing.' . $cache_key, $product_data);
			}

            if (!is_dir(DIR_IMAGE . 'catalog/module_ebay_listing/')) {
                @mkdir(DIR_IMAGE . 'catalog/module_ebay_listing/', 0777);
            }

            $placeholder_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('module_ebay_listing_width'), $this->config->get('module_ebay_listing_height'));

			foreach($product_data['products'] as $product) {
				if (isset($product['pictures'][0])) {
				    // download the image from ebay
                    if (!file_exists(DIR_IMAGE . 'catalog/module_ebay_listing/' . md5($product['pictures'][0]) . '.jpg')) {
                        @copy($product['pictures'][0], DIR_IMAGE . 'catalog/module_ebay_listing/' . md5($product['pictures'][0]) . '.jpg');
                    }

                    if (file_exists(DIR_IMAGE . 'catalog/module_ebay_listing/' . md5($product['pictures'][0]) . '.jpg')) {
					    $image = $this->model_tool_image->resize('catalog/module_ebay_listing/' . md5($product['pictures'][0]) . '.jpg', $this->config->get('module_ebay_listing_width'), $this->config->get('module_ebay_listing_height'));
                    } else {
                        $image = $placeholder_image;
                    }
                }

				$data['products'][] = array(
					'thumb' => $image,
					'name'  => base64_decode($product['Title']),
					'price' => $this->currency->format($product['priceGross'], $this->session->data['currency']),
					'href'  => (string)$product['link']
				);
			}

			$data['tracking_pixel'] = $product_data['tracking_pixel'];

			return $this->load->view('extension/module/ebay_listing', $data);
		}
	}
}
