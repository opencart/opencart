<?php
class ControllerExtensionPaymentKlarnaInvoice extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/klarna_invoice');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$status = false;

			foreach ($this->request->post['payment_klarna_invoice'] as $klarna_invoice) {
				if ($klarna_invoice['status']) {
					$status = true;

					break;
				}
			}

			$klarna_data = array(
				'klarna_invoice_pclasses' => $this->pclasses,
				'klarna_invoice_status'   => $status
			);

			$this->model_setting_setting->editSetting('payment_klarna_invoice', array_merge($this->request->post, $klarna_data));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/klarna_invoice', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/klarna_invoice', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		$data['countries'] = array();

		$data['countries'][] = array(
			'name' => $this->language->get('text_germany'),
			'code' => 'DEU'
		);

		$data['countries'][] = array(
			'name' => $this->language->get('text_netherlands'),
			'code' => 'NLD'
		);

		$data['countries'][] = array(
			'name' => $this->language->get('text_denmark'),
			'code' => 'DNK'
		);

		$data['countries'][] = array(
			'name' => $this->language->get('text_sweden'),
			'code' => 'SWE'
		);

		$data['countries'][] = array(
			'name' => $this->language->get('text_norway'),
			'code' => 'NOR'
		);

		$data['countries'][] = array(
			'name' => $this->language->get('text_finland'),
			'code' => 'FIN'
		);

		if (isset($this->request->post['payment_klarna_invoice'])) {
			$data['payment_klarna_invoice'] = $this->request->post['payment_klarna_invoice'];
		} else {
			$data['payment_klarna_invoice'] = $this->config->get('payment_klarna_invoice');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$file = DIR_LOGS . 'klarna_invoice.log';

		if (file_exists($file)) {
			$data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$data['log'] = '';
		}

		$data['clear'] = $this->url->link('extension/payment/klarna_invoice/clear', 'user_token=' . $this->session->data['user_token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/klarna_invoice', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/klarna_invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	private function parseResponse($node, $document) {
		$child = $node;

		switch ($child->nodeName) {
			case 'string':
				$value = $child->nodeValue;
				break;

			case 'boolean':
				$value = (string)$child->nodeValue;

				if ($value == '0') {
					$value = false;
				} elseif ($value == '1') {
					$value = true;
				} else {
					$value = null;
				}

				break;

			case 'integer':
			case 'int':
			case 'i4':
			case 'i8':
				$value = (int)$child->nodeValue;
				break;

			case 'array':
				$value = array();

				$xpath = new DOMXPath($document);
				$entries = $xpath->query('.//array/data/value', $child);

				for ($i = 0; $i < $entries->length; $i++) {
					$value[] = $this->parseResponse($entries->item($i)->firstChild, $document);
				}

				break;

			default:
				$value = null;
		}

		return $value;
	}

	public function clear() {
		$this->load->language('extension/payment/klarna_invoice');

		$file = DIR_LOGS . 'klarna_invoice.log';

		$handle = fopen($file, 'w+');

		fclose($handle);

		$this->session->data['success'] = $this->language->get('text_success');

		$this->response->redirect($this->url->link('extension/payment/klarna_invoice', 'user_token=' . $this->session->data['user_token'], true));
	}
}