<?php
class ControllerExtensionModuleSlicebox extends Controller {
	public function index($setting) {
		static $module = 0;		

		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$data['slicebox_type'] = $setting['type'];

		$this->document->addStyle('catalog/view/javascript/jquery/slicebox/css/slicebox.css');
		$this->document->addStyle('catalog/view/javascript/jquery/slicebox/css/custom.css');
		$this->document->addStyle('catalog/view/javascript/jquery/slicebox/css/demo.css');
		$this->document->addScript('catalog/view/javascript/jquery/slicebox/modernizr.custom.46884.js');

		$this->document->addScript('catalog/view/javascript/jquery/slicebox/jquery.slicebox.js');

		$data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/slicebox', $data);
	}
}
