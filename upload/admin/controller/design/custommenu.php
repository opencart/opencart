<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerDesignCustomMenu extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('design/custommenu');
        $this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_setting->editSetting('configcustommenu', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('design/custommenu', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');

		$data['text_success'] = $this->language->get('text_success');
		$data['text_new_custommenu_item'] = $this->language->get('text_new_custommenu_item');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_information'] = $this->language->get('text_information');
		$data['text_custom'] = $this->language->get('text_custom');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['text_custommenu_title'] = $this->language->get('text_custommenu_title');
		$data['text_custommenu_description'] = $this->language->get('text_custommenu_description');
		$data['text_sub_item'] = $this->language->get('text_sub_item');
		$data['text_custommenu_name'] = $this->language->get('text_custommenu_name');
		$data['text_custommenu_link'] = $this->language->get('text_custommenu_link');

		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['column_custom_name'] = $this->language->get('column_custom_name');
		$data['column_custom_link'] = $this->language->get('column_custom_link');
		$data['column_category_name'] = $this->language->get('column_category_name');
		$data['column_product_name'] = $this->language->get('column_product_name');
		$data['column_manufacturer_name'] = $this->language->get('column_manufacturer_name');
		$data['column_information_name'] = $this->language->get('column_information_name');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_columns'] = $this->language->get('entry_columns');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_store'] = $this->language->get('entry_store');

		$data['button_custom'] = $this->language->get('button_custom');
		$data['button_categories'] = $this->language->get('button_categories');
		$data['button_products'] = $this->language->get('button_products');
		$data['button_manufacturers'] = $this->language->get('button_manufacturers');
		$data['button_informations'] = $this->language->get('button_informations');

		$data['button_disable'] = $this->language->get('button_disable');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_delete'] = $this->language->get('button_delete');

		$data['error_permission'] = $this->language->get('error_permission');
		$data['error_name'] = $this->language->get('error_name');
		$data['error_link'] = $this->language->get('error_link');

		$data['text_custommenu_enable'] = $this->language->get('text_custommenu_enable');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_default'] = $this->language->get('text_default');

        $this->load->model('design/custommenu');

        $this->document->addStyle('view/javascript/jquery/layout/jquery-ui.css');
        $this->document->addStyle('view/stylesheet/custommenu.css');
        $this->document->addStyle('view/stylesheet/layout.css');

		$this->document->addScript('view/javascript/jquery/layout/jquery-ui.js');
        $this->document->addScript('view/javascript/jquery/layout/jquery-lockfixed.js');
        $this->document->addScript('view/javascript/custommenu/custommenu.js');

		$data['changecustommenuPosition'] = $this->url->link('design/custommenu/changecustommenuPosition', 'token=' . $this->session->data['token'], 'SSL');

		$data['deletecustommenu'] = $this->url->link('design/custommenu/deletecustommenu', 'token=' . $this->session->data['token'], 'SSL');
		$data['deleteChildcustommenu'] = $this->url->link('design/custommenu/deleteChildcustommenu', 'token=' . $this->session->data['token'], 'SSL');

		$data['enablecustommenu'] = $this->url->link('design/custommenu/enablecustommenu', 'token=' . $this->session->data['token'], 'SSL');
		$data['enableChildcustommenu'] = $this->url->link('design/custommenu/enableChildcustommenu', 'token=' . $this->session->data['token'], 'SSL');

		$data['disablecustommenu'] = $this->url->link('design/custommenu/disablecustommenu', 'token=' . $this->session->data['token'], 'SSL');
		$data['disableChildcustommenu'] = $this->url->link('design/custommenu/disableChildcustommenu', 'token=' . $this->session->data['token'], 'SSL');

		$data['refresch'] = $this->url->link('design/custommenu', 'token=' . $this->session->data['token'], 'SSL');
		$data['add'] = $this->url->link('design/custommenu/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['save'] = $this->url->link('design/custommenu/save', 'token=' . $this->session->data['token'], 'SSL');

		$data['custommenu_child'] = array();

		$custommenus = $this->model_design_custommenu->getcustommenus();
        $custommenu_child = $this->model_design_custommenu->getChildcustommenus();

        $rent_custommenu = array();

        $this->load->model('catalog/product');
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

        foreach($custommenus as $id => $custommenu) {
            $rent_custommenu[] = array(
                'name'          => $custommenu['name'] ,
                'custommenu_id'       => $custommenu['custommenu_id'],
                'custommenu_type'     => $custommenu['custommenu_type'],
                'status'        => $custommenu['status'],
                'store'         => $this->model_design_custommenu->getcustommenuStores($custommenu['custommenu_id']),
                'isSubcustommenu'     => ''
            );

            foreach($custommenu_child as $child_id => $child_custommenu) {
                if (($custommenu['custommenu_id'] != $child_custommenu['custommenu_id']) or !is_numeric($child_id)) {
                    continue;
                }

                $rent_custommenu[] = array(
                    'name'          => $child_custommenu['name'],
                    'custommenu_id'       => $child_custommenu['custommenu_child_id'],
                    'custommenu_type'     => $child_custommenu['custommenu_type'],
                    'status'        => $child_custommenu['status'],
                    'store'         => $this->model_design_custommenu->getChildcustommenuStores($child_custommenu['custommenu_child_id']),
                    'isSubcustommenu'     => $custommenu['custommenu_id']
                );
            }
        }

		$data['custommenu_desc'] = $this->model_design_custommenu->getcustommenuDesc();
		$data['custommenu_child_desc'] = $this->model_design_custommenu->getcustommenuChildDesc();

        $data['custommenus'] = $rent_custommenu;

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$data['action'] = $this->url->link('design/custommenu', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['configcustommenu_custommenu'])) {
			$data['configcustommenu_custommenu'] = $this->request->post['configcustommenu_custommenu'];
		} else {
			$data['configcustommenu_custommenu'] = $this->config->get('configcustommenu_custommenu');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/custommenu.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/custommenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/custommenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

    public function add() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->load->model('design/custommenu');
            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();

            $custommenu = $this->model_design_custommenu->add($this->request->post, $languages);

            if (!empty($custommenu)) {
                echo $this->addHtml($custommenu, $languages);
            }
        }
    }

    protected function addHtml($custommenu, $languages){
        $this->load->language('design/custommenu');

        $count = '0';

        $html  = '<li id="custommenu-item-' . $custommenu['custommenu_id'] . '" class="custommenu-item custommenu-item-depth-0 custommenu-item-page custommenu-item-edit-inactive pending">';
        $html .= '	<dl class="custommenu-item-bar">';
        $html .= '		<dt class="custommenu-item-handle">';
        $html .= '			<span class="item-title"><span class="custommenu-item-title">' . $custommenu['name'] .'</span> <span class="is-subcustommenu" style="display: none;">' . $this->language->get('text_sub_item') . '</span></span>';
        $html .= '          <span class="item-controls">';
        $html .= '			<span class="item-type">' . ucwords($custommenu['custommenu_type']) . '</span>';
        $html .= '				<a class="item-edit opencustommenuItem ' . $custommenu['custommenu_type'] . '" id="edit-' . $custommenu['custommenu_id'] . '" title="">';
        $html .= '                <i class="fa fa-caret-down"></i>';
        $html .= '              </a>';
        $html .= '			</span>';
        $html .= '      </dt>';
        $html .= ' </dl>';

        $custommenu_desc = $this->model_design_custommenu->getcustommenuDesc();

        $html .= '<div class="custommenu-item-settings" id="custommenu-item-settings-edit-' . $custommenu['custommenu_id'] . '">';
        $html .= $this->language->get('text_custommenu_name') . '</br>';

        foreach ($languages as $language) {
			$html .= '<div class="input-group"><span class="input-group-addon"><img src="language/' . $language["code"] . '/' . $language["code"] . '.png' . '" title="' . $language["name"] . '"/></span>';
            $html .= '   <input type="text" name="custommenu_name[' . $language['language_id'] . ']" value="' . $custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['name']. '" placeholder="' . $this->language->get('text_custommenu_name') . '" class="form-control" />';
            $html .= '</div>';
        }
        $html .= '<br>';

        if ($custommenu['custommenu_type'] == 'custom') {
            $html .= $this->language->get('text_custommenu_link') . '<br>';

            foreach ($languages as $language) {
                $html .= '<div class="input-group"><span class="input-group-addon"><img src="language/' . $language["code"] . '/' . $language['code'] . '.png' . '" title="' . $language['name'] . '" /></span>';
                $html .= '   <input type="text" name="custommenu_link[' . $language['language_id'] . ']" value="' . $custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['link'] . '" placeholder="' . $this->language->get('text_custommenu_link') . '" class="form-control" />';
                $html .= '</div>';
            }

            $html .= '<br>';
        }

        $html .= $this->language->get('entry_columns');
        $html .= '<div class="input-group">';
        $html .= '  <input type="text" name="custommenu_columns" value="1" placeholder="" id="input-columns" class="form-control" />';
        $html .= '</div>';
        $html .= '<br>';
        $html .= '<div class="pull-right">';
        $html .= ' <a id="disablecustommenu-'. $count . '" onclick="statuscustommenu(\'disable\', \'' . $custommenu['custommenu_id'] . '\', \'custommenu-item-' . $custommenu['custommenu_id'] . '\', \'disablecustommenu-' . $count .'\')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="' . $this->language->get('button_disable') . '" class="btn btn-danger btn-xs btn-edit btn-group"><i class="fa fa-times-circle"></i></a>';
        $html .= ' <a onclick="savecustommenu(\'custommenu-item-settings-edit-' . $custommenu['custommenu_id'] . '\', \'custommenu-item-' . $custommenu['custommenu_id'] . '\')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="' . $this->language->get('button_save') . '" class="btn btn-success btn-xs btn-edit btn-group"><i class="fa fa-save"></i></a>';
        $html .= ' <button type="button" data-toggle="tooltip" title="" style="top:2px!important;font-size:1.2em !important;" class="btn btn-danger btn-xs btn-edit btn-group btn-loading" onclick="confirm(\'Are you sure?\') ? deletecustommenu(\'' . $custommenu['custommenu_id'] . '\', \'custommenu-item-' . $custommenu['custommenu_id'] . '\') : false;" data-original-title="Delete"><i class="fa fa-trash-o"></i></button>';
        $html .= '</div>';
        $html .= '<br><br>';

        $html .= '<input class="custommenu-item-data-typecustommenu" type="hidden" name="custommenu-item-typecustommenu[Maincustommenu-' . $custommenu['custommenu_id'].']" value="Maincustommenu">';
        $html .= '<input class="custommenu-item-data-db-id" type="hidden" name="custommenu-item-db-id[Maincustommenu-' . $custommenu['custommenu_id'] . ']" value="' . $custommenu['custommenu_id'] . '">';
        $html .= '<input class="custommenu-item-data-parent-id" type="hidden" name="custommenu-item-parent-id[Maincustommenu-' . $custommenu['custommenu_id'] .']" value="0">';
        $html .= '<input class="custommenu-item-data-position" type="hidden" name="custommenu-item-position[Maincustommenu-' . $custommenu['custommenu_id'] . ']" value="' . $custommenu['custommenu_id'] . '">';
        $html .= '<input class="custommenu-item-data-type" type="hidden" name="custommenu-item-type[Maincustommenu-' . $custommenu['custommenu_id'] . ']" value="post_type">';

        $html .= '</div>';
        $html .= '<ul class="custommenu-item-transport"></ul>';
        $html .= '</li>';

        return $html;
    }

    public function save() {
        $this->load->language('design/custommenu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('design/custommenu');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if($this->request->get['type'] == 'child') {
                $this->model_design_custommenu->saveChild($this->request->post);
            } else {
                $this->model_design_custommenu->save($this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');
        }
    }

    public function deletecustommenu() {
        $this->load->language('design/custommenu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('design/custommenu');

        if (isset($this->request->post['custommenu_id']) && $this->validateDelete()) {
            $this->model_design_custommenu->deletecustommenu($this->request->post['custommenu_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $json['success'] = $this->language->get('text_success');
            $json['error']   = $this->language->get('text_error');

        } else {
            $this->session->data['error'] = $this->language->get('text_error');

            $json['success'] = '';
            $json['error']   = $this->language->get('text_error');
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteChildcustommenu() {
        $this->load->language('design/custommenu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('design/custommenu');

        if (isset($this->request->post['custommenu_id']) && $this->validateDelete()) {
            $this->model_design_custommenu->deleteChildcustommenu($this->request->post['custommenu_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $json['success'] = $this->language->get('text_success');
            $json['error']   = '';

        } else {
            $this->session->data['error'] = $this->language->get('text_error');

            $json['success'] = '';
            $json['error']   = $this->language->get('text_error');
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

	public function enablecustommenu() {
        $this->load->language('design/custommenu');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('design/custommenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_design_custommenu->enablecustommenu($this->request->post['custommenu_id']);
			$this->session->data['success'] = $this->language->get('text_success');

		}

		$id = explode('-', $this->request->post['id']);

		$button = "<a id=\"disablecustommenu-" . $id[1] . "\" onclick=\"statuscustommenu('disable', '" . $this->request->post['custommenu_id'] . "', 'custommenu-item-" .  $this->request->post['custommenu_id'] . "', 'disablecustommenu-" . $id[1] . "')\" data-type=\"iframe\" data-toggle=\"tooltip\" style=\"top:2px!important;font-size:1.2em !important;\" title=\"\" class=\"btn btn-danger btn-xs btn-edit btn-group\"><i class=\"fa fa-times-circle\"></i></a>";

		echo $button;
		exit();
	}

	public function disablecustommenu() {
        $this->load->language('design/custommenu');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('design/custommenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_design_custommenu->disablecustommenu($this->request->post['custommenu_id']);
			$this->session->data['success'] = $this->language->get('text_success');

		}

		$id = explode('-', $this->request->post['id']);

		$button = "<a id=\"enablecustommenu-" . $id[1] . "\" onclick=\"statuscustommenu('enable', '" . $this->request->post['custommenu_id'] . "', 'custommenu-item-" .  $this->request->post['custommenu_id'] . "', 'enablecustommenu-" . $id[1] . "')\" data-type=\"iframe\" data-toggle=\"tooltip\" style=\"top:2px!important;font-size:1.2em !important;\" title=\"\" class=\"btn btn-success btn-xs btn-edit btn-group\"><i class=\"fa fa-check-circle\"></i></a>";

		echo $button;
		exit();
	}

	public function enableChildcustommenu() {
        $this->load->language('design/custommenu');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('design/custommenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_design_custommenu->enableChildcustommenu($this->request->post['custommenu_id']);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		$id = explode('-', $this->request->post['id']);

		$button = "<a id=\"disablecustommenu-" . $id[1] . "\" onclick=\"statuscustommenu('disable', '" . $this->request->post['custommenu_id'] . "', 'custommenu-child-item-" .  $this->request->post['custommenu_id'] . "', 'disablecustommenu-" . $id[1] . "')\" data-type=\"iframe\" data-toggle=\"tooltip\" style=\"top:2px!important;font-size:1.2em !important;\" title=\"\" class=\"btn btn-danger btn-xs btn-edit btn-group\"><i class=\"fa fa-times-circle\"></i></a>";

		echo $button;
		exit();
	}

	public function disableChildcustommenu() {
        $this->load->language('design/custommenu');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('design/custommenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_design_custommenu->disableChildcustommenu($this->request->post['custommenu_id']);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		$id = explode('-', $this->request->post['id']);

		$button = "<a id=\"enablecustommenu-" . $id[1] . "\" onclick=\"statuscustommenu('enable', '" . $this->request->post['custommenu_id'] . "', 'custommenu-child-item-" .  $this->request->post['custommenu_id'] . "', 'enablecustommenu-" . $id[1] . "')\" data-type=\"iframe\" data-toggle=\"tooltip\" style=\"top:2px!important;font-size:1.2em !important;\" title=\"\" class=\"btn btn-success btn-xs btn-edit btn-group\"><i class=\"fa fa-check-circle\"></i></a>";

		echo $button;
		exit();
	}

	public function changecustommenuPosition() {
        $this->load->language('design/custommenu');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('design/custommenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_design_custommenu->changecustommenuPosition($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

		}
	}

	public function autocomplete() {
		$json = array();

		#Category
		if (isset($this->request->get['filter_category_name'])) {
			$this->load->model('catalog/category');

			if (isset($this->request->get['filter_category_name'])) {
				$filter_name = $this->request->get['filter_category_name'];
			} else {
				$filter_name = '';
			}

			$filter_data = array(
				'filter_name' => $filter_name,
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_category->getCategories($filter_data);

			foreach ($results as $result) {

				$result['index'] = $result['name'];
				if(strpos($result['name'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
					$result['name'] = explode ('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['name']);
					$result['name'] = end($result['name']);
				}

				$json[] = array(
					'category_id' => $result['category_id'],
					'index'		  => $result['index'],
					'name'		  => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		#Product
		if (isset($this->request->get['filter_product_name'])) {
			$this->load->model('catalog/product');

			if (isset($this->request->get['filter_product_name'])) {
				$filter_name = $this->request->get['filter_product_name'];
			} else {
				$filter_name = '';
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'index'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		#Manufacturer
		if (isset($this->request->get['filter_manufacturer_name'])) {
			$this->load->model('catalog/manufacturer');

			if (isset($this->request->get['filter_manufacturer_name'])) {
				$filter_name = $this->request->get['filter_manufacturer_name'];
			} else {
				$filter_name = '';
			}

			$filter_data = array(
				'filter_name' => $filter_name,
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'manufacturer_id' => $result['manufacturer_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'index'           => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		#Information
		if (isset($this->request->get['filter_information_name'])) {
			$this->load->model('catalog/information');

			if (isset($this->request->get['filter_information_name'])) {
				$filter_name = $this->request->get['filter_information_name'];
			} else {
				$filter_name = '';
			}

			$filter_data = array(
				'filter_name' => $filter_name,
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_information->getInformations($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'information_id' => $result['information_id'],
					'name'            => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')),
					'index'           => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
