<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Api;
/**
 * Class Reward
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Api
 */
class Reward extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('extension/opencart/api/reward');

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$data['reward'] = 0;

		if ($order_id) {
			$order_totals = $this->model_sale_order->getTotalsByCode($order_id, 'reward');

			foreach ($order_totals as $order_total) {
				// If coupon or reward points
				$start = strpos($order_total['title'], '(');
				$end = strrpos($order_total['title'], ')');

				if ($start !== false && $end !== false) {
					$data['reward'] = substr($order_total['title'], $start + 1, $end - ($start + 1));
				}
			}
		}

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/api/reward', $data);
	}
}
