<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/
namespace Opencart\System\Engine;
/**
 * Class Registry
 *
 * Loaded during application bootup
 * @property \Opencart\System\Engine\Config $config
 * @property \Opencart\System\Engine\Event $event
 * @property \Opencart\System\Engine\Loader $load
 * @property \Opencart\System\Engine\Registry $autoloader
 * @property \Opencart\System\Library\Cache $cache
 * @property \Opencart\System\Library\Cart\Cart $cart
 * @property \Opencart\System\Library\Cart\Currency $currency
 * @property \Opencart\System\Library\Cart\Customer $customer
 * @property \Opencart\System\Library\Cart\Length $length
 * @property \Opencart\System\Library\Cart\Tax $tax
 * @property \Opencart\System\Library\Cart\Weight $weight
 * @property \Opencart\System\Library\DB $db
 * @property \Opencart\System\Library\Document $document
 * @property \Opencart\System\Library\Language $language
 * @property \Opencart\System\Library\Log $log
 * @property \Opencart\System\Library\Request $request
 * @property \Opencart\System\Library\Response $response
 * @property \Opencart\System\Library\Session $session
 * @property \Opencart\System\Library\Template $template
 * @property \Opencart\System\Library\Url $url
 *
 * Loaded during login
 * @property ?\Opencart\System\Library\Cart\User $user
 *
 * Various models being loaded
 * @property null|\Opencart\Admin\Model\Catalog\Attribute $model_catalog_attribute
 * @property null|\Opencart\Admin\Model\Catalog\AttributeGroup $model_catalog_attribute_group
 * @property null|\Opencart\Admin\Model\Catalog\Category|\Opencart\Catalog\Model\catalog\Category $model_catalog_category
 * @property null|\Opencart\Admin\Model\Catalog\Download $model_catalog_download
 * @property null|\Opencart\Admin\Model\Catalog\Filter $model_catalog_filter
 * @property null|\Opencart\Admin\Model\Catalog\Information|\Opencart\Catalog\Model\catalog\Information $model_catalog_information
 * @property null|\Opencart\Admin\Model\Catalog\Manufacturer|\Opencart\Catalog\Model\catalog\Manufacturer $model_catalog_manufacturer
 * @property null|\Opencart\Admin\Model\Catalog\Option $model_catalog_option
 * @property null|\Opencart\Admin\Model\Catalog\Product|\Opencart\Catalog\Model\catalog\Product $model_catalog_product
 * @property null|\Opencart\Admin\Model\Catalog\Review|\Opencart\Catalog\Model\catalog\Review $model_catalog_review
 * @property null|\Opencart\Admin\Model\Catalog\Subscription_plan $model_catalog_subscription_plan
 * @property null|\Opencart\Admin\Model\Cms\Antispam|\Opencart\Catalog\Model\cms\Antispam $model_cms_antispam
 * @property null|\Opencart\Admin\Model\Cms\Article|\Opencart\Catalog\Model\cms\Article $model_cms_article
 * @property null|\Opencart\Admin\Model\Cms\Topic|\Opencart\Catalog\Model\cms\Topic $model_cms_topic
 * @property null|\Opencart\Admin\Model\Customer\CustomerApproval $model_customer_customer_approval
 * @property null|\Opencart\Admin\Model\Customer\CustomerGroup $model_customer_customer_group
 * @property null|\Opencart\Admin\Model\Customer\Customer|\Opencart\Catalog\Model\Customer\Customer $model_customer_customer
 * @property null|\Opencart\Admin\Model\Customer\CustomField $model_customer_custom_field
 * @property null|\Opencart\Admin\Model\Customer\Gdpr $model_customer_gdpr
 * @property null|\Opencart\Admin\Model\Design\Banner|\Opencart\Catalog\Model\Design\Banner $model_design_banner
 * @property null|\Opencart\Admin\Model\Design\Layout|\Opencart\Catalog\Model\design\Layout $model_design_layout
 * @property null|\Opencart\Admin\Model\Design\SeoUrl|\Opencart\Catalog\Model\design\AeoUrl $model_design_seo_url
 * @property null|\Opencart\Admin\Model\Design\Theme|\Opencart\Catalog\Model\design\Theme $model_design_theme
 * @property null|\Opencart\Admin\Model\Design\Translation|\Opencart\Catalog\Model\design\Translation $model_design_translation
 * @property null|\Opencart\Admin\Model\Localisation\AddressFormat $model_localisation_address_format
 * @property null|\Opencart\Admin\Model\Localisation\Country|\Opencart\Catalog\Model\localisation\Country $model_localisation_country
 * @property null|\Opencart\Admin\Model\Localisation\Currency|\Opencart\Catalog\Model\localisation\Currency $model_localisation_currency
 * @property null|\Opencart\Admin\Model\Localisation\GeoZone|\Opencart\Catalog\Model\Localisation\GeoZone $model_localisation_geo_zone
 * @property null|\Opencart\Admin\Model\Localisation\Language|\Opencart\Catalog\Model\localisation\Language $model_localisation_language
 * @property null|\Opencart\Admin\Model\Localisation\LengthClass $model_localisation_length_class
 * @property null|\Opencart\Admin\Model\Localisation\Location|\Opencart\Catalog\Model\localisation\Location $model_localisation_location
 * @property null|\Opencart\Admin\Model\Localisation\OrderStatus|\Opencart\Catalog\Model\localisation\Order_status $model_localisation_order_status
 * @property null|\Opencart\Admin\Model\Localisation\ReturnAction $model_localisation_return_action
 * @property null|\Opencart\Admin\Model\Localisation\ReturnReason|\Opencart\Catalog\Model\localisation\Return_reason $model_localisation_return_reason
 * @property null|\Opencart\Admin\Model\Localisation\ReturnStatus|\Opencart\Catalog\Model\Localisation\ReturnStatus $model_localisation_return_status
 * @property null|\Opencart\Admin\Model\Localisation\StockStatus|\Opencart\Catalog\Model\localisation\Stock_status $model_localisation_stock_status
 * @property null|\Opencart\Admin\Model\Localisation\SubscriptionStatus|\Opencart\Catalog\Model\localisation\Subscription_status $model_localisation_subscription_status
 * @property null|\Opencart\Admin\Model\Localisation\TaxClass|\Opencart\Catalog\Model\Localisation\TaxClass $model_localisation_tax_class
 * @property null|\Opencart\Admin\Model\Localisation\TaxRate $model_localisation_tax_rate
 * @property null|\Opencart\Admin\Model\Localisation\WeightClass $model_localisation_weight_class
 * @property null|\Opencart\Admin\Model\Localisation\Zone|\Opencart\Catalog\Model\localisation\Zone $model_localisation_zone
 * @property null|\Opencart\Admin\Model\Marketing\Affiliate $model_marketing_affiliate
 * @property null|\Opencart\Admin\Model\Marketing\Coupon|\Opencart\Catalog\Model\marketing\Coupon $model_marketing_coupon
 * @property null|\Opencart\Admin\Model\Marketing\Marketing|\Opencart\Catalog\Model\marketing\Marketing $model_marketing_marketing
 * @property null|\Opencart\Admin\Model\Report\Online|\Opencart\Catalog\Model\Report\Online $model_report_online
 * @property null|\Opencart\Admin\Model\Report\Statistics|\Opencart\Catalog\Model\report\Statistics $model_report_statistics
 * @property null|\Opencart\Admin\Model\Sale\Order|\Opencart\Catalog\Model\Sale\Order $model_sale_order
 * @property null|\Opencart\Admin\Model\Sale\Returns $model_sale_returns
 * @property null|\Opencart\Admin\Model\Sale\Subscription $model_sale_subscription
 * @property null|\Opencart\Admin\Model\Sale\Voucher $model_sale_voucher
 * @property null|\Opencart\Admin\Model\Sale\VoucherTheme $model_sale_voucher_theme
 * @property null|\Opencart\Admin\Model\Setting\Cron|\Opencart\Catalog\Model\setting\Cron $model_setting_cron
 * @property null|\Opencart\Admin\Model\Setting\Event|\Opencart\Catalog\Model\setting\Event $model_setting_event
 * @property null|\Opencart\Admin\Model\Setting\Extension|\Opencart\Catalog\Model\setting\Extension $model_setting_extension
 * @property null|\Opencart\Admin\Model\Setting\Modification $model_setting_modification
 * @property null|\Opencart\Admin\Model\Setting\Module|\Opencart\Catalog\Model\setting\Module $model_setting_module
 * @property null|\Opencart\Admin\Model\Setting\Setting|\Opencart\Catalog\Model\setting\Setting $model_setting_setting
 * @property null|\Opencart\Admin\Model\Setting\Startup|\Opencart\Catalog\Model\setting\Startup $model_setting_startup
 * @property null|\Opencart\Admin\Model\Setting\Store|\Opencart\Catalog\Model\setting\Store $model_setting_store
 * @property null|\Opencart\Admin\Model\Tool\Backup $model_tool_backup
 * @property null|\Opencart\Admin\Model\Tool\Image|\Opencart\Catalog\Model\tool\UJmage $model_tool_image
 * @property null|\Opencart\Admin\Model\Tool\Notification $model_tool_notification
 * @property null|\Opencart\Admin\Model\Tool\Upload|\Opencart\Catalog\Model\tool\upload $model_tool_upload
 * @property null|\Opencart\Admin\Model\User\Api $model_user_api
 * @property null|\Opencart\Admin\Model\User\UserGroup $model_user_user_group
 * @property null|\Opencart\Admin\Model\User\User|\Opencart\Catalog\Model\User\User $model_user_user
 * @property null|\Opencart\Catalog\Model\account\Activity $model_account_activity
 * @property null|\Opencart\Catalog\Model\account\Address $model_account_address
 * @property null|\Opencart\Catalog\Model\account\Affiliate $model_account_affiliate
 * @property null|\Opencart\Catalog\Model\account\Api $model_account_api
 * @property null|\Opencart\Catalog\Model\account\Customer $model_account_customer
 * @property null|\Opencart\Catalog\Model\account\CustomerGroup $model_account_customer_group
 * @property null|\Opencart\Catalog\Model\account\Custom_field $model_account_custom_field
 * @property null|\Opencart\Catalog\Model\account\Download $model_account_download
 * @property null|\Opencart\Catalog\Model\account\Gdpr $model_account_gdpr
 * @property null|\Opencart\Catalog\Model\account\Order $model_account_order
 * @property null|\Opencart\Catalog\Model\account\Payment_method $model_account_payment_method
 * @property null|\Opencart\Catalog\Model\account\Returns $model_account_returns
 * @property null|\Opencart\Catalog\Model\account\Reward $model_account_reward
 * @property null|\Opencart\Catalog\Model\account\Search $model_account_search
 * @property null|\Opencart\Catalog\Model\account\Subscription $model_account_subscription
 * @property null|\Opencart\Catalog\Model\account\Transaction $model_account_transaction
 * @property null|\Opencart\Catalog\Model\account\Wishlist $model_account_wishlist
 * @property null|\Opencart\Catalog\Model\checkout\Cart $model_checkout_cart
 * @property null|\Opencart\Catalog\Model\checkout\Order $model_checkout_order
 * @property null|\Opencart\Catalog\Model\checkout\Payment_method $model_checkout_payment_method
 * @property null|\Opencart\Catalog\Model\checkout\Shipping_method $model_checkout_shipping_method
 * @property null|\Opencart\Catalog\Model\checkout\Subscription $model_checkout_subscription
 * @property null|\Opencart\Catalog\Model\checkout\Voucher $model_checkout_voucher
 * @property null|\Opencart\Catalog\Model\checkout\Voucher_theme $model_checkout_voucher_theme
 * @property null|\Opencart\Catalog\Model\cms\Blog $model_cms_blog
 * @property null|\Opencart\Catalog\Model\Extension\OcPaymentExample\Payment\CreditCard $model_extension_oc_payment_example_payment_credit_card
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Dashboard\Map $model_extension_opencart_dashboard_map
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Fraud\Ip $model_extension_opencart_fraud_ip
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Module\Bestseller $model_extension_opencart_module_bestseller
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Module\Latest $model_extension_opencart_module_latest
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Report\Activity $model_extension_opencart_report_activity
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Report\Coupon $model_extension_opencart_report_coupon
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Report\Customer $model_extension_opencart_report_customer
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Report\CustomerTransaction $model_extension_opencart_report_customer_transaction
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Report\Marketing $model_extension_opencart_report_marketing
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Report\ProductPurchased $model_extension_opencart_report_product_purchased
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Report\ProductViewed $model_extension_opencart_report_product_viewed
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Report\Returns $model_extension_opencart_report_returns
 * @property null|\Opencart\Catalog\Model\Extension\Opencart\Report\Sale $model_extension_opencart_report_sale
 * @property null|\Opencart\Catalog\Model\setting\Api $model_setting_api
 * @property null|\Opencart\Catalog\Model\tool\online $model_tool_online
 * @property null|\Opencart\Install\Model\Install\Install $model_install_install
 */
