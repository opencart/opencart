<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate all country data.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function add(array $args = []): array {
		$this->load->language('task/catalog/topic');




		return ['success' => $this->language->get('text_task')];
	}


}