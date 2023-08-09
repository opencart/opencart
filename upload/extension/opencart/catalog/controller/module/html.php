<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Module;
/**
 * Class HTML
 *
 * @package
 */
class HTML extends \Opencart\System\Engine\Controller {
	/**
	 * @param array $setting
	 *
	 * @return string
	 */
	public function index(array $setting): string {
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');

			$data['html'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');

			return $this->load->view('extension/opencart/module/html', $data);
		} else {
			return '';
		}
	}
}