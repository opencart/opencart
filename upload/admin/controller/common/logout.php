<?php       
class ControllerCommonLogout extends Controller {   
	public function index() { 
    	$this->user->logout();
 
 		unset($this->session->data['token']);

		$this->redirect(HTTPS_SERVER . 'index.php?route=common/login');
  	}
}  
?>