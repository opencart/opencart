<?php
class ControllerFeedGoogleSitemap extends Controller {
   public function index() {
	  if ($this->config->get('google_sitemap_status')) {
		 $output  = '<?xml version="1.0" encoding="UTF-8"?>';
		 $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		 
		 $this->load->model('tool/seo_url');
		 
		 $this->load->model('catalog/product');
		 
		 $products = $this->model_catalog_product->getProducts();
		 
		 foreach ($products as $product) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&product_id=' . $product['product_id'])) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';   
		 }
		 
		 $this->load->model('catalog/category');
		 
		 $categories = $this->model_catalog_category->getCategories();
		 
		 $output .= $this->getCategories(0);
		 
		 $this->load->model('catalog/manufacturer');
		 
		 $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
		 
		 foreach ($manufacturers as $manufacturer) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $manufacturer['manufacturer_id'])) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';   
			
			$products = $this->model_catalog_product->getProductsByManufacturerId($manufacturer['manufacturer_id']);
			
			foreach ($products as $product) {
			   $output .= '<url>';
			   $output .= '<loc>' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&manufacturer_id=' . $manufacturer['manufacturer_id'] . '&product_id=' . $product['product_id'])) . '</loc>';
			   $output .= '<changefreq>weekly</changefreq>';
			   $output .= '<priority>1.0</priority>';
			   $output .= '</url>';   
			}         
		 }
		 
		 $this->load->model('catalog/information');
		 
		 $informations = $this->model_catalog_information->getInformations();
		 
		 foreach ($informations as $information) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/information&information_id=' . $information['information_id'])) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.5</priority>';
			$output .= '</url>';   
		 }
		 
		 $output .= '</urlset>';
		 
		 $this->response->addHeader('Content-Type: application/xml');
		 $this->response->setOutput($output);
	  }
   }
   
   protected function getCategories($parent_id, $current_path = '') {
	  $output = '';
	  
	  $results = $this->model_catalog_category->getCategories($parent_id);
	  
	  foreach ($results as $result) {
		 if (!$current_path) {
			$new_path = $result['category_id'];
		 } else {
			$new_path = $current_path . '_' . $result['category_id'];
		 }

		 $output .= '<url>';
		 $output .= '<loc>' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/category&path=' . $new_path)) . '</loc>';
		 $output .= '<changefreq>weekly</changefreq>';
		 $output .= '<priority>0.7</priority>';
		 $output .= '</url>';         

		 $products = $this->model_catalog_product->getProductsByCategoryId($result['category_id']);
		 
		 foreach ($products as $product) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&path=' . $new_path . '&product_id=' . $product['product_id'])) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';   
		 }   
		 
		   $output .= $this->getCategories($result['category_id'], $new_path);
	  }

	  return $output;
   }      
}
?>
