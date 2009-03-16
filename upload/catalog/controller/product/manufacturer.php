<?php 
class ControllerProductManufacturer extends Controller {  
	public function index() { 
		$this->load->language('product/manufacturer');

		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->helper('image');
		
		$this->document->breadcrumbs = array();
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer(@$this->request->get['manufacturer_id']);
	
		if ($manufacturer_info) {
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id']),
        		'text'      => $manufacturer_info['name'],
        		'separator' => $this->language->get('text_separator')
      		);
					  		
			$this->document->title = $manufacturer_info['name'];
									
			$this->data['heading_title'] = $manufacturer_info['name'];

			$this->data['text_sort'] = $this->language->get('text_sort');
		
			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($this->request->get['manufacturer_id']);
			
			if ($product_total) {
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}				

				if (isset($this->request->get['sort'])) {
					$sort = $this->request->get['sort'];
				} else {
					$sort = 'pd.name';
				}

				if (isset($this->request->get['order'])) {
					$order = $this->request->get['order'];
				} else {
					$order = 'ASC';
				}
			
				$this->load->model('catalog/review');
			
				$this->data['products'] = array();
        		
				$results = $this->model_catalog_product->getProductsByManufacturerId($this->request->get['manufacturer_id'], $sort, $order, ($page - 1) * 12, 12);
				
        		foreach ($results as $result) {
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}
					
					$rating = $this->model_catalog_review->getAverageRating($result['product_id']);
					
          			$this->data['products'][] = array(
            			'name'   => $result['name'],
						'model'  => $result['model'],
						'rating' => $rating,
						'stars'  => sprintf($this->language->get('text_stars'), $rating),            			
						'thumb'  => HelperImage::resize($image, 120, 120),
            			'price'  => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'href'   => $this->url->http('product/product&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&product_id=' . $result['product_id'])
          			);
        		}

				$url = '';
		
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}	
				
				$this->data['sorts'] = array();
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=pd.name&order=ASC')
				);  
 
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=pd.name&order=DESC')
				);  

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.price&order=ASC')
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.price&order=DESC')
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=rating&order=DESC')
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=rating&order=ASC')
				); 

				$url = '';
		
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = 12; 
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&page=%s');
			
				$this->data['pagination'] = $pagination->render();

				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
				
				$this->id       = 'content';
				$this->template = $this->config->get('config_template') . 'product/manufacturer.tpl';
				$this->layout   = 'module/layout';
		
				$this->render();										
      		} else {
        		$this->document->title = $manufacturer_info['name'];

        		$this->data['heading_title'] = $manufacturer_info['name'];

        		$this->data['text_error'] = $this->language->get('text_empty');

        		$this->data['button_continue'] = $this->language->get('button_continue');

        		$this->data['continue'] = $this->url->http('common/home');
		
				$this->id       = 'content';
				$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
				$this->layout   = 'module/layout';
		
				$this->render();					
      		}
    	} else {
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
			
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url),
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
}
?>