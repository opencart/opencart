<?php
class ControllerAccountDownload extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/download', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
         		
		$this->language->load('account/download');

		$this->document->setTitle($this->language->get('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_downloads'),
			'href'      => $this->url->link('account/download', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);
				
		$this->load->model('account/download');

		$download_total = $this->model_account_download->getTotalDownloads();
		
		if ($download_total) {
			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_order'] = $this->language->get('text_order');
			$this->data['text_date_added'] = $this->language->get('text_date_added');
			$this->data['text_name'] = $this->language->get('text_name');
			$this->data['text_remaining'] = $this->language->get('text_remaining');
			$this->data['text_size'] = $this->language->get('text_size');
			
			$this->data['button_download'] = $this->language->get('button_download');
			$this->data['button_continue'] = $this->language->get('button_continue');

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}			
	
			$this->data['downloads'] = array();
			
			$results = $this->model_account_download->getDownloads(($page - 1) * $this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit'));
			
			foreach ($results as $result) {
				if (file_exists(DIR_DOWNLOAD . $result['filename'])) {
					$size = filesize(DIR_DOWNLOAD . $result['filename']);

					$i = 0;

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

					$this->data['downloads'][] = array(
						'order_id'   => $result['order_id'],
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
						'name'       => $result['name'],
						'remaining'  => $result['remaining'],
						'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
						'href'       => $this->url->link('account/download/download', 'order_download_id=' . $result['order_download_id'], 'SSL')
					);
				}
			}
		
			$pagination = new Pagination();
			$pagination->total = $download_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_catalog_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('account/download', 'page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['continue'] = $this->url->link('account/account', '', 'SSL');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/download.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/download.tpl';
			} else {
				$this->template = 'default/template/account/download.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'		
			);
							
			$this->response->setOutput($this->render());				
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_error'] = $this->language->get('text_empty');

			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['continue'] = $this->url->link('account/account', '', 'SSL');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'		
			);
										
			$this->response->setOutput($this->render());
		}
	}

	public function download() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/download', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/download');
		
		if (isset($this->request->get['order_download_id'])) {
			$order_download_id = $this->request->get['order_download_id'];
		} else {
			$order_download_id = 0;
		}
		
		$download_info = $this->model_account_download->getDownload($order_download_id);
		
		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));
					
					if (ob_get_level()) ob_end_clean();
					
					readfile($file, 'rb');
					
					$this->model_account_download->updateRemaining($this->request->get['order_download_id']);
					
					exit;
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->redirect($this->url->link('account/download', '', 'SSL'));
		}
	}
}
?>