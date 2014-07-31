<?php
class EventModulePPLogin extends Controller {
	public function logout() {
		if (isset($this->session->data['pp_login'])) {
			unset($this->session->data['pp_login']);
		}
	}
}