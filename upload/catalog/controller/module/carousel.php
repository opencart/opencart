<?php  
class ControllerModuleCarousel extends Controller {
	protected function index($setting) {
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->document->addScript('catalog/view/javascript/jquery/jquery.jcarousel.min.js');

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/carousel.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/carousel.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/carousel.css');
		}

		$this->data['limit'] = $setting['limit'];
		$this->data['scroll'] = $setting['scroll'];

		$this->data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}

		$this->data['module'] = $module++; 

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/carousel.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/carousel.tpl';
		} else {
			$this->template = 'default/template/module/carousel.tpl';
		}

		$this->render(); 
	}
}
?>