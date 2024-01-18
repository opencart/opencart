<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Subscription
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Subscription extends \Opencart\System\Engine\Controller {
	/**
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param array<mixed>      $output
	 *
	 *  addHistory
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		if (isset($args[0])) {
			$subscription_id = $args[0];
		} else {
			$subscription_id = 0;
		}

		if (isset($args[1]['subscription'])) {
			$subscription = $args[1]['subscription'];
		} else {
			$subscription = [];
		}

		if (isset($args[2])) {
			$comment = $args[2];
		} else {
			$comment = '';
		}

		if (isset($args[3])) {
			$notify = $args[3];
		} else {
			$notify = '';
		}
		/*
		$subscription['order_product_id']
		$subscription['customer_id']
		$subscription['order_id']
		$subscription['subscription_plan_id']
		$subscription['customer_payment_id'],
		$subscription['name']
		$subscription['description']
		$subscription['trial_price']
		$subscription['trial_frequency']
		$subscription['trial_cycle']
		$subscription['trial_duration']
		$subscription['trial_remaining']
		$subscription['trial_status']
		$subscription['price']
		$subscription['frequency']
		$subscription['cycle']
		$subscription['duration']
		$subscription['remaining']
		$subscription['date_next']
		$subscription['status']
		*/
	}

	// catalog/model/checkout/order/addHistory/before

	/**
	 * Alert
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function alert(string &$route, array &$args): void {
		if (isset($args[0])) {
			$order_id = $args[0];
		} else {
			$order_id = 0;
		}

		if (isset($args[1])) {
			$order_status_id = $args[1];
		} else {
			$order_status_id = 0;
		}

		if (isset($args[2])) {
			$comment = $args[2];
		} else {
			$comment = '';
		}

		if (isset($args[3])) {
			$notify = $args[3];
		} else {
			$notify = '';
		}

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info && !$order_info['order_status_id'] && $order_status_id && in_array('order', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/order_alert');

			$subject = html_entity_decode(sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $order_info['order_id']), ENT_QUOTES, 'UTF-8');

			$data['order_id'] = $order_info['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			$order_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

			if ($order_status_query->num_rows) {
				$data['order_status'] = $order_status_query->row['name'];
			} else {
				$data['order_status'] = '';
			}

			$this->load->model('tool/upload');

			$data['products'] = [];

			$order_products = $this->model_checkout_order->getProducts($order_id);

			foreach ($order_products as $order_product) {
				$option_data = [];

				$order_options = $this->model_checkout_order->getOptions($order_info['order_id'], $order_product['order_product_id']);

				foreach ($order_options as $order_option) {
					if ($order_option['type'] != 'file') {
						$value = $order_option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = [
						'name'  => $order_option['name'],
						'value' => (oc_strlen($value) > 20 ? oc_substr($value, 0, 20) . '..' : $value)
					];
				}

				$description = '';

				$this->load->model('checkout/subscription');

				$subscription_info = $this->model_checkout_order->getSubscription($order_info['order_id'], $order_product['order_product_id']);

				if ($subscription_info) {
					if ($subscription_info['trial_status']) {
						$trial_price = $this->currency->format($subscription_info['trial_price'] + ($this->config->get('config_tax') ? $subscription_info['trial_tax'] : 0), $this->session->data['currency']);
						$trial_cycle = $subscription_info['trial_cycle'];
						$trial_frequency = $this->language->get('text_' . $subscription_info['trial_frequency']);
						$trial_duration = $subscription_info['trial_duration'];

						$description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
					}

					$price = $this->currency->format($subscription_info['price'] + ($this->config->get('config_tax') ? $subscription_info['tax'] : 0), $this->session->data['currency']);
					$cycle = $subscription_info['cycle'];
					$frequency = $this->language->get('text_' . $subscription_info['frequency']);
					$duration = $subscription_info['duration'];

					if ($duration) {
						$description .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
					} else {
						$description .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
					}
				}

				$data['products'][] = [
					'name'         => $order_product['name'],
					'model'        => $order_product['model'],
					'quantity'     => $order_product['quantity'],
					'option'       => $option_data,
					'subscription' => $description,
					'total'        => html_entity_decode($this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? $order_product['tax'] * $order_product['quantity'] : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
				];
			}

			$data['vouchers'] = [];

			$order_vouchers = $this->model_checkout_order->getVouchers($order_id);

			foreach ($order_vouchers as $order_voucher) {
				$data['vouchers'][] = [
					'description' => $order_voucher['description'],
					'amount'      => html_entity_decode($this->currency->format($order_voucher['amount'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
				];
			}

			$data['totals'] = [];

			$order_totals = $this->model_checkout_order->getTotals($order_id);

			foreach ($order_totals as $order_total) {
				$data['totals'][] = [
					'title' => $order_total['title'],
					'value' => html_entity_decode($this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
				];
			}

			$data['comment'] = nl2br($order_info['comment']);

			$data['store'] = html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8');
			$data['store_url'] = $order_info['store_url'];

			if ($this->config->get('config_mail_engine')) {
				$mail_option = [
					'parameter'     => $this->config->get('config_mail_parameter'),
					'smtp_hostname' => $this->config->get('config_mail_smtp_hostname'),
					'smtp_username' => $this->config->get('config_mail_smtp_username'),
					'smtp_password' => html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
					'smtp_port'     => $this->config->get('config_mail_smtp_port'),
					'smtp_timeout'  => $this->config->get('config_mail_smtp_timeout')
				];

				$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'), $mail_option);
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setHtml($this->load->view('mail/order_alert', $data));
				$mail->send();

				// Send to additional alert emails
				$emails = explode(',', $this->config->get('config_mail_alert_email'));

				foreach ($emails as $email) {
					if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$mail->setTo(trim($email));
						$mail->send();
					}
				}
			}
		}
	}
}
