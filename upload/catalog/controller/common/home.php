<?php
namespace Opencart\Catalog\Controller\Common;
use Melbahja\Seo\MetaTags;

/**
 * Class Home
 *
 * @package Opencart\Catalog\Controller\Common
 */
class Home extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {

		$metadata = new MetaTags();
		$metadata
			->title($this->config->get('config_meta_title'))
			->description($this->config->get('config_meta_description'));
		$this->document->setSeo($metadata);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
