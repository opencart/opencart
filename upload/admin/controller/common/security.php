<?php
class ControllerCommonSecurity extends Controller {
	public function index() {
		$this->load->language('common/security');
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$args = array(
			DIR_SYSTEM . 'storage/',
			str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '../')) . '/',
			'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');',
			'define(\'DIR_STORAGE\', \'' . realpath($this->request->server['DOCUMENT_ROOT'] . '../') . '/storage/\');',
			'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');',
			'define(\'DIR_STORAGE\', \'' . realpath($this->request->server['DOCUMENT_ROOT'] . '../') . '/storage/\');'
		);
		
		$data['text_instruction'] = vsprintf($this->language->get('text_instruction'), $args);

		//$data['path'] = substr($this->request->server['DOCUMENT_ROOT'], 0, strrpos($this->request->server['DOCUMENT_ROOT'], '/')) . '/';
			
		$data['paths'] = array();
		
		$path = '';
			
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

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/setting');






			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
}
