<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Module;
class ReturnSubscription extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/module/return_subscription');

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
			'href' => $this->url->link('extension/opencart/module/return_subscription', 'user_token=' . $this->session->data['user_token'])
		];

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/module/return_subscription', $data));
	}

	// admin/view/sale/return_form/after
	public function notify(string &$route, array &$args, string &$code): void {
		$this->load->language('sale/return');

		$this->load->model('sale/subscription');

		$filter_data = array(
			'filter_order_id' => $args['order_id']
		);

		$subscription_total = $this->model_sale_subscription->getTotalSubscriptions($filter_data);

		if ($subscription_total) {
			$text_subscription = $this->language->get('text_subscription');
		} else {
			$text_subscription = $this->language->get('text_no_subscription');
		}

		$search = '<div id="tab-general" class="tab-pane active">';

		$replace = '<div class="alert alert-info"><i class="fas fa-info-circle"></i> <?php echo $text_subscription; ?></div>';

		$code = str_replace($search, $search . "\n" . $replace, $code);
	}
}
