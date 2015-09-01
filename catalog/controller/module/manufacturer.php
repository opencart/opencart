<?php
class ControllerModuleManufacturer extends Controller {
	public function index() {
		$this->load->language('module/manufacturer');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_select'] = $this->language->get('text_select');

         if (isset($this->request->get['manufacturer_id'])) {
            $data['manufacturer_id'] = $this->request->get['manufacturer_id'];
        } else {
            $data['manufacturer_id'] = 0;
        }

		$this->load->model('catalog/manufacturer');

		$data['manufacturers'] = array();

        $results = $this->model_catalog_manufacturer->getManufacturers();

        foreach ($results as $result) {

			$data['manufacturers'][] = array(
                'manufacturer_id' => $result['manufacturer_id'],
                'name'       	  => $result['name'],
				'href'            => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
            );
        }

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturer.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/manufacturer.tpl', $data);
		} else {
			return $this->load->view('default/template/module/manufacturer.tpl', $data);
		}
	}
}