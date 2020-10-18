<?php
namespace Opencart\Application\Controller\Event;
class Currency extends \Opencart\System\Engine\Controller {
	// admin/model/setting/setting/editSetting/after
	// admin/model/localisation/currency/addCurrency/after
	// admin/model/localisation/currency/editCurrency/after
	public function index(&$route, &$args, &$output) {
		if ($route == 'model/setting/setting/editSetting' && $args[0] == 'config' && isset($args[1]['config_currency'])) {
			$this->load->controller('extension/' . 'currency/' . $this->config->get('config_currency_engine') . '/currency', $args[1]['config_currency']);
		} else {
		//	$this->load->controller('extension/currency/' . $this->config->get('config_currency_engine') . '/currency', $this->config->get('config_currency'));
		}
	}
	
	// admin/model/localisation/currency/deleteCurrency/after
	public function validateCurrency(&$route, &$args, &$output) {
		if (isset($this->request->get['currency_id']) && isset($this->request->get['currency_description'])) {
			$this->load->model('localisation/currency');
			$this->load->model('localisation/country');
			
			foreach ($this->request->post['currency_description'] as $language_id => $value) {
				$country_info = $this->model_localisation_country->getCountry($value['country_id']);
				
				if (!$country_info) {
					$this->model_localisation_currency->deleteCurrency($this->request->get['currency_id']);
				}
			}
		}
	}
}
