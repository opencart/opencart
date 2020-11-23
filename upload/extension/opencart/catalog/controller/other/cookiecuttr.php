<?php
namespace Opencart\Application\Controller\Extension\Opencart\Other;
class Cookiecuttr extends \Opencart\System\Engine\Controller {
	public function index(&$route, &$args, &$output) {
		if ($this->config->get('other_cookiecuttr_status')) {
			$key = 'cookiecuttr.' . (int)$this->config->get('config_store_id');

			$cookiecuttr = $this->cache->get($key);
			$cookiecuttr = '';
			if (!$cookiecuttr) {
				$this->load->language('extensison/opencart/other/cookiecuttr');

				//$data['text_cookie'] = sprintf($this->language->get('text_cookie'), $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_cookie_id')));

				$output = str_replace('</body></html>', $this->load->view('extension/opencart/other/cookiecuttr') . '</body></html>', $output);

				$this->cache->set($key, $output);
			} else {
				$output = $cookiecuttr;
			}
		}
	}
}