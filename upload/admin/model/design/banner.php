<?php
namespace Opencart\Admin\Model\Design;
/**
 * Class Banner
 *
 * Can be loaded using $this->load->model('design/banner');
 *
 * @package Opencart\Admin\Model\Design
 */
class Banner extends \Opencart\System\Engine\Model {
	/**
	 * Add Banner
	 *
	 * Create a new banner record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new banner record
	 *
	 * @example
	 *
	 * $banner_data = [
	 *     'banner_image_description' => [],
	 *     'name'                     => 'Banner Name',
	 *     'status'                   => 0
	 * ];
	 *
	 * $this->load->model('design/banner');
	 *
	 * $banner_id = $this->model_design_banner->addBanner($banner_data);
	 */
	public function addBanner(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "banner` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$banner_id = $this->db->getLastId();

		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $language_id => $value) {
				foreach ($value as $banner_image) {
					$this->addImage($banner_id, $language_id, $banner_image);
				}
			}
		}

		return $banner_id;
	}

	/**
	 * Edit Status
	 *
	 * Edit banner status record in the database.
	 *
	 * @param int  $banner_id primary key of the banner record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->editStatus($category_id, $status);
	 */
	public function editStatus(int $banner_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "banner` SET `status` = '" . (bool)$status . "' WHERE `banner_id` = '" . (int)$banner_id . "'");
	}

	/**
	 * Edit Banner
	 *
	 * Edit banner record in the database.
	 *
	 * @param int                  $banner_id primary key of the banner record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $banner_data = [
	 *     'banner_image_description' => [],
	 *     'name'                     => 'Banner Name',
	 *     'status'                   => 1
	 * ];
	 *
	 * $this->load->model('design/banner');
	 *
	 * $this->model_design_banner->editBanner($banner_id, $banner_data);
	 */
	public function editBanner(int $banner_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "banner` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `banner_id` = '" . (int)$banner_id . "'");

		$this->deleteImages($banner_id);

		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $language_id => $value) {
				foreach ($value as $banner_image) {
					$this->addImage($banner_id, $language_id, $banner_image);
				}
			}
		}
	}

	/**
	 * Delete Banner
	 *
	 * Delete banner record in the database.
	 *
	 * @param int $banner_id primary key of the banner record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/banner');
	 *
	 * $this->model_design_banner->deleteBanner($banner_id);
	 */
	public function deleteBanner(int $banner_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "banner` WHERE `banner_id` = '" . (int)$banner_id . "'");

		$this->deleteImages($banner_id);
	}

	/**
	 * Get Banner
	 *
	 * Get the record of the banner record in the database.
	 *
	 * @param int $banner_id primary key of the banner record
	 *
	 * @return array<string, mixed> banner record that has banner ID
	 *
	 * @example
	 *
	 * $this->load->model('design/banner');
	 *
	 * $banner_info = $this->model_design_banner->getBanner($banner_id);
	 */
	public function getBanner(int $banner_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "banner` WHERE `banner_id` = '" . (int)$banner_id . "'");

		return $query->row;
	}

	/**
	 * Get Banners
	 *
	 * Get the record of the banner records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> banner records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('design/banner');
	 *
	 * $results = $this->model_design_banner->getBanners($filter_data);
	 */
	public function getBanners(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "banner`";

		$sort_data = [
			'name',
			'status'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `name`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Banners
	 *
	 * Get the total number of banner records in the database.
	 *
	 * @return int total number of banner records
	 *
	 * @example
	 *
	 * $this->load->model('design/banner');
	 *
	 * $banner_total = $this->model_design_banner->getTotalBanners();
	 */
	public function getTotalBanners(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "banner`");

		return (int)$query->row['total'];
	}

	/**
	 * Add Image
	 *
	 * Create a new banner image record in the database.
	 *
	 * @param int                  $banner_id   primary key of the banner record
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of filters
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $banner_image_data = [
	 *     'title'      => 'Banner Title',
	 *     'link'       => 'Banner Link',
	 *     'image'      => 'Banner Image',
	 *     'sort_order' => 0
	 * ];
	 *
	 * $this->load->model('design/banner');
	 *
	 * $this->model_design_banner->addImage($banner_id, $language_id, $banner_image_data);
	 */
	public function addImage(int $banner_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "banner_image` SET `banner_id` = '" . (int)$banner_id . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($data['title']) . "', `link` = '" . $this->db->escape($data['link']) . "', `image` = '" . $this->db->escape($data['image']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");
	}

	/**
	 * Delete Images
	 *
	 * Delete banner image records in the database.
	 *
	 * @param int $banner_id primary key of the banner record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/banner');
	 *
	 * $this->model_design_banner->deleteImages($banner_id);
	 */
	public function deleteImages(int $banner_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "banner_image` WHERE `banner_id` = '" . (int)$banner_id . "'");
	}

	/**
	 * Delete Images By Language ID
	 *
	 * Delete banner images by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/banner');
	 *
	 * $this->model_design_banner->deleteImagesByLanguageId($language_id);
	 */
	public function deleteImagesByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "banner_image` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Images
	 *
	 * Get the record of the banner image records in the database.
	 *
	 * @param int $banner_id primary key of the banner record
	 *
	 * @return array<int, array<int, array<string, mixed>>> image records that have banner ID
	 *
	 * @example
	 *
	 * $this->load->model('design/banner');
	 *
	 * $banner_images = $this->model_design_banner->getImages($banner_id);
	 */
	public function getImages(int $banner_id, int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image` WHERE `banner_id` = '" . (int)$banner_id . "' AND `language_id` = '" . (int)$language_id . "' ORDER BY `sort_order` ASC");

		return $query->rows;
	}

	/**
	 * Get Images By Language ID
	 *
	 * Get the record of the banner images by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, mixed>> image records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('design/banner');
	 *
	 * $results = $this->model_design_banner->getImagesByLanguageId($language_id);
	 */
	public function getImagesByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
