<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Module;
class Store extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$status = true;

		if ($this->config->get('module_store_admin')) {
			$this->user = new \Opencart\System\Library\Cart\User($this->registry);

			$status = $this->user->isLogged();
		}

		if ($status) {
			$this->load->language('extension/opencart/module/store');

			$data['store_id'] = $this->config->get('config_store_id');

			$data['stores'] = [];

			$data['stores'][] = [
				'store_id' => 0,
				'name'     => $this->language->get('text_default'),
				'url'      => HTTP_SERVER . 'index.php?route=common/home&session_id=' . $this->session->getId()
			];

			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$data['stores'][] = [
					'store_id' => $result['store_id'],
					'name'     => $result['name'],
					'url'      => $result['url'] . 'index.php?route=common/home&session_id=' . $this->session->getId()
				];
			}

			return $this->load->view('extension/opencart/module/store', $data);
		}
	}
}
