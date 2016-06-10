<?php
class ModelExtensionPaymentDivido extends Model {
	const CACHE_KEY_PLANS = 'divido_plans';

	public function setMerchant($api_key) {
		if ($api_key) {
			Divido::setMerchant($api_key);
		}
	}

	public function getMethod($payment_address, $total) {
		$this->load->language('extension/payment/divido');
		$this->load->model('localisation/currency');

		if (!$this->isEnabled()) {
			return array();
		}

		if ($this->session->data['currency'] != 'GBP') {
			return array();
		}

		if ($payment_address['iso_code_2'] != 'GB') {
			return array();
		}

		$cart_threshold = $this->config->get('divido_cart_threshold');
		if ($cart_threshold > $total) {
			return array();
		}

		$plans = $this->getCartPlans($this->cart);
		$has_plan = false;

		foreach ($plans as $plan) {
			$planMinTotal = $total - ($total * ($plan->min_deposit / 100));
			if ($plan->min_amount <= $planMinTotal) {
				$has_plan = true;
				break;
			}
		}

		if (!$has_plan) {
			return array();
		}

		$title = $this->language->get('text_checkout_title');
		if ($title_override = $this->config->get('divido_title')) {
			$title = $title_override;
		}

		$method_data = array(
			'code' => 'divido',
			'title' => $title,
			'terms' => '',
			'sort_order' => $this->config->get('divido_sort_order')
		);

		return $method_data;
	}

	public function getProductSettings($product_id) {
		return $this->db->query("SELECT `display`, `plans` FROM `" . DB_PREFIX . "divido_product` WHERE `product_id` = '" . (int)$product_id . "'")->row;
	}

	public function isEnabled() {
		$api_key = $this->config->get('divido_api_key');
		$enabled = $this->config->get('divido_status');

		return !empty($api_key) && $enabled == 1;
	}

	public function hashOrderId($order_id, $salt) {
		return hash('sha256', $order_id . $salt);
	}

	public function saveLookup($order_id, $salt, $proposal_id = null, $application_id = null, $deposit_amount = null) {
		$order_id = (int)$order_id;
		$salt = $this->db->escape($salt);
		$proposal_id = $this->db->escape($proposal_id);
		$application_id = $this->db->escape($application_id);
		$deposit_amount = $this->db->escape($deposit_amount);

		$query_get_lookup = "SELECT `application_id` from `" . DB_PREFIX . "divido_lookup` WHERE order_id = " . $order_id;
		$result_get_lookup = $this->db->query($query_get_lookup);

		if ($result_get_lookup->num_rows == 0) {
			$proposal_id = ($proposal_id) ? "'" . $proposal_id . "'" : 'NULL';
			$application_id = ($application_id) ? "'" . $application_id . "'" : 'NULL';
			$deposit_amount = ($deposit_amount) ? $deposit_amount : 'NULL';

			$query_upsert = "INSERT INTO `" . DB_PREFIX . "divido_lookup` (`order_id`, `salt`, `proposal_id`, `application_id`, `deposit_amount`) VALUES (" . $order_id . ", '" . $salt . "', " . $proposal_id . ", " . $application_id . ", " . $deposit_amount . ")";
		} else {
			$query_upsert = "UPDATE `" . DB_PREFIX . "divido_lookup` SET `salt` = '" . $salt . "'";

			if ($proposal_id) {
				$query_upsert .= ", `proposal_id` = '" . $proposal_id . "'";
			}

			if ($application_id) {
				$query_upsert .= ", `application_id` = '" . $application_id . "'";
			}

			if ($deposit_amount) {
				$query_upsert .= ", `deposit_amount` = " . $deposit_amount;
			}

			$query_upsert .= " WHERE `order_id` = " . $order_id;
		}

		$this->db->query($query_upsert);
	}

