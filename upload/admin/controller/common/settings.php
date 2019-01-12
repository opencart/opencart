<?php
class ControllerCommonSettings extends Controller {

	public function index(array $setting) {
		$this->load->model('setting/setting');

		$store = empty($this->request->get['store_id']) ? 0 : $this->request->get['store_id'];

		if (!empty($setting['store_id'])) {
			$store = $setting['store_id'];
		}

		$settings = $this->model_setting_setting->getSetting($setting['code'], $store);

		foreach ($settings as $key => $value) {
			$data[$key] = $value;
		}

		return $data;
	}

	public function getDataModule(array $args)
	{
		$this->load->model('setting/module');

		$module_info = $this->model_setting_module->getModule($args['module_id']);

		foreach ($module_info as $key => $value) {
			$data[$key] = $value;
		}

		return $data;
	}

}
