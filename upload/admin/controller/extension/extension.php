<?php
class ControllerExtensionExtension extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_type'] = $this->language->get('text_type');
		$data['text_filter'] = $this->language->get('text_filter');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['type'])) {
			$data['type'] = $this->request->get['type'];
		} else {
			$data['type'] = '';
		}

		$data['categories'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/extension/extension/*.php', GLOB_BRACE);
		
		foreach ($files as $file) {
			$extension = basename($file, '.php');
			
			// Compatibility code for old extension folders
			$this->load->language('extension/extension/' . $extension);
		
			if ($this->user->hasPermission('access', 'extension/extension/' . $extension)) {
				$files = glob(DIR_APPLICATION . 'controller/{extension/' . $extension . ',' . $extension . '}/*.php', GLOB_BRACE);
		
				$data['categories'][] = array(
					'code' => $extension,
					'text' => $this->language->get('heading_title') . ' (' . count($files) .')',
					'href' => $this->url->link('extension/extension/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}			
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/extension', $data));
	}
}