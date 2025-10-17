<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Review
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Review extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * catalog/model/catalog/review.addReview/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		if (!in_array('review', (array)$this->config->get('config_mail_alert'))) {
			return;
		}

		$this->load->language('mail/review');

		// Product
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct((int)$args[0]);

		if (!$product_info) {
			return;
		}

		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$data['product'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
		$data['reviewer'] = html_entity_decode($args[1]['author'], ENT_QUOTES, 'UTF-8');
		$data['rating'] = (int)$args[1]['rating'];
		$data['text'] = nl2br($args[1]['text']);

		$data['store'] = $store_name;
		$data['store_url'] = $this->config->get('config_url');

		$emails = [];

		$emails[] = $this->config->get('config_email');

		$tos = explode(',', (string)$this->config->get('config_mail_alert_email'));

		foreach ($tos as $to) {
			$to = trim($to);

			if (oc_validate_email($to)) {
				$emails[] = $to;
			}
		}

		$this->load->model('setting/task');

		foreach ($emails as $email) {
			$task_data = [
				'code'   => 'mail_review',
				'action' => 'task/system/mail',
				'args'   => [
					'to'      => $email,
					'from'    => $this->config->get('config_email'),
					'sender'  => $store_name,
					'subject' => sprintf($this->language->get('text_subject'), $store_name),
					'content' => $this->load->view('mail/review', $data)
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
