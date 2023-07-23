<?php
class ControllerMarketplaceMarketplace extends Controller {
	public function index() {
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		
		$time = time();
		
		// We create a hash from the data in a similar method to how amazon does things.
		$string  = 'marketplace/api/list' . "\n";
		$string .= $this->config->get('opencart_username') . "\n";
		$string .= $this->request->server['HTTP_HOST'] . "\n";
		$string .= VERSION . "\n";
		$string .= $time . "\n";

		$signature = base64_encode(hash_hmac('sha1', $string, (string)$this->config->get('opencart_secret'), 1));

		$url  = '&username=' . urlencode((string)$this->config->get('opencart_username'));
		$url .= '&domain=' . $this->request->server['HTTP_HOST'];
		$url .= '&version=' . urlencode(VERSION);
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
			$url .= '&filter_member=' . $this->request->get['filter_member'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api' . $url);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);

		$response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		$response_info = json_decode($response, true);

		$extension_total = $response_info['extension_total'] ?? 0;

		$url  = '';

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

		$data['promotions'] = array();

		if (isset($response_info['promotions']) && $response_info['promotions'] && $page == 1) {
			foreach ($response_info['promotions'] as $result) {
				$data['promotions'][] = array(
					'name'         => $result['name'],
					'description'  => $result['description'],
					'image'        => $result['image'],
					'license'      => $result['license'],
					'price'        => $result['price'],
					'rating'       => $result['rating'],
					'rating_total' => $result['rating_total'],
					'href'         => $this->url->link('marketplace/marketplace/info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id'] . $url, true)
				);
			}
		}

		$data['extensions'] = array();

		if (isset($response_info['extensions']) && $response_info['extensions']) {
			foreach ($response_info['extensions'] as $result) {
				$data['extensions'][] = array(
					'name'         => $result['name'],
					'description'  => $result['description'],
					'image'        => $result['image'],
					'license'      => $result['license'],
					'price'        => $result['price'],
					'rating'       => $result['rating'],
					'rating_total' => $result['rating_total'],
					'href'         => $this->url->link('marketplace/marketplace/info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id'] . $url, true)
				);
			}
		}

		$data['user_token'] = $this->session->data['user_token'];

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

		$data['categories'] = array();

