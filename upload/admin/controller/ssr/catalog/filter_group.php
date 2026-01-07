<?php
namespace Opencart\admin\controller\ssr\catalog;
/**
 * Class Filter Group
 *
 * Called using admin/model/catalog/filter_group/addFilter/after
 * Called using admin/model/catalog/filter_group/editFilter/after
 * Called using admin/model/catalog/filter_group/catalog/deleteFilter/after
 *
 * @package Opencart\Admin\Controller\Event
 */
class FilterGroup extends \Opencart\System\Engine\Controller {
	/**
	 * addFilter
	 *
	 * Adds task to generate new filter group list
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editFilterGroup(string &$route, array &$args, &$output): void {
		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProductsByFilterGroupId($args[0]);

		$this->load->model('setting/task');

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'product.info.' . $result['product_id'],
				'action' => 'task/catalog/product.info',
				'args'   => ['product_id' => $result['product_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
