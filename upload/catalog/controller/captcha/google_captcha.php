<?php
class ControllerInformationContact extends Controller {
    private $error = array();

    public function index() {
        if ($this->config->get('config_google_captcha_status')) {
			$this->document->addScript('https://www.google.com/recaptcha/api.js');

			$data['site_key'] = $this->config->get('config_google_captcha_public');
		} else {
			$data['site_key'] = '';
		}
    }

    public function validate() {
        if ($this->config->get('config_google_captcha_status')) {
            $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

            $recaptcha = json_decode($recaptcha, true);

            if (!$recaptcha['success']) {
                $this->error['captcha'] = $this->language->get('error_captcha');
            }
        }
    }
}
