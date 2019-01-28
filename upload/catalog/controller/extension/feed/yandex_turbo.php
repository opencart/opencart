<?php
/**
 * Класс YML экспорта
 * YML (Yandex Market Language) - стандарт, разработанный "Яндексом"
 * для принятия и публикации информации в базе данных Яндекс.Маркет
 * YML основан на стандарте XML (Extensible Markup Language)
 * описание формата YML http://partner.market.yandex.ru/legal/tt/
 */
class ControllerExtensionFeedYandexTurbo extends Controller {
	private $currencies = array();
	private $categories = array();
	private $eol = ""; 

	public function index() {
		if ($this->config->get('feed_yandex_turbo_status')) {
		
		$this->load->model('extension/feed/yandex_turbo');
		$this->load->model('tool/image');		
			
		$this->eol = "\n";
		$output  = '<?xml version="1.0" encoding="UTF-8"?>' . $this->eol;
		$output .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . $this->eol;
		$output .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . $this->eol;
		$output .= '<shop>' . $this->eol;
		$output .= '<name>' . $this->config->get('config_name') . '</name>' . $this->eol ;
		$output .= '<company>' . $this->config->get('config_owner') . '</company>' . $this->eol ;
		$output .= '<url>' . HTTPS_SERVER . '</url>' . $this->eol ;
		$output .= '<phone>' . $this->config->get('config_telephone') . '</phone>' . $this->eol ;
		$output .= '<platform>Opencart</platform>' . $this->eol ;
		$output .= '<version>' . VERSION . '</version>' . $this->eol ;
		
		$offers_currency = $this->config->get('feed_yandex_turbo_currency');

		$this->load->model('localisation/currency');
		$this->load->model('tool/image');
		
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
		$output .= '<currencies>' . $this->eol;
		foreach ($this->currencies as $currency) {
			$output .= $this->getElement($currency, 'currency');
		}
		$output .= '</currencies>' . $this->eol;
		$decimal = (int)$this->currency->getDecimalPlace($offers_currency);
		
		$categories = $this->model_extension_feed_yandex_turbo->getCategories();
		foreach ($categories as $category) {
			$this->setCategory($category['name'], $category['category_id'], $category['parent_id']);
		}
	
		$output .= '<categories>' . $this->eol;
		
		foreach ($this->categories as $category) {

			$category_name = $category['name'];
			unset($category['name'], $category['export']);
			$output .= $this->getElement($category, 'category', $category_name);

			
		}
		
		$output .= '</categories>' . $this->eol;
		$output .= '<offers>' . $this->eol;
		


		$products = $this->model_extension_feed_yandex_turbo->getProducts();
		
		foreach ($products as $product) {
			$output .= '<offer id="' . $product['product_id'] . '" available="' . ($product['quantity'] > 0 ? 'true' : 'false') . '">' . $this->eol;
			$output .= '<url>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</url>' . $this->eol;
			$output .= '<price>' . number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal, '.', '') . '</price>';
			$output .= '<currencyId>' . $offers_currency . '</currencyId>' . $this->eol;
			$output .= '<categoryId>' . $product['category_id'] . '</categoryId>' . $this->eol;
			$output .= '<picture>' . $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')) . '</picture>' . $this->eol;
			$output .= '<name><![CDATA[' . $this->prepareField($product['name']) . ']]></name>' . $this->eol;
			$output .= '<description><![CDATA[' . $this->prepareField($product['description']) . ']]></description>' . $this->eol; 
			$output .= '</offer>' . $this->eol;
		}
		
		$output .= '</offers>' . $this->eol;
		$output .= '</shop>'. $this->eol;
		$output .= '</yml_catalog>';

		$this->response->addHeader('Content-Type: application/xml');
		$this->response->setOutput($output);
		
		}
	}
	
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
	
	private function getElement($attributes, $element_name, $element_value = '') {
		$retval = '<' . $element_name . ' ';
		foreach ($attributes as $key => $value) {
			$retval .= $key . '="' . $value . '" ';
		}
		$retval .= $element_value ? '>' . $this->eol . $element_value . '</' . $element_name . '>' : '/>';
		$retval .= $this->eol;

		return $retval;
	}
	
	private function prepareField($field) {

		$field = htmlspecialchars_decode($field);
		$field = strip_tags($field);
		
		$from = array('"', '&', '>', '<', '°', '\'');
		$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&#176;', '&apos;');
		$field = str_replace($from, $to, $field);
		
		$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

		return trim($field);
	}
}
