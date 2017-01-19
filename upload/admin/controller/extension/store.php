<?php
class ControllerExtensionStore extends Controller {
	public function index() {
		$this->load->language('extension/store');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url, true)
		);
	
		$url  = '&domain=' . $this->request->server['HTTP_HOST'];
		$url .= '&language=' . $this->config->get('config_language');
		$url .= '&currency=' . $this->config->get('config_currency');
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
				
		$curl = curl_init('https://www.opencart.com/index.php?route=marketplace/api' . $url);
		
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
		
		$data['promotions'] = array();
		
		if ($status == 200) {
			foreach ($response_info['promotions'] as $result) {
				$data['extensions'][] = array(
					'name'         => $result['name'],
					'description'  => $result['description'],
					'image'        => $result['image'],
					'license'      => $result['license'],
					'price'        => $result['price'],
					'rating'       => $result['rating'],
					'review_total' => $result['review_total'],
					'href'         => $this->url->link('extension/store/info', 'token=' . $this->session->data['token'] . '&extension_id=' . $result['extension_id'] . $url, true)
				);
			}
		}		
		
		$data['extensions'] = array();
		
		if ($status == 200) {
			foreach ($response_info['extensions'] as $result) {
				$data['extensions'][] = array(
					'name'         => $result['name'],
					'description'  => $result['description'],
					'image'        => $result['image'],
					'license'      => $result['license'],
					'price'        => $result['price'],
					'rating'       => $result['rating'],
					'review_total' => $result['review_total'],
					'href'         => $this->url->link('extension/store/info', 'token=' . $this->session->data['token'] . '&extension_id=' . $result['extension_id'] . $url, true)
				);
			}
		}
				
		$data['heading_title'] = $this->language->get('heading_title');
        
		$data['text_list'] = $this->language->get('text_list');
		$data['text_search'] = $this->language->get('text_search');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
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
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url, true)
		);	
			
		$data['categories'][] = array(
			'text'  => $this->language->get('text_theme'),
			'value' => 'theme',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_category=theme' . $url, true)
		);
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_marketplace'),
			'value' => 'marketplace',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_category=marketplace' . $url, true)
		);		
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_payment'),
			'value' => 'payment',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_category=payment' . $url, true)
		);	
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_shipping'),
			'value' => 'shipping',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_category=shipping' . $url, true)
		);		

		$data['categories'][] = array(
			'text'  => $this->language->get('text_module'),
			'value' => 'module',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_category=module' . $url, true)
		);	

		$data['categories'][] = array(
			'text'  => $this->language->get('text_total'),
			'value' => 'total',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_category=total' . $url, true)
		);	
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_feed'),
			'value' => 'feed',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_category=feed' . $url, true)
		);			
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_report'),
			'value' => 'report',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_category=report' . $url, true)
		);		
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_other'),
			'value' => 'other',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_category=other' . $url, true)
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
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url, true)
		);	
			
		$data['licenses'][] = array(
			'text'  => $this->language->get('text_free'),
			'value' => 'free',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_license=free' . $url, true)
		);
		
		$data['licenses'][] = array(
			'text'  => $this->language->get('text_paid'),
			'value' => 'paid',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_license=paid' . $url, true)
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
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url . '&sort=popularity')
		);

		$data['sorts'][] = array(
			'text'  => 'Rating',
			'value' => 'rating',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url . '&sort=rating')
		);

		$data['sorts'][] = array(
			'text'  => 'Date Modified',
			'value' => 'date_modified',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url . '&sort=date_modified')
		);

		$data['sorts'][] = array(
			'text'  => 'Date Added',
			'value' => 'date_added',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url . '&sort=date_added')
		);

		$data['sorts'][] = array(
			'text'  => 'Name',
			'value' => 'name',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url . '&sort=name')
		);

		$data['sorts'][] = array(
			'text'  => 'Price',
			'value' => 'price',
			'href'  => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url . '&sort=price')
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
		$pagination->url = $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

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
		
		$this->response->setOutput($this->load->view('extension/store_list', $data));
	}
	
	public function info() {
		if (isset($this->request->get['extension_id'])) {
			$extension_id = $this->request->get['extension_id'];
		} else {
			$extension_id = 0;
		}
				
		$url  = '&domain=' . $this->request->server['HTTP_HOST'];
		$url .= '&language=' . $this->config->get('config_language');
		$url .= '&currency=' . $this->config->get('config_currency');
		$url .= '&version=' . VERSION;

		$curl = curl_init('https://www.opencart.com/index.php?route=marketplace/api/info&extension_id=' . $extension_id . $url);
				
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
			
		$response = curl_exec($curl);
		
		curl_close($curl);
		
		$response_info = json_decode($response, true);
		
		if ($response_info) {
			$this->load->language('extension/store');
	
			$this->document->setTitle($this->language->get('heading_title'));
							
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['text_loading'] = $this->language->get('text_loading');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_partner'] = $this->language->get('text_partner');
			$data['text_rating'] = $this->language->get('text_rating');
			$data['text_downloaded'] = $this->language->get('text_downloaded');
			$data['text_sales'] = $this->language->get('text_sales');
			$data['text_compatibility'] = $this->language->get('text_compatibility');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_date_modified'] = $this->language->get('text_date_modified');
			
			$data['button_buy'] = $this->language->get('button_buy');
			$data['button_install'] = $this->language->get('button_install');
			$data['button_cancel'] = $this->language->get('button_cancel');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_documentation'] = $this->language->get('tab_documentation');
			$data['tab_download'] = $this->language->get('tab_download');
			$data['tab_comment'] = $this->language->get('tab_comment');
			
			$data['token'] = $this->session->data['token'];
			
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
			
			$data['cancel'] = $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url, true);
			
			$data['breadcrumbs'] = array();
	
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
	
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url, true)
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
			$data['rating'] = $response_info['rating'];
			$data['downloaded'] = $response_info['downloaded'];
			$data['sales'] = $response_info['sales'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($response_info['date_added']));
			$data['date_modified'] = date($this->language->get('date_format_short'), strtotime($response_info['date_modified']));
			$data['member_username'] = $response_info['member_username'];
			$data['member_image'] = $response_info['member_image'];
			$data['member_date_added'] = $response_info['member_date_added'];
			$data['filter_member'] = $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_member=' . $response_info['member_username']);
			$data['comment_total'] = $response_info['comment_total'];
			$data['compatibility'] = $response_info['compatibility'];
			
			$data['images'] = array();

			foreach ($response_info['images'] as $result) {
				$data['images'][] = array(
					'thumb' => $result['thumb'],
					'popup' => $result['popup']
				);
			}			
			
			$this->load->model('extension/extension');
			
			$data['downloads'] = array();
			
			if ($response_info['downloads']) {
				foreach ($response_info['downloads'] as $result) {					
					$compatibility = explode(', ', $result['compatibility']);
															
					//if (in_array(VERSION, $compatibility)) {
						$data['downloads'][] = array(
							'name'       => $result['name'],
							'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
							'install'    => $this->url->link('extension/store/install', 'token=' . $this->session->data['token'] . '&extension_download_id=' . $result['extension_download_id'], true),
							'uninstall'  => $this->url->link('extension/store/uninstall', 'token=' . $this->session->data['token'] . '&extension_download_id=' . $result['extension_download_id'], true),
							'installed'  => $this->model_extension_extension->getTotalPathsByExtensionDownloadId($result['extension_download_id'])
						);
					//}	
				}
			}		
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('extension/store_info', $data));			
		} else {
			return new Action('error/not_found');
		}	
	}
	
	public function comment() {
	
	}
	
	public function install() {
		$this->load->language('extension/store');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
				
		if (!$json) {
			if (isset($this->request->get['extension_download_id'])) {
				$extension_download_id = $this->request->get['extension_download_id'];
			} else {
				$extension_download_id = 0;
			}			
			
			$json['text'] = $this->language->get('text_download');
					
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/download', 'token=' . $this->session->data['token'] . '&extension_download_id=' . $extension_download_id, true));
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));				
	}
		
	public function download() {
		$this->load->language('extension/store');

		$json = array();
		
		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}
							
		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (!$json) {
			$curl = curl_init('https://www.opencart.com/index.php?route=marketplace/api/download&extension_download_id=' . $extension_download_id);
			
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
					
			$response = curl_exec($curl);
			
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			
			curl_close($curl);
			
			if ($response && $status == 200) {
				$file = tempnam(ini_get('upload_tmp_dir'), 'ext');
			
				$handle = fopen($file, 'w');
				
				fwrite($handle, $response);
		
				fclose($handle);
				
				$json['text'] = $this->language->get('text_unzip');
				
				$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/unzip', 'token=' . $this->session->data['token'] . '&extension_download_id=' . $extension_download_id . '&download=' . basename($file, '.tmp'), true));		
			} else {
				$json['error'] = $this->language->get('error_download');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
				
	public function unzip() {
		$this->load->language('extension/store');

		$json = array();
		
		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}
		
		if ($this->request->get['download']) {
			$download = $this->request->get['download'];
		} else {
			$download = '';
		}	
				
		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Sanitize the filename
		$directory = ini_get('upload_tmp_dir');

		if (!is_file($directory . '/' . $download . '.tmp') || substr(str_replace('\\', '/', realpath($directory . '/' . $download . '.tmp')), 0, strlen($directory)) != str_replace('\\', '/', $directory)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($directory . '/' . $download . '.tmp')) {
				$zip->extractTo($directory . '/' . $download);
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($directory . '/' . $download . '.tmp');
			
			$json['text'] = $this->language->get('text_move');
			
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/move', 'token=' . $this->session->data['token'] . '&extension_download_id=' . $extension_download_id . '&download=' . $download, true));		
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
		
	public function move() {
		$this->load->language('extension/store');

		$json = array();
		
		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}
				
		if (isset($this->request->get['download'])) {
			$download = $this->request->get['download'];
		} else {
			$download = '';
		}
				
		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
			
		$directory = ini_get('upload_tmp_dir');
		
		if (is_dir($directory . '/' . $download . '/upload/') && substr(str_replace('\\', '/', realpath($directory . '/' . $download . '/upload/')), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
			$files = array();
			
			// Get a list of files ready to upload
			$path = array($directory . '/' . $download . '/upload/*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					$files[] = $file;
				}
			}
			
			// First we need to do some checks
			foreach ($files as $file) {
				$destination = str_replace('\\', '/', substr($file, strlen($directory . '/' . $download . '/upload/')));
				
				// Check if the file is not going into an allowed directory
				$allowed = array(
					'admin/controller/extension/',
					'admin/model/extension/',
					'admin/view/template/extension/',
					'catalog/controller/extension/',
					'catalog/model/extension/',
					'catalog/view/theme/'
				);
				
				// Language Admin
				$data['languages'] = array();
				
				$folders = glob(DIR_LANGUAGE . '*', GLOB_ONLYDIR);
			
				foreach ($folders as $folder) {
					$allowed[] = 'admin/language/' . basename($folder) . '/extension/';
				}
				
				// Language Catalog
				$folders = glob(DIR_CATALOG . 'language/*', GLOB_ONLYDIR);
			
				foreach ($folders as $folder) {
					$allowed[] = 'catalog/language/' . basename($folder) . '/extension/';
				}			
							
				$safe = false;
				
				foreach ($allowed as $value) {
					if (strlen($destination) < strlen($value) && substr($value, 0, strlen($destination)) == $destination) {
						$safe = true;
						
						break;
					}
					
					if (strlen($destination) > strlen($value) && substr($destination, 0, strlen($value)) == $value) {
						$safe = true;
						
						break;						
					}
				}
			
				if ($safe) {
					// Check if the copy location exists or not	
					if (substr($destination, 0, 5) == 'admin') {
						$destination = DIR_APPLICATION . substr($destination, 6);
					}
		
					if (substr($destination, 0, 7) == 'catalog') {
						$destination = DIR_CATALOG . substr($destination, 8);
					}
		
					if (substr($destination, 0, 5) == 'image') {
						$destination = DIR_IMAGE . substr($destination, 6);
					}
		
					if (substr($destination, 0, 6) == 'system') {
						$destination = DIR_SYSTEM . substr($destination, 7);
					}
					
					if (is_file($destination)) {
						$json['error'] = sprintf($this->language->get('error_exists'), $destination);
						
						break;
					}				
				} else {
					$json['error'] = sprintf($this->language->get('error_allowed'), $destination);
					
					break;
				}
			}

			if (!$json) { 
				$this->load->model('extension/extension');
				
				foreach ($files as $file) {
					$destination = str_replace('\\', '/', substr($file, strlen($directory . '/' . $download . '/upload/')));
		
					$path = '';
					
					if (substr($destination, 0, 5) == 'admin') {
						$path = DIR_APPLICATION . substr($destination, 6);
					}
		
					if (substr($destination, 0, 7) == 'catalog') {
						$path = DIR_CATALOG . substr($destination, 8);
					}
		
					if (substr($destination, 0, 5) == 'image') {
						$path = DIR_IMAGE . substr($destination, 6);
					}
		
					if (substr($destination, 0, 6) == 'system') {
						$path = DIR_SYSTEM . substr($destination, 7);
					}
	
					if (is_dir($file) && !is_dir($path)) {
						if (mkdir($path, 0777)) {
							$this->model_extension_extension->addPath($extension_download_id, $destination);
						} else {
							$json['error'] = sprintf($this->language->get('error_move'), $path);
						}
					}
				
					if (is_file($file)) {
						if (rename($file, $path)) {
							$this->model_extension_extension->addPath($extension_download_id, $destination);
						} else {
							$json['error'] = sprintf($this->language->get('error_move'), $file);
						}
					}
				}
			}
		}
		
		if (!$json) {
			$json['text'] = $this->language->get('text_xml');
				
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/xml', 'token=' . $this->session->data['token'] . '&extension_download_id=' . $extension_download_id . '&download=' . $download, true));		
		}
			
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function xml() {
		$this->load->language('extension/store');

		$json = array();
		
		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}
				
		if ($this->request->get['download']) {
			$download = $this->request->get['download'];
		} else {
			$download = '';
		}	
		
		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
				
		$directory = ini_get('upload_tmp_dir');

		if (is_file($directory . '/' . $download . '/install.xml') && substr(str_replace('\\', '/', realpath($directory . '/' . $download . '/install.xml')), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
			// If xml file just put it straight into the DB
			$xml = file_get_contents($directory . '/' . $download . '/install.xml');

			if ($xml) {
				try {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($xml);

					$name = $dom->getElementsByTagName('name')->item(0);

					if ($name) {
						$name = $name->nodeValue;
					} else {
						$name = '';
					}

					$code = $dom->getElementsByTagName('code')->item(0);

					if ($code) {
						$code = $code->nodeValue;

						// Check to see if the modification is already installed or not.
						$modification_info = $this->model_extension_modification->getModificationByCode($code);

						if ($modification_info) {
							$json['error'] = sprintf($this->language->get('error_xml'), $modification_info['name']);
						}
					} else {
						$json['error'] = $this->language->get('error_code');
					}

					$author = $dom->getElementsByTagName('author')->item(0);

					if ($author) {
						$author = $author->nodeValue;
					} else {
						$author = '';
					}

					$version = $dom->getElementsByTagName('version')->item(0);

					if ($version) {
						$version = $version->nodeValue;
					} else {
						$version = '';
					}

					$link = $dom->getElementsByTagName('link')->item(0);

					if ($link) {
						$link = $link->nodeValue;
					} else {
						$link = '';
					}
					
					if (!$json) {
						$modification_data = array(
							'extension_download_id' => $extension_download_id,
							'name'                  => $name,
							'code'                  => $code,
							'author'                => $author,
							'version'               => $version,
							'link'                  => $link,
							'xml'                   => $xml
						);	
						
						$this->load->model('extension/modification');
										
						$this->model_extension_modification->addModification($modification_data);
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}
		
		if (!$json) {
			$json['text'] = $this->language->get('text_remove');
			
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/remove', 'token=' . $this->session->data['token'] . '&download=' . $download, true));		
		}
					
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}		
	
	public function remove() {
		$this->load->language('extension/store');

		$json = array();
				
		if ($this->request->get['download']) {
			$download = $this->request->get['download'];
		} else {
			$download = '';
		}
		
		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		$directory = ini_get('upload_tmp_dir');
		
		if (!is_dir($directory . '/' . $download) || substr(str_replace('\\', '/', realpath($directory . '/' . $download)), 0, strlen($directory)) != str_replace('\\', '/', $directory)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

			$path = array($directory . '/' . $download . '/');

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
			
			if (is_file($directory . '/' . $download . '.tmp')) {
				unlink($directory . '/' . $download . '.tmp');
			}

			if (is_dir($directory . '/' . $download)) {
				rmdir($directory . '/' . $download);
			}
			
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
	
	public function uninstall() {
		$this->load->language('extension/extension');

		$json = array();
		
		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}
		
		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
	
		if (!$json) {
			$this->load->model('extension/extension');
			
			$results = $this->model_extension_extension->getPathsByExtensionDownloadId($extension_download_id);
			
			rsort($results);
			
			foreach ($results as $result) {
				$source = '';
				
				// Check if the copy location exists or not	
				if (substr($result['path'], 0, 5) == 'admin') {
					$source = DIR_APPLICATION . substr($result['path'], 6);
				}
	
				if (substr($result['path'], 0, 7) == 'catalog') {
					$source = DIR_CATALOG . substr($result['path'], 8);
				}
	
				if (substr($result['path'], 0, 5) == 'image') {
					$source = DIR_IMAGE . substr($result['path'], 6);
				}
	
				if (substr($result['path'], 0, 6) == 'system') {
					$source = DIR_SYSTEM . substr($result['path'], 7);
				}
				
				if (is_file($source)) {
					unlink($source);
				}	
				
				if (is_dir($source)) {
					// Get a list of files ready to upload
					$files = array();
		
					$path = array($source);
		
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
					
					unlink($source);
				}	
				
				$this->model_extension_extension->deletePath($result['extension_install_id']);							
			}
			
			$json['success'] = $this->language->get('text_success');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}	
}