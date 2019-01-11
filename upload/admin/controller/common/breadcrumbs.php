<?php
class ControllerCommonCore extends Controller {
	private $route;
	private $urls = array();
	private $routes = array();
	private $breadcrumbs = array();

	public function index() {

		$this->urls = $this->load->controller('common/urls');

		$this->route = array_pop($this->request->get['route']);

		$this->routes = explode('/', $this->route);
		
		// set home page

		$this->breadcrumbs[] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->urls->getLink(['route' => 'common/dashboard'])
		];

		$this->config->set('breadcrumbs', $this);

		return $this;
	}

	public function setDefaults()
	{
		$this->load->language($this->route);

		$heading_title = $this->language->get('heading_title');

		if ($this->routes[0] === 'extension') {				
			$this->load->language('marketplace/extension');

			$this->breadcrumbs[] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->urls->getLink(['route' => 'marketplace/extension'])
			];
		}

		$this->breadcrumbs[] = [
			'text' => $heading_title,
			'href' => $this->urls->getLink(['route' => $this->route])
		];
	}

	public function set(array $args)
	{
		$this->breadcrumbs[] = [
			'text' => $this->language->get($args['text']),
			'href' => $args['href']
		];
	}

	public function render()
	{
		$data['breadcrumbs'] = $this->breadcrumbs;

		return $this->load->view('common/breadcrumbs', $data);
	}

}
