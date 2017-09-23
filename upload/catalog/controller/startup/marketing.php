<?php
class ControllerStartupMarketing extends Controller {
	public function index() {
		// Tracking Code
		if (isset($this->request->get['tracking'])) {
			setcookie('tracking', $this->request->get['tracking'], time() + 3600 * 24 * 1000, '/');

			$this->load->model('marketing/marketing');

			$marketing_info = $this->model_marketing_marketing->getMarketingByCode($this->request->get['tracking']);

			if ($marketing_info) {
				$this->model_marketing_marketing->addMarketingHistory($marketing_info['marketing_id'], $this->request->server['REMOTE_ADDR']);
			}

			$this->load->model('account/customer');

			$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->get['tracking']);

			if ($affiliate_info) {
				$this->model_account_customer->addAffiliateHistory($affiliate_info['customer_id'], $this->request->server['REMOTE_ADDR']);
			}
		}
	}
}
