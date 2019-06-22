<?php
class ControllerCommonPagination extends Controller {
	public function index(int $total, $limit = '') {
		$provider_url = $this->provider->link('', array('page' => '{page}'));

		$limit = empty($limit) ? $this->provider->limit : $limit;

		$url = str_replace('%7Bpage%7D', '{page}', $provider_url);

		$num_links = 8;
		$num_pages = ceil($total / $limit);

		$data['page'] = $this->provider->page;

		if ($this->provider->page > 1) {
			$data['first'] = str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $url);

			if ($this->provider->page - 1 === 1) {
				$data['prev'] = str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $url);
			} else {
				$data['prev'] = str_replace('{page}', $this->provider->page - 1, $url);
			}
		} else {
			$data['first'] = '';
			$data['prev'] = '';
		}

		$data['links'] = array();

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $this->provider->page - floor($num_links / 2);
				$end = $this->provider->page + floor($num_links / 2);

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
				$data['links'][] = array(
					'page' => $i,
					'href' => str_replace('{page}', $i, $url)
				);
			}
		}

		if ($num_pages > $this->provider->page) {
			$data['next'] = str_replace('{page}', $this->provider->page + 1, $url);
			$data['last'] = str_replace('{page}', $num_pages, $url);
		} else {
			$data['next'] = '';
			$data['last'] = '';
		}

		if ($num_pages > 1) {
			return $this->load->view('common/pagination', $data);
		} else {
			return '';
		}
	}

	public function results(int $total, $limit = '')
	{
		$limit = empty($limit) ? $this->provider->limit : $limit;

		return sprintf($this->language->get('text_pagination'), ($total) ? (($this->provider->page - 1) * $limit) + 1 : 0, ((($this->provider->page - 1) * $limit) > ($total - $limit)) ? $total : ((($this->provider->page - 1) * $limit) + $limit), $total, ceil($total / $limit));
	}
}
