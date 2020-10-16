<?php
namespace Opencart\Application\Controller\Marketplace;
class Promotion extends \Opencart\System\Engine\Controller {
	public function index() {
		$promotion = $this->cache->get('promotion');

		if (!$promotion) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, OPENCART_SERVER . 'index.php?route=api/recommended&type=' . substr($this->request->get['route'], strrpos($this->request->get['route'], '/') + 1));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			curl_close($curl);

			if ($response) {
				$promotion = $response;
			} else {
				$promotion = '';
			}

			$this->cache->set('promotion', $promotion, 3600 * 24);
		}

		if ($promotion) {
			$data['banner'] = $promotion['banner'];

			$data['extensions'] = [];

			if ($promotion['extensions']) {
				foreach ($promotion['extensions'] as $result) {
					$data['extensions'][] = [
						'name'     => $result['name'],
						'download' => $this->url->link('marketplace/marketplace/download', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id'] . '&extension_download_id=' . $result['extension_download_id'])
					];
				}
			}
		}

		return $this->load->view('marketplace/promotion', $data);
	}
}