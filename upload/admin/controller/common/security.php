<?php
class ControllerCommonSecurity extends Controller {
	public function index() {
		$this->load->language('common/security');
		
		$args = array(
			DIR_SYSTEM . 'storage/',
			str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '../')) . '/',
			'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');',
			'define(\'DIR_STORAGE\', \'' . realpath($this->request->server['DOCUMENT_ROOT'] . '../') . '/storage/\');',
			'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');',
			'define(\'DIR_STORAGE\', \'' . realpath($this->request->server['DOCUMENT_ROOT'] . '../') . '/storage/\');'
		);
		//vsprintf(, $args);
		$data['text_instruction'] = $this->language->get('text_instruction');
	
		$data['user_token'] = $this->session->data['user_token'];
		
		$path = '';
		
		$data['paths'] = '';
			
		$parts = explode('/', substr($this->request->server['DOCUMENT_ROOT'], 0, strrpos($this->request->server['DOCUMENT_ROOT'], '/')));	
		
		foreach ($parts as $part) {
			$path .= $part . '/';
			
			$data['paths'][] = $path;
		}
		
		rsort($data['paths']);	
			
		return $this->load->view('common/security', $data);
	}
	
	public function move() {
		$this->load->language('common/security');

		$json = array();
		
		if (!$this->request->post['path']) {
			$path = $this->request->post['path'];
		} else {
			$path = '';
		}
				
		if (!$this->request->post['directory']) {
			$directory = $this->request->post['directory'];
		} else {
			$directory = '';
		}
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}
				
		if (!$path) {
			$json['error'] = $this->language->get('error_path');
		}
		
		if (!$directory || preg_match($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}
					
		if (is_dir($path . $directory)) {
			$json['error'] = $this->language->get('error_exists');
		}
							
		if (!is_dir($path . $directory) || (str_replace('\\', '/', realpath($path . $directory)) != str_replace('\\', '/', substr($this->request->server['DOCUMENT_ROOT'], 0, strlen($path . $directory))))) {
			$json['error'] = $this->language->get('error_directory');
		}
		
		if (!$json) {
			//	$this->load->model('setting/setting');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
}