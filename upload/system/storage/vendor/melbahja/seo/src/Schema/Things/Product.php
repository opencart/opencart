<?php

namespace Melbahja\Seo\Schema\Things;

use Melbahja\Seo\Schema\Thing;

class Product extends Thing
{
	public function __construct()
	{
		parent::__construct("Product", []);
	}

	public function setName(string $value) :self
	{
		$this->data['name']=$value;
		return $this;
	}

	public function setSku(string $value) :self
	{
		$this->data['sku']=$value;
		return $this;
	}

	public function setImage(string $value) :self
	{
		$this->data['image']=$value;
		return $this;
	}

	public function setDescription(string $value) :self
	{
		$this->data['description']=$value;
		return $this;
	}

	public function setOffers(Offer $value) :self
	{
		$this->data['offers']=$value;
		return $this;
	}
}