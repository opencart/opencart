<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleCachemanager extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/cachemanager');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		
		$data['column_description'] = $this->language->get('column_description');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['image_description'] = $this->language->get('image_description');
		$data['system_description'] = $this->language->get('system_description');
		
		$data['button_clearallcache'] = $this->language->get('button_clearallcache');
		$data['button_clearcache'] = $this->language->get('button_clearcache');
		$data['button_clearsystemcache'] = $this->language->get('button_clearsystemcache');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/cachemanager', 'token=' . $this->session->data['token'], true)
		);

		$data['clearallcache'] = (HTTPS_SERVER . 'index.php?route=extension/module/cachemanager/clearallcache&token=' . $this->session->data['token']);
		$data['clearcache'] = (HTTPS_SERVER . 'index.php?route=extension/module/cachemanager/clearcache&token=' . $this->session->data['token']);
		$data['clearsystemcache'] = (HTTPS_SERVER . 'index.php?route=extension/module/cachemanager/clearsystemcache&token=' . $this->session->data['token']);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		$data['modules'] = array();
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/cachemanager', $data));
	}

	public function clearsystemcache() {
		$this->load->language('extension/module/cachemanager');

		$files = glob(DIR_CACHE . '*');
		if (!empty($files)) {
			foreach($files as $file){
				$this->deldir($file);
			}
		}

		$this->session->data['success'] = $this->language->get('text_success_system');

		$this->response->redirect($this->url->link('extension/module/cachemanager', 'token=' . $this->session->data['token'], true));
	}

	public function clearcache() {
		$this->load->language('extension/module/cachemanager');

		$imgfiles = glob(DIR_IMAGE . 'cache/*');
		if (!empty($imgfiles)) {
			foreach($imgfiles as $imgfile){
				$this->deldir($imgfile);
			}
		}

		$this->session->data['success'] = $this->language->get('text_success_img');

		$this->response->redirect($this->url->link('extension/module/cachemanager', 'token=' . $this->session->data['token'], true));
	}

	public function clearallcache() {
		$this->load->language('extension/module/cachemanager');

		$imgfiles = glob(DIR_IMAGE . 'cache/*');
		if (!empty($imgfiles)) {
			foreach($imgfiles as $imgfile){
				$this->deldir($imgfile);
			}
		}
		$files = glob(DIR_CACHE . '*');
		if (!empty($files)) {
			foreach($files as $file){
				$this->deldir($file);
			}
		}

		$this->session->data['success'] = $this->language->get('text_success');

		$this->response->redirect($this->url->link('extension/module/cachemanager', 'token=' . $this->session->data['token'], true));
	}

	public function deldir($dirname){
		if(file_exists($dirname)) {
			if(is_dir($dirname)){
				$dir=opendir($dirname);
				while(($filename=readdir($dir)) !== false){
					if($filename!="." && $filename!=".."){
						$file=$dirname."/".$filename;
						$this->deldir($file); 
					}
				}
				closedir($dir);
				rmdir($dirname);
			} else {
				@unlink($dirname);
			}
		}
	}
}
