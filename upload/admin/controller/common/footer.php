<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Footer
 *
 * @package Opencart\Admin\Controller\Common
 */
class Footer extends \Opencart\System\Engine\Controller {
	/**
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/footer');

		if ($this->user->isLogged() && $this->jwt->validateToken()) {
			$data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
		} else {
			$data['text_version'] = '';
		}

		$data['bootstrap'] = 'view/javascript/bootstrap/js/bootstrap.bundle.min.js';

		return $this->load->view('common/footer', $data);
	}
}
