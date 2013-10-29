<?php
class ControllerCommonFooter extends Controller {   
	protected function index() {
		$this->language->load('common/footer');

		$this->data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);

		if (file_exists(DIR_SYSTEM . 'config/svn/svn.ver')) {
			$this->data['text_footer'] .= '.r' . trim(file_get_contents(DIR_SYSTEM . 'config/svn/svn.ver'));
		}

		$this->template = 'common/footer.tpl';

		$this->render();
	}
}
?>