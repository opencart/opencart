<?php
namespace Opencart\Catalog\Controller\Product;
/**
 * Class Thumb
 *
 * Can be loaded using $this->load->controller('product/thumb', $product_data);
 *
 * @example
 *
 * $product_data = [
 *     'description' => '',
 *     'thumb'       => '',
 *     'price'       => 1.00,
 *     'special'     => 0.00,
 *     'tax'         => 0.00,
 *     'minimum'     => 1,
 *     'href'        => ''
 * ];
 *
 * @package Opencart\Catalog\Controller\Product
 */
class Thumb extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return string
	 */
	public function index(array $data): string {
		$this->load->language('product/thumb');

		$data['cart'] = $this->url->link('common/cart.info', 'language=' . $this->config->get('config_language'));

		$data['cart_add'] = $this->url->link('checkout/cart.add', 'language=' . $this->config->get('config_language'));
		$data['wishlist_add'] = $this->url->link('account/wishlist.add', 'language=' . $this->config->get('config_language'));
		$data['compare_add'] = $this->url->link('product/compare.add', 'language=' . $this->config->get('config_language'));

		$data['review_status'] = (int)$this->config->get('config_review_status');

		$data['currency'] = $this->session->data['currency'];

		return $this->load->view('product/thumb', $data);
	}
}
