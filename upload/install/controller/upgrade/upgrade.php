<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_upgrade'] = $this->language->get('text_upgrade');
		$data['text_server'] = $this->language->get('text_server');
		$data['text_steps'] = $this->language->get('text_steps');
		$data['text_error'] = $this->language->get('text_error');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_refresh'] = $this->language->get('text_refresh');
		$data['text_admin'] = $this->language->get('text_admin');
		$data['text_user'] = $this->language->get('text_user');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_progress'] = $this->language->get('entry_progress');

		$data['button_continue'] = $this->language->get('button_continue');

		$server = trim(HTTP_SERVER, '/');

		$data['server'] = substr($server, 0, strrpos($server, '/')) . '/';
		$data['total'] = count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php'));

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view('upgrade/upgrade', $data));
	}
}
