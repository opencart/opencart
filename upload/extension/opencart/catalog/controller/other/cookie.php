<?php
namespace Opencart\Application\Controller\Extension\Opencart\Other;
class Cookie extends \Opencart\System\Engine\Controller {
	public function index(&$route, &$args, &$output) {
		if ($this->config->get('other_cookie_status')) {
			$key = 'cookie.' . (int)$this->config->get('config_store_id');

			$cookie = $this->cache->get($key);

			$cookie = '';

			if (!$cookie) {
				$this->load->language('extension/opencart/other/cookie');

				//$data['text_cookie'] = sprintf($this->language->get('text_cookie'), $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_cookie_id')));

				$output = str_replace('</body>', $this->load->view('extension/opencart/other/cookie') . '</body>', $output);

				$this->cache->set($key, $output);
			} else {
				$output = $cookie;
			}
		}
	}

	public function agree() {
		if ($this->request->get['']) {


		}
	}
}