<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Total;
/**
 * Class Coupon
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Total
 */
class Coupon extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/total/coupon');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/total/coupon', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/total/coupon.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total');

		$data['total_coupon_status'] = $this->config->get('total_coupon_status');
		$data['total_coupon_sort_order'] = $this->config->get('total_coupon_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/total/coupon', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/total/coupon');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/total/coupon')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('total_coupon', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function order(): string {
		$this->load->language('extension/opencart/total/coupon');

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$data['coupon'] = '';

		if ($order_id) {
			$order_totals = $this->model_sale_order->getTotalsByCode($order_id, 'coupon');

			foreach ($order_totals as $order_total) {
				// If coupon or reward points
				$start = strpos($order_total['title'], '(');
				$end = strrpos($order_total['title'], ')');

				if ($start !== false && $end !== false) {
					$data['coupon'] = substr($order_total['title'], $start + 1, $end - ($start + 1));
				}
			}
		}

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/total/coupon_order', $data);
	}
}
