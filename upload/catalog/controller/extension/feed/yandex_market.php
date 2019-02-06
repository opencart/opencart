<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

/**
 * Класс YML экспорта
 * YML (Yandex Market Language) - стандарт, разработанный "Яндексом"
 * для принятия и публикации информации в базе данных Яндекс.Маркет
 * YML основан на стандарте XML (Extensible Markup Language)
 * описание формата YML http://partner.market.yandex.ru/legal/tt/
 */
class ControllerExtensionFeedYandexMarket extends Controller {
	private $shop = array();
	private $currencies = array();
	private $categories = array();
	private $offers = array();
	private $from_charset = 'utf-8';
	private $eol = "\n";

	public function index() {
		if ($this->config->get('feed_yandex_market_status')) {
			// Защитный ключ
			$secret_key = $this->config->get('feed_yandex_market_secret_key');

			if ($secret_key) {
				if (isset($this->request->get['secret_key'])) {
					if ($this->request->get['secret_key'] != $secret_key) exit();
				} else {
					exit();
				}
			}

			// Выборка категорий и производителей
			$allowed_categories = $this->config->get('feed_yandex_market_categories');
			$allowed_manufacturers = $this->config->get('feed_yandex_market_manufacturers');

			//if (!$allowed_categories && !$allowed_manufacturers) exit();

			$this->load->model('extension/feed/yandex_market');
			$this->load->model('localisation/currency');
			$this->load->model('tool/image');

			// Магазин
			$this->setShop('name', $this->config->get('feed_yandex_market_shopname'));
			$this->setShop('company', $this->config->get('feed_yandex_market_company'));
			$this->setShop('url', HTTP_SERVER);
			$this->setShop('phone', $this->config->get('config_telephone'));
			$this->setShop('platform', 'OCSTORE.COM');
			$this->setShop('version', VERSION);

			// Валюты
			// TODO: Добавить возможность настраивать проценты в админке.
			$offers_currency = $this->config->get('feed_yandex_market_currency');
			if (!$this->currency->has($offers_currency)) exit();

			$decimal_place = $this->currency->getDecimalPlace($offers_currency);

			$shop_currency = $this->config->get('config_currency');

			$this->setCurrency($offers_currency, 1);

			$currencies = $this->model_localisation_currency->getCurrencies();

			$supported_currencies = array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAH');

			$currencies = array_intersect_key($currencies, array_flip($supported_currencies));

			foreach ($currencies as $currency) {
				if ($currency['code'] != $offers_currency && $currency['status'] == 1) {
					$this->setCurrency($currency['code'], number_format(1/$this->currency->convert($currency['value'], $offers_currency, $shop_currency), 4, '.', ''));
				}
			}

			// Категории
			$categories = $this->model_extension_feed_yandex_market->getCategory();

			foreach ($categories as $category) {
				$this->setCategory($category['name'], $category['category_id'], $category['parent_id']);
			}

			// Товарные предложения
			$bus_id = $this->config->get('feed_yandex_market_id'); // Идентификатор товара - "id"
			$bus_type = $this->config->get('feed_yandex_market_type'); // Тип предложений - "type"
			$bus_name = $this->config->get('feed_yandex_market_name'); // Название товара - "name"
			$bus_model = $this->config->get('feed_yandex_market_model'); // Код товара - "model"
			$bus_vendorCode = $this->config->get('feed_yandex_market_vendorCode'); // Артикул товара - "vendorCode"

			$bus_image = $this->config->get('feed_yandex_market_image'); // Статус товара без изображений
			$bus_image_width = $this->config->get('feed_yandex_market_image_width'); // Ширина изображения товара
			$bus_image_height = $this->config->get('feed_yandex_market_image_height'); // Высота изображения товара
			$bus_image_quantity = $this->config->get('feed_yandex_market_image_quantity'); // Количество изображений товара

			$bus_main_category = $this->config->get('feed_yandex_market_main_category'); // Статус товара без главной категории

			$in_stock_id = $this->config->get('feed_yandex_market_in_stock'); // id статуса товара "В наличии"
			$out_of_stock_id = $this->config->get('feed_yandex_market_out_of_stock'); // id статуса товара "Нет на складе"
			$bus_quantity_status = $this->config->get('feed_yandex_market_quantity_status'); // Статус товара "количество равное 0"

			$vendor_required = false; // true - только товары у которых задан производитель, необходимо для 'vendor.model'

			$products = $this->model_extension_feed_yandex_market->getProduct($allowed_categories, $allowed_manufacturers, $out_of_stock_id, $vendor_required, $bus_image, $bus_image_quantity, $bus_main_category, $bus_quantity_status);

			foreach ($products as $product) {
				$data = array();

				// Атрибуты товарного предложения
				if (!empty($product[$bus_id])) {
					$data['id'] = $product[$bus_id];
				} else {
					$data['id'] = $product['product_id'];
				}
//				$data['type'] = $bus_type;
//				$data['type'] = 'vendor.model';
				$data['available'] = ($product['quantity'] > 0 || $product['stock_status_id'] == $in_stock_id);
//				$data['bid'] = 10;
//				$data['cbid'] = 15;

				// Параметры товарного предложения
				$data['url'] = $this->url->link('product/product', 'path=' . $this->getPath($product['category_id']) . '&product_id=' . $product['product_id']);
				$data['price'] = number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
				$data['currencyId'] = $offers_currency;
				$data['categoryId'] = $product['category_id'];
				$data['delivery'] = 'true';
//				$data['local_delivery_cost'] = 100;
				if (!empty($product[$bus_name])) {
					$data['name'] = $product[$bus_name];
				} else {
					$data['name'] = $product['name'];
				}
				if (!empty($product['manufacturer'])) {
					$data['vendor'] = $product['manufacturer'];
				} else {
					$data['vendor'] = '';
				}
				if (!empty($product[$bus_vendorCode])) {
					$data['vendorCode'] = $product[$bus_vendorCode];
				} else {
					$data['vendorCode'] = '';
				}
				if (!empty($product[$bus_model])) {
					$data['model'] = $product[$bus_model];
				} else {
					$data['model'] = '';
				}
				if (!empty($product['description'])) {
					$data['description'] = $product['description'];
				} else {
					$data['description'] = '';
				}
//				$data['manufacturer_warranty'] = 'true';
//				$data['barcode'] = $product['sku'];

				if (!empty($product['image'])) {
					$data['picture'] = $this->model_tool_image->resize($product['image'], $bus_image_width, $bus_image_height);
				}

				if (isset($product['images'])) {
					foreach (explode(',', $product['images']) as $image) {
						$data['picture'] .= ',' . $this->model_tool_image->resize($image, $bus_image_width, $bus_image_height);
					}
				}
/*
				// пример структуры массива для вывода параметров
				$data['param'] = array(
					array(
						'name'=>'Wi-Fi',
						'value'=>'есть'
					), array(
						'name'=>'Размер экрана',
						'unit'=>'дюйм',
						'value'=>'20'
					), array(
						'name'=>'Вес',
						'unit'=>'кг',
						'value'=>'4.6'
					)
				);
*/
				$this->setOffer($data);
			}

			$this->categories = array_filter($this->categories, array($this, "filterCategory"));

			if (!$this->categories) exit();

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($this->getYml());
		}
	}

