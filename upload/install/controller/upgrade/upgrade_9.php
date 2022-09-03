<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade9 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		// Fix https://github.com/opencart/opencart/issues/11594
		$this->db->query("UPDATE `" . DB_PREFIX . "layout_route` SET `route` = REPLACE(`route`, '|', '.')");
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_url` SET `value` = REPLACE(`value`, '|', '.') WHERE `key` = 'route'");
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = REPLACE(`trigger`, '|', '.'), `action` = REPLACE(`action`, '|', '.')");
		$this->db->query("UPDATE `" . DB_PREFIX . "banner_image` SET `link` = REPLACE(`link`, '|', '.')");

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['redirect'] = $this->url->link('install/step_4', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
