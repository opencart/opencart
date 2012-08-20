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

		$connection = ftp_connect($this->config->get('config_ftp_host'), $this->config->get('config_ftp_port'));

		if (!$connection) {
			exit($this->language->get('error_ftp_connection') . $this->config->get('config_ftp_host') . ':' . $this->config->get('config_ftp_port')) ;
		}
		
		$login = ftp_login($connection, $this->config->get('config_ftp_username'), $this->config->get('config_ftp_password'));
		
		if (!$login) {
			exit('Couldn\'t connect as ' . $this->config->get('config_ftp_username'));
		}
	
		$file = 'public_html/news-blog.zip';
		
		//$handle = fopen(DIR_DOWNLOAD . 'news-blog.zip', 'c+');
		
		if (ftp_put($connection, $file, DIR_DOWNLOAD . 'news-blog.zip', FTP_BINARY)) {			
			echo "Successfully uploaded $file\n";
		} else {
    		echo "There was a problem while uploading $file\n";
		}
							
	/*
		$zip = zip_open(DIR_DOWNLOAD . 'news-blog.zip');
				
		while ($handle = zip_read($zip)) {
			$filename = zip_entry_name($handle) . '<br />';
			
			echo $filename . '<br />';
				
			zip_entry_open($zip, $handle, 'r');
				
			// read entry
			//$content = zip_entry_read($file, zip_entry_filesize($file));		
				
			if (ftp_fput($connection, 'public_html/test/' . $filename, $handle, FTP_ASCII)) {			
				echo "Successfully uploaded $file\n";
			}
			
			zip_entry_close($file);
		}
		
		zip_close($zip);
		
		*/
		ftp_close($connection);

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