	/**
	 * Методы формирования YML
	 */

	/**
	 * Формирование массива для элемента shop описывающего магазин
	 *
	 * @param string $name - Название элемента
	 * @param string $value - Значение элемента
	 */
	private function setShop($name, $value) {
		$allowed = array('name', 'company', 'url', 'phone', 'platform', 'version', 'agency', 'email');
		if (in_array($name, $allowed)) {
			$this->shop[$name] = $this->prepareField($value);
		}
	}

	/**
	 * Валюты
	 *
	 * @param string $id - код валюты (RUR, RUB, USD, BYR, KZT, EUR, UAH)
	 * @param float|string $rate - курс этой валюты к валюте, взятой за единицу.
	 *	Параметр rate может иметь так же следующие значения:
	 *		CBRF - курс по Центральному банку РФ.
	 *		NBU - курс по Национальному банку Украины.
	 *		NBK - курс по Национальному банку Казахстана.
	 *		СВ - курс по банку той страны, к которой относится интернет-магазин
	 * 		по Своему региону, указанному в Партнерском интерфейсе Яндекс.Маркета.
	 * @param float $plus - используется только в случае rate = CBRF, NBU, NBK или СВ
	 *		и означает на сколько увеличить курс в процентах от курса выбранного банка
	 * @return bool
	 */
	private function setCurrency($id, $rate = 'CBRF', $plus = 0) {
		$allow_id = array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAH');
		if (!in_array($id, $allow_id)) {
			return false;
		}
		$allow_rate = array('CBRF', 'NBU', 'NBK', 'CB');
		if (in_array($rate, $allow_rate)) {
			$plus = str_replace(',', '.', $plus);
			if (is_numeric($plus) && $plus > 0) {
				$this->currencies[] = array(
					'id'=>$this->prepareField(strtoupper($id)),
					'rate'=>$rate,
					'plus'=>(float)$plus
				);
			} else {
				$this->currencies[] = array(
					'id'=>$this->prepareField(strtoupper($id)),
					'rate'=>$rate
				);
			}
		} else {
			$rate = str_replace(',', '.', $rate);
			if (!(is_numeric($rate) && $rate > 0)) {
				return false;
			}
			$this->currencies[] = array(
				'id'=>$this->prepareField(strtoupper($id)),
				'rate'=>(float)$rate
			);
		}

		return true;
	}

