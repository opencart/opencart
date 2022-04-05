<?php
namespace Opencart\Install\Controller\Common;
class Header extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('common/header');
		
		$data['title'] = $this->document->getTitle();
		$data['description'] = $this->document->getDescription();
		$data['base'] = HTTP_SERVER;
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();

		return $this->load->view('common/header', $data);
	}
}