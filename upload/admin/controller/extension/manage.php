<?php
class ControllerExtensionManage extends Controller {
	public function index() {
		$this->load->language('extension/manage');
		 
		$this->document->setTitle($this->language->get('heading_title')); 

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/manage', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_upload'] = $this->language->get('button_upload');

		$zip = zip_open(DIR_DOWNLOAD . 'news-blog.zip');
			
		while ($file = zip_read($zip)) {
			
			echo zip_entry_name($file) . '<br />';
			
			zip_entry_open($zip, $zip_entry, 'r');
		}
		
		zip_close($zip);
 
			/*
		//if ($ttt) { 
			$connection = ftp_connect($this->config->get('config_ftp_host'), $this->config->get('config_ftp_port'));
	
			if ($connection) {
				$login = ftp_login($connection, $this->config->get('config_ftp_username'), $this->config->get('config_ftp_password'));
				
				if ($login) {
					
				} else {
					echo "Couldn't connect as $ftp_user\n";	
				}
			
			
				ftp_close($connection);
			} else {
				echo $this->language->get('error_ftp_connection');
			}
	
			*/
	
	

		
		//}
		


		$this->template = 'extension/manage.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function upload() {
			if (!$this->user->hasPermission('modify', 'catalog/download')) {
      		$json['error'] = $this->language->get('error_permission');
    	}		
		
		$this->language->load('sale/order');
		
		$json = array();
		
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
			
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
				$json['error'] = $this->language->get('error_filename');
			}	  	
					
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
	
		if (!isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$ext = md5(mt_rand());
				 
				$json['filename'] = $filename . '.' . $ext;
				$json['mask'] = $filename;
				
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $filename . '.' . $ext);
			}
						
			$json['success'] = $this->language->get('text_upload');
		}	
	
		$this->response->setOutput(json_encode($json));
	}			
}
?>