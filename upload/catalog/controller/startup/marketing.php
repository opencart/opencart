<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Marketing
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Marketing extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
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

			if ($this->config->get('config_affiliate_status')) {
				$this->load->model('account/affiliate');

				$affiliate_info = $this->model_account_affiliate->getAffiliateByTracking($tracking);

				if ($affiliate_info && $affiliate_info['status']) {
					$this->model_account_affiliate->addReport($affiliate_info['customer_id'], $this->request->server['REMOTE_ADDR']);
				}

				if ($marketing_info || ($affiliate_info && $affiliate_info['status'])) {
					$this->session->data['tracking'] = $tracking;

					if (!isset($this->request->cookie['tracking'])) {
						$option = [
							'expires'  => $this->config->get('config_affiliate_expire') ? time() + (int)$this->config->get('config_affiliate_expire') : 0,
							'path'     => $this->config->get('session_path'),
							'SameSite' => $this->config->get('config_session_samesite')
						];

						setcookie('tracking', $tracking, $option);
					}
				}
			}
		}
	}
}
