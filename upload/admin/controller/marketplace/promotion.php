<?php
namespace Opencart\Application\Controller\Marketplace;
class Promotion extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('marketplace/promotion');

		$promotion = $this->cache->get('promotion');

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			$type = substr($this->request->get['route'], strrpos($this->request->get['route'], '/') + 1);
		}

		$promotion = $this->cache->get('promotion.' . $type);

		if (!$promotion) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, OPENCART_SERVER . 'index.php?route=api/recommended&type=' . $type . '&version=' . VERSION);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			curl_close($curl);

			if ($response) {
				$promotion = json_decode($response, true);
			} else {
				$promotion = '';
			}

			$this->cache->set('promotion.' . $type, $promotion, 3600 * 24);
		}

		if (isset($promotion['banner'])) {
			$data['banner'] = $promotion['banner'];
		} else {
			$data['banner'] = '';
		}

		$data['extensions'] = [];

		if (isset($promotion['extensions'])) {
			$this->load->model('setting/extension');

			foreach ($promotion['extensions'] as $result) {
				$extension_download_info = $this->model_setting_extension->getInstallByExtensionDownloadId($result['extension_download_id']);

				if (!$extension_download_info) {
					$data['extensions'][] = [
						'name' => $result['name'],
						'href' => $this->url->link('marketplace/marketplace|info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id']),
						'download' => $this->url->link('marketplace/marketplace|download', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id'] . '&extension_download_id=' . $result['extension_download_id'])
					];
				}
			}
		}

		return $this->load->view('marketplace/promotion', $data);
	}
}