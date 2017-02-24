<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleSagepayServerCards extends Controller {
	public function index() {
		if ($this->config->get('sagepay_server_cards_status') && $this->config->get('sagepay_server_status') && $this->customer->isLogged()) {
			$this->load->language('account/sagepay_server_cards');

			$data['text_card'] = $this->language->get('text_card');
			$data['card'] = $this->url->link('account/sagepay_server_cards', '', true);

			return $this->load->view('extension/module/sagepay_server_cards', $data);
		}
	}

}