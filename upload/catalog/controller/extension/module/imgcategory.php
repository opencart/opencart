<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleImgcategory extends Controller {
	
	public function index($setting) {
		
		$this->load->language('extension/module/imgcategory');

    	$data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('catalog/category');

		$this->load->model('tool/image');

		$data['categories'] = array();
		
		$results = $this->model_catalog_category->getCategories($setting['category_id']);
		
		
		foreach ($results as $result) {
			
			if ($result['image']) {
                $image = $result['image'];
            } else {
                $image = 'placeholder.png';
            }
			
			
            $data['categories'][] = array (
				'href' 	=> $this->url->link('product/category', 'path=' . $result['category_id']),
				'thumb'	=> $this->model_tool_image->resize($image, $setting['width'], $setting['height']),
				'name' 	=> $result['name'],
			);
		}
	
		return $this->load->view('extension/module/imgcategory', $data);
  	}
	
}
?>