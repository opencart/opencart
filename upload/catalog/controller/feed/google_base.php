<?php 
class ControllerFeedGoogleBase extends Controller {
	public function index() {
		if ($this->config->get('google_base_status')) { 
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
            $output .= '<channel>';
			$output .= '<title>' . $this->config->get('config_store') . '</title>'; 
			$output .= '<description>' . $this->config->get('config_meta_description') . '</description>';
			$output .= '<link>' . HTTP_SERVER . '</link>';
			
			$this->load->model('catalog/category');
			
			$this->load->model('catalog/product');
			
			$this->load->helper('image');
			
			$products = $this->model_catalog_product->getProducts();
			
			foreach ($products as $product) {
				if ($product['description']) {
					$output .= '<item>';
					$output .= '<title>' . $product['name'] . '</title>';
					$output .= '<g:brand>' . $product['manufacturer'] . '</g:brand>';
					$output .= '<g:condition>new</g:condition>';
					$output .= '<description>' . $product['description'] . '</description>';

					$output .= '<guid>' . $product['product_id'] . '</guid>';
					
					if ($product['image']) {
						$output .= '<g:image_link>' . image_resize($product['image'], 500, 500) . '</g:image_link>';
					} else {
						$output .= '<g:image_link>' . image_resize('no_image.jpg', 500, 500) . '</g:image_link>';
					}
					
					$output .= '<link>' . $this->url->http('product/product&product_id=' . $product['product_id']) . '</link>';
					$output .= '<g:mpn>' . $product['model'] . '</g:mpn>';
					$output .= '<g:price>' . $this->tax->calculate($product['price'], $product['tax_class_id']) . '</g:price>';
					
					$categories = $this->model_catalog_product->getCategories($product['product_id']);
					
					foreach ($categories as $category) {
						$path = $this->getPath($category['category_id']);
						
						if ($path) {
							$string = '';
							
							foreach (explode('_', $path) as $path_id) {
								$category_info = $this->model_catalog_category->getCategory($path_id);
								
								if ($category_info) {
									if (!$string) {
										$string = $category_info['name'];
									} else {
										$string .= ' &gt; ' . $category_info['name'];
									}
								}
							}
						 
							$output .= '<g:product_type>' . $string . '</g:product_type>';
						}
					}
					
					$output .= '<g:quantity>' . $product['quantity'] . '</g:quantity>';
					$output .= '<g:upc>' . $product['model'] . '</g:upc>'; 
					$output .= '<g:weight>' . $this->weight->format($product['weight'], $product['weight_class_id']) . '</g:weight>'; 
					$output .= '</item>';
				}
			}
			
			$output .= '</channel>';
			$output .= '</rss>';	
			
			$this->response->addHeader('Content-Type', 'application/rss+xml');
			$this->response->setOutput($output);
		}
	}
	
	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);
	
		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}	
		
			$path = $this->getPath($category_info['parent_id'], $new_path);
					
			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}		
}
?>