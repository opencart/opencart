<?php
class ControllerCommonFileManager extends Controller {
	private $error = array();
	
	public function index() {
		$this->language->load('common/filemanager');
		
		$this->load->model('catalog/image');
		
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
						
		$this->load->model('tool/image');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$this->data['images'] = array();
		
		$data = array(
			'filter_name' => $filter_name, 
			'sort'        => 'id.name',
			'order'       => 'ASC',
			'start'       => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'       => $this->config->get('config_admin_limit')
		);
		
		$image_total = $this->model_catalog_image->getTotalImages();
 
		$results = $this->model_catalog_image->getImages($data);
 
    	foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['filename'])) {
				$image = $this->model_tool_image->resize($result['filename'], 100, 100);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
									
			$this->data['images'][] = array(
				'image_id' => $result['image_id'],
				'image'    => $image,
				'name'     => $result['name'],
			);
		}	
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_search'] = $this->language->get('text_search');
		
		$this->data['token'] = $this->session->data['token'];
		
		$url = '';
						
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
					
		$pagination = new Pagination();
		$pagination->total = $image_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->url = $this->url->link('common/filemanager', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'common/filemanager.tpl';
		
		$this->response->setOutput($this->render());
	}	
} 
?>