<?php
class ControllerCaptchaGoogleCaptcha extends Controller {
    public function index($error = array()) {
        $this->load->language('captcha/google_captcha');

        $data['heading_title'] = $this->language->get('heading_title');

		$data['entry_captcha'] = $this->language->get('entry_captcha');

		$this->document->addScript('https://www.google.com/recaptcha/api.js');

        if (isset($error['captcha'])) {
			$data['error_captcha'] = $error['captcha'];
		} else {
			$data['error_captcha'] = '';
		}

		$data['site_key'] = $this->config->get('google_captcha_key');

        $data['route'] = $this->request->get['route']; 

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/captcha/google_captcha.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/captcha/google_captcha.tpl', $data);
		} else {
			return $this->load->view('default/template/captcha/google_captcha.tpl', $data);
		}
    }

    public function validate() {
        $this->load->language('captcha/google_captcha');

        $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

        $recaptcha = json_decode($recaptcha, true);

        if (!$recaptcha['success']) {
            return $this->language->get('error_captcha');
        }
    }
}
