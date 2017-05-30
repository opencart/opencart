<?php
class ControllerExtensionOpenbayEbay extends Controller {
    public function inbound() {
		$post_data      = $this->request->post;
		$secret         = $this->config->get('ebay_secret');
		$active         = $this->config->get('ebay_status');

		$this->load->model('extension/openbay/ebay_product');
		$this->load->model('extension/openbay/ebay_order');

		if(empty($post_data)) {
            http_response_code(400);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode(array('error' => 'Bad request')));
			$this->response->output();
			exit();
		} else {
            $data = $this->openbay->decrypt($post_data['data'], $this->openbay->ebay->getEncryptionKey(), $this->openbay->ebay->getEncryptionIv());

			if(isset($data['secret']) && $secret == $data['secret'] && $active == 1) {
				if($data['action'] == 'ItemUnsold') {
					$this->openbay->ebay->log('Action: Unsold Item');
					$product_id = $this->openbay->ebay->getProductId($data['itemId']);

					if($product_id != false) {
						$this->openbay->ebay->log('eBay item link found with internal product');
						$rules = $this->model_extension_openbay_ebay_product->getRelistRule($data['itemId']);

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
						$this->openbay->ebay->log('A link was found with product id: ' . $product_id . ', item id: ' . $data['itemId'] . ' and key: ' . $data['key']);
					} else {
						$this->openbay->ebay->log('No link found to previous item');
					}

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode(array('msg' => 'ok')));
				}

				if($data['action'] == 'newOrder') {
					$this->openbay->ebay->log('Action: newOrder / Order data from polling');
					$this->model_extension_openbay_ebay_order->importOrders($data['data2']);

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode(array('msg' => 'ok')));
				}

				if($data['action'] == 'notificationOrder') {
					$this->openbay->ebay->log('Action: notificationOrder / Order data from notification');
					$this->model_extension_openbay_ebay_order->importOrders($data['data']);

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode(array('msg' => 'ok')));
				}

				if($data['action'] == 'outputLog') {
				    if (file_exists(DIR_LOGS . "ebaylog.log")) {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header('Content-Type: application/force-download');
                        header('Content-Length: ' . filesize(DIR_LOGS . "ebaylog.log"));
                        header('Content-Disposition: attachment; filename="ebaylog.log"');
                        header('Content-Transfer-Encoding: binary');
                        header('Connection: close');
                        readfile(DIR_LOGS . "ebaylog.log");
                        exit();
                    } else {
					    $this->openbay->ebay->log('Action: outputLog / No log file found');

                        http_response_code(404);
                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode(array('error' => 'Log file not found')));
                    }
				}

				if($data['action'] == 'config') {
					$this->openbay->ebay->log('Action: config / Check store php limits for import options');

                    $post_size   = ini_get('post_max_size');
                    $post_size   = (int)str_replace(array('M','m','Mb','MB'), '', $post_size);
                    $version     = (int)$this->config->get('feed_openbaypro_version');

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode(array('msg' => 'ok', 'max_post' => $post_size, 'version' => $version)));
				}
			} else {
				$this->openbay->ebay->log('Secret incorrect or module not active.');

                http_response_code(401);
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode(array('error' => 'Authorisation failed or module inactive')));
                $this->response->output();
                exit();
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
			$this->load->model('extension/openbay/ebay_order');
			$this->load->model('extension/openbay/ebay_product');
			$this->model_extension_openbay_ebay_product->importItems($data);
			$this->response->setOutput(json_encode(array('msg' => 'ok', 'error' => false)));
		} else {
			$this->response->setOutput(json_encode(array('msg' => 'Auth failed', 'error' => true)));
		}
	}

	public function setup() {
		@set_time_limit(0);

		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('ebay');

		$this->response->addHeader('Cache-Control: no-cache, must-revalidate');
		$this->response->addHeader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		$this->response->addHeader('Content-type: application/json; charset=utf-8');

		if(
			(isset($settings['ebay_token']) && !empty($settings['ebay_token'])) ||
			(isset($settings['ebay_secret']) && !empty($settings['ebay_secret'])) ||
			(isset($settings['ebay_encryption_key']) && !empty($settings['ebay_encryption_key'])) ||
			(isset($settings['ebay_encryption_iv']) && !empty($settings['ebay_encryption_iv'])) ||
			!isset($this->request->post['token']) ||
			!isset($this->request->post['secret']) ||
			!isset($this->request->post['encryption_key']) ||
			!isset($this->request->post['encryption_iv'])
		) {
			$this->response->setOutput(json_encode(array('msg' => 'fail', 'reason' => 'Tokens are already setup or data missing')));
		} else {
			$settings['ebay_token']   = $this->request->post['token'];
			$settings['ebay_secret']  = $this->request->post['secret'];
			$settings['ebay_encryption_key'] = $this->request->post['encryption_key'];
			$settings['ebay_encryption_iv'] = $this->request->post['encryption_iv'];

			$this->openbay->ebay->editSetting('ebay', $settings);

			$this->response->setOutput(json_encode(array('msg' => 'ok', 'reason' => 'Auto setup has completed','version' => (int)$this->config->get('feed_openbaypro_version'))));
		}
	}

	public function sync() {
		@set_time_limit(0);

		$this->response->addHeader('Content-Type: application/json');

		if($this->request->post['process'] == 'categories') {
			$this->response->setOutput(json_encode($this->openbay->ebay->updateCategories()));
		}elseif($this->request->post['process'] == 'settings') {
			$this->response->setOutput(json_encode($this->openbay->ebay->updateSettings()));
		}elseif($this->request->post['process'] == 'store') {
			$this->response->setOutput(json_encode($this->openbay->ebay->updateStore()));
		}
	}

	public function eventAddOrder($route, $data) {

	}

	public function eventAddOrderHistory($route, $data) {
		$this->openbay->ebay->log('eventAddOrderHistory Event fired: ' . $route);

		if (isset($data[0]) && !empty($data[0])) {
			$this->load->model('extension/openbay/ebay_order');

			$this->openbay->ebay->log('Order ID: ' . (int)$data[0]);

			$this->model_extension_openbay_ebay_order->addOrderHistory((int)$data[0]);
		}
	}
}