	/**
	 * Категории товаров
	 *
	 * @param string $name - название рубрики
	 * @param int $id - id рубрики
	 * @param int $parent_id - id родительской рубрики
	 * @return bool
	 */
	private function setCategory($name, $id, $parent_id = 0) {
		$id = (int)$id;
		if ($id < 1 || trim($name) == '') {
			return false;
		}
		if ((int)$parent_id > 0) {
			$this->categories[$id] = array(
				'id'=>$id,
				'parentId'=>(int)$parent_id,
				'name'=>$this->prepareField($name)
			);
		} else {
			$this->categories[$id] = array(
				'id'=>$id,
				'name'=>$this->prepareField($name)
			);
		}

		return true;
	}

	/**
	 * Товарные предложения
	 *
	 * @param array $data - массив параметров товарного предложения
	 */
	private function setOffer($data) {
		$offer = array();

		$attributes = array('id', 'type', 'available', 'bid', 'cbid', 'param');
		$attributes = array_intersect_key($data, array_flip($attributes));

		foreach ($attributes as $key => $value) {
			switch ($key)
			{
				case 'id':
				case 'bid':
				case 'cbid':
					$value = (int)$value;
					if ($value > 0) {
						$offer[$key] = $value;
					}
					break;

				case 'type':
					if (in_array($value, array('vendor.model', 'book', 'audiobook', 'artist.title', 'tour', 'ticket', 'event-ticket'))) {
						$offer['type'] = $value;
					}
					break;

				case 'available':
					$offer['available'] = ($value ? 'true' : 'false');
					break;

				case 'param':
					if (is_array($value)) {
						$offer['param'] = $value;
					}
					break;

				default:
					break;
			}
		}

		$type = isset($offer['type']) ? $offer['type'] : '';

		$allowed_tags = array('url'=>0, 'buyurl'=>0, 'price'=>1, 'wprice'=>0, 'currencyId'=>1, 'xCategory'=>0, 'categoryId'=>1, 'picture'=>0, 'store'=>0, 'pickup'=>0, 'delivery'=>0, 'deliveryIncluded'=>0, 'local_delivery_cost'=>0, 'orderingTime'=>0);

		switch ($type) {
			case 'vendor.model':
				$allowed_tags = array_merge($allowed_tags, array('typePrefix'=>0, 'vendor'=>1, 'vendorCode'=>0, 'model'=>1, 'provider'=>0, 'tarifplan'=>0));
				break;

			case 'book':
				$allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'binding'=>0, 'page_extent'=>0, 'table_of_contents'=>0));
				break;

