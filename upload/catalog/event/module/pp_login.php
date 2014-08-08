<?php
class EventModulePPLogin extends Event {
	public function logout() {
		if (isset($this->session->data['pp_login'])) {
			unset($this->session->data['pp_login']);
		}
	}
}