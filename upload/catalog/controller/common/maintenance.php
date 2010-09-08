<?php
class ControllerCommonMaintenance extends Controller {
	public function index() {

        $this->load->language('common/maintenance');
        
        $this->document->title = $this->language->get('heading_title');
        
        $this->data['charset'] = $this->language->get('charset');
        $this->data['language'] = $this->language->get('code');
        $this->data['direction'] = $this->language->get('direction');
        
        $this->data['title'] = $this->language->get('heading_title');
                
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href'      => (HTTP_SERVER . 'index.php?route=common/maintenance'),
            'text'      => $this->language->get('text_maintenance'),
            'separator' => FALSE
        ); 
        
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;
        $this->data['message'] = $this->language->get('text_message');
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/maintenance.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/maintenance.tpl';
        } else {
            $this->template = 'default/template/common/maintenance.tpl';
        }
        
        $this->children = array(
            'common/header',
            'common/footer'
        );
        
        $this->response->setOutput($this->render(TRUE));

    }
    
    public function check() {
        if ($this->config->get('config_maintenance')) {

			// Require to be logged in unless it is a payment callback
			$ignore = array(
				'payment',
			);

			$match = false;
			if (isset($this->request->get['route'])) {
				foreach ($ignore as $i) {
					if (strpos($this->request->get['route'], $i) !== false) {
						$match = true;
						break;
					}
				}
			}

            // Show site if logged in as admin
			$this->load->library('user');
			$this->registry->set('user', new User($this->registry));

            if (!$this->user->isLogged() && !$match) {
                return $this->forward('common/maintenance');
            }
        }
    }
}
?>