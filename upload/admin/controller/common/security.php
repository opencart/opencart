<?php
class ControllerCommonSecurity extends Controller {
	public function index() {
		$this->load->language('common/security');
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$args = array(
			DIR_SYSTEM . 'storage/',
			str_replace('\\', '/', realpath(DIR_SYSTEM . '../../')) . '/',
			'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');',
			'define(\'DIR_STORAGE\', \'' . realpath(DIR_SYSTEM . '../../') . '/storage/\');',
			'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');',
			'define(\'DIR_STORAGE\', \'' . realpath(DIR_SYSTEM . '../../') . '/storage/\');'
		);
		
		$data['text_instruction'] = vsprintf($this->language->get('text_instruction'), $args);

		$data['path'] = substr($this->request->server['DOCUMENT_ROOT'], 0, strrpos($this->request->server['DOCUMENT_ROOT'], '/')) . '/';
			
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