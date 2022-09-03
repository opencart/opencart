<?php
namespace Opencart\Admin\Controller\Marketplace;
class Marketplace extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('marketplace/marketplace');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_search'])) {
			$filter_search = $this->request->get['filter_search'];
		} else {
			$filter_search = '';
		}

		if (isset($this->request->get['filter_category'])) {
			$filter_category = $this->request->get['filter_category'];
		} else {
			$filter_category = '';
		}

		if (isset($this->request->get['filter_license'])) {
			$filter_license = $this->request->get['filter_license'];
		} else {
			$filter_license = '';
		}

		if (isset($this->request->get['filter_rating'])) {
			$filter_rating = $this->request->get['filter_rating'];
		} else {
			$filter_rating = '';
		}

		if (isset($this->request->get['filter_member_type'])) {
			$filter_member_type = $this->request->get['filter_member_type'];
		} else {
			$filter_member_type = '';
		}

		if (isset($this->request->get['filter_member'])) {
			$filter_member = $this->request->get['filter_member'];
		} else {
			$filter_member = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_modified';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . $this->request->get['filter_search'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_license'])) {
			$url .= '&filter_license=' . $this->request->get['filter_license'];
		}

		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}

		if (isset($this->request->get['filter_member_type'])) {
			$url .= '&filter_member_type=' . $this->request->get['filter_member_type'];
		}

		if (isset($this->request->get['filter_member'])) {
			$url .= '&filter_member=' . $this->request->get['filter_member'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$time = time();

		// We create a hash from the data in a similar method to how amazon does things.
		$string = 'api/marketplace/list' . "\n";
		$string .= $this->config->get('opencart_username') . "\n";
		$string .= $this->request->server['HTTP_HOST'] . "\n";
		$string .= VERSION . "\n";
		$string .= $time . "\n";

		$signature = base64_encode(hash_hmac('sha1', $string, $this->config->get('opencart_secret'), 1));

		$url  = '&username=' . urlencode($this->config->get('opencart_username'));
		$url .= '&domain=' . $this->request->server['HTTP_HOST'];
		$url .= '&version=' . VERSION;
		$url .= '&time=' . $time;
		$url .= '&signature=' . rawurlencode($signature);

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . urlencode($this->request->get['filter_search']);
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_license'])) {
			$url .= '&filter_license=' . $this->request->get['filter_license'];
		}

		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}

		if (isset($this->request->get['filter_member_type'])) {
			$url .= '&filter_member_type=' . $this->request->get['filter_member_type'];
		}

		if (isset($this->request->get['filter_member'])) {
			$url .= '&filter_member=' . urlencode($this->request->get['filter_member']);
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/marketplace' . $url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);

		$response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		$response_info = json_decode($response, true);

		if (isset($response_info['extension_total'])) {
			$extension_total = (int)$response_info['extension_total'];
		} else {
			$extension_total = 0;
		}

		$url = '';

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . $this->request->get['filter_search'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_license'])) {
			$url .= '&filter_license=' . $this->request->get['filter_license'];
		}

		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}

		if (isset($this->request->get['filter_member_type'])) {
			$url .= '&filter_member_type=' . $this->request->get['filter_member_type'];
		}

		if (isset($this->request->get['filter_member'])) {
			$url .= '&filter_member=' . $this->request->get['filter_member'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['promotions'] = [];

		if (isset($response_info['promotions']) && $page == 1) {
			foreach ($response_info['promotions'] as $result) {
				$data['promotions'][] = [
					'name'         => $result['name'],
					'description'  => $result['description'],
					'image'        => $result['image'],
					'license'      => $result['license'],
					'price'        => $result['price'],
					'rating'       => $result['rating'],
					'rating_total' => $result['rating_total'],
					'href'         => $this->url->link('marketplace/marketplace.info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id'] . $url)
				];
			}
		}

		$data['extensions'] = [];

		if (isset($response_info['extensions'])) {
			foreach ($response_info['extensions'] as $result) {
				$data['extensions'][] = [
					'name'         => $result['name'],
					'description'  => $result['description'],
					'image'        => $result['image'],
					'license'      => $result['license'],
					'price'        => $result['price'],
					'rating'       => $result['rating'],
					'rating_total' => $result['rating_total'],
					'href'         => $this->url->link('marketplace/marketplace.info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id'] . $url)
				];
			}
		}

		if (isset($response_info['error'])) {
			$data['error_signature'] = $response_info['error'];
		} else {
			$data['error_signature'] = '';
		}

		// Categories
		$url = '';

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . $this->request->get['filter_search'];
		}

		if (isset($this->request->get['filter_license'])) {
			$url .= '&filter_license=' . $this->request->get['filter_license'];
		}

		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}

		if (isset($this->request->get['filter_member_type'])) {
			$url .= '&filter_member_type=' . $this->request->get['filter_member_type'];
		}

		if (isset($this->request->get['filter_member'])) {
			$url .= '&filter_member=' . $this->request->get['filter_member'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		$data['categories'] = [];

		$data['categories'][] = [
			'text'  => $this->language->get('text_all'),
			'value' => '',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['categories'][] = [
			'text'  => $this->language->get('text_theme'),
			'value' => 'theme',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=theme' . $url)
		];

		$data['categories'][] = [
			'text'  => $this->language->get('text_marketplace'),
			'value' => 'marketplace',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=marketplace' . $url)
		];

		$data['categories'][] = [
			'text'  => $this->language->get('text_language'),
			'value' => 'language',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=language' . $url)
		];

		$data['categories'][] = [
			'text'  => $this->language->get('text_payment'),
			'value' => 'payment',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=payment' . $url)
		];

		$data['categories'][] = [
			'text' => $this->language->get('text_shipping'),
			'value' => 'shipping',
			'href' => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=shipping' . $url)
		];

		$data['categories'][] = [
			'text'  => $this->language->get('text_module'),
			'value' => 'module',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=module' . $url)
		];

		$data['categories'][] = [
			'text'  => $this->language->get('text_total'),
			'value' => 'total',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=total' . $url)
		];

		$data['categories'][] = [
			'text'  => $this->language->get('text_feed'),
			'value' => 'feed',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=feed' . $url)
		];

		$data['categories'][] = [
			'text'  => $this->language->get('text_report'),
			'value' => 'report',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=report' . $url)
		];

		$data['categories'][] = [
			'text'  => $this->language->get('text_other'),
			'value' => 'other',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=other' . $url)
		];

		// Licenses
		$url = '';

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . $this->request->get['filter_search'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}

		if (isset($this->request->get['filter_member_type'])) {
			$url .= '&filter_member_type=' . $this->request->get['filter_member_type'];
		}

		if (isset($this->request->get['filter_member'])) {
			$url .= '&filter_member=' . $this->request->get['filter_member'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['licenses'] = [];

		$data['licenses'][] = [
			'text'  => $this->language->get('text_all'),
			'value' => '',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['licenses'][] = [
			'text'  => $this->language->get('text_recommended'),
			'value' => 'recommended',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_license=recommended' . $url)
		];

		$data['licenses'][] = [
			'text'  => $this->language->get('text_free'),
			'value' => 'free',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_license=free' . $url)
		];

		$data['licenses'][] = [
			'text'  => $this->language->get('text_paid'),
			'value' => 'paid',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_license=paid' . $url)
		];

		$data['licenses'][] = [
			'text'  => $this->language->get('text_purchased'),
			'value' => 'purchased',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_license=purchased' . $url)
		];

		// Sort
		$url = '';

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . $this->request->get['filter_search'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_license'])) {
			$url .= '&filter_license=' . $this->request->get['filter_license'];
		}

		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}

		if (isset($this->request->get['filter_member_type'])) {
			$url .= '&filter_member_type=' . $this->request->get['filter_member_type'];
		}

		if (isset($this->request->get['filter_member'])) {
			$url .= '&filter_member=' . $this->request->get['filter_member'];
		}

		$data['sorts'] = [];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_modified'),
			'value' => 'date_modified',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=date_modified')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_added'),
			'value' => 'date_added',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=date_added')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_rating'),
			'value' => 'rating',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=rating')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_name'),
			'value' => 'name',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=name')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_price'),
			'value' => 'price',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=price')
		];

		// Pagination
		$url = '';

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . $this->request->get['filter_search'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_license'])) {
			$url .= '&filter_license=' . $this->request->get['filter_license'];
		}

		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}

		if (isset($this->request->get['filter_member_type'])) {
			$url .= '&filter_member_type=' . $this->request->get['filter_member_type'];
		}

		if (isset($this->request->get['filter_member'])) {
			$url .= '&filter_member=' . $this->request->get['filter_member'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $extension_total,
			'page'  => $page,
			'limit' => 12,
			'url'   => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['filter_search'] = $filter_search;
		$data['filter_category'] = $filter_category;
		$data['filter_license'] = $filter_license;
		$data['filter_member_type'] = $filter_member_type;
		$data['filter_rating'] = $filter_rating;
		$data['sort'] = $sort;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/marketplace_list', $data));
	}

	public function info(): object|null {
		if (isset($this->request->get['extension_id'])) {
			$extension_id = (int)$this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		$time = time();

		// We create a hash from the data in a similar method to how amazon does things.
		$string = 'api/marketplace/info' . "\n";
		$string .= $this->config->get('opencart_username') . "\n";
		$string .= $this->request->server['HTTP_HOST'] . "\n";
		$string .= VERSION . "\n";
		$string .= $extension_id . "\n";
		$string .= $time . "\n";

		$signature = base64_encode(hash_hmac('sha1', $string, $this->config->get('opencart_secret'), 1));

		$url  = '&username=' . urlencode($this->config->get('opencart_username'));
		$url .= '&domain=' . $this->request->server['HTTP_HOST'];
		$url .= '&version=' . VERSION;
		$url .= '&extension_id=' . $extension_id;
		$url .= '&time=' . $time;
		$url .= '&signature=' . rawurlencode($signature);

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/marketplace/info' . $url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);

		$response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		$response_info = json_decode($response, true);

		if ($response_info) {
			$this->load->language('marketplace/marketplace');

			$this->document->setTitle($this->language->get('heading_title'));

			if (isset($response_info['error'])) {
				$data['error_signature'] = $response_info['error'];
			} else {
				$data['error_signature'] = '';
			}

			$url = '';

			if (isset($this->request->get['filter_search'])) {
				$url .= '&filter_search=' . $this->request->get['filter_search'];
			}

			if (isset($this->request->get['filter_category'])) {
				$url .= '&filter_category=' . $this->request->get['filter_category'];
			}

			if (isset($this->request->get['filter_license'])) {
				$url .= '&filter_license=' . $this->request->get['filter_license'];
			}

			if (isset($this->request->get['filter_username'])) {
				$url .= '&filter_username=' . $this->request->get['filter_username'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['back'] = $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url);

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url)
			];

			$data['banner'] = $response_info['banner'];

			$data['extension_id'] = (int)$this->request->get['extension_id'];
			$data['name'] = $response_info['name'];
			$data['description'] = $response_info['description'];
			$data['documentation'] = $response_info['documentation'];
			$data['price'] = $response_info['price'];

			$data['license'] = $response_info['license'];
			$data['license_period'] = $response_info['license_period'];
			$data['purchased'] = $response_info['purchased'];

			$data['rating'] = $response_info['rating'];
			$data['rating_total'] = $response_info['rating_total'];

			$data['downloaded'] = $response_info['downloaded'];
			$data['sales'] = $response_info['sales'];

			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($response_info['date_added']));
			$data['date_modified'] = date($this->language->get('date_format_short'), strtotime($response_info['date_modified']));

			$data['member_username'] = $response_info['member_username'];
			$data['member_image'] = $response_info['member_image'];
			$data['member_date_added'] = $response_info['member_date_added'];
			$data['filter_member'] = $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_member=' . $response_info['member_username']);

			if (isset($response_info['comment_total'])) {
				$data['comment_total'] = $response_info['comment_total'];
			} else {
				$data['comment_total'] = 0;
			}

			$data['images'] = [];

			foreach ($response_info['images'] as $result) {
				$data['images'][] = [
					'thumb' => $result['thumb'],
					'popup' => $result['popup']
				];
			}

			$this->load->model('setting/extension');

			$data['downloads'] = [];

			if ($response_info['downloads']) {
				$this->session->data['extension_download'][$extension_id] = $response_info['downloads'];
			} else {
				$this->session->data['extension_download'][$extension_id] = [];
				$this->session->data['extension_download'][$extension_id] = [];
			}

			$this->document->addStyle('view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('view/javascript/jquery/magnific/jquery.magnific-popup.min.js');

			$data['user_token'] = $this->session->data['user_token'];

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('marketplace/marketplace_info', $data));

			return null;
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}
	}

	public function extension(): void {
		$this->load->language('marketplace/marketplace');

		if (isset($this->request->get['extension_id'])) {
			$extension_id = (int)$this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		$this->load->model('setting/extension');

		$data['downloads'] = [];

		if (isset($this->session->data['extension_download'][$extension_id])) {
			$results = $this->session->data['extension_download'][$extension_id];

			foreach ($results as $result) {
				if (substr($result['filename'], -10) == '.ocmod.zip') {
					$code = basename($result['filename'], '.ocmod.zip');

					$install_info = $this->model_setting_extension->getInstallByCode($code);

					// Download
					if (!$install_info) {
						$download = $this->url->link('marketplace/marketplace.download', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&extension_download_id=' . $result['extension_download_id']);
					} else {
						$download = '';
					}

			 		// Install
					if ($install_info && !$install_info['status']) {
						$install = $this->url->link('marketplace/installer.install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $install_info['extension_install_id']);
					} else {
						$install = '';
					}

					// Uninstall
					if ($install_info && $install_info['status']) {
						$uninstall = $this->url->link('marketplace/installer.uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $install_info['extension_install_id']);
					} else {
						$uninstall = '';
					}

					// Delete
					if ($install_info && !$install_info['status']) {
						$delete = $this->url->link('marketplace/installer.delete', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $install_info['extension_install_id']);
					} else {
						$delete = '';
					}

					$data['downloads'][] = [
						'name'       => $result['name'],
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
						'download'   => $download,
						'install'    => $install,
						'uninstall'  => $uninstall,
						'delete'     => $delete
					];
				}
			}
		}

		$this->response->setOutput($this->load->view('marketplace/marketplace_extension', $data));
	}

	public function purchase(): void {
		$this->load->language('marketplace/marketplace');

		$json = [];

		if (isset($this->request->get['extension_id'])) {
			$extension_id = (int)$this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/marketplace')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$this->config->get('opencart_username') || !$this->config->get('opencart_secret')) {
			$json['error'] = $this->language->get('error_opencart');
		}

		if (!$this->request->post['pin']) {
			$json['error'] = $this->language->get('error_pin');
		}

		if (!$json) {
			$time = time();

			// We create a hash from the data in a similar method to how amazon does things.
			$string = 'api/marketplace/purchase' . "\n";
			$string .= $this->config->get('opencart_username') . "\n";
			$string .= $this->request->server['HTTP_HOST'] . "\n";
			$string .= VERSION . "\n";
			$string .= $extension_id . "\n";
			$string .= $this->request->post['pin'] . "\n";
			$string .= $time . "\n";

			$signature = base64_encode(hash_hmac('sha1', $string, $this->config->get('opencart_secret'), 1));

			$url  = '&username=' . urlencode($this->config->get('opencart_username'));
			$url .= '&domain=' . $this->request->server['HTTP_HOST'];
			$url .= '&version=' . urlencode(VERSION);
			$url .= '&extension_id=' . $extension_id;
			$url .= '&time=' . $time;
			$url .= '&signature=' . rawurlencode($signature);

			$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/marketplace/purchase' . $url);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

			$response = curl_exec($curl);

			curl_close($curl);

			$response_info = json_decode($response, true);

			if (isset($response_info['success'])) {
				// If purchase complete we update the status for all downloads to be available.
				if (isset($this->session->data['extension_download'][$extension_id])) {
					$results = $this->session->data['extension_download'][$extension_id];

					foreach (array_keys($results) as $key) {
						$this->session->data['extension_download'][$extension_id][$key]['status'] = 1;
					}
				}

				$json['success'] = $response_info['success'];
			} elseif (isset($response_info['error'])) {
				$json['error'] = $response_info['error'];
			} else {
				$json['error'] = $this->language->get('error_purchase');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function download(): void {
		$this->load->language('marketplace/marketplace');

		$json = [];

		if (isset($this->request->get['extension_id'])) {
			$extension_id = (int)$this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = (int)$this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/marketplace')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$time = time();

			// We create a hash from the data in a similar method to how amazon does things.
			$string  = 'api/marketplace/download' . "\n";
			$string .= $this->config->get('opencart_username') . "\n";
			$string .= $this->request->server['HTTP_HOST'] . "\n";
			$string .= VERSION . "\n";
			$string .= $extension_id . "\n";
			$string .= $extension_download_id . "\n";
			$string .= $time . "\n";

			$signature = base64_encode(hash_hmac('sha1', $string, $this->config->get('opencart_secret'), 1));

			$url  = '&username=' . urlencode($this->config->get('opencart_username'));
			$url .= '&domain=' . $this->request->server['HTTP_HOST'];
			$url .= '&version=' . urlencode(VERSION);
			$url .= '&extension_id=' . $extension_id;
			$url .= '&extension_download_id=' . $extension_download_id;
			$url .= '&time=' . $time;
			$url .= '&signature=' . rawurlencode($signature);

			$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/marketplace/download&extension_download_id=' . $extension_download_id . $url);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

			$response = curl_exec($curl);

			$response_info = json_decode($response, true);

			curl_close($curl);

			if (isset($response_info['download'])) {
				if (substr($response_info['filename'], -10) == '.ocmod.zip') {
					$handle = fopen(DIR_STORAGE . 'marketplace/' . $response_info['filename'], 'w');

					$download = file_get_contents($response_info['download']);

					fwrite($handle, $download);

					fclose($handle);

					$extension_data = [
						'extension_id'          => $extension_id,
						'extension_download_id' => $extension_download_id,
						'name'                  => $response_info['name'],
						'code' 				    => basename($response_info['filename'], '.ocmod.zip'),
						'author'                => $response_info['author'],
						'version'               => $response_info['version'],
						'link' 					=> OPENCART_SERVER . 'index.php?route=marketplace/extension.info&extension_id=' . $extension_id
					];

					$this->load->model('setting/extension');

					$json['extension_install_id'] = $this->model_setting_extension->addInstall($extension_data);

					$json['success'] = $this->language->get('text_success');
				} else {
					$json['redirect'] = $response_info['download'];
				}
			} elseif (isset($response_info['error'])) {
				$json['error'] = $response_info['error'];
			} else {
				$json['error'] = $this->language->get('error_download');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addComment(): void {
		$this->load->language('marketplace/marketplace');

		$json = [];

		if (isset($this->request->get['extension_id'])) {
			$extension_id = (int)$this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		if (isset($this->request->get['parent_id'])) {
			$parent_id = (int)$this->request->get['parent_id'];
		} else {
			$parent_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/marketplace')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$this->config->get('opencart_username') || !$this->config->get('opencart_secret')) {
			$json['error'] = $this->language->get('error_opencart');
		}

		if (!$json) {
			$time = time();

			// We create a hash from the data in a similar method to how amazon does things.
			$string = 'api/marketplace/addcomment' . "\n";
			$string .= urlencode($this->config->get('opencart_username')) . "\n";
			$string .= $this->request->server['HTTP_HOST'] . "\n";
			$string .= urlencode(VERSION) . "\n";
			$string .= $extension_id . "\n";
			$string .= $parent_id . "\n";
			$string .= urlencode(base64_encode($this->request->post['comment'])) . "\n";
			$string .= $time . "\n";

			$signature = base64_encode(hash_hmac('sha1', $string, $this->config->get('opencart_secret'), 1));

			$url  = '&username=' . $this->config->get('opencart_username');
			$url .= '&domain=' . $this->request->server['HTTP_HOST'];
			$url .= '&version=' . VERSION;
			$url .= '&extension_id=' . $extension_id;
			$url .= '&parent_id=' . $parent_id;
			$url .= '&time=' . $time;
			$url .= '&signature=' . rawurlencode($signature);

			$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/marketplace/addcomment&extension_id=' . $extension_id . $url);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, ['comment' => $this->request->post['comment']]);

			$response = curl_exec($curl);

			curl_close($curl);

			$response_info = json_decode($response, true);

			if (isset($response_info['success'])) {
				$json['success'] = $response_info['success'];
			} elseif (isset($response_info['error'])) {
				$json['error'] = $response_info['error'];
			} else {
				$json['error'] = $this->language->get('error_comment');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function comment(): void {
		$this->load->language('marketplace/marketplace');

		if (isset($this->request->get['extension_id'])) {
			$extension_id = (int)$this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['button_more'] = $this->language->get('button_more');
		$data['button_reply'] = $this->language->get('button_reply');

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/marketplace/comment&extension_id=' . $extension_id . '&page=' . $page);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

		$response = curl_exec($curl);

		curl_close($curl);

		$json = json_decode($response, true);

		$data['comments'] = [];

		$comment_total = $json['comment_total'];

		if ($json['comments']) {
			$results = $json['comments'];

			foreach ($results as $result) {
				if ($result['reply_total'] > 5) {
					$next = $this->url->link('marketplace/marketplace.reply', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $result['extension_comment_id'] . '&page=2');
				} else {
					$next = '';
				}

				$data['comments'][] = [
					'extension_comment_id' => $result['extension_comment_id'],
					'member'               => $result['member'],
					'image'                => $result['image'],
					'comment'              => $result['comment'],
					'date_added'           => $result['date_added'],
					'reply'                => $result['reply'],
					'add'                  => $this->url->link('marketplace/marketplace.addcomment', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $result['extension_comment_id']),
					'refresh'              => $this->url->link('marketplace/marketplace.reply', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $result['extension_comment_id'] . '&page=1'),
					'next'                 => $next
				];
			}
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $comment_total,
			'page'  => $page,
			'limit' => 20,
			'url'   => $this->url->link('marketplace/marketplace.comment', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&page={page}')
		]);

		$data['refresh'] = $this->url->link('marketplace/marketplace.comment', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&page=' . $page);

		$this->response->setOutput($this->load->view('marketplace/marketplace_comment', $data));
	}

	public function reply(): void {
		$this->load->language('marketplace/marketplace');

		if (isset($this->request->get['extension_id'])) {
			$extension_id = (int)$this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		if (isset($this->request->get['parent_id'])) {
			$parent_id = (int)$this->request->get['parent_id'];
		} else {
			$parent_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/marketplace/comment&extension_id=' . $extension_id . '&parent_id=' . $parent_id . '&page=' . $page);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

		$response = curl_exec($curl);

		$json = json_decode($response, true);

		$data['replies'] = [];

		$reply_total = $json['reply_total'];

		if ($json['replies']) {
			$results = $json['replies'];

			foreach ($results as $result) {
				$data['replies'][] = [
					'extension_comment_id' => $result['extension_comment_id'],
					'member'               => $result['member'],
					'image'                => $result['image'],
					'comment'              => $result['comment'],
					'date_added'           => $result['date_added']
				];
			}
		}

		$data['refresh'] = $this->url->link('marketplace/marketplace.reply', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $parent_id . '&page=' . $page);

		if (($page * 5) < $reply_total) {
			$data['next'] = $this->url->link('marketplace/marketplace.reply', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $parent_id . '&page=' . ($page + 1));
		} else {
			$data['next'] = '';
		}

		$this->response->setOutput($this->load->view('marketplace/marketplace_reply', $data));
	}
}
