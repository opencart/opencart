<?php
class ControllerCommonBreadcrumbs extends Controller {
	private $route;
	private $routes = array();
	private $breadcrumbs = array();

	public function index() {
		$this->route = $this->provider->hasRequest('route') ? $this->request->get['route'] : '';

		$this->routes = explode('/', $this->route);
		
		// set home page

		$this->breadcrumbs[] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		return $this;
	}

	public function setDefaults()
	{
		$count_routes = count($this->routes);

		if ($this->routes[0] === 'extension') {				
			$this->load->language('marketplace/extension');

			$this->breadcrumbs[] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->provider->link('marketplace/extension')
			];
		}

		if ($this->routes[0] === 'setting') {				
			$this->load->language('setting/store');

			$this->breadcrumbs[] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->provider->link('setting/store')
			];
		}

		if ($count_routes >= 2) {
			$routes = array_slice($this->routes, 0, 2);

			$route = implode('/', $routes);

			$this->load->language($route);

			$this->breadcrumbs[] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->provider->link($route)
			];
		}
	}

	public function set(string $text, string $route = '')
	{
		$this->breadcrumbs[] = [
			'text' => $this->language->get($text),
			'href' => $this->provider->link($route)
		];
	}

	public function render()
	{
		$data['breadcrumbs'] = $this->breadcrumbs;

		return $this->load->view('common/breadcrumbs', $data);
	}
}