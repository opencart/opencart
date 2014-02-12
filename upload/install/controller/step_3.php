<?php
class ControllerStep3 extends Controller {
	private $error = array();

	public function index() {		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('install');

			$this->model_install->database($this->request->post);

			$output  = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_OPENCART . '\');' . "\n\n";

			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'' . HTTP_OPENCART . '\');' . "\n\n";

			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'catalog/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART. 'system/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'catalog/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'catalog/view/theme/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'system/cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'system/download/\');' . "\n";
			$output .= 'define(\'DIR_MODIFICATION\', \'' . DIR_OPENCART. 'system/modification/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', \'' . DIR_OPENCART . 'system/logs/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['db_hostname']) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['db_username']) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes($this->request->post['db_password']) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['db_database']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['db_prefix']) . '\');' . "\n";

			$file = fopen(DIR_OPENCART . 'config.php', 'w');

			fwrite($file, $output);

			fclose($file);

			$output  = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'HTTP_CATALOG\', \'' . HTTP_OPENCART . '\');' . "\n\n";

			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'' . HTTP_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'HTTPS_CATALOG\', \'' . HTTP_OPENCART . '\');' . "\n\n";

			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART . 'system/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'admin/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'admin/view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'system/cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'system/download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', \'' . DIR_OPENCART . 'system/logs/\');' . "\n";
			$output .= 'define(\'DIR_MODIFICATION\', \'' . DIR_OPENCART. 'system/modification/\');' . "\n";
			$output .= 'define(\'DIR_CATALOG\', \'' . DIR_OPENCART . 'catalog/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['db_hostname']) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['db_username']) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes($this->request->post['db_password']) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['db_database']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['db_prefix']) . '\');' . "\n";

			$file = fopen(DIR_OPENCART . 'admin/config.php', 'w');

			fwrite($file, $output);

			fclose($file);

			$this->response->redirect($this->url->link('step_4'));
		}
		
		$this->document->setTitle($this->language->get('heading_step_3'));
		
		$data['heading_step_3'] = $this->language->get('heading_step_3');
		
		$data['text_license'] = $this->language->get('text_license');
		$data['text_installation'] = $this->language->get('text_installation');
		$data['text_configuration'] = $this->language->get('text_configuration');
		$data['text_finished'] = $this->language->get('text_finished');	
		$data['text_db_connection'] = $this->language->get('text_db_connection');	
		$data['text_db_administration'] = $this->language->get('text_db_administration');
		$data['text_mysql'] = $this->language->get('text_mysql');
		$data['text_pgsql'] = $this->language->get('text_pgsql');

