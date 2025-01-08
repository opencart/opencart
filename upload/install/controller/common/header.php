<?php
namespace Opencart\Install\Controller\Common;
/**
 * Class Header
 *
 * @package Opencart\Install\Controller\Common
 */
class Header extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/header');

		$data['title'] = $this->document->getTitle();
		$data['description'] = $this->document->getDescription();
		$data['base'] = HTTP_SERVER;
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();

		$data['language'] = $this->load->controller('common/language');

		return $this->load->view('common/header', $data);
	}
}
