<?php
namespace Tests\Melbahja\Seo;

use Melbahja\Seo\MetaTags;

class MetaTagsTest extends TestCase
{

	public function testMetaTags()
	{
		$metatags = new MetaTags(
		[
			'title' => 'My new article',
			'description' => 'My new article about how php is awesome',
			'keywords' => 'php, programming',
			'robots' => 'index, nofollow',
			'author' => 'Mohamed Elbahja'
		]);

		$this->assertEquals('<title>My new article</title><meta name="title" content="My new article" /><meta name="description" content="My new article about how php is awesome" /><meta name="keywords" content="php, programming" /><meta name="robots" content="index, nofollow" /><meta name="author" content="Mohamed Elbahja" /><meta property="twitter:title" content="My new article" /><meta property="twitter:description" content="My new article about how php is awesome" /><meta property="og:title" content="My new article" /><meta property="og:description" content="My new article about how php is awesome" />',
			str_replace("\n", '', (string) $metatags)
		);

		$metatags = new MetaTags();

		$metatags
				->title('PHP SEO')
				->description('This is my description')
				->meta('author', 'Mohamed Elabhja')
				->image('https://avatars3.githubusercontent.com/u/8259014')
				->mobile('https://m.example.com')
				->canonical('https://example.com')
				->shortlink('https://git.io/phpseo')
				->amp('https://apm.example.com')
				->hreflang('es-es', 'https://example.com/es/');

		$this->assertNotEmpty((string) $metatags);

		$this->assertEquals('<title>PHP SEO</title><meta name="title" content="PHP SEO" /><meta name="description" content="This is my description" /><meta name="author" content="Mohamed Elabhja" /><link href="https://m.example.com" rel="alternate" media="only screen and (max-width: 640px)" /><link rel="canonical" href="https://example.com" /><link rel="shortlink" href="https://git.io/phpseo" /><link rel="amphtml" href="https://apm.example.com" /><link rel="alternate" href="https://example.com/es/" hreflang="es-es" /><meta property="twitter:title" content="PHP SEO" /><meta property="twitter:description" content="This is my description" /><meta property="twitter:card" content="summary_large_image" /><meta property="twitter:image" content="https://avatars3.githubusercontent.com/u/8259014" /><meta property="og:title" content="PHP SEO" /><meta property="og:description" content="This is my description" /><meta property="og:image" content="https://avatars3.githubusercontent.com/u/8259014" />',
			str_replace("\n", '', (string)$metatags)
		);

	}
}
