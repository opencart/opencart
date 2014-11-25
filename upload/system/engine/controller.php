<?php
abstract class Controller {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
	
	/**
	 * Set output
	 * 
	 * @example $this->setOutput('product/category.tpl', $data)
	 * @param string $view_template - file path
	 * @param array $data - data for extracting into template
	 * @return $this
	 */
	public function setOutput($view_template, $data) {
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$view_template)) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/'.$view_template, $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/'.$view_template, $data));
		}
	
		return $this;
	}
}