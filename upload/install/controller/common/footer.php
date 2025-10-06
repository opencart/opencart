<?php
namespace Opencart\Install\Controller\Common;
/**
 * Class Footer
 *
 * @package Opencart\Install\Controller\Common
 */
class Footer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/footer');

		$data['text_project'] = $this->language->get('text_project');
		$data['text_documentation'] = $this->language->get('text_documentation');
		$data['text_support'] = $this->language->get('text_support');
		$data['text_footer'] = $this->language->get('text_footer');

		return $this->load->view('common/footer', $data);
	}
}
