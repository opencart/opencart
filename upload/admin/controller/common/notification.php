<?php
namespace Opencart\Application\Controller\Common;
class Notification extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('common/notification');

		$notification = $this->cache->get('notification');

		if (!$notification) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, OPENCART_SERVER . 'index.php?route=api/notification');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			curl_close($curl);

			if ($response) {
				$notification = json_decode($response, true);
			} else {
				$notification = '';
			}

			$this->cache->set('notification', $notification, 3600 * 24);
		}

		$data['notifications'] = [];

		if (isset($notification['notification'])) {
			foreach ($notification['notifications'] as $result) {
				$data['notifications'][] = [

					'message' => $result['message']
				];
			}
		}

		return $this->load->view('common/notification', $data);
	}
}