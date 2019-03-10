<?php
class ControllerCommonBreadcrumbs extends Controller {
	private $route;
	private $routes = array();
	private $breadcrumbs = array();

	public function index() {
		$this->route = isset($this->request->get['route']) ? $this->request->get['route'] : false;

		$this->routes = explode('/', $this->route);
		
		// set home page

		$this->breadcrumbs[] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
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
				'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'])
			];
		}

		$end_route = array_pop($this->routes);

		unset($this->routes[$end_route]);

		$this->breadcrumbs[] = [
			'text' => $heading_title,
			'href' => $this->url->link(implode('/', $this->routes), 'user_token=' . $this->session->data['user_token'])
		];
	}

	public function set(array $args)
	{
		$this->breadcrumbs[] = [
			'text' => $this->language->get($args['text']),
			'href' => $this->url->link($args['route'], 'user_token=' . $this->session->data['user_token'])
		];
	}

	public function render()
	{
		$data['breadcrumbs'] = $this->breadcrumbs;

		return $this->load->view('common/breadcrumbs', $data);
	}

}