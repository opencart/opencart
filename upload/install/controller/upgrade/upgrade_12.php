<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade11
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade12 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			$this->load->model('upgrade/upgrade');

			// Modify various DB field fields and indexes
			$type = $this->model_upgrade_upgrade->getFieldType('banner_image', 'link');
			if ($type != 'text') {
				$this->model_upgrade_upgrade->alterFieldType('banner_image', 'link', 'text', '');
			}
			$type = $this->model_upgrade_upgrade->getFieldType('article_description', 'description');
			if ($type != 'mediumtext') {
				$this->model_upgrade_upgrade->alterFieldType('article_description', 'description', 'mediumtext', '');
			}
			$type = $this->model_upgrade_upgrade->getFieldType('topic_description', 'description');
			if ($type != 'mediumtext') {
				$this->model_upgrade_upgrade->alterFieldType('topic_description', 'description', 'mediumtext', '');
			}
			$type = $this->model_upgrade_upgrade->getFieldType('category_description', 'description');
			if ($type != 'mediumtext') {
				$this->model_upgrade_upgrade->alterFieldType('category_description', 'description', 'mediumtext', '');
			}
			$type = $this->model_upgrade_upgrade->getFieldType('module', 'setting');
			if ($type != 'mediumtext') {
				$this->model_upgrade_upgrade->alterFieldType('module', 'setting', 'mediumtext', '');
			}
			if (!$this->model_upgrade_upgrade->hasIndex('order', 'order_status_id')) {
				$this->model_upgrade_upgrade->createIndex('order', 'order_status_id');
			}
			$this->model_upgrade_upgrade->dropField('product', 'sku');
			$this->model_upgrade_upgrade->dropField('product', 'upc');
			$this->model_upgrade_upgrade->dropField('product', 'ean');
			$this->model_upgrade_upgrade->dropField('product', 'jan');
			$this->model_upgrade_upgrade->dropField('product', 'isbn');
			$this->model_upgrade_upgrade->dropField('product', 'mpn');
			if (!$this->model_upgrade_upgrade->hasIndex('product_code', 'product_id')) {
				$this->model_upgrade_upgrade->createIndex('product_code', 'product_id');
			}
			$type = $this->model_upgrade_upgrade->getFieldType('product_description', 'description');
			if ($type != 'mediumtext') {
				$this->model_upgrade_upgrade->alterFieldType('product_description', 'description', 'mediumtext', '');
			}
			$type = $this->model_upgrade_upgrade->getFieldType('session', 'data');
			if ($type != 'mediumtext') {
				$this->model_upgrade_upgrade->alterFieldType('product_description', 'description', 'mediumtext', '');
			}
			$type = $this->model_upgrade_upgrade->getFieldType('setting', 'value');
			if ($type != 'mediumtext') {
				$this->model_upgrade_upgrade->alterFieldType('setting', 'value', 'mediumtext', '');
			}

			// add various config settings
			$setting_records = $this->model_upgrade_upgrade->getRecords('setting');

			$has_config_product_filters = false;
			$has_config_product_search = false;
			$has_config_product_search_admin = false;

			foreach ($setting_records as $row) {
				if ($row['key'] == 'config_product_filters') {
					$has_config_product_filters = true;
				}
				if ($row['key'] == 'config_product_search') {
					$has_config_product_search = true;
				}
				if ($row['key'] == 'config_product_search_admin') {
					$has_config_product_search_admin = true;
				}
			}

			if (!$has_config_product_filters) {
				$setting_data = [
					'store_id'   => 0,
					'code'       => 'config',
					'key'        => 'config_product_filters',
					'value'      => 'and',
					'serialized' => 0
				];
				$this->model_upgrade_upgrade->addRecord('setting', $setting_data);
			}

			if (!$has_config_product_search) {
				$setting_data = [
					'store_id'   => 0,
					'code'       => 'config',
					'key'        => 'config_product_search',
					'value'      => 'and',
					'serialized' => 0
				];
				$this->model_upgrade_upgrade->addRecord('setting', $setting_data);
			}

			if (!$has_config_product_search_admin) {
				$setting_data = [
					'store_id'   => 0,
					'code'       => 'config',
					'key'        => 'config_product_search_admin',
					'value'      => 'and',
					'serialized' => 0
				];
				$this->model_upgrade_upgrade->addRecord('setting', $setting_data);
			}

			// add some SEO URL keywords
			$language_records = $this->model_upgrade_upgrade->getRecords('language');

			foreach ($language_records as $language_row) {
				$language_id = $language_row['language_id'];

				$seo_url_records = $this->model_upgrade_upgrade->getRecords('seo_url');

				$has_brand = false;
				$has_blog = false;
				$has_article = false;

				foreach ($seo_url_records as $row) {
					if ($row['language_id'] != $language_id) {
						continue;
					}
					if ($row['keyword'] == 'brand') {
						$has_brand = true;
					}
					if ($row['keyword'] == 'blog') {
						$has_blog = true;
					}
					if ($row['keyword'] == 'article') {
						$has_article = true;
					}
				}

				if (!$has_brand) {
					$seo_url_data = [
						'store_id'    => 0,
						'language_id' => $language_id,
						'key'         => 'route',
						'value'       => 'product/manufacturer.info',
						'keyword'     => 'brand',
						'sort_order'  => -2
					];
					$this->model_upgrade_upgrade->addRecord('seo_url', $seo_url_data);
				}

				if (!$has_blog) {
					$seo_url_data = [
						'store_id'    => 0,
						'language_id' => $language_id,
						'key'         => 'route',
						'value'       => 'cms/blog',
						'keyword'     => 'blog',
						'sort_order'  => -1
					];
					$this->model_upgrade_upgrade->addRecord('seo_url', $seo_url_data);
				}

				if (!$has_article) {
					$seo_url_data = [
						'store_id'    => 0,
						'language_id' => $language_id,
						'key'         => 'route',
						'value'       => 'cms/blog.info',
						'keyword'     => 'article',
						'sort_order'  => -1
					];
					$this->model_upgrade_upgrade->addRecord('seo_url', $seo_url_data);
				}
			}

			$dir_storage = str_replace('\\', '/', realpath(DIR_STORAGE));
			$dir_current_storage = str_replace('\\', '/', realpath($this->getCurrentStorageDirectory()));
			$dir_vendor = $dir_storage . '/vendor';
			$dir_current_vendor = $dir_current_storage . '/vendor';

			// remove obsolete files and folders from vendor directory
			$obsoletes = [
				'aws/',
				'bin/',
				'guzzlehttp/',
				'mtdowling/',
				'psr/http-client/',
				'ralouphie/',
				'scssphp/scssphp/bin/',
				'scssphp/scssphp/src/Base/',
				'scssphp/scssphp/src/Block/',
				'scssphp/scssphp/src/Compiler/CachedResult.php',
				'scssphp/scssphp/src/Compiler/Environment.php',
				'scssphp/scssphp/src/Exception/CompilerException.php',
				'scssphp/scssphp/src/Exception/ParserException.php',
				'scssphp/scssphp/src/Exception/RangeException.php',
				'scssphp/scssphp/src/Exception/ServerException.php',
				'scssphp/scssphp/src/Formatter/',
				'scssphp/scssphp/src/SourceMap/SourceMapGenerator.php',
				'scssphp/scssphp/src/Block.php',
				'scssphp/scssphp/src/Cache.php',
				'scssphp/scssphp/src/Formatter.php',
				'scssphp/scssphp/src/Parser.php',
				'scssphp/scssphp/scss.inc.php',
				'symfony/deprecation-contracts/.gitignore',
				'symfony/polyfill-php81/',
				'twig/twig/phpstan-baseline.neon',
				'twig/twig/phpstan.neon.dist'
			];
			if ($dir_current_vendor != $dir_vendor) {
				$this->mergeDirectories($dir_vendor, $dir_current_vendor);
			}
			$this->deleteObsoletes($dir_vendor, $obsoletes);
			if ($dir_current_vendor != $dir_vendor) {
				$this->deleteObsoletes($dir_current_vendor, $obsoletes);
			}

			// clear modification folder
			$this->deleteEntries(DIR_EXTENSION . '/ocmod/*/*');

			// remove obsolete files
			if (isset($this->request->get['admin'])) {
				$admin = basename($this->request->get['admin']);
			} else {
				$admin = 'admin';
			}
			$dir_opencart = str_replace('\\', '/', realpath(DIR_OPENCART));
			$this->deleteEntry($dir_opencart . '/' . $admin . '/view/stylesheet/fonts/fontawesome/webfonts/fa-brands-400.ttf');
			$this->deleteEntry($dir_opencart . '/' . $admin . '/view/stylesheet/fonts/fontawesome/webfonts/fa-regular-400.ttf');
			$this->deleteEntry($dir_opencart . '/' . $admin . '/view/stylesheet/fonts/fontawesome/webfonts/fa-solid-900.ttf');
			$this->deleteEntry($dir_opencart . '/' . $admin . '/view/stylesheet/fonts/fontawesome/webfonts/fa-v4compatibility.ttf');
			$this->deleteEntry($dir_opencart . '/catalog/view/stylesheet/fonts/fontawesome/webfonts/fa-brands-400.ttf');
			$this->deleteEntry($dir_opencart . '/catalog/view/stylesheet/fonts/fontawesome/webfonts/fa-regular-400.ttf');
			$this->deleteEntry($dir_opencart . '/catalog/view/stylesheet/fonts/fontawesome/webfonts/fa-solid-900.ttf');
			$this->deleteEntry($dir_opencart . '/catalog/view/stylesheet/fonts/fontawesome/webfonts/fa-v4compatibility.ttf');
			$this->deleteEntry($dir_opencart . '/install/view/stylesheet/fontawesome/webfonts/fa-brands-400.ttf');
			$this->deleteEntry($dir_opencart . '/install/view/stylesheet/fontawesome/webfonts/fa-regular-400.ttf');
			$this->deleteEntry($dir_opencart . '/install/view/stylesheet/fontawesome/webfonts/fa-solid-900.ttf');
			$this->deleteEntry($dir_opencart . '/install/view/stylesheet/fontawesome/webfonts/fa-v4compatibility.ttf');
			$this->deleteEntry($dir_opencart . '/system/helper/filter.php');
			$this->deleteEntry($dir_opencart . '/system/helper/vendor.php');
			$this->deleteEntry($dir_opencart . '/system/vendor.php');

			// remove various obsolete extensions files along with the DB entries
			$this->removeByName($dir_opencart, 'ddos');
			$this->removeByNameFromDB('ddos');

		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['redirect'] = $this->url->link('install/step_4', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function removeByName(string $dir, string $name): bool {
		if (!file_exists($dir)) {
			return true;
		}

		if (!is_dir($dir)) {
			$file = $dir;
			if (strpos($file, $name) === false) {
				return true;
			}

			return @unlink($file);
		}

		$items = @scandir($dir);
		if (!is_array($items)) {
			return true;
		}
		foreach ($items as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (!$this->removeByName($dir . '/' . $item, $name)) {
				return false;
			}
		}

		return (strpos($dir, $name) === false) ? true : @rmdir($dir);
	}

	/**
	 * delete obsolete files and folders
	 *
	 * @param string        $dir
	 * @param array<string> $obsoletes
	 *
	 * @return void
	 */
	private function deleteObsoletes(string $dir, array $obsoletes): void {
		foreach ($obsoletes as $obsolete) {
			$this->deleteEntry($dir . '/' . $obsolete);
		}
	}

	private function getCurrentStorageDirectory(): string {
		$current_dir_storage = '';
		if (is_file(DIR_OPENCART . 'config.php') && filesize(DIR_OPENCART . 'config.php') > 0) {
			$lines = file(DIR_OPENCART . 'config.php');
			foreach ($lines as $line) {
				if (strpos($line, "'DIR_STORAGE'") !== false) {
					$line = str_replace("'DIR_STORAGE'", "'CURRENT_DIR_STORAGE'", $line);
					eval($line);
					$current_dir_storage = CURRENT_DIR_STORAGE;
					break;
				}
			}
		}

		return ($current_dir_storage != '') ? $current_dir_storage : DIR_STORAGE;
	}

	private function mergeDirectories(string $source, string $target): bool {
		if (!is_dir($source)) {
			return false;
		}

		// Create the target directory if it doesn't exist
		if (!is_dir($target)) {
			if (!@mkdir($target, 0755, true)) {
				return false;
			}
		}

		// Open the source directory
		$dir = @opendir($source);
		if ($dir === false) {
			return false;
		}

		// Loop through the files and folders in the source
		while (($file = @readdir($dir)) !== false) {
			// Skip '.' and '..' entries
			if ($file === '.' || $file === '..') {
				continue;
			}

			$source_path = $source . '/' . $file;
			$target_path = $target . '/' . $file;

			// If the item is a directory, recurse
			if (is_dir($source_path)) {
				if (!$this->mergeDirectories($source_path, $target_path)) {
					@closedir($dir);

					return false;
				}
			} else {
				// Otherwise, copy the file
				if (!copy($source_path, $target_path)) {
					@closedir($dir);

					return false;
				}
			}
		}

		@closedir($dir);

		return true;
	}

	private function deleteEntries(string $dir): bool {
		$paths = glob($dir, 0);
		if ($paths === false) {
			return false;
		}

		foreach ($paths as $path) {
			$entry = str_replace('\\', '/', realpath($path));
			$this->deleteEntry($entry);
		}

		return true;
	}

	private function deleteEntry(string $entry): bool {
		if (!file_exists($entry)) {
			return true;
		}

		if (!is_dir($entry)) {
			return @unlink($entry);
		}

		$dir = $entry;
		$items = @scandir($dir);
		if (!is_array($items)) {
			return true;
		}
		foreach ($items as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (!$this->deleteEntry($dir . '/' . $item)) {
				return false;
			}
		}

		return @rmdir($dir);
	}

	private function removeByNameFromDB(string $name): void {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_group`");
		foreach ($query->rows as $row) {
			$user_group_permission = json_decode($row['permission'], true);
			$user_group_id = $row['user_group_id'];
			if (!empty($user_group_permission) && is_array($user_group_permission)) {
				$new_user_group_permission = $user_group_permission;
				foreach ($user_group_permission as $type => $permission) {
					if (($type == 'access') || ($type == 'modify')) {
						if (!empty($permission) && is_array($permission)) {
							foreach ($permission as $key => $val) {
								$route = explode('/', $val);
								if ($name != end($route)) {
									continue;
								}
								unset($new_user_group_permission[$type][$key]);
							}
						}
					}
				}
				if (empty($new_user_group_permission['access']) && empty($new_user_group_permission['modify'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission`='' WHERE user_group_id='" . (int)$user_group_id . "';");
				} else {
					$json_user_group_permission = json_encode($new_user_group_permission);
					$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission`='" . $this->db->escape($json_user_group_permission) . "' WHERE user_group_id='" . (int)$user_group_id . "';");
				}
			}
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code`='" . $this->db->escape($name) . "';");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` LIKE '%_" . $this->db->escape($name) . "';");
		if ($name == 'squareup') {
			$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "squareup_transaction`");
			$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "squareup_token`");
			$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "squareup_customer`");
		}
	}
}
