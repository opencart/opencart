<?php
namespace Opencart\Catalog\Controller\Information;
/**
 * Class Contact
 *
 * @package Opencart\Catalog\Controller\Information
 */
class Contact extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('information/contact');

		$data['send'] = $this->url->link('information/contact.send', 'language=' . $this->config->get('config_language'));

		// Image
		$this->load->model('tool/image');

		if ($this->config->get('config_image') && is_file(DIR_IMAGE . html_entity_decode($this->config->get('config_image'), ENT_QUOTES, 'UTF-8'))) {
			$data['image'] = $this->model_tool_image->resize($this->config->get('config_image'), $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
		} else {
			$data['image'] = '';
		}

		$data['store'] = $this->config->get('config_name');
		$data['address'] = nl2br($this->config->get('config_address'));
		$data['map'] = 'https://maps.google.com/maps?q=' . urlencode(str_replace("\n", "\s", $this->config->get('config_address'))) . '&hl=' . $this->config->get('config_language') . '&t=m&z=15';

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
		} else {
			$data['captcha'] = '';
		}
	}

	/**
	 * Send
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function send(): void {
		$this->load->language('information/contact');

		$json = [];

		$required = [
			'name'    => '',
			'email'   => '',
			'enquiry' => ''
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['name'], 3, 32)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!oc_validate_email($post_info['email'])) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if (!oc_validate_length($post_info['enquiry'], 10, 3000)) {
			$json['error']['enquiry'] = $this->language->get('error_enquiry');
		}

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code'] . '.validate');

			if ($captcha) {
				$json['error']['captcha'] = $captcha;
			}
		}

		if (!$json) {
			$task_data = [
				'code'   => 'mail_alert',
				'action' => 'task/system/mail',
				'args'   => [
					'to'       => $this->config->get('config_email'),
					'from'     => $this->config->get('config_email'),
					'reply_to' => $post_info['email'],
					'sender'   => html_entity_decode($post_info['name'], ENT_QUOTES, 'UTF-8'),
					'subject'  => html_entity_decode(sprintf($this->language->get('email_subject'), $post_info['name']), ENT_QUOTES, 'UTF-8'),
					'content'  => $post_info['enquiry']
				]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			$json['redirect'] = $this->url->link('information/contact.success', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
