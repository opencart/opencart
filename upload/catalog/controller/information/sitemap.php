<?php
namespace Opencart\Catalog\Controller\Information;
class Sitemap extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('information/sitemap');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/sitemap', 'language=' . $this->config->get('config_language'))
		];

		$this->load->model('catalog/category');

		$data['categories'] = [];

		$categories_1 = $this->model_catalog_category->getCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = [];

			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = [];

				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = [
						'name' => $category_3['name'],
						'href' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $category_1['category_id'] . '_' . $category_2['category_id'] . '_' . $category_3['category_id'])
					];
				}

				$level_2_data[] = [
					'name'     => $category_2['name'],
					'children' => $level_3_data,
					'href'     => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $category_1['category_id'] . '_' . $category_2['category_id'])
				];
			}

			$data['categories'][] = [
				'name'     => $category_1['name'],
				'children' => $level_2_data,
				'href'     => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $category_1['category_id'])
			];
		}

		$data['special'] = $this->url->link('product/special', 'language=' . $this->config->get('config_language'));
		$data['account'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));
		$data['edit'] = $this->url->link('account/edit', 'language=' . $this->config->get('config_language'));
		$data['password'] = $this->url->link('account/password', 'language=' . $this->config->get('config_language'));
		$data['address'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));
		$data['history'] = $this->url->link('account/order', 'language=' . $this->config->get('config_language'));
		$data['download'] = $this->url->link('account/download', 'language=' . $this->config->get('config_language'));
		$data['cart'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));
		$data['checkout'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));
		$data['search'] = $this->url->link('product/search', 'language=' . $this->config->get('config_language'));
		$data['contact'] = $this->url->link('information/contact', 'language=' . $this->config->get('config_language'));

		$this->load->model('catalog/information');

		$data['informations'] = [];

		foreach ($this->model_catalog_information->getInformations() as $result) {
			$data['informations'][] = [
				'title' => $result['title'],
				'href'  => $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $result['information_id'])
			];
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('information/sitemap', $data));
	}
}
