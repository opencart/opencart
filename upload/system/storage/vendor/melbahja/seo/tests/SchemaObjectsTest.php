<?php

namespace Tests\Melbahja\Seo;

use Melbahja\Seo\Schema;
use Melbahja\Seo\Schema\Things\ContactPoint;
use Melbahja\Seo\Schema\Things\Organization;

class SchemaObjectsTest extends TestCase
{
	public function testSchemaObjectsResults()
	{
		$organization=new Organization();
		$organization->setUrl("https://example.com")
			->setLogo("https://example.com/logo.png")
			->setContactPoint((new ContactPoint())
				->setTelephone("+1-000-555-1212")
				->setContactType("customer service"));;
		$schema=new Schema($organization);

		$this->assertEquals('{"@context":"https:\/\/schema.org","@graph":[{"url":"https:\/\/example.com","logo":"https:\/\/example.com\/logo.png","contactPoint":{"telephone":"+1-000-555-1212","contactType":"customer service","@type":"ContactPoint","@context":"https:\/\/schema.org\/"},"@type":"Organization","@context":"https:\/\/schema.org\/"}]}', json_encode($schema));

	}
}
