<?php
namespace Opencart\Application\Controller\Extension\Opencart\Module;
class Carousel extends \Opencart\System\Engine\Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->language('extension/opencart/module/carousel');

		$this->load->model('design/banner');
		$this->load->model('tool/image');
		
		$this->document->addScript('extension/opencart/catalog/view/javascript/bootstrap/js/carousel.js');
		$this->document->addStyle('extension/opencart/catalog/view/javascript/bootstrap/css/carousel.css');

		$data['banners'] = [];

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$data['banners'][] = [
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), $setting['width'], $setting['height'])
				];
			}
		}

		$data['module'] = $module++;

		return $this->load->view('extension/opencart/module/carousel', $data);
	}
}