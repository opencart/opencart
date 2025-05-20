<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Notification
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Notification extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		if (empty($this->request->cookie['notification'])) {
			$curl = curl_init();

			// Gets the latest information from opencart.com about news, updates and security.
			curl_setopt($curl, CURLOPT_URL, OPENCART_SERVER . 'index.php?route=api/notification');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			curl_close($curl);

			// Notification
			if ($status == 200) {
				$notification = json_decode($response, true);
			} else {
				$notification = [];
			}

			if (isset($notification['notification'])) {
				$this->load->model('tool/notification');
				
				foreach ($notification['notifications'] as $result) {
					$notification_info = $this->model_tool_notification->addNotification($result['notification_id']);

					if (!$notification_info) {
						$this->model_tool_notification->addNotification($result);
					}
				}
			}

			// Only grab the
			$option = [
				'expires'  => time() + 3600 * 24 * 7,
				'path'     => $this->config->get('session_path'),
				'secure'   => $this->request->server['HTTPS'],
				'httponly' => false,
				'SameSite' => $this->config->get('config_session_samesite')
			];

			setcookie('notification', '1', $option);
		}
	}
}
