<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleManufacturer extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/manufacturer');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['manufacturer_id'] =  (string)$this->request->get['manufacturer_id'];
		} else {
			$data['manufacturer_id'] = array();
		}

		

		$this->load->model('catalog/manufacturer');

		$this->load->model('catalog/product');

		$data['manufacturers'] = array();

		$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

		foreach ($manufacturers as $manufacturer) {
			
		

			

			$data['manufacturers'][] = array(
				'manufacturer_id' => $manufacturer['manufacturer_id'],
				'name'        => $manufacturer['name'] ,
				'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])
			);
		}

		return $this->load->view('extension/module/manufacturer', $data);
	}
}