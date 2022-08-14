<?php
namespace Opencart\Catalog\Model\Localisation;
class Language extends \Opencart\System\Engine\Model {
	public function getLanguage(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->row;
	}

	public function getLanguageByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getLanguages(): array {
		$language_data = $this->cache->get('catalog.language');

		if (!$language_data) {
			$language_data = [];

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `status` = '1' ORDER BY `sort_order`, `name`");

			foreach ($query->rows as $result) {
				$image = HTTP_SERVER;

				if (!$result['extension']) {
					$image .= 'catalog/';
				} else {
					$image .= 'extension/' . $result['extension'] . '/catalog/';
				}

				$language_data[] = [
					'language_id' => $result['language_id'],
					'name'        => $result['name'],
					'code'        => $result['code'],
					'image'       => $image . 'language/' . $result['code'] . '/' . $result['code'] . '.png',
					'locale'      => $result['locale'],
					'extension'   => $result['extension'],
					'sort_order'  => $result['sort_order'],
					'status'      => $result['status']
				];
			}

			$this->cache->set('catalog.language', $language_data);
		}

		return $language_data;
	}
}
