<?php       
class ControllerCommonLogout extends Controller {   
	public function index() { 
    	$this->user->logout();
 
		$this->redirect(HTTPS_SERVER . 'index.php?route=common/login');
  	}
}  
?>