			case 'audiobook':
				$allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'table_of_contents'=>0, 'performed_by'=>0, 'performance_type'=>0, 'storage'=>0, 'format'=>0, 'recording_length'=>0));
				break;

			case 'artist.title':
				$allowed_tags = array_merge($allowed_tags, array('artist'=>0, 'title'=>1, 'year'=>0, 'media'=>0, 'starring'=>0, 'director'=>0, 'originalName'=>0, 'country'=>0));
				break;

			case 'tour':
				$allowed_tags = array_merge($allowed_tags, array('worldRegion'=>0, 'country'=>0, 'region'=>0, 'days'=>1, 'dataTour'=>0, 'name'=>1, 'hotel_stars'=>0, 'room'=>0, 'meal'=>0, 'included'=>1, 'transport'=>1, 'price_min'=>0, 'price_max'=>0, 'options'=>0));
				break;

			case 'event-ticket':
				$allowed_tags = array_merge($allowed_tags, array('name'=>1, 'place'=>1, 'hall'=>0, 'hall_part'=>0, 'date'=>1, 'is_premiere'=>0, 'is_kids'=>0));
				break;

			default:
				$allowed_tags = array_merge($allowed_tags, array('name'=>1, 'vendor'=>0, 'vendorCode'=>0, 'model'=>1));
				break;
		}

		$allowed_tags = array_merge($allowed_tags, array('aliases'=>0, 'additional'=>0, 'description'=>0, 'sales_notes'=>0, 'promo'=>0, 'manufacturer_warranty'=>0, 'country_of_origin'=>0, 'downloadable'=>0, 'adult'=>0, 'barcode'=>0));

		$required_tags = array_filter($allowed_tags);

		if (sizeof(array_intersect_key($data, $required_tags)) != sizeof($required_tags)) {
			return;
		}

		$data = array_intersect_key($data, $allowed_tags);
