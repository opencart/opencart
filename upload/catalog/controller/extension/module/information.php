<?php
class ControllerExtensionModuleInformation extends Controller {
	public function index() {
		$this->load->language('extension/module/information');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			$data['informations'][] = array(
				'title' => $result['title'],
				'href'  => $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $result['information_id'])
			);
		}

		$data['contact'] = $this->url->link('information/contact', 'language=' . $this->config->get('config_language'));
		$data['sitemap'] = $this->url->link('information/sitemap', 'language=' . $this->config->get('config_language'));

		return $this->load->view('extension/module/information', $data);
	}
}