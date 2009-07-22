<?php
class ControllerStep2 extends Controller {
	private $error = array();
	
	public function index() {		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('install');
			
			$this->model_install->mysql($this->request->post);
			
			$output  = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_OPENCART . '\');' . "\n";
			$output .= 'define(\'HTTP_IMAGE\', \'' . HTTP_OPENCART . 'image/\');' . "\n\n";

			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'\');' . "\n";
			$output .= 'define(\'HTTPS_IMAGE\', \'\');' . "\n\n";

			$output .= '// DIR' . "\n";
		
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'catalog/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART. 'system/\');' . "\n";
			$output .= 'define(\'DIR_DATABASE\', \'' . DIR_OPENCART . 'system/database/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'catalog/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'catalog/view/theme/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'download/\');' . "\n\n";
		
			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'mysql\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . $this->request->post['db_host'] . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . $this->request->post['db_user'] . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . $this->request->post['db_password'] . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . $this->request->post['db_name'] . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . $this->request->post['db_prefix'] . '\');' . "\n";
			$output .= '?>';				
		
			$file = fopen(DIR_OPENCART . 'config.php', 'w');
		
			fwrite($file, $output);

			fclose($file);
	 
			$output  = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'HTTP_CATALOG\', \'' . HTTP_OPENCART . '\');' . "\n";
			$output .= 'define(\'HTTP_IMAGE\', \'' . HTTP_OPENCART . 'image/\');' . "\n\n";

			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'\');' . "\n";
			$output .= 'define(\'HTTPS_IMAGE\', \'\');' . "\n\n";

			$output .= '// DIR' . "\n";
		
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART . 'system/\');' . "\n";
			$output .= 'define(\'DIR_DATABASE\', \'' . DIR_OPENCART . 'system/database/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'admin/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'admin/view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'download/\');' . "\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'mysql\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . $this->request->post['db_host'] . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . $this->request->post['db_user'] . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . $this->request->post['db_password'] . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . $this->request->post['db_name'] . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . $this->request->post['db_prefix'] . '\');' . "\n";
			$output .= '?>';	

			$file = fopen(DIR_OPENCART . 'admin/config.php', 'w');
		
			fwrite($file, $output);

			fclose($file);
			
			$this->redirect($this->url->http('step_3'));
		}
		
		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_db_host'] = @$this->error['db_host'];
		$this->data['error_db_user'] = @$this->error['db_user'];
		$this->data['error_db_name'] = @$this->error['db_name'];
		$this->data['error_username'] = @$this->error['username'];
		$this->data['error_password'] = @$this->error['password'];
		$this->data['error_email'] = @$this->error['email'];
		
		$this->data['action'] = $this->url->http('step_2');
		
		if (isset($this->request->post['db_host'])) {
			$this->data['db_host'] = $this->request->post['db_host'];
		} else {
			$this->data['db_host'] = 'localhost';
		}
		
		$this->data['db_user'] = @$this->request->post['db_user'];
		$this->data['db_password'] = @$this->request->post['db_password'];
		$this->data['db_name'] = @$this->request->post['db_name'];
		$this->data['db_prefix'] = @$this->request->post['db_prefix'];
		
		if (isset($this->request->post['username'])) {
			$this->data['username'] = $this->request->post['username'];
		} else {
			$this->data['username'] = 'admin';
		}
		
		$this->data['password'] = @$this->request->post['password'];
		$this->data['email'] = @$this->request->post['email'];
		
		$this->id       = 'content';
		$this->template = 'step_2.tpl';
		$this->layout   = 'layout';
		$this->render();		
	}
	
	private function validate() {
		if (!$this->request->post['db_host']) {
			$this->error['db_host'] = 'Host required!';
		}

		if (!$this->request->post['db_user']) {
			$this->error['db_user'] = 'User required!';
		}

		if (!$this->request->post['db_name']) {
			$this->error['db_name'] = 'Database Name required!';
		}
		
		if (!$this->request->post['username']) {
			$this->error['username'] = 'Username required!';
		}

		if (!$this->request->post['password']) {
			$this->error['password'] = 'Password required!';
		}

		if (!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$', @$this->request->post['email'])) {
			$this->error['email'] = 'Invalid E-Mail!';
		}

		if (!$connection = @mysql_connect($this->request->post['db_host'], $this->request->post['db_user'], $this->request->post['db_password'])) {
			$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password is correct!';
		} else {
			if (!@mysql_select_db($this->request->post['db_name'], $connection)) {
				$this->error['warning'] = 'Error: Database does not exist!';
			}
			
			mysql_close($connection);
		}
		
		if (!is_writable(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_OPENCART . 'config.php!';
		}
	
		if (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_OPENCART . 'admin/config.php!';
		}	
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}		
	}
}
?>