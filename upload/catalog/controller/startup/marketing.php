<?php
namespace Opencart\Catalog\Controller\Startup;
class Marketing extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$tracking = '';

		if (isset($this->request->get['tracking'])) {
			$tracking = (string)$this->request->get['tracking'];
		}

		if (isset($this->request->cookie['tracking'])) {
			$tracking = (string)$this->request->cookie['tracking'];
		}

		// Tracking Code
		if ($tracking) {
			$this->load->model('marketing/marketing');

			$marketing_info = $this->model_marketing_marketing->getMarketingByCode($tracking);

			if ($marketing_info) {
				$this->model_marketing_marketing->addReport($marketing_info['marketing_id'], $this->request->server['REMOTE_ADDR']);
			}

			$this->load->model('account/affiliate');

			$affiliate_info = $this->model_account_affiliate->getAffiliateByTracking($tracking);

			if ($affiliate_info && $affiliate_info['status']) {
				$this->model_account_affiliate->addReport($affiliate_info['customer_id'], $this->request->server['REMOTE_ADDR']);
			}

			if ($marketing_info || ($affiliate_info && $affiliate_info['status'])) {
				$this->session->data['tracking'] = $tracking;

				if (!isset($this->request->cookie['tracking'])) {
					$option = [
						'expires'  => time() + 3600 * 24 * 1000,
						'path'     => '/',
						'SameSite' => $this->config->get('session_samesite')
					];

					setcookie('tracking', $tracking, $option);
				}
			}
		}
	}
}
