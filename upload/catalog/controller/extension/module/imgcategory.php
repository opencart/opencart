<?php
// *	@copyright	OPENCART.PRO 2011 - 2016.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleImgcategory extends Controller {

	public function index($setting) {
		$this->load->language('extension/module/imgcategory');

    	$data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('catalog/category');

		$this->load->model('tool/image');

		$data['categories'] = $this->getCategories($setting['category_id']);

		return $this->load->view('extension/module/imgcategory', $data);
  	}

	protected function getCategories($parent_cat_id) {
		$categories = array();
		
		$results = $this->model_catalog_category->getCategories($parent_cat_id);

        if (empty($results)) {
            return $categories;
        }
		
		$i = 0;
		foreach ($results as $result) {
            $categories[$i]['href'] = $this->url->link('product/category', 'path=' . $result['category_id']);

			if ($result['image']) {
                $image = $result['image'];
            } else {
                $image = 'placeholder.png';
            }
			
			
			$categories[$i]['thumb'] = $this->model_tool_image->resize($image, $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
            $categories[$i]['name'] = $result['name'];
			
            $i++;
		}
		
		return $categories;
	}		
}
?>