	public function getLookupByOrderId($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "divido_lookup` WHERE `order_id` = " . $order_id);
	}
	public function getGlobalSelectedPlans() {
		$all_plans     = $this->getAllPlans();
		$display_plans = $this->config->get('divido_planselection');

		if ($display_plans == 'all' || empty($display_plans)) {
			return $all_plans;
		}

		$selected_plans = $this->config->get('divido_plans_selected');
		if (!$selected_plans) {
			return array();
		}

		$plans = array();
		foreach ($all_plans as $plan) {
			if (in_array($plan->id, $selected_plans)) {
				$plans[] = $plan;
			}
		}

		return $plans;
	}

	public function getAllPlans() {
		if ($plans = $this->cache->get(self::CACHE_KEY_PLANS)) {
			// OpenCart 2.1 decodes json objects to associative arrays so we
			// need to make sure we're getting a list of simple objects back.
			$plans = array_map(function ($plan) {
				return (object)$plan;
			}, $plans);

			return $plans;
		}

		$api_key = $this->config->get('divido_api_key');
		if (!$api_key) {
			throw new Exception("No Divido api-key defined");
		}

		Divido::setMerchant($api_key);

		$response = Divido_Finances::all();
		if ($response->status != 'ok') {
			throw new Exception("Can't get list of finance plans from Divido!");
		}

		$plans = $response->finances;

		// OpenCart 2.1 switched to json for their file storage cache, so
		// we need to convert to a simple object.
		$plans_plain = array();
		foreach ($plans as $plan) {
			$plan_copy = new stdClass();
			$plan_copy->id                 = $plan->id;
			$plan_copy->text               = $plan->text;
			$plan_copy->country            = $plan->country;
			$plan_copy->min_amount         = $plan->min_amount;
			$plan_copy->min_deposit        = $plan->min_deposit;
			$plan_copy->max_deposit        = $plan->max_deposit;
			$plan_copy->interest_rate      = $plan->interest_rate;
			$plan_copy->deferral_period    = $plan->deferral_period;
			$plan_copy->agreement_duration = $plan->agreement_duration;

			$plans_plain[] = $plan_copy;
		}

		$this->cache->set(self::CACHE_KEY_PLANS, $plans_plain);

		return $plans_plain;
	}

	public function getCartPlans($cart)	{
		$plans = array();
		$products = $cart->getProducts();
		foreach ($products as $product) {
			$product_plans = $this->getProductPlans($product['product_id']);
			if ($product_plans) {
				$plans = array_merge($plans, $product_plans);
			}
		}

		return $plans;
	}

	public function getPlans($default_plans) {
		if ($default_plans) {
			$plans = $this->getGlobalSelectedPlans();
		} else {
			$plans = $this->getAllPlans();
		}

		return $plans;
	}

	public function getOrderTotals() {
		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Because __call can not keep var references so we put them into an array.
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);

		$this->load->model('extension/extension');

		$sort_order = array();

		$results = $this->model_extension_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('extension/total/' . $result['code']);

				// We have to put the totals in an array so that they pass by reference.
				$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
			}
		}

		$sort_order = array();

		foreach ($totals as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $totals);

		return array($total, $totals);
	}

	public function getProductPlans($product_id) {
		$this->load->model('catalog/product');

		$product_info       = $this->model_catalog_product->getProduct($product_id);
		$settings           = $this->getProductSettings($product_id);
		$product_selection  = $this->config->get('divido_productselection');
		$divido_categories  = $this->config->get('divido_categories');
		$price_threshold    = $this->config->get('divido_price_threshold');

		if ($divido_categories) {
			$product_categories = $this->model_catalog_product->getCategories($product_id);

			$all_categories = array();
			foreach ($product_categories as $product_category) {
				$all_categories[] = $product_category['category_id'];
			}
			$category_matches = array_intersect($all_categories, $divido_categories);

			if (!$category_matches) {
				return null;
			}
		}

		if (empty($settings)) {
			$settings = array(
				'display' => 'default',
				'plans'   => '',
			);
		}

		if ($product_selection == 'selected' && $settings['display'] == 'custom' && empty($settings['plans'])) {
			return null;
		}

		$price = 0;
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			$base_price = !empty($product_info['special']) ? $product_info['special'] : $product_info['price'];
			$price = $this->tax->calculate($base_price, $product_info['tax_class_id'], $this->config->get('config_tax'));
		}

		if ($product_selection == 'threshold' && !empty($price_threshold) && $price < $price_threshold) {
			return null;
		}

		if ($settings['display'] == 'default') {
			$plans = $this->getPlans(true);
			return $plans;
		}

		// If the product has non-default plans, fetch all of them.
		$available_plans = $this->getPlans(false);
		$selected_plans  = explode(',', $settings['plans']);

		$plans = array();
		foreach ($available_plans as $plan) {
			if (in_array($plan->id, $selected_plans)) {
				$plans[] = $plan;
			}
		}

		if (empty($plans)) {
			return null;
		}

		return $plans;
	}

}
