<?php
namespace Tests\Melbahja\Seo;

use Melbahja\Seo\Schema;
use Melbahja\Seo\Schema\Thing;

class SchemaTest extends TestCase
{

	public function testSchemaResults()
	{
		$schema = new Schema(
			new Thing('Organization', [
				'url'          => 'https://example.com',
				'logo'         => 'https://example.com/logo.png',
				'contactPoint' => new Thing('ContactPoint', [
					'telephone' => '+1-000-555-1212',
					'contactType' => 'customer service'
				])
			])
		);


		$this->assertEquals('{"@context":"https:\/\/schema.org","@graph":[{"url":"https:\/\/example.com","logo":"https:\/\/example.com\/logo.png","contactPoint":{"telephone":"+1-000-555-1212","contactType":"customer service","@type":"ContactPoint","@context":"https:\/\/schema.org\/"},"@type":"Organization","@context":"https:\/\/schema.org\/"}]}', json_encode($schema));

		$product = new Thing('Product');
		$product->name  = "Foo Bar";
		$product->sku   = "sk12";
		$product->image = "/image.jpeg";
		$product->description = "testing";
		$product->offers = new Thing('Offer', [
			'availability' => 'https://schema.org/InStock',
			'priceCurrency' => 'USD',
			"price" => "119.99",
			'url' => 'https://gool.com',
		]);

		$webpage = new Thing("WebPage", [
			'@id' => "https://example.com/product/#webpage",
			'url' => "https://example.com/product",
			'name' => 'Foo Bar',
		]);


		$schema = new Schema(
			$product,
			$webpage
		);


		$this->assertEquals('<script type="application/ld+json">{"@context":"https:\/\/schema.org","@graph":[{"name":"Foo Bar","sku":"sk12","image":"\/image.jpeg","description":"testing","offers":{"availability":"https:\/\/schema.org\/InStock","priceCurrency":"USD","price":"119.99","url":"https:\/\/gool.com","@type":"Offer","@context":"https:\/\/schema.org\/"},"@type":"Product","@context":"https:\/\/schema.org\/"},{"@id":"https:\/\/example.com\/product\/#webpage","url":"https:\/\/example.com\/product","name":"Foo Bar","@type":"WebPage","@context":"https:\/\/schema.org\/"}]}</script>', (string) $schema);

	}
}
