<?php
class ControllerEbayOpenbay extends Controller {
	public function inbound() {
		$encrypted      = $this->request->post;
		$secret         = $this->config->get('ebay_secret');
		$active         = $this->config->get('ebay_status');
		$s1             = $this->config->get('ebay_string1');
		$s2             = $this->config->get('ebay_string2');

		$this->load->model('openbay/ebay_openbay');
		$this->load->model('openbay/ebay_product');
		$this->load->model('openbay/ebay_order');

		if(empty($encrypted)) {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode(array('msg' => 'error 002')));
		} else {
			$token  = $this->openbay->ebay->pbkdf2($s1, $s2, 1000, 32);
			$data   = $this->openbay->ebay->decrypt($encrypted['data'], $token, true);

			if($secret == $data['secret'] && $active == 1) {
				if($data['action'] == 'ItemUnsold') {
					$this->openbay->ebay->log('Action: Unsold Item');
					$product_id = $this->openbay->ebay->getProductId($data['itemId']);

					if($product_id != false) {
						$this->openbay->ebay->log('eBay item link found with internal product');
						$rules = $this->model_openbay_ebay_product->getRelistRule($data['itemId']);

						if(!empty($rules)) {
							$this->openbay->ebay->log('Item is due to be automatically relisted');
							$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_listing_pending` SET `ebay_item_id` = '" . $this->db->escape($data['itemId']) . "', `product_id` = '" . (int)$product_id . "', `key` = '" . $this->db->escape($data['key']) . "'");
							$this->openbay->ebay->removeItemByItemId($data['itemId']);
						} else {
							$this->openbay->ebay->log('No automation rule set');
							$this->openbay->ebay->removeItemByItemId($data['itemId']);
						}
					}

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode(array('msg' => 'ok')));
				}

				if($data['action'] == 'ItemListed') {
					$this->openbay->ebay->log('Action: Listed Item');

					$product_id = $this->openbay->ebay->getProductIdFromKey($data['key']);

					if($product_id != false) {
						$this->openbay->ebay->createLink($product_id, $data['itemId'], '');
						$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_listing_pending` WHERE `key` = '" . $this->db->escape($data['key']) . "' LIMIT 1");
						$this->openbay->ebay->log('A link was found with product id: '.$product_id.', item id: '.$data['itemId'].' and key: '.$data['key']);
					} else {
						$this->openbay->ebay->log('No link found to previous item');
					}

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode(array('msg' => 'ok')));
				}

				if($data['action'] == 'newOrder') {
					$this->openbay->ebay->log('Action: newOrder / Order data from polling');
					$this->model_openbay_ebay_openbay->importOrders($data['data2']);

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode(array('msg' => 'ok')));
				}

				if($data['action'] == 'notificationOrder') {
					$this->openbay->ebay->log('Action: notificationOrder / Order data from notification');
					$this->model_openbay_ebay_openbay->importOrders($data['data']);

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode(array('msg' => 'ok')));
				}

				if($data['action'] == 'outputLog') {
					$this->model_openbay_ebay_openbay->outputLog();
				}

				if($data['action'] == 'updateLog') {
					$this->model_openbay_ebay_openbay->updateLog();
				}
			} else {
				$this->openbay->ebay->log('Secret incorrect or module not active.');

				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode(array('msg' => 'error 001')));
			}
		}
	}

	public function importItems() {
		set_time_limit(0);

		$data   = $this->request->post;
		$secret = $this->config->get('ebay_secret');
		$active = $this->config->get('ebay_status');

		$this->response->addHeader('Content-Type: application/json');

		if(isset($data['secret']) && $secret == $data['secret'] && $active == 1 && isset($data['data'])) {
			$this->load->model('openbay/ebay_openbay');
			$this->load->model('openbay/ebay_product');
			$this->model_openbay_ebay_product->importItems($data);
			$this->response->setOutput(json_encode(array('msg' => 'ok', 'error' => false)));
		} else {
			$this->response->setOutput(json_encode(array('msg' => 'Auth failed', 'error' => true)));
		}
	}

	public function ping() {
		$postSize   = ini_get('post_max_size');
		$postSize   = (int)str_replace(array('M','m','Mb','MB'), '', $postSize);
		$version    = (int)$this->config->get('openbay_version');

		$this->response->addHeader('Cache-Control: no-cache, must-revalidate');
		$this->response->addHeader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		$this->response->addHeader('Content-type: application/json; charset=utf-8');

		$this->response->setOutput(json_encode(array('msg' => 'ok', 'max_post' => $postSize, 'version' => $version)));
	}

	public function autoSetup() {
		set_time_limit(0);
		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('ebay');

		$this->response->addHeader('Cache-Control: no-cache, must-revalidate');
		$this->response->addHeader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		$this->response->addHeader('Content-type: application/json; charset=utf-8');

		if(
			(isset($settings['ebay_token']) && !empty($settings['ebay_token'])) ||
			(isset($settings['ebay_secret']) && !empty($settings['ebay_secret'])) ||
			(isset($settings['ebay_string1']) && !empty($settings['ebay_string1'])) ||
			(isset($settings['ebay_string2']) && !empty($settings['ebay_string2'])) ||
			!isset($this->request->post['token']) ||
			!isset($this->request->post['secret']) ||
			!isset($this->request->post['s1']) ||
			!isset($this->request->post['s2'])
		) {
			$this->response->setOutput(json_encode(array('msg' => 'fail', 'reason' => 'Tokens are already setup or data missing')));
		} else {
			$settings['ebay_token']   = $this->request->post['token'];
			$settings['ebay_secret']  = $this->request->post['secret'];
			$settings['ebay_string1'] = $this->request->post['s1'];
			$settings['ebay_string2'] = $this->request->post['s2'];
			$this->openbay->ebay->editSetting('ebay', $settings);

			$this->response->setOutput(json_encode(array('msg' => 'ok', 'reason' => 'Auto setup has been completed','version' => (int)$this->config->get('openbay_version'))));
		}
	}

	public function autoSync() {
		set_time_limit(0);

		$this->response->addHeader('Content-Type: application/json');

		if($this->request->post['process'] == 'categories') {
			$this->response->setOutput(json_encode($this->openbay->ebay->loadCategories()));
		}elseif($this->request->post['process'] == 'settings') {
			$this->response->setOutput(json_encode($this->openbay->ebay->loadSettings()));
		}elseif($this->request->post['process'] == 'store') {
			$this->response->setOutput(json_encode($this->openbay->ebay->loadSellerStore()));
		}
	}

	public function testfile() {
		/*
		// Commented out by default, only used for debug during support request
		$post = $this->request->post;
		$postSize   = ini_get('post_max_size');
		$postSize   = (int)str_replace(array('M','m','Mb','MB'), '', $postSize);

		$response = array();
		$response['php_postsize'] = $postSize;
		$response['string1_length'] = strlen($post['string1']);
		$response['string1_text'] = $post['string1'];
		$response['string2_length'] = isset($post['string2']) ? strlen($post['string2']) : '';

		echo json_encode($response);
		*/
	}
}