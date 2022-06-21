<?php
namespace Opencart\Admin\Controller\Common;
class Authorize extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('common/authorize');

		$this->document->setTitle($this->language->get('heading_title'));

		if (!isset($this->session->data['authorize'])) {
			$token = token(64);

			$os = [
				'/windows nt 6.2/i'      => 'Windows 8',
				'/windows nt 6.1/i'      => 'Windows 7',
				'/windows nt 6.0/i'      => 'Windows Vista',
				'/windows nt 5.2/i'      => 'Windows Server 2003/XP x64',
				'/windows nt 5.1/i'      => 'Windows XP',
				'/windows xp/i'          => 'Windows XP',
				'/windows nt 5.0/i'      => 'Windows 2000',
				'/windows me/i'          => 'Windows ME',
				'/win98/i'               => 'Windows 98',
				'/win95/i'               => 'Windows 95',
				'/win16/i'               => 'Windows 3.11',
				'/macintosh|mac os x/i'  => 'Mac OS X',
				'/mac_powerpc/i'         => 'Mac OS 9',
				'/linux/i'               => 'Linux',
				'/ubuntu/i'              => 'Ubuntu',
				'/iphone/i'              => 'iPhone',
				'/ipod/i'                => 'iPod',
				'/ipad/i'                => 'iPad',
				'/android/i'             => 'Android',
				'/blackberry/i'          => 'BlackBerry',
				'/webos/i'               => 'Mobile'
			];

			$user_agent = $this->request->server['HTTP_USER_AGENT'];

			$platform = 'Unknown OS Platform';

			foreach ($os as $regex => $value) {
				if (preg_match($regex, $user_agent)) {
					$platform = $value;
				}
			}

			$login_data = [
				'token'      => $token,
				'ip'         => $this->request->server['REMOTE_ADDR'],
				'device'     => $platform,
				'user_agent' => $user_agent
			];

			$this->model_user_user->addLogin($this->user->getId(), $login_data);

			$this->session->data['authorize'] = $token;

			setcookie('opencart', $token, time() + 60 * 60 * 24 * 365 * 10);
		}

		$data['action'] = $this->url->link('common/authorize|validate', 'user_token=' . $this->session->data['user_token']);

		if (isset($this->request->get['route']) && $this->request->get['route'] != 'common/login') {
			$args = $this->request->get;

			$route = $args['route'];

			unset($args['route']);
			unset($args['user_token']);

			$url = '';

			if ($args) {
				$url .= http_build_query($args);
			}

			$data['redirect'] = $this->url->link($route, $url);
		} else {
			$data['redirect'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize', $data));
	}

	private function validate(): void {
		$this->load->language('common/authorize');

		$json = [];

		if (!$this->session->data['code']) {
			$token = $this->session->data['authorize'];
		} else {
			$token = '';
		}

		$login_info = $this->model_user_user->getLoginByToken($this->user->getId(), $token);

		if (!$login_info) {
			$json['error'] = $this->language->get('error_user');
		}

		if ($login_info && !isset($this->request->post['code']) || !isset($this->session->data['code']) || $this->request->post['code'] != $this->session->data['code']) {
			$json['error'] = $this->language->get('error_code');

			$this->model_fraud_pin->addAttempt($this->user->getId(), $login_info['user_login_id']);
		}

		if ($this->model_user_user->getTotalAttempts($this->user->getId()) >= 3) {
			$json['error'] = $this->language->get('error_attempts');
		}

		if (!$json) {
			$this->model_user_user->resetAttempts($this->user->getId());

			// Register the cookie for security.
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0)) {
				$json['redirect'] = str_replace('&amp;', '&', $this->request->post['redirect']) . '&user_token=' . $this->session->data['user_token'];
			} else {
				$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function resend() {
		$this->load->language('common/authorize');

		$json = [];

		$json['success'] = $this->language->get('text_reset');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function unlock() {
		$this->load->language('common/authorize');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize_unlock', $data));
	}

	public function reset() {
		if (isset($this->request->get['email'])) {
			$email = (string)$this->request->get['email'];
		} else {
			$email = '';
		}

		if (isset($this->request->get['code'])) {
			$code = (string)$this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($email);

		if ($user_info && $user_info['code'] && $code && $user_info['code'] === $code) {
			$this->user->logout();

			$this->model_user_user->editPin($user_info['member_id'], '');

			$this->model_user_user->editCode($user_info['email'], '');

			$this->model_user_user->deletePin($user_info['user_id']);

			$this->session->data['success'] = 'Success: Your PIN has been reset!';
		} else {
			$this->model_user_user->editCode($email, '');

			$this->session->data['error'] = 'Warning: Could not reset your PIN!';
		}

		$this->response->redirect($this->url->link('account/login'));
	}
}
