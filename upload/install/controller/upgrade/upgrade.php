<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_upgrade'] = $this->language->get('text_upgrade');
		$data['text_server'] = $this->language->get('text_server');
		$data['text_steps'] = $this->language->get('text_steps');
		$data['text_error'] = $this->language->get('text_error');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_refresh'] = $this->language->get('text_refresh');
		$data['text_admin'] = $this->language->get('text_admin');
		$data['text_user'] = $this->language->get('text_user');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_progress'] = $this->language->get('entry_progress');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['server'] = HTTP_SERVER;
		$data['total'] = count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php'));

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view('upgrade/upgrade', $data));
	}

	public function start() {
		$this->load->language('upgrade/upgrade');

		$json = [];

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (isset($this->request->get['admin'])) {
			$admin = basename($this->request->get['admin']);
		} else {
			$admin = 'admin';
		}

		$directory = DIR_OPENCART . $admin . '/';

		if (!is_dir($directory)) {
			$json['error'] = sprintf($this->language->get('error_admin'), $directory);
		}

		$file = $directory . 'config.php';

		if (!is_file($file)) {
			$json['error'] = sprintf($this->language->get('error_admin'), $file);
		}


		if (!$json) {

			$directory_old = DIR_OPENCART . 'admin/';

			if ($directory_old != $directory && !is_dir($directory_old)) {


				rename($file, $directory_old . 'config.php');

			}


			if ($directory_old != $directory && !is_dir($directory_old)) {


				rename($file, $directory_old . 'config.php');

			}



			$file_old = DIR_OPENCART . $admin . '/config.php';

			if (!is_file($file)) {
				$json['error'] = sprintf($this->language->get('error_admin'), $file);
			}





			$json['text'] = sprintf($this->language->get('text_progress'), 1, 1, 9);

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_2', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}