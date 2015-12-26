<?php
class ControllerUpgradeUpgrade extends Controller {
	private $error = array();

	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('upgrade');

			$this->model_upgrade->mysql();

			$this->response->redirect($this->url->link('upgrade/success'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('common/upgrade');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['language'] = $this->load->controller('common/language');

		$this->response->setOutput($this->load->view('upgrade/upgrade', $data));
	}

	public function success() {
		$data = array();

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['language'] = $this->load->controller('common/language');

		$this->response->setOutput($this->load->view('upgrade/success', $data));
	}

	private function validate() {
		if (DB_DRIVER == 'mysql') {
			if (!$connection = @mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD)) {
				$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password is correct in the config.php file!';
			} else {
				if (!mysql_select_db(DB_DATABASE, $connection)) {
					$this->error['warning'] = 'Error: Database "' . DB_DATABASE . '" does not exist!';
				}

				mysql_close($connection);
			}
		}

		return !$this->error;
	}
}