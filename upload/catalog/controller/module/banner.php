<?php  
class ControllerModuleBanner extends Controller {
	protected function index($module) {
		$this->load->model('design/banner');
		$this->load->model('tool/image');
				
		$this->data['banners'] = array();
		
		$results = $this->model_design_banner->getBanner($this->config->get('banner_' . $module . '_banner_id'));
		  
		foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $this->config->get('banner_' . $module . '_width'), $this->config->get('banner_' . $module . '_height'))
				);
			}
		}
		
		$this->data['module'] = $module;
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/banner.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/banner.tpl';
		} else {
			$this->template = 'default/template/module/banner.tpl';
		}
		
		$this->render();
	}
}
?>