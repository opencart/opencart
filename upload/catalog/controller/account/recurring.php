<?php 
class ControllerAccountRecurring extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/recurring');

		$this->load->model('account/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/recurring', $url, 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['column_created'] = $this->language->get('column_created');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_profile_id'] = $this->language->get('column_profile_id');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['button_view'] = $this->language->get('button_view');
		$this->data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['orders'] = array();

		$recurring_total = $this->model_account_recurring->getTotalRecurring();

		$results = $this->model_account_recurring->getAllProfiles(($page - 1) * 10, 10);

		$this->data['profiles'] = array();

		if($results){
			foreach ($results as $result) {
				$this->data['profiles'][] = array(
					'id'                    => $result['order_recurring_id'],
					'name'                  => $result['product_name'],
					'status'                => $result['status'],
					'created'               => date($this->language->get('date_format_short'), strtotime($result['created'])),
					'href'                  => $this->url->link('account/recurring/info','recurring_id='.$result['order_recurring_id'],'SSL'),
				);
			}
		}

		$this->data['status_types'] = array(
			1 => $this->language->get('text_status_inactive'),
			2 => $this->language->get('text_status_active'),
			3 => $this->language->get('text_status_suspended'),
			4 => $this->language->get('text_status_cancelled'),
			5 => $this->language->get('text_status_expired'),
			6 => $this->language->get('text_status_pending'),
		);

		$pagination = new Pagination();
		$pagination->total = $recurring_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/recurring', 'page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/recurring_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/recurring_list.tpl';
		} else {
			$this->template = 'default/template/account/recurring_list.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

	public function info() {
		$this->load->model('account/recurring');
		$this->load->language('account/recurring');

		if (isset($this->request->get['recurring_id'])) {
			$recurring_id = $this->request->get['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/recurring/info', 'recurring_id=' . $recurring_id, 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		if(isset($this->session->data['error'])){
			$this->data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}else{
			$this->data['error'] = '';
		}

		if(isset($this->session->data['success'])){
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}else{
			$this->data['success'] = '';
		}

		$profile = $this->model_account_recurring->getProfile($this->request->get['recurring_id']);

		$profile['transactions'] = $this->model_account_recurring->getProfileTransactions($this->request->get['recurring_id']);

		$profile['created'] = date($this->language->get('date_format_short'), strtotime($profile['created']));
		$profile['product_link'] = $this->url->link('product/product', 'product_id='.$profile['product_id'], 'SSL');
		$profile['order_link'] = $this->url->link('account/order/info', 'order_id='.$profile['order_id'], 'SSL');

		if($profile['status'] == 1 || $profile['status'] == 2){
			/**
			 * If the payment profiles payment type has a cancel action then link to that. If not then hide the button.
			 */
			if(!empty($profile['payment_code']) && $this->hasAction('payment/' . $profile['payment_code'] . '/recurringCancel') == true && $this->config->get($profile['payment_code'] . '_profile_cancel_status')){
				$this->data['cancel_link'] = $this->url->link('payment/'.$profile['payment_code'].'/recurringCancel', 'recurring_id='.$this->request->get['recurring_id'], 'SSL');
			}else{
				$this->data['cancel_link'] = '';
			}
		}else{
			$this->data['cancel_link'] = '';
		}

		$this->data['status_types'] = array(
			1 => $this->language->get('text_status_inactive'),
			2 => $this->language->get('text_status_active'),
			3 => $this->language->get('text_status_suspended'),
			4 => $this->language->get('text_status_cancelled'),
			5 => $this->language->get('text_status_expired'),
			6 => $this->language->get('text_status_pending'),
		);

		$this->data['transaction_types'] = array(
			0 => $this->language->get('text_transaction_created'),
			1 => $this->language->get('text_transaction_payment'),
			2 => $this->language->get('text_transaction_outstanding_payment'),
			3 => $this->language->get('text_transaction_skipped'),
			4 => $this->language->get('text_transaction_failed'),
			5 => $this->language->get('text_transaction_cancelled'),
			6 => $this->language->get('text_transaction_suspended'),
			7 => $this->language->get('text_transaction_suspended_failed'),
			8 => $this->language->get('text_transaction_outstanding_failed'),
			9 => $this->language->get('text_transaction_expired'),
		);

		if ($profile) {
			$this->document->setTitle($this->language->get('text_recurring'));

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_account'),
				'href'      => $this->url->link('account/account', '', 'SSL'),
				'separator' => $this->language->get('text_separator')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('account/recurring', $url, 'SSL'),
				'separator' => $this->language->get('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_recurring'),
				'href'      => $this->url->link('account/recurring/info', 'recurring_id=' . $this->request->get['recurring_id'] . $url, 'SSL'),
				'separator' => $this->language->get('text_separator')
			);

			$this->data['heading_title'] = $this->language->get('text_recurring');

			$this->data['column_created'] = $this->language->get('column_created');
			$this->data['column_type'] = $this->language->get('column_type');
			$this->data['column_amount'] = $this->language->get('column_amount');

			$this->data['text_recurring_id'] = $this->language->get('text_recurring_id');
			$this->data['text_date_added'] = $this->language->get('text_date_added');
			$this->data['text_empty_transactions'] = $this->language->get('text_empty_transactions');
			$this->data['text_payment_method'] = $this->language->get('text_payment_method');
			$this->data['text_recurring_detail'] = $this->language->get('text_recurring_detail');
			$this->data['text_status'] = $this->language->get('text_status');
			$this->data['text_ref'] = $this->language->get('text_ref');
			$this->data['text_product'] = $this->language->get('text_product');
			$this->data['text_order'] = $this->language->get('text_order');
			$this->data['text_quantity'] = $this->language->get('text_quantity');
			$this->data['text_transactions'] = $this->language->get('text_transactions');
			$this->data['text_recurring_description'] = $this->language->get('text_recurring_description');

			$this->data['button_return'] = $this->language->get('button_return');
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['button_cancel_profile'] = $this->language->get('button_cancel_profile');
			$this->data['text_confirm_cancel'] = $this->language->get('text_confirm_cancel');
			$this->data['continue'] = $this->url->link('account/recurring', '', 'SSL');
			$this->data['profile'] = $profile;

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/recurring_info.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/recurring_info.tpl';
			} else {
				$this->template = 'default/template/account/recurring_info.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->response->setOutput($this->render());
		} else {
			$this->redirect($this->url->link('account/recurring', '', 'SSL'));
		}
	}
}
?>