//		if (isset($data['tarifplan']) && !isset($data['provider'])) {
//			unset($data['tarifplan']);
//		}

		$allowed_tags = array_intersect_key($allowed_tags, $data);

		// Стандарт XML учитывает порядок следования элементов,
		// поэтому важно соблюдать его в соответствии с порядком описанным в DTD
		$offer['data'] = array();
		foreach ($allowed_tags as $key => $value) {
			$offer['data'][$key] = $this->prepareField($data[$key]);
		}

		$this->offers[] = $offer;
	}

	/**
	 * Формирование YML файла
	 *
	 * @return string
	 */
	private function getYml() {
		$yml  = '<?xml version="1.0" encoding="windows-1251"?>' . $this->eol;
		$yml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . $this->eol;
		$yml .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . $this->eol;
		$yml .= '<shop>' . $this->eol;

		// информация о магазине
		$yml .= $this->array2Tag($this->shop);

		// валюты
		$yml .= '<currencies>' . $this->eol;
		foreach ($this->currencies as $currency) {
			$yml .= $this->getElement($currency, 'currency');
		}
		$yml .= '</currencies>' . $this->eol;

		// категории
		$yml .= '<categories>' . $this->eol;
		foreach ($this->categories as $category) {
			$category_name = $category['name'];
			unset($category['name'], $category['export']);
			$yml .= $this->getElement($category, 'category', $category_name);
		}
		$yml .= '</categories>' . $this->eol;

		// товарные предложения
		$yml .= '<offers>' . $this->eol;
		foreach ($this->offers as $offer) {
			$tags = $this->array2Tag($offer['data']);
			unset($offer['data']);
			if (isset($offer['param'])) {
				$tags .= $this->array2Param($offer['param']);
				unset($offer['param']);
			}
			$yml .= $this->getElement($offer, 'offer', $tags);
		}
		$yml .= '</offers>' . $this->eol;

		$yml .= '</shop>';
		$yml .= '</yml_catalog>';

		return $yml;
	}

	/**
	 * Фрмирование элемента
	 *
	 * @param array $attributes
	 * @param string $element_name
	 * @param string $element_value
	 * @return string
	 */
	private function getElement($attributes, $element_name, $element_value = '') {
		$retval = '<' . $element_name . ' ';
		foreach ($attributes as $key => $value) {
			$retval .= $key . '="' . $value . '" ';
		}
		$retval .= $element_value ? '>' . $this->eol . $element_value . '</' . $element_name . '>' : '/>';
		$retval .= $this->eol;

		return $retval;
	}

	/**
	 * Преобразование массива в теги
	 *
	 * @param array $tags
	 * @return string
	 */
	private function array2Tag($tags) {
		$retval = '';
		foreach ($tags as $key => $value) {
			if ($key == 'picture') {
				foreach (explode(',', $value) as $val) {
					$retval .= '<' . $key . '>' . $val . '</' . $key . '>' . $this->eol;
				}
			} elseif ($key == 'description') {
				$retval .= '<' . $key . '><![CDATA[﻿' . substr($value, 0, 3000) . ']]></' . $key . '>' . $this->eol;
			} else {
				$retval .= '<' . $key . '>' . $value . '</' . $key . '>' . $this->eol;
			}
		}

		return $retval;
	}

	/**
	 * Преобразование массива в теги параметров
	 *
	 * @param array $params
	 * @return string
	 */
	private function array2Param($params) {
		$retval = '';
		foreach ($params as $param) {
			$retval .= '<param name="' . $this->prepareField($param['name']);
			if (isset($param['unit'])) {
				$retval .= '" unit="' . $this->prepareField($param['unit']);
			}
			$retval .= '">' . $this->prepareField($param['value']) . '</param>' . $this->eol;
		}

		return $retval;
	}

	/**
	 * Подготовка текстового поля в соответствии с требованиями Яндекса
	 * Запрещаем любые html-тэги, стандарт XML не допускает использования в текстовых данных
	 * непечатаемых символов с ASCII-кодами в диапазоне значений от 0 до 31 (за исключением
	 * символов с кодами 9, 10, 13 - табуляция, перевод строки, возврат каретки). Также этот
	 * стандарт требует обязательной замены некоторых символов на их символьные примитивы.
	 * @param string $text
	 * @return string
	 */
	private function prepareField($field) {
		$field = htmlspecialchars_decode($field);
		$field = strip_tags($field);
		$from = array('"', '&', '>', '<', '\'');
		$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;');
		$field = str_replace($from, $to, $field);
		if ($this->from_charset != 'windows-1251') {
			$field = iconv($this->from_charset, 'windows-1251//TRANSLIT//IGNORE', $field);
		}
		$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

		return trim($field);
	}

	protected function getPath($category_id, $current_path = '') {
		if (isset($this->categories[$category_id])) {
			$this->categories[$category_id]['export'] = 1;

			if (!$current_path) {
				$new_path = $this->categories[$category_id]['id'];
			} else {
				$new_path = $this->categories[$category_id]['id'] . '_' . $current_path;
			}	

			if (isset($this->categories[$category_id]['parentId'])) {
				return $this->getPath($this->categories[$category_id]['parentId'], $new_path);
			} else {
				return $new_path;
			}

		}
	}

	function filterCategory($category) {
		return isset($category['export']);
	}
}