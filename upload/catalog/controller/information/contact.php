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

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact', 'language=' . $this->config->get('config_language'))
		];

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
		$data['language'] = $this->config->get('config_language');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['open'] = nl2br($this->config->get('config_open'));
		$data['comment'] = nl2br($this->config->get('config_comment'));

		// Location
		$data['locations'] = [];

		$this->load->model('localisation/location');

		foreach ((array)$this->config->get('config_location') as $location_id) {
			$location_info = $this->model_localisation_location->getLocation((int)$location_id);

			if ($location_info) {
				if ($location_info['image'] && is_file(DIR_IMAGE . html_entity_decode($location_info['image'], ENT_QUOTES, 'UTF-8'))) {
					$image = $this->model_tool_image->resize($location_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
				} else {
					$image = '';
				}

				$data['locations'][] = [
					'image'   => $image,
					'address' => nl2br($location_info['address']),
					'map'     => 'https://maps.google.com/maps?q=' . urlencode(str_replace("\n", "\s", $location_info['address'])) . '&hl=' . $this->config->get('config_language') . '&t=m&z=15',
					'open'    => nl2br($location_info['open'])
				] + $location_info;
			}
		}

		$data['name'] = $this->customer->getFirstName();
		$data['email'] = $this->customer->getEmail();

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
		} else {
			$data['captcha'] = '';
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('information/contact', $data));
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

	/**
	 * Success
	 *
	 * @return void
	 */
	public function success(): void {
		$this->load->language('information/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact', 'language=' . $this->config->get('config_language'))
		];

		$data['text_message'] = $this->language->get('text_message');

		$data['continue'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}
