<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Module;
class ReturnRecurring extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/module/return_recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/module/return_recurring', 'user_token=' . $this->session->data['user_token'])
		];

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/module/return_recurring', $data));
	}

	public function notify(string &$route, array &$args, string &$code): void {
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

		$search = '<div id="tab-general" class="tab-pane active">';

		$replace = '<div class="alert alert-info"><i class="fas fa-info-circle"></i> <?php echo $text_recurring; ?></div>';

		$code = str_replace($search, $search . "\n" . $replace, $code);
	}
}
