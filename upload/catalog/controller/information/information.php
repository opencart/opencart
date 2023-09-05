<?php
namespace Opencart\Catalog\Controller\Information;
/**
 * Class Information
 *
 * @package Opencart\Catalog\Controller\Information
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): object|null {
		$this->load->language('information/information');

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$this->document->setTitle($information_info['meta_title']);
			$this->document->setDescription($information_info['meta_description']);
			$this->document->setKeywords($information_info['meta_keyword']);

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			];

			$data['breadcrumbs'][] = [
				'text' => $information_info['title'],
				'href' => $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' .  $information_id)
			];

			$data['heading_title'] = $information_info['title'];

			$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language'));

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/information', $data));
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}

		return null;
	}

	/**
	 * @return void
	 */
	public function info(): void {
		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$data['title'] = $information_info['title'];
			$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

			$this->response->addHeader('X-Robots-Tag: noindex');
			$this->response->setOutput($this->load->view('information/information_info', $data));
		}
	}
}