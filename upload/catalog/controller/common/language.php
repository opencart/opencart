<?php
namespace Opencart\Catalog\Controller\Common;
/**
 * Class Language
 *
 * @package Opencart\Catalog\Controller\Common
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/language');

		$url_data = $this->request->get;

		if (isset($url_data['route'])) {
			$route = $url_data['route'];
		} else {
			$route = $this->config->get('action_default');
		}

		unset($url_data['route']);
		unset($url_data['_route_']);
		unset($url_data['language']);

		$url = '';

		if ($url_data) {
			$url .= '&' . urldecode(http_build_query($url_data));
		}

		// Added so the correct SEO language URL is used.
		$language_id = $this->config->get('config_language_id');

		$data['languages'] = [];

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			$this->config->set('config_language_id', $result['language_id']);

			$data['languages'][] = [
				'name'  => $result['name'],
				'code'  => $result['code'],
				'image' => $result['image'],
				'href'  => $this->url->link($route, 'language=' . $result['code'] . $url, true)
			];
		}

		$this->config->set('config_language_id', $language_id);

		$data['code'] = $this->config->get('config_language');

		return $this->load->view('common/language', $data);
	}
}
