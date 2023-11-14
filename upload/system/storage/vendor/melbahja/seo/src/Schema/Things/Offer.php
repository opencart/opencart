<?php

namespace Melbahja\Seo\Schema\Things;

use Melbahja\Seo\Schema\Thing;

class Offer extends Thing
{
	public function __construct()
	{
		parent::__construct("Offer", []);
	}

	public function setAvailability(string $value) :self
	{
		$this->data['availability']=$value;
		return $this;
	}

	public function setPriceCurrency(string $value) :self
	{
		$this->data['priceCurrency']=$value;
		return $this;
	}

	public function setPrice(float $value) :self
	{
		$this->data['price']=$value;
		return $this;
	}

	public function setUrl(string $value) :self
	{
		$this->data['url']=$value;
		return $this;
	}
}