<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Module;
class Information extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('extension/opencart/module/information');

		$this->load->model('catalog/information');

		$data['informations'] = [];

		foreach ($this->model_catalog_information->getInformations() as $result) {
			$data['informations'][] = [
				'title' => $result['title'],
				'href'  => $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $result['information_id'])
			];
		}

		$data['contact'] = $this->url->link('information/contact', 'language=' . $this->config->get('config_language'));
		$data['sitemap'] = $this->url->link('information/sitemap', 'language=' . $this->config->get('config_language'));

		return $this->load->view('extension/opencart/module/information', $data);
	}
}