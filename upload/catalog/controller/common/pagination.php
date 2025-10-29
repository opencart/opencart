<?php
namespace Opencart\Catalog\Controller\Common;
/**
 * Class Pagination
 *
 * Can be loaded using $this->load->controller('common/pagination', $setting);
 *
 * @example
 *
 * $setting = [
 *     'total' => 10,
 *     'page'  => 1,
 *     'limit' => 10,
 *     'url'   => ''
 * ];
 *
 * @package Opencart\Catalog\Controller\Common
 */
class Pagination extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param array<string, mixed> $setting array of filters
	 *
	 * @return string
	 */
	public function index(array $setting): string {
		if (isset($setting['total'])) {
			$total = (int)$setting['total'];
		} else {
			$total = 0;
		}

		if (isset($setting['page']) && $setting['page'] > 0) {
			$page = (int)$setting['page'];
		} else {
			$page = 1;
		}

		if (isset($setting['limit']) && (int)$setting['limit']) {
			$limit = (int)$setting['limit'];
		} else {
			$limit = 10;
		}

		if (isset($setting['url'])) {
			$url = str_replace('{page}', '%d', (string)$setting['url']);
		} else {
			$url = '';
		}

		$num_links = 8;
		$num_pages = ceil($total / $limit);

		if ($page > 1 && $num_pages < $page) {
			$back = true;
		} else {
			$back = false;
		}

		$data['page'] = $page;

		if ($page > 1) {
			$data['first'] = sprintf($url, 0);

			if ($page - 1 === 1) {
				$data['prev'] = sprintf($url, 0);
			} else {
				$data['prev'] = sprintf($url, $page - 1);
			}
		} else {
			$data['first'] = '';
			$data['prev'] = '';
		}

		$data['links'] = [];

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);

				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}

				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}

			for ($i = $start; $i <= $end; $i++) {
				$data['links'][] = [
					'page' => $i,
					'href' => sprintf($url, $i)
				];
			}
		}

		if ($num_pages > $page) {
			$data['next'] = sprintf($url, $page + 1);
			$data['last'] = sprintf($url, $num_pages);
		} else {
			$data['next'] = '';
			$data['last'] = '';
		}

		if ($num_pages > 1 || $back) {
			return $this->load->view('common/pagination', $data);
		} else {
			return '';
		}
	}
}
