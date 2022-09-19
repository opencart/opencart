<?php
namespace Opencart\Admin\Controller\Common;
class Header extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['title'] = $this->document->getTitle();
		$data['base'] = HTTP_SERVER;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();

		// Hard coding css so they can be replaced via the event's system.
		$data['bootstrap'] = 'view/stylesheet/bootstrap.css';
		$data['icons'] = 'view/stylesheet/fonts/fontawesome/css/all.min.css';
		$data['stylesheet'] = 'view/stylesheet/stylesheet.css';

		// Hard coding scripts so they can be replaced via the event's system.
		$data['jquery'] = 'view/javascript/jquery/jquery-3.6.1.min.js';

		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();

		$this->load->language('common/header');

		if (!isset($this->request->get['user_token']) || !isset($this->session->data['user_token']) || ($this->request->get['user_token'] != $this->session->data['user_token'])) {
			$data['logged'] = false;

			$data['home'] = $this->url->link('common/login');
		} else {
			$data['logged'] = true;

			$data['home'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']);

			$data['language'] = $this->load->controller('common/language');

			// Notifications
			$filter_data = [
				'start' => 0,
				'limit' => 5
			];

			$data['notifications'] = [];

			$this->load->model('tool/notification');

			$results = $this->model_tool_notification->getNotifications($filter_data);

			foreach ($results as $result) {
				$data['notifications'][] = [
					'title' => $result['title'],
					'href'  => $this->url->link('tool/notification.info', 'user_token=' . $this->session->data['user_token'] . '&notification_id=' . $result['notification_id'])
				];
			}

			$data['notification_all'] = $this->url->link('tool/notification', 'user_token=' . $this->session->data['user_token']);
			$data['notification_total'] = $this->model_tool_notification->getTotalNotifications(['filter_status' => 0]);

			$data['profile'] = $this->url->link('user/profile', 'user_token=' . $this->session->data['user_token']);

			$this->load->model('tool/image');

			$data['image'] = $this->model_tool_image->resize('profile.png', 45, 45);

			$this->load->model('user/user');

			$user_info = $this->model_user_user->getUser($this->user->getId());

			if ($user_info) {
				$data['firstname'] = $user_info['firstname'];
				$data['lastname'] = $user_info['lastname'];
				$data['username']  = $user_info['username'];
				$data['user_group'] = $user_info['user_group'];

				if (is_file(DIR_IMAGE . html_entity_decode($user_info['image'], ENT_QUOTES, 'UTF-8'))) {
					$data['image'] = $this->model_tool_image->resize(html_entity_decode($user_info['image'], ENT_QUOTES, 'UTF-8'), 45, 45);
				}
			}  else {
				$data['firstname'] = '';
				$data['lastname'] = '';
				$data['user_group'] = '';
			}

			// Stores
			$data['stores'] = [];

			$data['stores'][] = [
				'name' => $this->config->get('config_name'),
				'href' => HTTP_CATALOG
			];

			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$data['stores'][] = [
					'name' => $result['name'],
					'href' => $result['url']
				];
			}

			$data['logout'] = $this->url->link('common/logout', 'user_token=' . $this->session->data['user_token']);
		}

		return $this->load->view('common/header', $data);
	}
}
