<?php
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		$this->language->load('common/column_left');

		$data['text_license'] = $this->language->get('text_license');
		$data['text_installation'] = $this->language->get('text_installation');
		$data['text_configuration'] = $this->language->get('text_configuration');
		$data['text_finished'] = $this->language->get('text_finished');
		
		if (isset($this->request->get['route'])) {
			$data['route'] = $this->request->get['route'];
		} else {
			$data['route'] = 'install/step_1';
		}
		
		return $this->load->view('common/column_left', $data);
	}
}