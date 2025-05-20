<?php
namespace Opencart\Admin\Controller\Marketplace;
/**
 * Class Promotion
 *
 * Can be loaded using $this->load->controller('marketplace/promotion');
 *
 * @package Opencart\Admin\Controller\Marketplace
 */
class Promotion extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('marketplace/promotion');

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			// Just in case there are any direct calls to methods we need to remove them to get the extension type
			$pos = strrpos($this->request->get['route'], '.');

			if ($pos !== false) {
				$route = substr($this->request->get['route'], 0, $pos);
			} else {
				$route = $this->request->get['route'];
			}

			$type = substr($route, strrpos($route, '/') + 1);
		}

		$promotion = $this->cache->get('promotion.' . $type);

		if (!$promotion) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, OPENCART_SERVER . 'index.php?route=api/recommended&type=' . $type . '&version=' . VERSION);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			curl_close($curl);

			if ($status == 200) {
				$promotion = json_decode($response, true);
			} else {
				$promotion = [];
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
			// Extension
			$this->load->model('setting/extension');

			foreach ($promotion['extensions'] as $result) {
				$extension_install_info = $this->model_setting_extension->getInstallByExtensionDownloadId($result['extension_download_id']);

				// Download
				if (!$extension_install_info) {
					$download = $this->url->link('marketplace/marketplace.download', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id'] . '&extension_download_id=' . $result['extension_download_id']);
				} else {
					$download = '';
				}

				// Install
				if ($extension_install_info && !$extension_install_info['status']) {
					$install = $this->url->link('marketplace/installer.install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_info['extension_install_id']);
				} else {
					$install = '';
				}

				// Delete
				if ($extension_install_info && !$extension_install_info['status']) {
					$delete = $this->url->link('marketplace/installer.delete', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_info['extension_install_id']);
				} else {
					$delete = '';
				}

				if (!$extension_install_info || !$extension_install_info['status']) {
					$data['extensions'][] = [
						'name'     => $result['name'],
						'href'     => $this->url->link('marketplace/marketplace.info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id']),
						'download' => $download,
						'install'  => $install,
						'delete'   => $delete
					];
				}
			}
		}

		return $this->load->view('marketplace/promotion', $data);
	}
}
