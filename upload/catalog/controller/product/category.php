<?php
namespace Opencart\Catalog\Controller\Product;
/**
 * Class Category
 *
 * @package Opencart\Catalog\Controller\Product
 */
class Category extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index() {
		if (isset($this->request->get['product_id'])) {
			return $this->load->controller('product/product');
		}

		$this->load->language('product/category');

		if (isset($this->request->get['path'])) {
			$path = (string)$this->request->get['path'];
		} else {
			$path = '';
		}

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'asc';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit']) && (int)$this->request->get['limit']) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_pagination');
		}

		// Category
		$parts = explode('_', $path);

		$category_id = (int)array_pop($parts);

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if (!$category_info) {
			return new \Opencart\System\Engine\Action('error/not_found');
		}

		$this->document->setTitle($category_info['meta_title']);
		$this->document->setDescription($category_info['meta_description']);
		$this->document->setKeywords($category_info['meta_keyword']);

		$this->document->addScript('view/javascript/catalog/category.js');

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$allowed = [
			'sort',
			'order',
			'limit'
		];

		$url = '&' . http_build_query(array_diff_key($this->request->get, array_flip($allowed)));

		$path = '';

		foreach ($parts as $path_id) {
			if (!$path) {
				$path = (int)$path_id;
			} else {
				$path .= '_' . (int)$path_id;
			}

			$parent_info = $this->model_catalog_category->getCategory((int)$path_id);

			if ($parent_info) {
				$data['breadcrumbs'][] = [
					'text' => $parent_info['name'],
					'href' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $path . $url)
				];
			}
		}

		$allowed = [
			'path',
			'filter',
			'sort',
			'order',
			'page',
			'limit'
		];

		$url = '&' . http_build_query(array_diff_key($this->request->get, array_flip($allowed)));

		// Set the last category breadcrumb
		$data['breadcrumbs'][] = [
			'text' => $category_info['name'],
			'href' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . $url)
		];

		$data['heading_title'] = $category_info['name'];

		$data['text_compare'] = sprintf($this->language->get('text_compare'), isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0);

		// Image
		$this->load->model('tool/image');

		if (!empty($category_info['image']) && is_file(DIR_IMAGE . html_entity_decode($category_info['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['image'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
		} else {
			$data['image'] = '';
		}

		$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
		$data['compare'] = $this->url->link('product/compare', 'language=' . $this->config->get('config_language'));

		$allowed = [
			'filter',
			'sort',
			'order',
			'page',
			'limit'
		];

		$url = '&' . http_build_query(array_diff_key($this->request->get, array_flip($allowed)));

		// Categories
		$data['categories'] = [];

		// Product
		$this->load->model('catalog/product');

		$results = $this->model_catalog_category->getCategories($category_id);

		foreach ($results as $result) {
			$filter_data = [
				'filter_category_id'  => $result['category_id'],
				'filter_sub_category' => false
			];

			$data['categories'][] = [
				'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
				'href' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
			];
		}

		$allowed = [
			'filter',
			'sort',
			'order',
			'page',
			'limit'
		];

		$url = '&' . http_build_query(array_diff_key($this->request->get, array_flip($allowed)));

		// Product
		$data['products'] = [];

		$filter_data = [
			'filter_category_id'  => $category_id,
			'filter_sub_category' => false,
			'filter_filter'       => $filter,
			'sort'                => $sort,
			'order'               => $order,
			'start'               => ($page - 1) * $limit,
			'limit'               => $limit
		];

		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			$description = trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')));

			if (oc_strlen($description) > $this->config->get('config_product_description_length')) {
				$description = oc_substr($description, 0, $this->config->get('config_product_description_length')) . '..';
			}

			if ($result['image'] && is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $result['image'];
			} else {
				$image = 'placeholder.png';
			}

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
			} else {
				$special = false;
			}

			if ($this->config->get('config_tax')) {
				$tax = (float)$result['special'] ? $result['special'] : $result['price'];
			} else {
				$tax = false;
			}

			$product_data = [
				'description' => $description,
				'thumb'       => $this->model_tool_image->resize($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'href'        => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
			] + $result;

			$data['products'][] = $this->load->controller('product/thumb', $product_data);
		}

		$allowed = [
			'path',
			'filter',
			'limit'
		];

		$url = '&' . http_build_query(array_diff_key($this->request->get, array_flip($allowed)));

		$data['sorts'] = [];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_default'),
			'value' => 'sort_order-asc',
			'href'  => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'name-asc',
			'href'  => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&sort=name&order=asc' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'name-desc',
			'href'  => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&sort=name&order=desc' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'price-asc',
			'href'  => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&sort=price&order=asc' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'price-desc',
			'href'  => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&sort=price&order=desc' . $url)
		];

		if ($this->config->get('config_review_status')) {
			$data['sorts'][] = [
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-desc',
				'href'  => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&sort=rating&order=desc' . $url)
			];

			$data['sorts'][] = [
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-asc',
				'href'  => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&sort=rating&order=asc' . $url)
			];
		}

		$data['sorts'][] = [
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'model-asc',
			'href'  => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&sort=model&order=asc' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'model-desc',
			'href'  => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&sort=model&order=desc' . $url)
		];

		$allowed = [
			'path',
			'filter',
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_diff_key($this->request->get, array_flip($allowed)));

		$data['limits'] = [];

		$limits = array_unique([$this->config->get('config_pagination'), 25, 50, 75, 100]);

		sort($limits);

		foreach ($limits as $value) {
			$data['limits'][] = [
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('product/category', $url . '&limit=' . $value)
			];
		}

		$url = '';

		if (isset($this->request->get['path'])) {
			$url .= '&path=' . $this->request->get['path'];
		}

		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		// Total Products
		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $product_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . $url . '&page=' . $page)
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

		// https://developers.google.com/search/blog/2011/09/pagination-with-relnext-and-relprev
		if ($page == 1) {
			$this->document->addLink($this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $this->request->get['path']), 'canonical');
		} else {
			$this->document->addLink($this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $this->request->get['path'] . '&page=' . $page), 'canonical');
		}

		if ($page > 1) {
			$this->document->addLink($this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $this->request->get['path'] . (($page - 2) ? '&page=' . ($page - 1) : '')), 'prev');
		}

		if ($limit && ceil($product_total / $limit) > $page) {
			$this->document->addLink($this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $this->request->get['path'] . '&page=' . ($page + 1)), 'next');
		}

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('product/category', $data));

		return null;
	}
}
