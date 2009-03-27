<?php  
class ControllerProductProduct extends Controller {
	private $error = array(); 
	
	public function index() { 
		$this->load->language('product/product');
		
		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
		
		$this->load->model('catalog/category');	
		
		// Categories
      	if (isset($this->request->get['path'])) {
			$path = '';
				
			foreach (explode('_', $this->request->get['path']) as $path_id) {
				$category_info = $this->model_catalog_category->getCategory($path_id);
				
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
				
        		$this->document->breadcrumbs[] = array(
					'href'      => $this->url->http('product/category&path=' . $path),
           			'text'      => $category_info['name'],
           			'separator' => $this->language->get('text_separator')
        		);
        	}			
      	}
		
		$this->load->model('catalog/manufacturer');	
		
		// Manufacturer
		if (isset($this->request->get['manufacturer_id'])) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer(@$this->request->get['manufacturer_id']);
	      		
			$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id']),
        		'text'      => @$manufacturer_info['name'],
        		'separator' => $this->language->get('text_separator')
      		);	
		}
		
		// Search
		if (isset($this->request->get['keyword'])) {
			$url = '';
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}				
			
			$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('product/search&keyword=' . $this->request->get['keyword'] . $url),
        		'text'      => $this->language->get('text_search'),
        		'separator' => $this->language->get('text_separator')
      		);	
		}
		
		$this->load->model('catalog/product');
		
		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
    	
		if ($product_info) {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['keyword'])) {
				$url .= '&keyword=' . $this->request->get['keyword'];
			}			
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}				
									
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('product/product' . $url . '&product_id=' . $this->request->get['product_id']),
        		'text'      => $product_info['name'],
        		'separator' => $this->language->get('text_separator')
      		);			
			

			$this->document->title = $product_info['name'];
			
			$this->document->description = $product_info['meta_description'];
			
			$this->data['heading_title'] = $product_info['name'];
			
			$this->data['text_enlarge'] = $this->language->get('text_enlarge');
      		$this->data['text_options'] = $this->language->get('text_options');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_availability'] = $this->language->get('text_availability');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_qty'] = $this->language->get('text_qty');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_average'] = $this->language->get('text_average');
			$this->data['text_no_rating'] = $this->language->get('text_no_rating');
			$this->data['text_note'] = $this->language->get('text_note');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_verification'] = $this->language->get('entry_verification');

			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->load->model('catalog/review');

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_image'] = $this->language->get('tab_image');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']));
			
			$average = $this->model_catalog_review->getAverageRating($this->request->get['product_id']);	
			
			$this->data['text_stars'] = sprintf($this->language->get('text_stars'), $average);
			
			$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');

			$this->data['action'] = $this->url->http('checkout/cart');

			$this->load->helper('image');
			
			if ($product_info['image']) {
				$image = $product_info['image'];
			} else {
				$image = 'no_image.jpg';
			}	
					
			$this->data['popup'] = HelperImage::resize($image, 400, 400);
	  		$this->data['thumb'] = HelperImage::resize($image, 250, 250);
			$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			
			$special = $this->model_catalog_product->getProductSpecial($this->request->get['product_id']);
			
			if ($special) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($special, $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = FALSE;
			}
			
			$this->data['stock'] = ($product_info['quantity'] > 0)? $this->language->get('text_instock') : $product_info['stock'];
			$this->data['model'] = $product_info['model'];
			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->http('product/manufacturer&manufacturer_id=' . $product_info['manufacturer_id']);
			$this->data['description'] = html_entity_decode($product_info['description']);
      		$this->data['product_id'] = $this->request->get['product_id'];
			$this->data['write'] = $this->url->http('product/review' . $url . '&product_id=' . $this->request->get['product_id']);
			$this->data['average'] = $average;
			
			$this->data['options'] = array();
			
			$options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
			
			foreach ($options as $option) { 
				$option_value_data = array();
				
				foreach ($option['option_value'] as $option_value) {
					$option_value_data[] = array(
            			'option_value_id' => $option_value['product_option_value_id'],
            			'name'            => $option_value['name'],
            			'price'           => (int)$option_value['price'] ? $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : FALSE,
            			'prefix'          => $option_value['prefix']
          			);
				}
				
				$this->data['options'][] = array(
          			'option_id'    => $option['product_option_id'],
          			'name'         => $option['name'],
          			'option_value' => $option_value_data
				);
			}
			
			$this->data['images'] = array();
			
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
			
      		foreach ($results as $result) {
        		$this->data['images'][] = array(
          			'popup' => HelperImage::resize($result['image'] , 500, 500),
          			'thumb' => HelperImage::resize($result['image'], 150, 150)
        		);
      		}
	  		
			$this->model_catalog_product->updateViewed($this->request->get['product_id']);
						
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'product/product.tpl';
			$this->layout   = 'module/layout';
		
			$this->render();
    	} else {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['keyword'])) {
				$url .= '&keyword=' . $this->request->get['keyword'];
			}			
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}		
					
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('product/product' . $url . '&product_id=' . $this->request->get['product_id']),
        		'text'      => $this->language->get('text_error'),
        		'separator' => $this->language->get('text_separator')
      		);			
		
      		$this->document->title = $this->language->get('text_error');

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->http('common/home');
	  
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
			$this->layout   = 'module/layout';
		
			$this->render();
    	}
  	}
	
	public function review() {
    	$this->load->language('product/product');
		
		$this->load->model('catalog/review');

		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['reviews'] = array();
			
		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);
      		
		foreach ($results as $result) {
        	$this->data['reviews'][] = array(
        		'author'     => $result['author'],
				'rating'     => $result['rating'],
				'text'       => strip_tags($result['text']),
        		'stars'      => sprintf($this->language->get('text_stars'), $result['rating']),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
			
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->http('product/product/review&product_id=' . $this->request->get['product_id'] . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = $this->config->get('config_template') . 'product/review.tpl';
		
		$this->render();
	}
	
	public function write() {
    	$this->load->language('product/product');
		
		$this->load->model('catalog/review');
		
		$data = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);
    		
			$data['success'] = $this->language->get('text_success');
		} else {
			$data['error'] = $this->error['message'];
		}	
		
		$this->load->helper('json');
		
		$this->response->setOutput(Json::encode($data));
	}
	
	public function verification() {
		$this->load->library('verification');
		
		$verification = new Verification();
		
		$this->session->data['verification'] = $verification->getCode();
		
		$verification->showImage();
	}
	
  	private function validate() {
    	if ((strlen($this->request->post['name']) < 3) || (strlen($this->request->post['name']) > 25)) {
      		$this->error['message'] = $this->language->get('error_name');
    	}
		
    	if ((strlen($this->request->post['text']) < 25) || (strlen($this->request->post['text']) > 1000)) {
      		$this->error['message'] = $this->language->get('error_text');
    	}

    	if (!@$this->request->post['rating']) {
      		$this->error['message'] = $this->language->get('error_rating');
    	}

    	if (@$this->session->data['verification'] != $this->request->post['verification']) {
      		$this->error['message'] = $this->language->get('error_verification');
    	}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}	
	}	
}
?>