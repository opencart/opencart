<?php 
class ControllerExtensionModuleNewsletter Extends Controller {
    public function index() {
        $this->load->language('extension/module/newsletter');
        
        return $this->load->view('extension/module/newsletter');
        
    }
    
    public function subscribe() {
        $json = array();
        
        if (isset($this->request->get['email'])) {
            $email = $this->request->get['email'];
        } else {
            $email = '';
        }
        
        if ($email) {
            $this->load->language('extension/module/newsletter');
            
            if ((utf8_strlen($email) > 96) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    			$json['error'] = $this->language->get('error_email');
    		}
            
            $this->load->model('extension/module/newsletter');
            
            $newsletter_info = $this->model_extension_module_newsletter->getTotalNewsletterByEmail($email);
            
            if ($newsletter_info) {
                $json['error'] = $this->language->get('error_exist');
            }

            if (!$json) {
                if (isset($this->request->server['HTTP_X_REAL_IP'])) {
    				$ip = $this->request->server['HTTP_X_REAL_IP'];
    			} else if (isset($this->request->server['REMOTE_ADDR'])) {
    				$ip = $this->request->server['REMOTE_ADDR'];
    			} else {
    				$ip = '';
    			}
                
                $newsletter = array(
                    'email'   => $email,
                    'ip'      => $ip,
                    'country' => ''
                );
                
                $result = $this->model_extension_module_newsletter->addNewsletter($newsletter);
                
                if ($result) {
                    $json['success'] = $this->language->get('text_success');
                }            
            }
        }
        
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }
}