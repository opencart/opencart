<?php 
class ControllerToolErrorLog extends Controller { 
	public function index() {		
		$this->language->load('tool/error_log');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['button_clear'] = $this->language->get('button_clear');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL')
   		);
		
		$this->data['clear'] = $this->url->link('tool/error_log/clear', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['error_warning'] = '';
		$this->data['log'] = '';
		
		$file = DIR_LOGS . $this->config->get('config_error_filename');
		
		if (file_exists($file)) {
			$size = filesize($file);
			
			if ($size >= 5242880){
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}
				
				$this->data['error_warning'] = sprintf($this->language->get('error_warning'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			} else {
				$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
			}
		}		

		$this->template = 'tool/error_log.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function clear() {
		$this->language->load('tool/error_log');
		
		$file = DIR_LOGS . $this->config->get('config_error_filename');
		
		$handle = fopen($file, 'w+'); 
				
		fclose($handle); 			
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$this->redirect($this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL'));		
	}
}
?>