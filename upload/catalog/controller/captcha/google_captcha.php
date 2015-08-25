<?php
class ControllerCaptchaGoogleCaptcha extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('captcha/google_captcha');

		$this->document->addScript('https://www.google.com/recaptcha/api.js');

        if (isset($this->error['captcha'])) {
			$data['error_captcha'] = $this->error['captcha'];
		} else {
			$data['error_captcha'] = '';
		}

		$data['site_key'] = $this->config->get('google_captcha_key');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/captcha/google_captcha.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/captcha/google_captcha.tpl', $data);
		} else {
			return $this->load->view('default/template/captcha/google_captcha.tpl', $data);
		}
    }

    public function validate() {
        $this->load->language('captcha/google_captcha');

        $json = array();

        $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

        $recaptcha = json_decode($recaptcha, true);

        if (!$recaptcha['success']) {
            $json['error'] = $this->language->get('error_captcha');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
