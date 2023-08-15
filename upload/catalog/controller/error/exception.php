<?php
namespace Opencart\Catalog\Controller\Error;
/**
 * Class Exception
 *
 * @package Opencart\Catalog\Controller\Error
 */
class Exception extends \Opencart\System\Engine\Controller {
	/**
	 * @param string $message
	 * @param string $code
	 * @param string $file
	 * @param string $line
	 *
	 * @return void
	 */
	public function index(string $message, string $code, string $file, string $line): void {
		$this->load->language('error/exception');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('error/exception', 'user_token=' . $this->session->data['user_token'])
		];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('error/exception', $data));
	}
}