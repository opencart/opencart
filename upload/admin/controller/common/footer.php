<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Footer
 *
 * Can be loaded using $this->load->controller('common/footer');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Footer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/footer');

		if ($this->user->isLogged() && isset($this->request->get['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
		} else {
			$data['text_version'] = '';
		}

		// Hard coding css so they can be replaced via the event's system.
		$data['scripts'] = $this->document->getScripts();

		return $this->load->view('common/footer', $data);
	}
}
