<?php
namespace Opencart\Admin\Controller\Marketplace;
/**
 * Class Extension
 *
 * @package Opencart\Admin\Controller\Marketplace
 */
class Extension extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketplace/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'])
		];

		if (isset($this->request->get['type'])) {
			$data['type'] = $this->request->get['type'];
		} else {
			$data['type'] = '';
		}

		$data['categories'] = [];

		$this->load->model('setting/extension');

		$files = glob(DIR_APPLICATION . 'controller/extension/*.php');

		foreach ($files as $file) {
			$extension = basename($file, '.php');

			$this->load->language('extension/' . $extension, $extension);

			if ($this->user->hasPermission('access', 'extension/' . $extension)) {
				$extensions = $this->model_setting_extension->getPaths('%/admin/controller/' . $extension . '/%.php');

				$data['categories'][] = [
					'code' => $extension,
					'text' => $this->language->get($extension . '_heading_title') . ' (' . count($extensions) . ')',
					'href' => $this->url->link('extension/' . $extension, 'user_token=' . $this->session->data['user_token'])
				];
			}
		}

		if (isset($this->request->get['type'])) {
			$data['extension'] = $this->load->controller('extension/' . basename($this->request->get['type']) . '.getList');
		} elseif ($data['categories']) {
			$data['extension'] = $this->load->controller('extension/' . $data['categories'][0]['code'] . '.getList');
		} else {
			$data['extension'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/extension', $data));
	}
}