<?php  
class ControllerModuleManufacturer extends Controller {
	protected function index($module) {
		$this->language->load('module/manufacturer');	
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		
		$this->load->model('catalog/manufacturer');
		$this->load->model('tool/image');
		
		$this->data['limit'] = $this->config->get('manufacturer_' . $module . '_limit');
		$this->data['scroll'] = $this->config->get('manufacturer_' . $module . '_scroll');
		$this->data['axis'] = $this->config->get('manufacturer_' . $module . '_axis');
				
		$this->data['manufacturers'] = array();
		
		$results = $this->model_catalog_manufacturer->getManufacturers();
		
		foreach ($results as $result) {
			if ($result['image']) {
				$this->data['manufacturers'][] = array(
					'manufacturer_id' => $result['manufacturer_id'],
					'name'            => $result['name'],
					'href'            => $this->url->link('product/manufacturer/product', 'manufacturer_id=' . $result['manufacturer_id']),
					'image'           => $this->model_tool_image->resize($result['image'], $this->config->get('manufacturer_' . $module . '_image_width'), $this->config->get('manufacturer_' . $module . '_image_height'))
				);
			}
		}
		
		$this->data['module'] = $module; 
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/manufacturer.tpl';
		} else {
			$this->template = 'default/template/module/manufacturer.tpl';
		}
		
		$this->render(); 
	}
	
	public function css() {
		if (isset($this->request->get['module'])) {
			$module = (int)$this->request->get['module'];
		} else {
			$module = 0;
		}
		
		// Horizontal
		$output  = '#manufacturer' . $module . ' .jcarousel-container-horizontal {';
		$output .= '	width: ' . $this->config->get('manufacturer_' . $module . '_width') . 'px;';
		$output .= '	height: ' . $this->config->get('manufacturer_' . $module . '_height') . 'px;';
		$output .= '}' . "\n";	
		
		$output .= '#manufacturer' . $module . ' .jcarousel-clip-horizontal {';
		$output .= '	width: ' . ($this->config->get('manufacturer_' . $module . '_width') - 36) . 'px;';
		$output .= '	height: ' . $this->config->get('manufacturer_' . $module . '_height') . 'px;';
		$output .= '}' . "\n";		

		$output .= '#manufacturer' . $module . ' .jcarousel-item-horizontal {';
		$output .= '	width: ' . $this->config->get('manufacturer_' . $module . '_image_width') . 'px;';
		$output .= '	height: ' . $this->config->get('manufacturer_' . $module . '_image_height') . 'px;';
		$output .= '}' . "\n";	
				
		// Vertical
		$output .= '#manufacturer' . $module . ' .jcarousel-container-vertical {';
		$output .= '	width: ' . $this->config->get('manufacturer_' . $module . '_width') . 'px;';
		$output .= '	height: ' . $this->config->get('manufacturer_' . $module . '_height') . 'px;';
		$output .= '}' . "\n";	
		
		$output .= '#manufacturer' . $module . ' .jcarousel-clip-vertical {';
		$output .= '	width: ' . $this->config->get('manufacturer_' . $module . '_width') . 'px;';
		$output .= '	height: ' . ($this->config->get('manufacturer_' . $module . '_height') - 36) . 'px;';
		$output .= '}' . "\n";		
		
		$output .= '#manufacturer' . $module . ' .jcarousel-item-vertical {';
		$output .= '	width: 100%;';
		$output .= '	height: ' . $this->config->get('manufacturer_' . $module . '_image_height') . 'px;';
		$output .= '}' . "\n";	
		
		$this->response->addHeader('Content-type: text/css');
		$this->response->setOutput($output);
	}
}
?>