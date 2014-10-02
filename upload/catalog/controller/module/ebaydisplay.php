<?php
class ControllerModuleEbaydisplay extends Controller {
	public function index($setting) {
		$this->language->load('module/ebaydisplay');
		$this->load->model('tool/image');
		$this->load->model('openbay/ebay_product');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['products'] = array();

		$products = $this->cache->get('ebaydisplay.' . md5(serialize($setting)));

		if(!$products){
			$products = $this->model_openbay_ebay_product->getDisplayProducts();
			$this->cache->set('ebaydisplay.' . md5(serialize($setting)), $products);
		}

		foreach ($products['products'] as $product) {
			if(isset($product['pictures'][0])){
				$image = $this->model_openbay_ebay_product->resize($product['pictures'][0], $setting['width'], $setting['height']);
			}else{
				$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
			}

			$data['products'][] = array(
				'thumb'   	 => $image,
				'name'    	 => base64_decode($product['Title']),
				'price'   	 => $this->currency->format($product['priceGross']),
				'href'    	 => (string)$product['link'],
			);
		}

		$data['tracking_pixel'] = $products['tracking_pixel'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ebaydisplay.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/ebaydisplay.tpl', $data);
		} else {
			return $this->load->view('default/template/module/ebaydisplay.tpl', $data);
		}
	}
}