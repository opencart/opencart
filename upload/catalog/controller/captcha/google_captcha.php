<?php
class ControllerInformationContact extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('captcha/google_captcha');

		$this->document->addScript('https://www.google.com/recaptcha/api.js');

		$data['site_key'] = $this->config->get('config_google_captcha_public');
    }

    public function validate() {
        $this->load->language('captcha/google_captcha');

        $json = array();

        $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

        $recaptcha = json_decode($recaptcha, true);

        if (!$recaptcha['success']) {
            $json['error'] = $this->language->get('error_captcha');
        } else {
            $json['success'] = '';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