class Registry {
	/**
	 * @var array
	 */
	private array $data = [];

	/**
	 * __get
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.get
	 *
	 * @param    string  $key
	 *
	 * @return   object
	 */
	public function __get(string $key): object {
		return $this->get($key);
	}

	/**
	 * __set
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
	 *
	 * @param    string  $key
	 * @param    object  $value
	 *
	 * @return   null
	 */
	public function __set(string $key, object $value): void {
		$this->set($key, $value);
	}

	/**
	 * __isset
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
	 *
	 * @param    string  $key
	 *
	 * @return   bool
	 */
	public function __isset(string $key): bool {
		return $this->has($key);
	}

	/**
     * Get
     *
     * @param	string	$key
	 * 
	 * @return	mixed
     */
	public function get(string $key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

    /**
     * Set
     *
     * @param	string	$key
	 * @param	object	$value
	 *
	 * @return void
     */	
	public function set(string $key, object $value): void {
		$this->data[$key] = $value;
	}
	
    /**
     * Has
     *
     * @param	string	$key
	 *
	 * @return	bool
     */
	public function has(string $key): bool {
		return isset($this->data[$key]);
	}

	/**
	 * Unset
	 *
	 * Unsets registry value by key.
	 *
	 * @param	string	$key
	 *
	 * @return	null
	 */
	public function unset(string $key): void {
		if (isset($this->data[$key])) {
			unset($this->data[$key]);
		}
	}
}
