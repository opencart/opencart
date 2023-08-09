<?php
namespace Opencart\Admin\Model\Setting;
class Admin extends \Opencart\System\Engine\Model
{
	public function getByKeys(array $keys): array {
		$settings = new Setting($this->registry);
		return $settings->getSettingsByKeys($keys, -1);
	}
	public function getByKey(string $key): mixed {
		$settings = new Setting($this->registry);
		$result = $settings->getSettingsByKeys([$key], -1);
		return $result[$key] ?? null;
	}

	public function save(array $data): void {
		$settings = new Setting($this->registry);
		$settings->editSetting('config', $data, -1);
	}
}
