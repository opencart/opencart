<?php
class ControllerCommonUrls extends Controller {
	private $link, $route;
	private $urls = array();
	
	public function index()
	{
		$this->urls = array();

		$this->route = empty($this->request->get['route']) ? false : $this->request->get['route'];

		if (!empty($this->urls['route'])) {
			unset($this->urls['route']);
		}

		foreach ($this->request->get as $key => $value) {
			if (isset($this->request->get[$key])) {

				unset($this->urls[$key]);

				$this->urls[$key] = "&{$key}={$value}";

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
		$this->urls[$args['key']] = "&{$args['key']}={$args['value']}";
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

		$url = empty($args['url']) ? $this->link : (string)$args['url'];

		$value = empty($args['value']) ? '' : (string)$args['value'];

		return $this->url->link($route, $url . $value);
	}
	
}