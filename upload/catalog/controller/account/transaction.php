<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Transaction
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Transaction extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/transaction');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/transaction', 'language=' . $this->config->get('config_language'));

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
			'text' => $this->language->get('text_transaction'),
			'href' => $this->url->link('account/transaction', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

		$limit = 10;

		// Transactions
		$data['transactions'] = [];

		$filter_data = [
			'sort'  => 'date_added',
			'order' => 'desc',
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		];

		$this->load->model('account/transaction');

		$results = $this->model_account_transaction->getTransactions($this->customer->getId(), $filter_data);

		foreach ($results as $result) {
			$data['transactions'][] = [
				'amount'     => $result['amount'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			] + $result;
		}

		// Total Transactions
		$transaction_total = $this->model_account_transaction->getTotalTransactions($this->customer->getId());

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $transaction_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('account/transaction', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&page=' . $page)
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($transaction_total - $limit)) ? $transaction_total : ((($page - 1) * $limit) + $limit), $transaction_total, ceil($transaction_total / $limit));

		$data['total'] = $this->customer->getBalance();

		$data['continue'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['currency'] = $this->config->get('config_currency');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/transaction', $data));
	}
}