		$data['entry_db_driver'] = $this->language->get('entry_db_driver');
		$data['entry_db_hostname'] = $this->language->get('entry_db_hostname');
		$data['entry_db_username'] = $this->language->get('entry_db_username');
		$data['entry_db_password'] = $this->language->get('entry_db_password');
		$data['entry_db_database'] = $this->language->get('entry_db_database');
		$data['entry_db_prefix'] = $this->language->get('entry_db_prefix');
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_email'] = $this->language->get('entry_email');
		
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['db_hostname'])) {
			$data['error_db_hostname'] = $this->error['db_hostname'];
		} else {
			$data['error_db_hostname'] = '';
		}

		if (isset($this->error['db_username'])) {
			$data['error_db_username'] = $this->error['db_username'];
		} else {
			$data['error_db_username'] = '';
		}

		if (isset($this->error['db_database'])) {
			$data['error_db_database'] = $this->error['db_database'];
		} else {
			$data['error_db_database'] = '';
		}

		if (isset($this->error['db_prefix'])) {
			$data['error_db_prefix'] = $this->error['db_prefix'];
		} else {
			$data['error_db_prefix'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}		

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}	

		$data['action'] = $this->url->link('step_3');

		if (isset($this->request->post['db_driver'])) {
			$search = array('mysqli', 'mpdo.mysql', 'postgre', 'mpdo.pgsql');
			$replace = array('mysql', 'mysql', 'postgres', 'postgres');
			$data['db_driver'] = str_replace($search, $replace, $this->request->post['db_driver'] );
		} else {
			$data['db_driver'] = '';
		}

		if (isset($this->request->post['db_hostname'])) {
			$data['db_hostname'] = $this->request->post['db_hostname'];
		} else {
			$data['db_hostname'] = 'localhost';
		}

		if (isset($this->request->post['db_username'])) {
			$data['db_username'] = html_entity_decode($this->request->post['db_username']);
		} else {
			$data['db_username'] = '';
		}

		if (isset($this->request->post['db_password'])) {
			$data['db_password'] = html_entity_decode($this->request->post['db_password']);
		} else {
			$data['db_password'] = '';
		}

		if (isset($this->request->post['db_database'])) {
			$data['db_database'] = html_entity_decode($this->request->post['db_database']);
		} else {
			$data['db_database'] = '';
		}

		if (isset($this->request->post['db_prefix'])) {
			$data['db_prefix'] = html_entity_decode($this->request->post['db_prefix']);
		} else {
			$data['db_prefix'] = 'oc_';
		}

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = 'admin';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		$data['has_mysql_drivers'] = extension_loaded('mysqli') || extension_loaded('mysql');
		$data['has_pg_drivers'] = extension_loaded('pgsql');

		$data['mpdo'] = extension_loaded('pdo');
		if ($data['mpdo']) {
			$availablePDODrivers = PDO::getAvailableDrivers();
			if (!empty($availablePDODrivers)) {
				$data['has_mysql_drivers'] = $data['has_mysql_drivers'] || in_array('mysql', $availablePDODrivers);
				$data['has_pg_drivers'] = $data['has_pg_drivers'] || in_array('pgsql', $availablePDODrivers);
			} else {
				$data['mpdo'] = false;
			}
		}

		$data['back'] = $this->url->link('step_2');

		$data['footer'] = $this->load->controller('footer');
		$data['header'] = $this->load->controller('header');

		$this->response->setOutput($this->load->view('step_3.tpl', $data));		
	}

	private function validate() {
		if (!$this->request->post['db_hostname']) {
			$this->error['db_hostname'] = 'Hostname required!';
		}

		if (!$this->request->post['db_username']) {
			$this->error['db_username'] = 'Username required!';
		}

		if (!$this->request->post['db_database']) {
			$this->error['db_database'] = 'Database Name required!';
		}

		if ($this->request->post['db_prefix'] && preg_match('/[^a-z0-9_]/', $this->request->post['db_prefix'])) {
			$this->error['db_prefix'] = 'DB Prefix can only contain lowercase characters in the a-z range, 0-9 and "_"!';
		}

		$this->checkDatabaseConnection();

		if (!$this->request->post['username']) {
			$this->error['username'] = 'Username required!';
		}

		if (!$this->request->post['password']) {
			$this->error['password'] = 'Password required!';
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email'] = 'Invalid E-Mail!';
		}

		if (!is_writable(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_OPENCART . 'config.php!';
		}

		if (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_OPENCART . 'admin/config.php!';
		}	

		return !$this->error;
	}

	protected function checkDatabaseConnection()
	{
		$availablePDODrivers = array();
		if (extension_loaded('pdo')) {
			$availablePDODrivers = PDO::getAvailableDrivers();
		}

		if ($this->request->post['db_driver'] == 'mysql') {
			if (in_array('mysql', $availablePDODrivers)) {
				$this->checkPDOConnection('mysql');
			} else {
				$this->checkMySQLConnection();
			}
		} elseif ($this->request->post['db_driver'] == 'postgres') {
			if (in_array('pgsql', $availablePDODrivers)) {
				$this->checkPDOConnection('pgsql');
			} else {
				$this->checkPostreSQLConnection();
			}
		} else {
			$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password is correct!';
		}
	}

	protected function checkPDOConnection($dsnPrefix)
	{
		try {
			$this->request->post['db_driver'] = 'mpdo.' . $dsnPrefix;
			list($db, $host, $user, $pass) = $this->getConnectionParams();
			$dsn = sprintf("%s:host=%s;dbname=%s", $dsnPrefix, $host, $db);
			$conn = new PDO($dsn, $user, $pass);
		} catch (PDOException $e) {
			$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username, password and database name are correct!';
		}

		$conn = null;
	}

	protected function checkMySQLConnection()
	{
		$mysqli = extension_loaded('mysqli');
		$mysql = extension_loaded('mysql');

		if ($mysqli) {
			$driver = 'mysqli';
		} elseif ($mysql) {
			$driver = 'mysql';
		} else {
			$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password are correct!';
			return;
		}

		$this->request->post['db_driver'] = $driver;
		list($db, $host, $user, $pass) = $this->getConnectionParams();
		$functions = array(
			'connect' => sprintf("%s_connect", $driver),
			'select_db' => sprintf("%s_select_db", $driver),
			'close' => sprintf("%s_close", $driver)
		);

		$link = @call_user_func_array($functions['connect'], array($host, $user, $pass));
		if ($link) {
			if (!call_user_func_array($functions['select_db'], array($db, $link))) {
				$this->error['warning'] = 'Error: Database does not exist!';
			}
			call_user_func($functions['close'], $link);
			return;
		}

		$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password are correct!';
	}

	protected function checkPostgreSQLConnection()
	{
		if (!extension_loaded('pgsql')) {
			$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password are correct!';
		}

		$this->request->post['db_driver'] = 'postgre';
		list($db, $host, $user, $pass) = $this->getConnectionParams();
		$connectionString = sprintf("host=%s dbname=%s user=%s password=%s", $host, $db, $user, $pass);
		$link = pg_connect($connectionString);

		if (!$link) {
			$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username, password and database name are correct!';
		}

		pg_close($link);
	}

	protected function getConnectionParams()
	{
		return array(
			$this->request->post['db_database'],
			$this->request->post['db_hostname'],
			$this->request->post['db_username'],
			$this->request->post['db_password']
		);
	}
}
