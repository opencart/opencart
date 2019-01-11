<?php
class ControllerCommonUrls extends Controller {
	private $link, $route;
	private $urls = array();
	
	public function index()
	{
		$this->urls = array();

		$this->route = empty($this->request->get['route']) ? false : $this->request->get['route'];

		foreach ($this->request->get as $key => $value) {
			if (isset($this->request->get[$key])) {

				unset($this->urls[$key]);

				$this->urls[$key] = ($key === 'route') ? $this->route : "&{$key}={$value}";

				if ($key === 'order') {
					$this->urls['order'] = ($value === 'ASC') ? '&order=DESC' : '&order=ASC';
				}
			}
		}

		$this->link = implode($this->urls);

		$this->config->set('urls', $this);

		return $this;
	}

	public function set(array $args)
	{
		$this->urls[$args['key']] = "&{(string)$args['key']}={(string)$args['value']}";
	}

	public function get()
	{
		return $this->urls;
	}

	public function getKey(array $args)
	{
		return $this->urls[$args['key']];
	}

	public function getLink(array $args = array())
	{
		$route = empty($args['route']) ? $this->route : (string)$args['route'];

		return $this->url->link($route, $this->session->data['user_token'] . (string)$args['url'] . $this->link, true);
	}
	
}
