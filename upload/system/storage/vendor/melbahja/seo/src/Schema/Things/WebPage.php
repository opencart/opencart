<?php

namespace Melbahja\Seo\Schema\Things;

use Melbahja\Seo\Schema\Thing;

class WebPage extends Thing
{
	public function __construct()
	{
		parent::__construct("WebPage", []);
	}

	public function setAtId(string $value) :self
	{
		$this->data['@id']=$value;
		return $this;
	}

	public function setUrl(string $value) :self
	{
		$this->data['url']=$value;
		return $this;
	}

	public function setName(string $value) :self
	{
		$this->data['name']=$value;
		return $this;
	}
}