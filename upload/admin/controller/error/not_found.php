<?php
namespace Opencart\Admin\Controller\Error;
/**
 * Class Not Found
 *
 * @package Opencart\Admin\Controller\Error
 */
class NotFound extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('error/not_found');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', isset($this->session->data['user_token']) ? 'user_token=' . $this->session->data['user_token'] : '')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('error/not_found', isset($this->session->data['user_token']) ? 'user_token=' . $this->session->data['user_token'] : '')
		];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('error/not_found', $data));
	}
}