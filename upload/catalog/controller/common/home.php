<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$language_id = (int)$this->config->get('config_language_id');
		$meta_tag = $this->config->get('config_meta_tag');
		
		$this->document->setTitle($meta_tag[$language_id]['title']);
		$this->document->setDescription($meta_tag[$language_id]['description']);
		$this->document->setKeywords($meta_tag[$language_id]['keyword']);

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
		}
	}
}