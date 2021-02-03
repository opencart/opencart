<?php
class ControllerSaleVoucher extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/voucher');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/voucher');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_voucher->addVoucher($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/voucher');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_voucher->editVoucher($this->request->get['voucher_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/voucher');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $voucher_id) {
				$this->model_sale_voucher->deleteVoucher($voucher_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'v.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('sale/voucher/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('sale/voucher/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['vouchers'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$voucher_total = $this->model_sale_voucher->getTotalVouchers();

		$results = $this->model_sale_voucher->getVouchers($filter_data);

		foreach ($results as $result) {
			if ($result['order_id']) {	
				$order_href = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url, true);
			} else {
				$order_href = '';
			}

			$data['vouchers'][] = array(
				'voucher_id' => $result['voucher_id'],
				'code'       => $result['code'],
				'from'       => $result['from_name'],
				'to'         => $result['to_name'],
				'theme'      => $result['theme'],
				'amount'     => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('sale/voucher/edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $result['voucher_id'] . $url, true),
				'order'      => $order_href
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_code'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.code' . $url, true);
		$data['sort_from'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.from_name' . $url, true);
		$data['sort_to'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.to_name' . $url, true);
		$data['sort_theme'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=theme' . $url, true);
		$data['sort_amount'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.amount' . $url, true);
		$data['sort_status'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.status' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $voucher_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($voucher_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($voucher_total - $this->config->get('config_limit_admin'))) ? $voucher_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $voucher_total, ceil($voucher_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/voucher_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['voucher_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->request->get['voucher_id'])) {
			$data['voucher_id'] = (int)$this->request->get['voucher_id'];
		} else {
			$data['voucher_id'] = 0;
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		if (isset($this->error['from_name'])) {
			$data['error_from_name'] = $this->error['from_name'];
		} else {
			$data['error_from_name'] = '';
		}

		if (isset($this->error['from_email'])) {
			$data['error_from_email'] = $this->error['from_email'];
		} else {
			$data['error_from_email'] = '';
		}

		if (isset($this->error['to_name'])) {
			$data['error_to_name'] = $this->error['to_name'];
		} else {
			$data['error_to_name'] = '';
		}

		if (isset($this->error['to_email'])) {
			$data['error_to_email'] = $this->error['to_email'];
		} else {
			$data['error_to_email'] = '';
		}

		if (isset($this->error['amount'])) {
			$data['error_amount'] = $this->error['amount'];
		} else {
			$data['error_amount'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['voucher_id'])) {
			$data['action'] = $this->url->link('sale/voucher/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('sale/voucher/edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $this->request->get['voucher_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['voucher_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
			$voucher_info = $this->model_sale_voucher->getVoucher($this->request->get['voucher_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} elseif (!empty($voucher_info)) {
			$data['code'] = $voucher_info['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->request->post['from_name'])) {
			$data['from_name'] = $this->request->post['from_name'];
		} elseif (!empty($voucher_info)) {
			$data['from_name'] = $voucher_info['from_name'];
		} else {
			$data['from_name'] = '';
		}

		if (isset($this->request->post['from_email'])) {
			$data['from_email'] = $this->request->post['from_email'];
		} elseif (!empty($voucher_info)) {
			$data['from_email'] = $voucher_info['from_email'];
		} else {
			$data['from_email'] = '';
		}

		if (isset($this->request->post['to_name'])) {
			$data['to_name'] = $this->request->post['to_name'];
		} elseif (!empty($voucher_info)) {
			$data['to_name'] = $voucher_info['to_name'];
		} else {
			$data['to_name'] = '';
		}

		if (isset($this->request->post['to_email'])) {
			$data['to_email'] = $this->request->post['to_email'];
		} elseif (!empty($voucher_info)) {
			$data['to_email'] = $voucher_info['to_email'];
		} else {
			$data['to_email'] = '';
		}

		$this->load->model('sale/voucher_theme');

		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		if (isset($this->request->post['voucher_theme_id'])) {
			$data['voucher_theme_id'] = $this->request->post['voucher_theme_id'];
		} elseif (!empty($voucher_info)) {
			$data['voucher_theme_id'] = $voucher_info['voucher_theme_id'];
		} else {
			$data['voucher_theme_id'] = '';
		}

		if (isset($this->request->post['message'])) {
			$data['message'] = $this->request->post['message'];
		} elseif (!empty($voucher_info)) {
			$data['message'] = $voucher_info['message'];
		} else {
			$data['message'] = '';
		}

		if (isset($this->request->post['amount'])) {
			$data['amount'] = $this->request->post['amount'];
		} elseif (!empty($voucher_info)) {
			$data['amount'] = $voucher_info['amount'];
		} else {
			$data['amount'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($voucher_info)) {
			$data['status'] = $voucher_info['status'];
		} else {
			$data['status'] = true;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/voucher_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/voucher')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['code']) < 3) || (utf8_strlen($this->request->post['code']) > 10)) {
			$this->error['code'] = $this->language->get('error_code');
		}

		$voucher_info = $this->model_sale_voucher->getVoucherByCode($this->request->post['code']);

		if ($voucher_info) {
			if (!isset($this->request->get['voucher_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			} elseif ($voucher_info['voucher_id'] != $this->request->get['voucher_id'])  {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}

		if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
			$this->error['to_name'] = $this->language->get('error_to_name');
		}

		if ((utf8_strlen($this->request->post['to_email']) > 96) || !filter_var($this->request->post['to_email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['to_email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
			$this->error['from_name'] = $this->language->get('error_from_name');
		}

		if ((utf8_strlen($this->request->post['from_email']) > 96) || !filter_var($this->request->post['from_email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['from_email'] = $this->language->get('error_email');
		}

		if ($this->request->post['amount'] < 1) {
			$this->error['amount'] = $this->language->get('error_amount');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/voucher')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/order');

		foreach ($this->request->post['selected'] as $voucher_id) {
			$order_voucher_info = $this->model_sale_order->getOrderVoucherByVoucherId($voucher_id);

			if ($order_voucher_info) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_voucher_info['order_id'], true));

				break;
			}
		}

		return !$this->error;
	}

	public function history() {
		$this->load->language('sale/voucher');

		$this->load->model('sale/voucher');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_date_added'] = $this->language->get('column_date_added');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$results = $this->model_sale_voucher->getVoucherHistories($this->request->get['voucher_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_voucher->getTotalVoucherHistories($this->request->get['voucher_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('sale/voucher/history', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $this->request->get['voucher_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/voucher_history', $data));
	}

	public function send() {
		$this->load->language('mail/voucher');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/voucher')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('sale/voucher');

			$vouchers = array();

			if (isset($this->request->post['selected'])) {
				$vouchers = $this->request->post['selected'];
			} elseif (isset($this->request->post['voucher_id'])) {
				$vouchers[] = $this->request->post['voucher_id'];
			}

			if ($vouchers) {
				$this->load->model('sale/order');
				$this->load->model('sale/voucher_theme');

				foreach ($vouchers as $voucher_id) {
					$voucher_info = $this->model_sale_voucher->getVoucher($voucher_id);
			
					if ($voucher_info) {
						if ($voucher_info['order_id']) {
							$order_id = $voucher_info['order_id'];
						} else {
							$order_id = 0;
						}
			
						$order_info = $this->model_sale_order->getOrder($order_id);
			
						// If voucher belongs to an order
						if ($order_info) {
							$this->load->model('localisation/language');
			
							$language = new Language($order_info['language_code']);
							$language->load($order_info['language_code']);
							$language->load('mail/voucher');
			
							// HTML Mail
							$data['title'] = sprintf($language->get('text_subject'), $voucher_info['from_name']);
			
							$data['text_greeting'] = sprintf($language->get('text_greeting'), $this->currency->format($voucher_info['amount'], (!empty($order_info['currency_code']) ? $order_info['currency_code'] : $this->config->get('config_currency')), (!empty($order_info['currency_value']) ? $order_info['currency_value'] : $this->currency->getValue($this->config->get('config_currency')))));
							$data['text_from'] = sprintf($language->get('text_from'), $voucher_info['from_name']);
							$data['text_message'] = $language->get('text_message');
							$data['text_redeem'] = sprintf($language->get('text_redeem'), $voucher_info['code']);
							$data['text_footer'] = $language->get('text_footer');
			
							$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);
			
							if ($voucher_theme_info && is_file(DIR_IMAGE . $voucher_theme_info['image'])) {
								$data['image'] = HTTP_CATALOG . 'image/' . $voucher_theme_info['image'];
							} else {
								$data['image'] = '';
							}
			
							$data['store_name'] = $order_info['store_name'];
							$data['store_url'] = $order_info['store_url'];
							$data['message'] = nl2br($voucher_info['message']);
			
							$mail = new Mail($this->config->get('config_mail_engine'));
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
							$mail->setTo($voucher_info['to_email']);
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(sprintf($language->get('text_subject'), html_entity_decode($voucher_info['from_name'], ENT_QUOTES, 'UTF-8')));
							$mail->setHtml($this->load->view('mail/voucher', $data));
							$mail->send();
			
						// If voucher does not belong to an order
						} else {
							$this->language->load('mail/voucher');

							$data['title'] = sprintf($this->language->get('text_subject'), $voucher_info['from_name']);
			
							$data['text_greeting'] = sprintf($this->language->get('text_greeting'), $this->currency->format($voucher_info['amount'], $this->config->get('config_currency')));
							$data['text_from'] = sprintf($this->language->get('text_from'), $voucher_info['from_name']);
							$data['text_message'] = $this->language->get('text_message');
							$data['text_redeem'] = sprintf($this->language->get('text_redeem'), $voucher_info['code']);
							$data['text_footer'] = $this->language->get('text_footer');		
			
							$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);

							if ($voucher_theme_info && is_file(DIR_IMAGE . $voucher_theme_info['image'])) {
								$data['image'] = HTTP_CATALOG . 'image/' . $voucher_theme_info['image'];
							} else {
								$data['image'] = '';
							}

							$data['store_name'] = $this->config->get('config_name');
							$data['store_url'] = HTTP_CATALOG;
							$data['message'] = nl2br($voucher_info['message']);
			
							$mail = new Mail($this->config->get('config_mail_engine'));
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
							$mail->setTo($voucher_info['to_email']);
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), $voucher_info['from_name']), ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($this->load->view('mail/voucher', $data));
							$mail->send();
						}
					}
				}

				$json['success'] = $this->language->get('text_sent');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}