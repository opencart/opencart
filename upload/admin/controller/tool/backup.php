<?php 
class ControllerToolBackup extends Controller { 
	private $error = array();
	
	public function index() {		
		$this->load->language('tool/backup');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('tool/backup');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {			
			$this->model_tool_backup->import(file_get_contents(@$this->request->files['import']['tmp_name']));

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('tool/backup'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_restore'] = $this->language->get('entry_restore');
		 
		$this->data['button_import'] = $this->language->get('button_import');
		$this->data['button_export'] = $this->language->get('button_export');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		
		$this->data['error_warning'] = @$this->error['warning'];

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('tool/backup'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('tool/backup');

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);
				
		$this->data['export'] = $this->url->https('tool/backup/export');
		
		$this->id       = 'content'; 
		$this->template = 'tool/backup.tpl';
		$this->layout   = 'module/layout';
		
		$this->render();
	}
	
	public function export() {
		if ($this->validate()) {
			$this->response->addheader('Pragma', 'public');
			$this->response->addheader('Expires', '0');
			$this->response->addheader('Content-Description', 'File Transfer');
			$this->response->addheader('Content-Type', 'application/octet-stream');
			$this->response->addheader('Content-Disposition', 'attachment; filename=backup.sql');
			$this->response->addheader('Content-Transfer-Encoding', 'binary');
			
			$this->load->model('tool/backup');
			
			$this->response->setOutput($this->model_tool_backup->export());
		} else {
			return $this->forward('error/error_404', 'index');
		}
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}		
	}
}
?>