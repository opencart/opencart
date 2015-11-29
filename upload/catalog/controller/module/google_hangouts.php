<?php
class ControllerModuleGoogleHangouts extends Controller {
	public function index() {
		$this->load->language('module/google_hangouts');

		$data['heading_title'] = $this->language->get('heading_title');

		if ($this->request->server['HTTPS']) {
			$data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('google_hangouts_code')));
		} else {
			$data['code'] = html_entity_decode($this->config->get('google_hangouts_code'));
		}

		return $this->load->view('module/google_hangouts', $data);
	}
}