<?php
class ControllerCommonFooter extends Controller {   
	public function index() {
		$this->load->language('common/footer');

		$data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);

		if (file_exists(DIR_SYSTEM . 'config/svn/svn.ver')) {
			$data['text_footer'] .= '.r' . trim(file_get_contents(DIR_SYSTEM . 'config/svn/svn.ver'));
		}

		return $this->load->view('common/footer.tpl', $data);
	}
}