<?php
namespace Opencart\Catalog\Controller\Common;
/**
 * Class Wishlist
 *
 * @package Opencart\Catalog\Controller\Common
 */
class Wishlist extends \Opencart\System\Engine\Controller {
	/**
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/wishlist');

		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['wishlist'] = $this->url->link('account/wishlist', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : ''));

		return $this->load->view('common/wishlist', $data);
	}

	/**
	 * @return void
	 */
	public function info(): void {
		$this->response->setOutput($this->index());
	}
}