		$data['categories'][] = array(
			'text'  => $this->language->get('text_all'),
			'value' => '',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_theme'),
			'value' => 'theme',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=theme' . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_marketplace'),
			'value' => 'marketplace',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=marketplace' . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_language'),
			'value' => 'language',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=language' . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_payment'),
			'value' => 'payment',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=payment' . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_shipping'),
			'value' => 'shipping',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=shipping' . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_module'),
			'value' => 'module',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=module' . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_total'),
			'value' => 'total',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=total' . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_feed'),
			'value' => 'feed',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=feed' . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_report'),
			'value' => 'report',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=report' . $url, true)
		);

		$data['categories'][] = array(
			'text'  => $this->language->get('text_other'),
			'value' => 'other',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_category=other' . $url, true)
		);

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

		$data['licenses'] = array();

		$data['licenses'][] = array(
			'text'  => $this->language->get('text_all'),
			'value' => '',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['licenses'][] = array(
			'text'  => $this->language->get('text_free'),
			'value' => 'free',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_license=free' . $url, true)
		);

		$data['licenses'][] = array(
			'text'  => $this->language->get('text_paid'),
			'value' => 'paid',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_license=paid' . $url, true)
		);


		$data['licenses'][] = array(
			'text'  => $this->language->get('text_purchased'),
			'value' => 'purchased',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . '&filter_license=purchased' . $url, true)
		);

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

		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_modified'),
			'value' => 'date_modified',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=date_modified')
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added'),
			'value' => 'date_added',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=date_added')
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_rating'),
			'value' => 'rating',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=rating')
		);


		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name'),
			'value' => 'name',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=name')
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price'),
			'value' => 'price',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=price')
		);

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

		$pagination = new Pagination();
		$pagination->total = $extension_total;
		$pagination->page = $page;
		$pagination->limit = 12;
		$pagination->url = $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['filter_search'] = $filter_search;
		$data['filter_category'] = $filter_category;
		$data['filter_license'] = $filter_license;
		$data['filter_member_type'] = $filter_member_type;
		$data['filter_rating'] = $filter_rating;
		$data['sort'] = $sort;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/marketplace_list', $data));
	}

	public function info() {
		if (isset($this->request->get['extension_id'])) {
			$extension_id = $this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		$time = time();

		// We create a hash from the data in a similar method to how amazon does things.
		$string  = 'marketplace/api/info' . "\n";
		$string .= $this->config->get('opencart_username') . "\n";
		$string .= $this->request->server['HTTP_HOST'] . "\n";
		$string .= VERSION . "\n";
		$string .= $extension_id . "\n";
		$string .= $time . "\n";

		$signature = base64_encode(hash_hmac('sha1', $string, (string)$this->config->get('opencart_secret'), 1));

		$url  = '&username=' . urlencode((string)$this->config->get('opencart_username'));
		$url .= '&domain=' . $this->request->server['HTTP_HOST'];
		$url .= '&version=' . urlencode(VERSION);
		$url .= '&extension_id=' . $extension_id;
		$url .= '&time=' . $time;
		$url .= '&signature=' . rawurlencode($signature);

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/info' . $url);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
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

			$data['user_token'] = $this->session->data['user_token'];

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

			$data['cancel'] = $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url, true);

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url, true)
			);

			$this->load->helper('bbcode');

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

			$data['comment_total'] = $response_info['comment_total'];

			$data['images'] = array();

			foreach ($response_info['images'] as $result) {
				$data['images'][] = array(
					'thumb' => $result['thumb'],
					'popup' => $result['popup']
				);
			}

			$this->load->model('setting/extension');

			$data['downloads'] = array();

			if ($response_info['downloads']) {
				foreach ($response_info['downloads'] as $result) {
					$extension_install_info = $this->model_setting_extension->getExtensionInstallByExtensionDownloadId($result['extension_download_id']);

					if ($extension_install_info) {
						$extension_install_id = $extension_install_info['extension_install_id'];
					} else {
						$extension_install_id = 0;
					}

					$data['downloads'][] = array(
						'extension_download_id' => $result['extension_download_id'],
						'extension_install_id'  => $extension_install_id,
						'name'                  => $result['name'],
						'filename'              => $result['filename'],
						'date_added'            => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
						'status'                => $result['status']
					);
				}
			}

			$this->document->addStyle('view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('view/javascript/jquery/magnific/jquery.magnific-popup.min.js');

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('marketplace/marketplace_info', $data));
		} else {
			return new Action('error/not_found');
		}
	}

	public function purchase() {
		$this->load->language('marketplace/marketplace');

		$json = array();

		if (isset($this->request->get['extension_id'])) {
			$extension_id = $this->request->get['extension_id'];
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
			$string  = 'marketplace/api/purchase' . "\n";
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

			$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/purchase' . $url);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

			$response = curl_exec($curl);

			curl_close($curl);

			$response_info = json_decode($response, true);

			if (isset($response_info['success'])) {
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

	public function download() {
		$this->load->language('marketplace/marketplace');

		$json = array();

		if (isset($this->request->get['extension_id'])) {
			$extension_id = $this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/marketplace')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Check if there is a install zip already there
		$files = glob(DIR_UPLOAD . '*.tmp');

		foreach ($files as $file) {
			if (is_file($file) && (filectime($file) < (time() - 5))) {
				unlink($file);
			}

			if (is_file($file)) {
				$json['error'] = $this->language->get('error_install');

				break;
			}
		}

		// Check for any install directories
		$directories = glob(DIR_UPLOAD . 'tmp-*');

		foreach ($directories as $directory) {
			if (is_dir($directory) && (filectime($directory) < (time() - 5))) {
				// Get a list of files ready to upload
				$files = array();

				$path = array($directory);

				while (count($path) != 0) {
					$next = array_shift($path);

					// We have to use scandir function because glob will not pick up dot files.
					foreach (array_diff(scandir($next), array('.', '..')) as $file) {
						$file = $next . '/' . $file;

						if (is_dir($file)) {
							$path[] = $file;
						}

						$files[] = $file;
					}
				}

				rsort($files);

				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}

				rmdir($directory);
			}

			if (is_dir($directory)) {
				$json['error'] = $this->language->get('error_install');

				break;
			}
		}

		if (!$json) {
			$time = time();

			// We create a hash from the data in a similar method to how amazon does things.
			$string  = 'marketplace/api/download' . "\n";
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

			$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/download&extension_download_id=' . $extension_download_id . $url);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

			$response = curl_exec($curl);

			$response_info = json_decode($response, true);

			curl_close($curl);

			if (isset($response_info['download'])) {
				if (substr($response_info['filename'], -10) == '.ocmod.zip') {
					$this->session->data['install'] = token(10);

					$download = file_get_contents($response_info['download']);

					$handle = fopen(DIR_UPLOAD . $this->session->data['install'] . '.tmp', 'w');

					fwrite($handle, $download);

					fclose($handle);

					$this->load->model('setting/extension');

					$json['extension_install_id'] = $this->model_setting_extension->addExtensionInstall($response_info['extension'], $extension_download_id);

					$json['text'] = $this->language->get('text_install');

					$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $json['extension_install_id'], true));
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

	public function addComment() {
		$this->load->language('marketplace/marketplace');

		$json = array();

		if (isset($this->request->get['extension_id'])) {
			$extension_id = $this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		if (isset($this->request->get['parent_id'])) {
			$parent_id = $this->request->get['parent_id'];
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
			$string  = 'marketplace/api/addcomment' . "\n";
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

			$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/addcomment&extension_id=' . $extension_id . $url);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, array('comment' => $this->request->post['comment']));

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

	public function comment() {
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

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

		$response = curl_exec($curl);

		curl_close($curl);

		$json = json_decode($response, true);

		$data['comments'] = array();

		$comment_total = $json['comment_total'];

		if ($json['comments']) {
			$results = $json['comments'];

			foreach ($results as $result) {
				if ($result['reply_total'] > 5) {
					$next = $this->url->link('marketplace/marketplace/reply', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $result['extension_comment_id'] . '&page=2');
				} else {
					$next = '';
				}

				$data['comments'][] = array(
					'extension_comment_id' => $result['extension_comment_id'],
					'member'               => $result['member'],
					'image'                => $result['image'],
					'comment'              => $result['comment'],
					'date_added'           => $result['date_added'],
					'reply'                => $result['reply'],
					'add'                  => $this->url->link('marketplace/marketplace/addcomment', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $result['extension_comment_id']),
					'refresh'              => $this->url->link('marketplace/marketplace/reply', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $result['extension_comment_id'] . '&page=1'),
					'next'                 => $next
				);
			}
		}

		$pagination = new Pagination();
		$pagination->total = $comment_total;
		$pagination->page = $page;
		$pagination->limit = 20;
		$pagination->url = $this->url->link('marketplace/marketplace/comment', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['refresh'] = $this->url->link('marketplace/marketplace/comment', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&page=' . $page);

		$this->response->setOutput($this->load->view('marketplace/marketplace_comment', $data));
	}

	public function reply() {
		$this->load->language('marketplace/marketplace');

		if (isset($this->request->get['extension_id'])) {
			$extension_id = $this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}

		if (isset($this->request->get['parent_id'])) {
			$parent_id = $this->request->get['parent_id'];
		} else {
			$parent_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/comment&extension_id=' . $extension_id . '&parent_id=' . $parent_id . '&page=' . $page);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

		$response = curl_exec($curl);

		$json = json_decode($response, true);

		$data['replies'] = array();

		$reply_total = $json['reply_total'];

		if ($json['replies']) {
			$results = $json['replies'];

			foreach ($results as $result) {
				$data['replies'][] = array(
					'extension_comment_id' => $result['extension_comment_id'],
					'member'               => $result['member'],
					'image'                => $result['image'],
					'comment'              => $result['comment'],
					'date_added'           => $result['date_added']
				);
			}
		}

		$data['refresh'] = $this->url->link('marketplace/marketplace/reply', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $parent_id . '&page=' . $page);

		if (($page * 5) < $reply_total) {
			$data['next'] = $this->url->link('marketplace/marketplace/reply', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $extension_id . '&parent_id=' . $parent_id . '&page=' . ($page + 1));
		} else {
			$data['next'] = '';
		}

		$this->response->setOutput($this->load->view('marketplace/marketplace_reply', $data));
	}
}
