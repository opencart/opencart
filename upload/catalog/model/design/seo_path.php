<?php
namespace Opencart\Catalog\Model\Design;
/**
 * Class Seo Paths
 *
 * Can be called using $this->load->model('design/seo_regex');
 *
 * @package Opencart\Catalog\Model\Design
 */
class SeoPath extends \Opencart\System\Engine\Model {
	/**
	 * Get Seo Paths
	 *
	 * @param string $keyword
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_regex');
	 *
	 * $seo_regex_info = $this->model_design_seo_regex->getSeoUrlByKeyword($keyword);
	 */
	public function getSeoPaths(): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "seo_regex`");

		return $query->rows;
	}
}
