<?php
/**
 * Provider class
 */
class Provider
{
	/** @var object */
	private $url;

	/** @var object */
	private $request;
	
	/** @var string */
	private $route;

	/** @var int */
	private $page;

	/** @var int */
	private $limit;

	/** @var string */
	private $order;

	/** @var array */
	private $default_filter = array();
	
	/** @var array */
	private $params = array();

	/** @var array */
	private $attached = array();

	/** @var array */
	private $detached = array();

	/**
	 * construct
	 *
	 * @param object|Registry
	 * @param int	$limit config for admin or catalog
	 */	
	public function __construct(Registry $registry, int $limit) {		
		$this->url = $registry->get('url');
		
		$this->request = $registry->get('request');

		$this->route = $this->request->hasGet('route') ? (string)$this->request->get['route'] : '';

		$this->page = $this->request->hasGet('page') ? (int)$this->request->get['page'] : 1;

		$this->limit = $limit;

		$this->order = ($this->request->hasGet('order') && $this->request->get['order'] === 'ASC') ? 'DESC' : 'ASC';

		$this->default_filter = array('start' => ($this->page - 1) * $limit, 'limit' => $limit);

		$this->setParser('order', 'ASC');

		if ($this->page <= 0) {
			$this->page = 1;
		}
	}

	public function __get($property)
	{
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	/**
	 * params to match request header
	 *
	 * @param array	$params
	 */
	public function parser(array $params)
	{
		foreach ($params as $key => $param) {
			$this->params[$key] = $this->request->hasGet($key) ? $this->request->get[$key] : $param;
		}
	}

	/**
	 * setParser method
	 *
	 * @param string $key  	to @var $params
	 * @param string $value to @var $params
	 */
	public function setParser(string $key, string $value)
	{
		$this->params[$key] = $this->request->hasGet($key) ? $this->request->get[$key] : $value;
	}

	/**
	 * getParser method
	 *
	 * @param string $key in @var $params
	 *
	 * @return string
	 */
	public function getParser($key)
	{
		return isset($this->params[$key]) ? $this->params[$key] : '';
	}

	/**
	 * hasParser method
	 *
	 * @param string $key in @var $params
	 *
	 * @return bool
	 */
	public function hasParser($key)
	{
		return isset($this->params[$key]);
	}

	/**
	 * return all params
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * attach method
	 *
	 * @param string $key  	to merge in @method link
	 * @param string $value to merge in @method link
	 */
	public function attach(string $key, string $value)
	{
		$this->attached[$key] = $value;
	}

	/**
	 * clean value from @var object|Request in @method link
	 *
	 * @param string $key to merge in @method link
	 */
	public function detach(string $key)
	{
		$this->detached[$key] = '';
	}

	/**
	 * remove duplicate keys from request header
	 *
	 * @param string $route
	 * @param array	 $args to merge with @var object|Request and @var $detached and @var $this->attached
	 *
	 * @return string
	 */
	public function link(string $route = '', array $args = array())
	{
		$route = empty($route) ? $this->route : $route;

		$params = array_merge($this->request->get, $this->detached, $this->attached, $args);
		
		if ($this->request->hasGet('route')) {
			unset($params['route']);
		}

		$query = array_filter($params, function($value) {
			return $value !== '';
		});
		
		$url = empty($query) ? '' : '&' . http_build_query($query, '', '&');

		return $this->url->link($route, $url);
	}
}