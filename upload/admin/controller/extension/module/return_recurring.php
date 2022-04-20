<?php
class ControllerExtensionModuleReturnRecurring extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/return_recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/return_recurring', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/return_recurring', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/return_recurring')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	// admin/view/sale/sale/return_form/after
	public function notify(&$route, &$args, &$output) {
		$this->load->language('sale/return');
		
		$this->load->model('sale/recurring');
		
		$filter_data = array(
			'filter_order_id' => $args['order_id']
		);

		$recurring_total = $this->model_sale_recurring->getTotalRecurrings($filter_data);
		
		if ($recurring_total) {
			$text_recurring = $this->language->get('text_recurring');
		} else {
			$text_recurring = $this->language->get('text_no_recurring');
		}
		
		$search = '<div class="panel panel-default">';
		
		$replace = '<div class="alert alert-info"><?php echo $text_recurring; ?></div>';
		
		$output = str_replace($search, $replace . $search, $output);
	}
}
