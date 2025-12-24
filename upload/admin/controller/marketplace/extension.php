<?php
class ControllerMarketplaceExtension extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('marketplace/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['type'])) {
			$data['type'] = $this->request->get['type'];
		} else {
			$data['type'] = '';
		}
		
		$data['categories'] = array();
		
		// Fix: Removed GLOB_BRACE as it is not needed for a single extension pattern (*.php)
		// and it causes issues on Alpine Linux / musl systems.
		$files = glob(DIR_APPLICATION . 'controller/extension/extension/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				if ($extension == 'promotion') {
					continue;
				}

				// Compatibility code for old extension folders
				$this->load->language('extension/extension/' . $extension, 'extension');
			
				if ($this->user->hasPermission('access', 'extension/extension/' . $extension)) {
					// Fix: Removed GLOB_BRACE for compatibility
					$extension_files = glob(DIR_APPLICATION . 'controller/extension/' . $extension . '/*.php');
			
					$data['categories'][] = array(
						'code' => $extension,
						'text' => $this->language->get('extension')->get('heading_title') . ' (' . count($extension_files ? $extension_files : array()) . ')',
						'href' => $this->url->link('extension/extension/' . $extension, 'user_token=' . $this->session->data['user_token'], true)
					);
				}
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/extension', $data));
	}

	public function refreshMenu() {
		$output = $this->load->controller('common/column_left');
		$this->response->setOutput($output);
	}
}
