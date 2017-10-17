<?php
class ControllerExtensionPaymentWebmoneyWMZ extends Controller {
	private $error = array();
  const MAX_LAST_LOG_LINES = 500;
  const FILE_NAME_LOG = 'webmoney_wmz.log';
	
	public function index() {
		$this->load->language('extension/payment/webmoney_wmz');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payment_webmoney_wmz', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$data['help_log_file'] = sprintf($this->language->get('help_log_file'), self::MAX_LAST_LOG_LINES);
		$data['help_log'] = sprintf($this->language->get('help_log'), self::FILE_NAME_LOG);
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['merch_r'])) {
			$data['error_merch_r'] = $this->error['merch_r'];
		} else {
			$data['error_merch_r'] = '';
		}
		
		if (isset($this->error['secret_key'])) {
			$data['error_secret_key'] = $this->error['secret_key'];
		} else {
			$data['error_secret_key'] = '';
		}

		if (isset($this->error['secret_key_x20'])) {
			$data['error_secret_key_x20'] = $this->error['secret_key_x20'];
		} else {
			$data['error_secret_key_x20'] = '';
		}
		
   	$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
   	);

   	$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('marketplace/extension', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL')
   	);

   	$data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/webmoney_wmz', 'user_token=' . $this->session->data['user_token'], 'SSL')
   	);
				
		$data['action'] = $this->url->link('extension/payment/webmoney_wmz', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('marketplace/extension', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL');
		$data['clear_log'] = str_replace('&amp;', '&', $this->url->link('extension/payment/webmoney_wmz/clearLog', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		$data['log_lines'] = $this->readLastLines(DIR_LOGS . self::FILE_NAME_LOG, self::MAX_LAST_LOG_LINES);
		$data['log_filename'] = self::FILE_NAME_LOG;
		
		$data['logs'] = array(
			'0' => $this->language->get('text_log_off'),
			'1' => $this->language->get('text_log_short'),
			'2' => $this->language->get('text_log_full')
		);

		// Номер магазина
		if (isset($this->request->post['payment_webmoney_wmz_merch_r'])) {
			$data['payment_webmoney_wmz_merch_r'] = $this->request->post['payment_webmoney_wmz_merch_r'];
		} else {
			$data['payment_webmoney_wmz_merch_r'] = $this->config->get('payment_webmoney_wmz_merch_r');
		}
		
		// zp_merhant_key
		if (isset($this->request->post['payment_webmoney_wmz_secret_key'])) {
			$data['payment_webmoney_wmz_secret_key'] = $this->request->post['payment_webmoney_wmz_secret_key'];
		} else {
			$data['payment_webmoney_wmz_secret_key'] = $this->config->get('payment_webmoney_wmz_secret_key');
		}

		// zp_merhant_key X20
		if (isset($this->request->post['payment_webmoney_wmz_secret_key_x20'])) {
			$data['payment_webmoney_wmz_secret_key_x20'] = $this->request->post['payment_webmoney_wmz_secret_key_x20'];
		} else {
			$data['payment_webmoney_wmz_secret_key_x20'] = $this->config->get('payment_webmoney_wmz_secret_key_x20');
		}
		
		
		// URL
		$server = isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG;

		$data['payment_webmoney_wmz_result_url'] 		= $server . 'index.php?route=extension/payment/webmoney_wmz/callback';
		$data['payment_webmoney_wmz_success_url'] 	= $server . 'index.php?route=extension/payment/webmoney_wmz/success';
		$data['payment_webmoney_wmz_fail_url'] 			= $server . 'index.php?route=extension/payment/webmoney_wmz/fail';
		
		
		if (isset($this->request->post['payment_webmoney_wmz_order_confirm_status_id'])) {
			$data['payment_webmoney_wmz_order_confirm_status_id'] = $this->request->post['payment_webmoney_wmz_order_confirm_status_id'];
		} else {
			$data['payment_webmoney_wmz_order_confirm_status_id'] = $this->config->get('payment_webmoney_wmz_order_confirm_status_id'); 
		}

		if (isset($this->request->post['payment_webmoney_wmz_order_status_id'])) {
			$data['payment_webmoney_wmz_order_status_id'] = $this->request->post['payment_webmoney_wmz_order_status_id'];
		} else {
			$data['payment_webmoney_wmz_order_status_id'] = $this->config->get('payment_webmoney_wmz_order_status_id'); 
		}

		if (isset($this->request->post['payment_webmoney_wmz_order_fail_status_id'])) {
			$data['payment_webmoney_wmz_order_fail_status_id'] = $this->request->post['payment_webmoney_wmz_order_fail_status_id'];
		} else {
			$data['payment_webmoney_wmz_order_fail_status_id'] = $this->config->get('payment_webmoney_wmz_order_fail_status_id'); 
		}

		if (isset($this->request->post['payment_webmoney_wmz_hide_mode'])) {
			$data['payment_webmoney_wmz_hide_mode'] = $this->request->post['payment_webmoney_wmz_hide_mode'];
		} else {
			$data['payment_webmoney_wmz_hide_mode'] = $this->config->get('payment_webmoney_wmz_hide_mode'); 
		}

		if (isset($this->request->post['payment_webmoney_wmz_minimal_order'])) {
			$data['payment_webmoney_wmz_minimal_order'] = $this->request->post['payment_webmoney_wmz_minimal_order'];
		} else {
			$data['payment_webmoney_wmz_minimal_order'] = $this->config->get('payment_webmoney_wmz_minimal_order'); 
		}

		if (isset($this->request->post['payment_webmoney_wmz_maximal_order'])) {
			$data['payment_webmoney_wmz_maximal_order'] = $this->request->post['payment_webmoney_wmz_maximal_order'];
		} else {
			$data['payment_webmoney_wmz_maximal_order'] = $this->config->get('payment_webmoney_wmz_maximal_order'); 
		}
		
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['payment_webmoney_wmz_geo_zone_id'])) {
			$data['payment_webmoney_wmz_geo_zone_id'] = $this->request->post['payment_webmoney_wmz_geo_zone_id'];
		} else {
			$data['payment_webmoney_wmz_geo_zone_id'] = $this->config->get('payment_webmoney_wmz_geo_zone_id'); 
		}
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['payment_webmoney_wmz_status'])) {
			$data['payment_webmoney_wmz_status'] = $this->request->post['payment_webmoney_wmz_status'];
		} else {
			$data['payment_webmoney_wmz_status'] = $this->config->get('payment_webmoney_wmz_status');
		}
		
		if (isset($this->request->post['payment_webmoney_wmz_sort_order'])) {
			$data['payment_webmoney_wmz_sort_order'] = $this->request->post['payment_webmoney_wmz_sort_order'];
		} else {
			$data['payment_webmoney_wmz_sort_order'] = $this->config->get('payment_webmoney_wmz_sort_order');
		}
		
		if (isset($this->request->post['payment_webmoney_wmz_log'])) {
			$data['payment_webmoney_wmz_log'] = $this->request->post['payment_webmoney_wmz_log'];
		} else {
			$data['payment_webmoney_wmz_log'] = $this->config->get('payment_webmoney_wmz_log');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/webmoney_wmz', $data));
	}

   public function clearLog() {
    $this->load->language('extension/payment/webmoney_wmz');

    $json = array();

    if ($this->validatePermission()) {
      if (is_file(DIR_LOGS . self::FILE_NAME_LOG)) {
        @unlink(DIR_LOGS . self::FILE_NAME_LOG);
      }
        $json['success'] = $this->language->get('text_clear_log_success');
      } else {
        $json['error'] = $this->language->get('error_clear_log');
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }
	
	protected function validate() {
		if (!$this->validatePermission()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		// TODO проверку на валидность номера!
		if (!$this->request->post['payment_webmoney_wmz_merch_r']) {
			$this->error['merch_r'] = $this->language->get('error_merch_r');
		}
		
		if (!$this->request->post['payment_webmoney_wmz_secret_key']) {
			$this->error['secret_key'] = $this->language->get('error_secret_key');
		}

		if (!$this->request->post['payment_webmoney_wmz_secret_key_x20']) {
			$this->error['secret_key_x20'] = $this->language->get('error_secret_key_x20');
		}
		
		return !$this->error;
	}

  protected function validatePermission() {
    return $this->user->hasPermission('modify', 'extension/payment/webmoney_wmz');
  }

    protected function readLastLines($filename, $lines) {
        if (!is_file($filename)) {
            return array();
        }
        $handle = @fopen($filename, "r");
        if (!$handle) {
            return array();
        }
        $linecounter = $lines;
        $pos = -1;
        $beginning = false;
        $text = array();

        while ($linecounter > 0) {
            $t = " ";

            while ($t != "\n") {
                /* if fseek() returns -1 we need to break the cycle*/
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }

            $linecounter--;

            if ($beginning) {
                rewind($handle);
            }

            $text[$lines - $linecounter - 1] = fgets($handle);

            if ($beginning) {
                break;
            }
        }
        fclose($handle);

        return array_reverse($text);
    }
}
?>