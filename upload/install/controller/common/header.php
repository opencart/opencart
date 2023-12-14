<?php
namespace Opencart\Install\Controller\Common;
/**
 * Class Header
 *
 * @package Opencart\Install\Controller\Common
 */
class Header extends \Opencart\System\Engine\Controller {
	/**
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/header');

		$data['seo'] = $this->document->getSeo();
		$data['base'] = HTTP_SERVER;
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();

		return $this->load->view('common/header', $data);
	}
}
