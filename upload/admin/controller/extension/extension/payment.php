<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionExtensionPayment extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/extension/payment');
		
		$this->load->model('setting/extension');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/extension/payment');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->install('payment', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/payment/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/payment/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller('extension/payment/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/extension/payment');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->uninstall('payment', $this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller('extension/payment/' . $this->request->get['extension'] . '/uninstall');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	protected function getList() {
		$data['text_hide_payment'] = sprintf($this->language->get('text_hide_payment'), $this->url->link('user/user_permission', 'user_token=' . $this->session->data['user_token'], true));
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('payment');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/extension/payment/' . $value . '.php') && !is_file(DIR_APPLICATION . 'controller/payment/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('payment', $value);

				unset($extensions[$key]);
			}
		}

		$data['extensions'] = array();
		
		// Compatibility code for old extension folders
		$files = glob(DIR_APPLICATION . 'controller/extension/payment/*.php');
		
		$this->load->model('user/user_group');
		
		$user_group_info = $this->model_user_user_group->getUserGroup($this->user->getGroupId());
		
		if(isset($user_group_info['permission']['hiden'])) {
			$hiden = $user_group_info['permission']['hiden'];
		} else {
			$hiden = array();
		}
		
		$data['hiden'] = false;

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				if (!in_array('extension/payment/' . $extension, $hiden)) {
				$this->load->language('extension/payment/' . $extension, 'extension');

				$text_link = $this->language->get('extension')->get('text_' . $extension);

				if ($text_link != 'text_' . $extension) {
					$link = $text_link;
				} else {
					$link = '';
				}

				$data['extensions'][] = array(
					'name'       => $this->language->get('extension')->get('heading_title'),
					'link'       => $link,
					'status'     => $this->config->get('payment_' . $extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get('payment_' . $extension . '_sort_order'),
					'install'    => $this->url->link('extension/extension/payment/install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension, true),
					'uninstall'  => $this->url->link('extension/extension/payment/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension, true),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('extension/payment/' . $extension, 'user_token=' . $this->session->data['user_token'], true)
				);
				
				} else {
					$data['hiden'] = true;
				}
			}
		}

		$data['promoted_solution_1'] = $this->load->controller('extension/payment/pp_express/preferredSolution');
		$data['promoted_solution_2'] = $this->load->controller('extension/payment/pp_braintree/preferredSolution');

		$sort_order = array();
		
		foreach ($data['extensions'] as $key => $value) {
			if($value['installed']){
				$add = '0';
			}else{
				$add = '1';
			}
				$sort_order[$key] = $add.$value['name'];
		}
		array_multisort($sort_order, SORT_ASC, $data['extensions']);
		
		$this->response->setOutput($this->load->view('extension/extension/payment', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/extension/payment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}