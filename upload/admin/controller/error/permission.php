<?php
class ControllerErrorPermission extends Controller {
	public function index() {
		$this->load->language('error/permission');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_permission'] = $this->language->get('text_permission');

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('error/permission.tpl', $data));
	}

	public function check() {
		if (isset($this->request->get['route'])) {
			$route = '';

			$parts = explode('/', $this->request->get['route']);

			foreach ($parts as $part) {
				if (strlen(trim($route)) > 0) $route .= '/' . $part; else $route = $part;
				if (file_exists(DIR_APPLICATION . 'controller/' . $route . '.php')) {
					break;
				}
 			}
 
			$ignore = array(
				'common/dashboard',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
				return new Action('error/permission');
			}
		}
	}
}
