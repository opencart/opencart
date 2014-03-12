<?php
class ControllerAccountCustomField extends Controller {
	public function index($type) {
		// Custom Fields
		if (isset($this->request->post['custom_field'])) {
			$custom_field_info = $this->request->post['custom_field'];		
		} elseif (!empty($customer_info)) {
			$custom_field_info = unserialize($customer_info['custom_field']);
		} else {
			$custom_field_info = array();
		}	
				
		$this->load->model('account/custom_field');

		$data['custom_fields'] = array();

		// If a post request then get a list of all fields that should have been posted for validation checking.
		$custom_fields = $this->model_account_custom_field->getCustomFields($type, $this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['type'] == 'checkbox') {
				$value = array();
			} else {
				$value = '';
			}

			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $custom_field['custom_field_value'],
				'name'               => $custom_field['name'],
				'type'               => $custom_field['type'],
				'value'              => isset($custom_field_info[$custom_field['custom_field_id']]) ? $custom_field_info[$custom_field['custom_field_id']] : $value,
				'required'           => $custom_field['required'],
				'sort_order'         => $custom_field['sort_order']
			);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/custom_field.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/custom_field.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/custom_field.tpl', $data));
		}
	}	
}