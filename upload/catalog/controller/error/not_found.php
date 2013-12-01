<?php   
class ControllerErrorNotFound extends Controller {
	public function index() {		
		$this->language->load('error/not_found');

		$this->document->settitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		if (isset($this->request->get['route'])) {
			$data = $this->request->get;

			unset($data['_route_']);

			$route = $data['route'];

			unset($data['route']);

			$url = '';

			if ($data) {
				$url = '&' . urldecode(http_build_query($data, '', '&'));
			}	

			if (isset($this->request->server['https']) && (($this->request->server['https'] == 'on') || ($this->request->server['https'] == '1'))) {
				$connection = 'ssl';
			} else {
				$connection = 'nonssl';
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link($route, $url, $connection),
				'separator' => $this->language->get('text_separator')
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_error'] = $this->language->get('text_error');

		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->response->addheader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 not found');

		$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
		} else {
			$this->template = 'default/template/error/not_found.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setoutput($this->render());
	}
}
?>