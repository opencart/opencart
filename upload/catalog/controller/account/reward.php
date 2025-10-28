<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Reward
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Reward extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/reward');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/reward', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_reward'),
			'href' => $this->url->link('account/reward', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$limit = 10;

		// Rewards
		$data['rewards'] = [];

		$filter_data = [
			'sort'  => 'date_added',
			'order' => 'desc',
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		];

		$this->load->model('account/reward');

		$results = $this->model_account_reward->getRewards($this->customer->getId(), $filter_data);

		foreach ($results as $result) {
			$data['rewards'][] = [
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'       => $this->url->link('account/order.info', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&order_id=' . $result['order_id'])
			] + $result;
		}

		// Total Rewards
		$reward_total = $this->model_account_reward->getTotalRewards($this->customer->getId());

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $reward_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('account/reward', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&page=' . $page)
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($reward_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($reward_total - $limit)) ? $reward_total : ((($page - 1) * $limit) + $limit), $reward_total, ceil($reward_total / $limit));

		$data['total'] = (int)$this->customer->getRewardPoints();

		$data['continue'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/reward', $data));
	}
}
