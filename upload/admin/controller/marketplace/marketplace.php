<?php
class ControllerMarketplaceMarketplace extends Controller {
	private $error = array();

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
			
		$url  = '&domain=' . $this->request->server['HTTP_HOST'];
		$url .= '&version=' . VERSION;

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

		$time = time() + 30;

		// We create a hash from the data in a similar method to how amazon does things.
		$string  = 'marketplace/api/list' . "\n";
		$string .= OPENCART_USERNAME . "\n";
		$string .= $this->request->server['HTTP_HOST'] . "\n";
		$string .= VERSION . "\n";
		$string .= $time . "\n"; 
		
		$signature = base64_encode(hash_hmac('sha1', $string, OPENCART_SECRET, 1));
		
		$url  = '&username=' . OPENCART_USERNAME;
		$url .= '&domain=' . $this->request->server['HTTP_HOST'];
		$url .= '&version=' . VERSION;
		$url .= '&time=' . $time;
		$url .= '&signature=' . rawurlencode($signature);
							
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

		$extension_total = $response_info['total'];

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

		if ($response_info['promotions'] && $page == 1) {
			foreach ($response_info['promotions'] as $result) {
				$data['promotions'][] = array(
					'name'         => $result['name'],
					'description'  => $result['description'],
					'image'        => $result['image'],
					'license'      => $result['license'],
					'price'        => $result['price'],
					'rating'       => $result['rating'],
					'review_total' => $result['review_total'],
					'href'         => $this->url->link('marketplace/marketplace/info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id'] . $url, true)
				);
			}
		}

		$data['extensions'] = array();

		if ($response_info['extensions']) {
			foreach ($response_info['extensions'] as $result) {
				$data['extensions'][] = array(
					'name'         => $result['name'],
					'description'  => $result['description'],
					'image'        => $result['image'],
					'license'      => $result['license'],
					'price'        => $result['price'],
					'rating'       => $result['rating'],
					'review_total' => $result['review_total'],
					'href'         => $this->url->link('marketplace/marketplace/info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id'] . $url, true)
				);
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_search'] = $this->language->get('text_search');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		if (!defined('OPENCART_USERNAME') || !defined('OPENCART_SECRET') || !OPENCART_USERNAME || !OPENCART_SECRET) {
			$data['error_warning'] = $this->language->get('error_api');
		} else {
			$data['error_warning'] = '';
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
			'text'  => 'Popularity',
			'value' => 'popularity',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=popularity')
		);

		$data['sorts'][] = array(
			'text'  => 'Rating',
			'value' => 'rating',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=rating')
		);

		$data['sorts'][] = array(
			'text'  => 'Date Modified',
			'value' => 'date_modified',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=date_modified')
		);

		$data['sorts'][] = array(
			'text'  => 'Date Added',
			'value' => 'date_added',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=date_added')
		);

		$data['sorts'][] = array(
			'text'  => 'Name',
			'value' => 'name',
			'href'  => $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=name')
		);

		$data['sorts'][] = array(
			'text'  => 'Price',
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

		$url  = '&domain=' . $this->request->server['HTTP_HOST'];
		$url .= '&version=' . VERSION;

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/info&extension_id=' . $extension_id . $url);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);

		$response = curl_exec($curl);

		curl_close($curl);

		$response_info = json_decode($response, true);

		if ($response_info) {
			$this->load->language('marketplace/marketplace');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_loading'] = $this->language->get('text_loading');
			$data['text_no_results'] = $this->language->get('text_no_results');
			$data['text_pin'] = $this->language->get('text_pin');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_partner'] = $this->language->get('text_partner');
			$data['text_rating'] = $this->language->get('text_rating');
			$data['text_downloaded'] = $this->language->get('text_downloaded');
			$data['text_sales'] = $this->language->get('text_sales');
			$data['text_compatibility'] = $this->language->get('text_compatibility');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_date_modified'] = $this->language->get('text_date_modified');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_comment_add'] = $this->language->get('text_comment_add');
			$data['text_write'] = $this->language->get('text_write');

			$data['entry_pin'] = $this->language->get('entry_pin');

			$data['button_buy'] = $this->language->get('button_buy');
			$data['button_install'] = $this->language->get('button_install');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_comment'] = $this->language->get('button_comment');

			$data['tab_general'] = $this->language->get('tab_general');
			$data['tab_documentation'] = $this->language->get('tab_documentation');
			$data['tab_download'] = $this->language->get('tab_download');
			$data['tab_comment'] = $this->language->get('tab_comment');
		
			if (!defined('OPENCART_USERNAME') || !defined('OPENCART_SECRET') || !OPENCART_USERNAME || !OPENCART_SECRET) {
				$data['error_warning'] = $this->language->get('error_api');
			} else {
				$data['error_warning'] = '';
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
					$compatibility = explode(', ', $result['compatibility']);
					
					//if (in_array(VERSION, $compatibility)) {
						$data['downloads'][] = array(
							'extension_download_id' => $result['extension_download_id'],
							'name'                  => $result['name'],
							'date_added'            => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
							'installed'             => $this->model_setting_extension->getTotalPathsByCode('marketplace-' . $result['extension_download_id']),
							'status'                => $result['status']
						);
					//}
				}
			}

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

		if (!defined('OPENCART_USERNAME') || !defined('OPENCART_SECRET') || !OPENCART_USERNAME || !OPENCART_SECRET) {
			$json['error'] = $this->language->get('error_api');
		}
		
		if (!$this->request->post['pin']) {
			$json['error'] = $this->language->get('error_pin');
		}

		if (!$json) {
			$time = time() + 30;

			// We create a hash from the data in a similar method to how amazon does things.
			$string  = 'marketplace/api/purchase' . "\n";
			$string .= OPENCART_USERNAME . "\n";
			$string .= $this->request->server['HTTP_HOST'] . "\n";
			$string .= VERSION . "\n";
			$string .= $extension_id . "\n";
		 	$string .= $this->request->post['pin'] . "\n";
			$string .= $time . "\n";
			
			$signature = base64_encode(hash_hmac('sha1', $string, OPENCART_SECRET, 1));
			
			$url  = '&username=' . OPENCART_USERNAME;
			$url .= '&domain=' . $this->request->server['HTTP_HOST'];
			$url .= '&version=' . VERSION;
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
		
		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}
							
		if (!$this->user->hasPermission('modify', 'marketplace/marketplace')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (!defined('OPENCART_USERNAME') || !defined('OPENCART_SECRET') || !OPENCART_USERNAME || !OPENCART_SECRET) {
			$json['error'] = $this->language->get('error_api');
		}

		// Check if there is a install zip already there
		$file = ini_get('upload_tmp_dir') . '/install.tmp';
		
		if (is_file($file) && (filemtime($file) < (time() - 20))) {
			unlink($file);
		}

		if (is_file($file)) {
			$json['error'] = $this->language->get('error_install');
		}
		
		if (!$json) {
			$time = time() + 5;

			// We create a hash from the data in a similar method to how amazon does things.
			$string  = 'marketplace/api/download' . "\n";
			$string .= OPENCART_USERNAME . "\n";
			$string .= $this->request->server['HTTP_HOST'] . "\n";
			$string .= VERSION . "\n";
			$string .= $extension_download_id . "\n";
			$string .= $time . "\n";

			$signature = base64_encode(hash_hmac('sha1', $string, OPENCART_SECRET, 1));

			$url  = '&username=' . OPENCART_USERNAME;
			$url .= '&domain=' . $this->request->server['HTTP_HOST'];
			$url .= '&version=' . VERSION;
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
				$download = file_get_contents($response_info['download']);
				
				$handle = fopen(ini_get('upload_tmp_dir') . '/install.tmp', 'w');
				
				fwrite($handle, $download);
		
				fclose($handle);
				
				$json['text'] = $this->language->get('text_install');
				
				$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/install', 'user_token=' . $this->session->data['user_token'] . '&code=marketplace-' . $extension_download_id, true));		
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
		
		if (!defined('OPENCART_USERNAME') || !defined('OPENCART_SECRET') || !OPENCART_USERNAME || !OPENCART_SECRET) {
			$json['error'] = $this->language->get('error_api');
		}
					
		if (!$json) {	
			$time = time() + 30;

			// We create a hash from the data in a similar method to how amazon does things.
			$string  = 'marketplace/api/addcomment' . "\n";
			$string .= OPENCART_USERNAME . "\n";
			$string .= $this->request->server['HTTP_HOST'] . "\n";
			$string .= VERSION . "\n";
			$string .= $extension_id . "\n";
			$string .= $parent_id . "\n";
			$string .= urlencode(base64_encode($this->request->post['comment'])) . "\n";
			$string .= $time . "\n";

			$signature = base64_encode(hash_hmac('sha1', $string, OPENCART_SECRET, 1));

			$url  = '&username=' . OPENCART_USERNAME;
			$url .= '&domain=' . $this->request->server['HTTP_HOST'];
			$url .= '&version=' . VERSION;
			$url .= '&extension_id=' . $extension_id;
			$url .= '&parent_id=' . $parent_id;
			$url .= '&time=' . $time;
			$url .= '&signature=' . rawurlencode($signature);	

			$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/addcomment&extension_id=' . $extension_id);
		
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
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$data['button_more'] = $this->language->get('button_more');
		$data['button_reply'] = $this->language->get('button_reply');

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/comment&extension_id=' . $extension_id);
		
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
			$page = $this->request->get['page'];
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