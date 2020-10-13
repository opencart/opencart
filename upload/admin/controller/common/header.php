<?php
namespace Opencart\Application\Controller\Common;
class Header extends \Opencart\System\Engine\Controller {
	public function index() {
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['title'] = $this->document->getTitle();
		$data['base'] = HTTP_SERVER;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();

		// Hardcoding scripts so they can be replaced via the events system.
		$data['jquery'] = 'catalog/view/javascript/jquery/jquery-3.3.1.min.js';
		$data['bootstrap_js'] = 'catalog/view/javascript/bootstrap/js/bootstrap.bundle.min.js';
		$data['bootstrap_css'] = 'catalog/view/stylesheet/bootstrap.css';
		$data['icon'] = 'catalog/view/javascript/fontawesome/css/fontawesome-all.min.css';
		$data['font'] = '//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet';
		$data['stylesheet'] = 'catalog/view/stylesheet/stylesheet.css';

		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();

		$this->load->language('common/header');
		
		if (!isset($this->request->get['user_token']) || !isset($this->session->data['user_token']) || ($this->request->get['user_token'] != $this->session->data['user_token'])) {
			$data['logged'] = '';

			$data['home'] = $this->url->link('common/login');
		} else {
			$data['logged'] = true;

			$data['home'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']);
			$data['logout'] = $this->url->link('common/logout', 'user_token=' . $this->session->data['user_token']);
			$data['profile'] = $this->url->link('common/profile', 'user_token=' . $this->session->data['user_token']);

			$this->load->model('tool/image');

			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['user_group'] = '';
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
			} 		
			
			// Online Stores
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
		}

		return $this->load->view('common/header', $data);
	}
}
