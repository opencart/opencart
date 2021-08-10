<?php
namespace Opencart\Admin\Controller\Common;
class Security extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('common/security');

		// Check install directory exists
		if (is_dir(DIR_CATALOG . '../install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		if (DIR_STORAGE == DIR_SYSTEM . 'storage/') {

		}

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('common/security', $data);

	}
}
