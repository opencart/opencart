<?php
namespace Opencart\Catalog\Controller\Product;
/**
 * Class Manufacturer
 *
 * @package Opencart\Catalog\Controller\Product
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index() {
		if (isset($this->request->get['manufacturer_id'])) {
			return $this->load->controller('product/manufacturer.info');
		}

		$this->load->language('product/manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_brand'),
			'href' => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language'))
		];

		$data['categories'] = [];

		$this->load->model('catalog/manufacturer');

		$results = $this->model_catalog_manufacturer->getManufacturers();

		foreach ($results as $result) {
			if (is_numeric(oc_substr($result['name'], 0, 1))) {
				$key = '0 - 9';
			} else {
				$key = oc_substr(oc_strtoupper($result['name']), 0, 1);
			}

			if (!isset($data['categories'][$key])) {
				$data['categories'][$key]['name'] = $key;
				$data['categories'][$key]['href'] = $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language'));
			}

			$data['categories'][$key]['manufacturer'][] = ['href' => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $result['manufacturer_id'])] + $result;
		}

		$data['continue'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('product/manufacturer_list', $data));
	}

	/**
	 * Info
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function info() {
		if (isset($this->request->get['product_id'])) {
			return $this->load->controller('product/product');
		}

		$this->load->language('product/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$manufacturer_id = (int)$this->request->get['manufacturer_id'];
		} else {
			$manufacturer_id = 0;
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
			$limit = (int)$this->config->get('config_pagination');
		}

		// Manufacturer
		$this->load->model('catalog/manufacturer');

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

		if (!$manufacturer_info) {
			return new \Opencart\System\Engine\Action('error/not_found');
		}

		$this->document->setTitle($manufacturer_info['name']);

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_brand'),
			'href' => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language'))
		];

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['breadcrumbs'][] = [
			'text' => $manufacturer_info['name'],
			'href' => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
		];

		$data['heading_title'] = $manufacturer_info['name'];

		$data['text_compare'] = sprintf($this->language->get('text_compare'), isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0);

		$data['compare'] = $this->url->link('product/compare', 'language=' . $this->config->get('config_language'));

		// Product
		$data['products'] = [];

		$filter_data = [
			'filter_manufacturer_id' => $manufacturer_id,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $limit,
			'limit'                  => $limit
		];

		// Products
		$this->load->model('catalog/product');

		// Image
		$this->load->model('tool/image');

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
				'thumb'       => $this->model_tool_image->resize($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
				'description' => $description,
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'href'        => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $result['manufacturer_id'] . '&product_id=' . $result['product_id'] . $url)
			] + $result;

			$data['products'][] = $this->load->controller('product/thumb', $product_data);
		}

		$url = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sorts'] = [];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_default'),
			'value' => 'sort_order-asc',
			'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=sort_order&order=asc' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'name-asc',
			'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=name&order=asc' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'name-desc',
			'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=name&order=desc' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'price-asc',
			'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=price&order=asc' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'price-desc',
			'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=price&order=desc' . $url)
		];

		if ($this->config->get('config_review_status')) {
			$data['sorts'][] = [
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-desc',
				'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=rating&order=desc' . $url)
			];

			$data['sorts'][] = [
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-asc',
				'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=rating&order=asc' . $url)
			];
		}

		$data['sorts'][] = [
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'model-asc',
			'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=model&order=asc' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'model-desc',
			'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=model&order=desc' . $url)
		];

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['limits'] = [];

		$limits = array_unique([$this->config->get('config_pagination'), 25, 50, 75, 100]);

		sort($limits);

		foreach ($limits as $value) {
			$data['limits'][] = [
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&limit=' . $value)
			];
		}

		$url = '';

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
			'url'   => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&page=' . $page)
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

		// https://developers.google.com/search/blog/2011/09/pagination-with-relnext-and-relprev
		if ($page == 1) {
			$this->document->addLink($this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
		} else {
			$this->document->addLink($this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&page=' . $page), 'canonical');
		}

		if ($page > 1) {
			$this->document->addLink($this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . (($page - 2) ? '&page=' . ($page - 1) : '')), 'prev');
		}

		if ($limit && ceil($product_total / $limit) > $page) {
			$this->document->addLink($this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&page=' . ($page + 1)), 'next');
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

		$this->response->setOutput($this->load->view('product/manufacturer_info', $data));

		return null;
	}
}
