<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade8
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade8 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// Rename events
			$replace = [];

			$replace[] = [
				'code_old' => 'subscription',
				'code_new' => 'mail_subscription'
			];

			$replace[] = [
				'code_old' => 'admin_mail_customer_approve',
				'code_new' => 'mail_admin_customer_approve'
			];

			$replace[] = [
				'code_old' => 'admin_mail_affiliate_deny',
				'code_new' => 'mail_admin_affiliate_deny'
			];

			$replace[] = [
				'code_old' => 'admin_mail_customer_approve',
				'code_new' => 'mail_admin_customer_approve'
			];

			$replace[] = [
				'code_old' => 'admin_mail_customer_deny',
				'code_new' => 'mail_admin_customer_deny'
			];

			$replace[] = [
				'code_old' => 'admin_mail_customer_transaction',
				'code_new' => 'mail_admin_customer_transaction'
			];

			$replace[] = [
				'code_old' => 'admin_mail_forgotten',
				'code_new' => 'mail_admin_forgotten'
			];

			$replace[] = [
				'code_old' => 'admin_mail_gdpr',
				'code_new' => 'mail_admin_gdpr'
			];

			$replace[] = [
				'code_old' => 'admin_mail_return',
				'code_new' => 'mail_admin_return'
			];

			$replace[] = [
				'code_old' => 'admin_mail_reward',
				'code_new' => 'mail_admin_reward'
			];

			$replace[] = [
				'code_old' => 'admin_mail_transaction',
				'code_new' => 'mail_admin_transaction'
			];

			$replace[] = [
				'code_old' => 'admin_mail_user_authorize',
				'code_new' => 'mail_admin_user_authorize'
			];

			$replace[] = [
				'code_old' => 'admin_mail_user_authorize_reset',
				'code_new' => 'mail_admin_user_authorize_reset'
			];

			$replace[] = [
				'code_old' => 'admin_mail_user_forgotten',
				'code_new' => 'mail_admin_user_forgotten'
			];

			$replace[] = [
				'code_old' => 'admin_currency_add',
				'code_new' => 'currency_add'
			];

			$replace[] = [
				'code_old' => 'admin_currency_edit',
				'code_new' => 'currency_edit'
			];

			$replace[] = [
				'code_old' => 'admin_currency_setting',
				'code_new' => 'currency_setting'
			];

			foreach ($replace as $result) {
				$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($result['code_new']) . "' WHERE `code` = '" . $this->db->escape($result['code_old']) . "'");
			}

			// Add missing default events
			$events = [];

			// Activity
			$events[] = [
				'code'    => 'activity_customer_add',
				'trigger' => 'catalog/model/account/customer.addCustomer/after',
				'action'  => 'event/activity.addCustomer'
			];

			$events[] = [
				'code'    => 'activity_customer_edit',
				'trigger' => 'catalog/model/account/customer.editCustomer/after',
				'action'  => 'event/activity.editCustomer'
			];

			$events[] = [
				'code'    => 'activity_customer_password',
				'trigger' => 'catalog/model/account/customer.editPassword/after',
				'action'  => 'event/activity.editPassword'
			];

			$events[] = [
				'code'    => 'activity_customer_forgotten',
				'trigger' => 'catalog/model/account/customer.addToken/after',
				'action'  => 'event/activity.forgotten'
			];

			$events[] = [
				'code'    => 'activity_customer_transaction',
				'trigger' => 'catalog/model/account/customer.addTransaction/after',
				'action'  => 'event/activity.addTransaction'
			];

			$events[] = [
				'code'    => 'activity_customer_login',
				'trigger' => 'catalog/model/account/customer.deleteLoginAttempts/after',
				'action'  => 'event/activity.login'
			];

			$events[] = [
				'code'    => 'activity_address_add',
				'trigger' => 'catalog/model/account/address.addAddress/after',
				'action'  => 'event/activity.addAddress'
			];

			$events[] = [
				'code'    => 'activity_address_edit',
				'trigger' => 'catalog/model/account/address.editAddress/after',
				'action'  => 'event/activity.editAddress'
			];

			$events[] = [
				'code'    => 'activity_address_delete',
				'trigger' => 'catalog/model/account/address.deleteAddress/after',
				'action'  => 'event/activity.deleteAddress'
			];

			$events[] = [
				'code'    => 'activity_affiliate_add',
				'trigger' => 'catalog/model/account/customer.addAffiliate/after',
				'action'  => 'event/activity.addAffiliate'
			];

			$events[] = [
				'code'    => 'activity_affiliate_edit',
				'trigger' => 'catalog/model/account/customer.editAffiliate/after',
				'action'  => 'event/activity.editAffiliate'
			];

			$events[] = [
				'code'    => 'activity_order_add',
				'trigger' => 'catalog/model/checkout/order.addHistory/before',
				'action'  => 'event/activity.addHistory'
			];

			$events[] = [
				'code'    => 'activity_return_add',
				'trigger' => 'catalog/model/account/returns.addReturn/after',
				'action'  => 'event/activity.addReturn'
			];

			// Article
			$events[] = [
				'code'    => 'article_add',
				'trigger' => 'admin/model/cms/article.addArticle/after',
				'action'  => 'event/article'
			];

			$events[] = [
				'code'    => 'article_edit',
				'trigger' => 'admin/model/cms/article.editArticle/after',
				'action'  => 'event/article'
			];

			$events[] = [
				'code'    => 'article_delete',
				'trigger' => 'admin/model/cms/article.deleteArticle/after',
				'action'  => 'event/article'
			];

			// Banner
			$events[] = [
				'code'    => 'banner_add',
				'trigger' => 'admin/model/design/banner.addBanner/after',
				'action'  => 'event/banner'
			];

			$events[] = [
				'code'    => 'banner_edit',
				'trigger' => 'admin/model/design/banner.editArticle/after',
				'action'  => 'event/banner'
			];

			$events[] = [
				'code'    => 'banner_delete',
				'trigger' => 'admin/model/design/banner.deleteArticle/after',
				'action'  => 'event/banner'
			];

			// Category
			$events[] = [
				'code'    => 'category_add',
				'trigger' => 'admin/model/catalog/category.addCategory/after',
				'action'  => 'event/category'
			];

			$events[] = [
				'code'    => 'category_edit',
				'trigger' => 'admin/model/catalog/category.editCategory/after',
				'action'  => 'event/category'
			];

			$events[] = [
				'code'    => 'category_delete',
				'trigger' => 'admin/model/catalog/category.deleteCategory/after',
				'action'  => 'event/category'
			];

			// Country
			$events[] = [
				'code'    => 'country_add',
				'trigger' => 'admin/model/localisation/country.addCountry/after',
				'action'  => 'event/country'
			];

			$events[] = [
				'code'    => 'country_edit',
				'trigger' => 'admin/model/localisation/country.editCountry/after',
				'action'  => 'event/country'
			];

			$events[] = [
				'code'    => 'country_delete',
				'trigger' => 'admin/model/localisation/country.deleteCountry/after',
				'action'  => 'event/country'
			];

			// Currency
			$events[] = [
				'code'    => 'currency_add',
				'trigger' => 'admin/model/localisation/currency.addCurrency/after',
				'action'  => 'event/currency'
			];

			$events[] = [
				'code'    => 'currency_edit',
				'trigger' => 'admin/model/localisation/currency.editCurrency/after',
				'action'  => 'event/currency'
			];

			$events[] = [
				'code'    => 'currency_delete',
				'trigger' => 'admin/model/localisation/currency.deleteCurrency/after',
				'action'  => 'event/currency'
			];

			$events[] = [
				'code'    => 'currency_setting',
				'trigger' => 'admin/model/setting/setting.editSetting/after',
				'action'  => 'event/currency'
			];

			// Custom Field
			$events[] = [
				'code'    => 'custom_field_add',
				'trigger' => 'admin/model/customer/custom_field.addCustomField/after',
				'action'  => 'event/custom_field'
			];

			$events[] = [
				'code'    => 'custom_field_edit',
				'trigger' => 'admin/model/customer/custom_field.editCustomField/after',
				'action'  => 'event/custom_field'
			];

			$events[] = [
				'code'    => 'custom_field_delete',
				'trigger' => 'admin/model/localusation/custom_field.deleteCustomField/after',
				'action'  => 'event/custom_field'
			];

			// Customer Group
			$events[] = [
				'code'    => 'customer_group_add',
				'trigger' => 'admin/model/customer/customer_group.addCustomerGroup/after',
				'action'  => 'event/customer_group'
			];

			$events[] = [
				'code'    => 'customer_group_edit',
				'trigger' => 'admin/model/customer/customer_group.editCustomerGroup/after',
				'action'  => 'event/customer_group'
			];

			$events[] = [
				'code'    => 'customer_group_delete',
				'trigger' => 'admin/model/customer/customer_group.deleteCustomerGroup/after',
				'action'  => 'event/customer_group'
			];

			// Information
			$events[] = [
				'code'    => 'information_add',
				'trigger' => 'admin/model/catalog/information.addInformation/after',
				'action'  => 'event/information'
			];

			$events[] = [
				'code'    => 'information_edit',
				'trigger' => 'admin/model/catalog/information.editInformation/after',
				'action'  => 'event/information'
			];

			$events[] = [
				'code'    => 'information_delete',
				'trigger' => 'admin/model/catalog/information.deleteInformation/after',
				'action'  => 'event/information'
			];

			// Language
			$events[] = [
				'code'    => 'language_add',
				'trigger' => 'admin/model/localisation/language.addLanguage/after',
				'action'  => 'event/information'
			];

			$events[] = [
				'code'    => 'language_edit',
				'trigger' => 'admin/model/localisation/language.editLanguage/after',
				'action'  => 'event/information'
			];

			$events[] = [
				'code'    => 'language_delete',
				'trigger' => 'admin/model/localisation/language.deleteLanguage/after',
				'action'  => 'event/information'
			];

			// Language
			$events[] = [
				'code'    => 'length_class_add',
				'trigger' => 'admin/model/localisation/length_class.addLengthClass/after',
				'action'  => 'event/length_class'
			];

			$events[] = [
				'code'    => 'length_class_edit',
				'trigger' => 'admin/model/localisation/length_class.editLengthClass/after',
				'action'  => 'event/length_class'
			];

			$events[] = [
				'code'    => 'length_class_delete',
				'trigger' => 'admin/model/localisation/length_class.deleteLengthClass/after',
				'action'  => 'event/length_class'
			];

			// Manufacturer
			$events[] = [
				'code'    => 'manufacturer_add',
				'trigger' => 'admin/model/catalog/manufacturer.addManufacturer/after',
				'action'  => 'event/manufacturer'
			];

			$events[] = [
				'code'    => 'manufacturer_edit',
				'trigger' => 'admin/model/catalog/manufacturer.editManufacturer/after',
				'action'  => 'event/manufacturer'
			];

			$events[] = [
				'code'    => 'manufacturer_delete',
				'trigger' => 'admin/model/catalog/manufacturer.deleteManufacturer/after',
				'action'  => 'event/manufacturer'
			];

			// Mail
			$events[] = [
				'code'    => 'mail_customer_transaction',
				'trigger' => 'catalog/model/account/customer.addTransaction/after',
				'action'  => 'mail/transaction'
			];

			$events[] = [
				'code'    => 'mail_customer_forgotten',
				'trigger' => 'catalog/model/account/customer.addToken/after',
				'action'  => 'mail/forgotten'
			];

			$events[] = [
				'code'    => 'mail_customer_add',
				'trigger' => 'catalog/model/account/customer.addCustomer/after',
				'action'  => 'mail/register'
			];

			$events[] = [
				'code'    => 'mail_customer_alert',
				'trigger' => 'catalog/model/account/customer.addCustomer/after',
				'action'  => 'mail/register.alert'
			];

			$events[] = [
				'code'    => 'mail_affiliate_add',
				'trigger' => 'catalog/model/account/customer.addAffiliate/after',
				'action'  => 'mail/affiliate'
			];

			$events[] = [
				'code'    => 'mail_affiliate_alert',
				'trigger' => 'catalog/model/account/customer.addAffiliate/after',
				'action'  => 'mail/affiliate.alert'
			];

			$events[] = [
				'code'    => 'mail_order',
				'trigger' => 'catalog/model/checkout/order.addHistory/before',
				'action'  => 'mail/order'
			];

			$events[] = [
				'code'    => 'mail_order_alert',
				'trigger' => 'catalog/model/checkout/order.addHistory/before',
				'action'  => 'mail/order.alert'
			];

			$events[] = [
				'code'    => 'mail_admin_affiliate_approve',
				'trigger' => 'admin/model/customer/customer_approval.approveAffiliate/after',
				'action'  => 'mail/affiliate.approve'
			];

			$events[] = [
				'code'    => 'mail_admin_affiliate_deny',
				'trigger' => 'admin/model/customer/customer_approval.denyAffiliate/after',
				'action'  => 'mail/affiliate.deny'
			];

			$events[] = [
				'code'    => 'mail_admin_customer_approve',
				'trigger' => 'admin/model/customer/customer_approval.approveCustomer/after',
				'action'  => 'mail/customer.approve'
			];

			$events[] = [
				'code'    => 'mail_admin_customer_deny',
				'trigger' => 'admin/model/customer/customer_approval.denyCustomer/after',
				'action'  => 'mail/customer.deny'
			];

			$events[] = [
				'code'    => 'mail_admin_reward',
				'trigger' => 'admin/model/customer/customer.addReward/after',
				'action'  => 'mail/reward'
			];

			$events[] = [
				'code'    => 'mail_admin_customer_transaction',
				'trigger' => 'admin/model/customer/customer.addTransaction/after',
				'action'  => 'mail/transaction'
			];

			$events[] = [
				'code'    => 'mail_admin_return',
				'trigger' => 'admin/model/sale/return.addReturn/after',
				'action'  => 'mail/returns'
			];

			$events[] = [
				'code'    => 'mail_admin_forgotten',
				'trigger' => 'admin/model/user/user.addToken/after',
				'action'  => 'mail/forgotten'
			];

			// Product
			$events[] = [
				'code'    => 'product_add',
				'trigger' => 'admin/model/catalog/product.addProduct/after',
				'action'  => 'event/product'
			];

			$events[] = [
				'code'    => 'product_edit',
				'trigger' => 'admin/model/catalog/product.editProduct/after',
				'action'  => 'event/product'
			];

			$events[] = [
				'code'    => 'product_delete',
				'trigger' => 'admin/model/catalog/product.deleteProduct/after',
				'action'  => 'event/product'
			];

			// Product
			$events[] = [
				'code'    => 'product_add',
				'trigger' => 'admin/model/catalog/product.addProduct/after',
				'action'  => 'event/product'
			];

			$events[] = [
				'code'    => 'product_edit',
				'trigger' => 'admin/model/catalog/product.editProduct/after',
				'action'  => 'event/product'
			];

			$events[] = [
				'code'    => 'product_delete',
				'trigger' => 'admin/model/catalog/product.deleteProduct/after',
				'action'  => 'event/product'
			];

			// Return Reason
			$events[] = [
				'code'    => 'return_reason_add',
				'trigger' => 'admin/model/localisation/return_reason.addReturnReason/after',
				'action'  => 'event/return_reason'
			];

			$events[] = [
				'code'    => 'return_reason_edit',
				'trigger' => 'admin/model/localisation/return_reason.editReturnReason/after',
				'action'  => 'event/return_reason'
			];

			$events[] = [
				'code'    => 'return_reason_delete',
				'trigger' => 'admin/model/localisation/return_reason.deleteReturnReason/after',
				'action'  => 'event/return_reason'
			];

			// SSR
			$events[] = [
				'code'    => 'ssr_article',
				'trigger' => 'catalog/controller/cms/article/after',
				'action'  => 'event/ssr'
			];

			$events[] = [
				'code'    => 'ssr_topic',
				'trigger' => 'catalog/controller/cms/topic/after',
				'action'  => 'event/ssr'
			];

			$events[] = [
				'code'    => 'ssr_category',
				'trigger' => 'catalog/controller/product/category/after',
				'action'  => 'event/ssr'
			];

			$events[] = [
				'code'    => 'ssr_manufacturer',
				'trigger' => 'catalog/controller/product/manufacturer/after',
				'action'  => 'event/ssr'
			];

			$events[] = [
				'code'    => 'ssr_product',
				'trigger' => 'catalog/controller/product/product/after',
				'action'  => 'event/ssr'
			];

			$events[] = [
				'code'    => 'ssr_special',
				'trigger' => 'catalog/controller/product/special/after',
				'action'  => 'event/ssr'
			];

			$events[] = [
				'code'    => 'ssr_search',
				'trigger' => 'catalog/controller/product/search/after',
				'action'  => 'event/ssr'
			];

			$events[] = [
				'code'    => 'ssr_information',
				'trigger' => 'catalog/controller/information/information/after',
				'action'  => 'event/ssr'
			];

			$events[] = [
				'code'    => 'ssr_sitemap',
				'trigger' => 'catalog/controller/information/sitemap/after',
				'action'  => 'event/ssr'
			];

			// Statistics
			$events[] = [
				'code'    => 'statistics_review_add',
				'trigger' => 'catalog/model/catalog/review.addReview/after',
				'action'  => 'event/statistics.addReview'
			];

			$events[] = [
				'code'    => 'statistics_return_add',
				'trigger' => 'catalog/model/account/returns.addReturn/after',
				'action'  => 'event/statistics.addReturn'
			];

			$events[] = [
				'code'    => 'statistics_order_history',
				'trigger' => 'catalog/model/checkout/order.addHistory/after',
				'action'  => 'event/statistics.addHistory'
			];

			// Store
			$events[] = [
				'code'    => 'store_add',
				'trigger' => 'admin/model/setting/store.addStore/after',
				'action'  => 'event/store'
			];

			$events[] = [
				'code'    => 'store_edit',
				'trigger' => 'admin/model/setting/store.editStore/after',
				'action'  => 'event/store'
			];

			$events[] = [
				'code'    => 'store_delete',
				'trigger' => 'admin/model/setting/store.deleteStore/after',
				'action'  => 'event/store'
			];

			// Tax Class
			$events[] = [
				'code'    => 'tax_class_add',
				'trigger' => 'admin/model/localisation/tax_class.addTaxClass/after',
				'action'  => 'event/tax'
			];

			$events[] = [
				'code'    => 'tax_class_edit',
				'trigger' => 'admin/model/localisation/tax_class.editTaxClass/after',
				'action'  => 'event/tax'
			];

			$events[] = [
				'code'    => 'tax_class_delete',
				'trigger' => 'admin/model/localisation/tax_class.deleteTaxClass/after',
				'action'  => 'event/tax'
			];

			// Tax Rate
			$events[] = [
				'code'    => 'tax_rate_add',
				'trigger' => 'admin/model/localisation/tax_rate.addTaxRate/after',
				'action'  => 'event/tax'
			];

			$events[] = [
				'code'    => 'tax_rate_edit',
				'trigger' => 'admin/model/localisation/tax_rate.editTaxRate/after',
				'action'  => 'event/tax'
			];

			$events[] = [
				'code'    => 'tax_rate_delete',
				'trigger' => 'admin/model/localisation/tax_rate.deleteTaxRate/after',
				'action'  => 'event/tax'
			];

			// Theme
			$events[] = [
				'code'    => 'theme_add',
				'trigger' => 'admin/model/design/theme.addTheme/after',
				'action'  => 'event/theme'
			];

			$events[] = [
				'code'    => 'theme_edit',
				'trigger' => 'admin/model/design/theme.editTheme/after',
				'action'  => 'event/theme'
			];

			$events[] = [
				'code'    => 'theme_delete',
				'trigger' => 'admin/theme/design/theme.deleteTheme/after',
				'action'  => 'event/theme'
			];

			// Topic
			$events[] = [
				'code'    => 'topic_add',
				'trigger' => 'admin/model/cms/topic.addTopic/after',
				'action'  => 'event/topic'
			];

			$events[] = [
				'code'    => 'topic_edit',
				'trigger' => 'admin/model/cms/topic.editTopic/after',
				'action'  => 'event/topic'
			];

			$events[] = [
				'code'    => 'topic_delete',
				'trigger' => 'admin/theme/cms/topic.deleteTopic/after',
				'action'  => 'event/topic'
			];

			// Translation
			$events[] = [
				'code'    => 'translation_add',
				'trigger' => 'admin/model/cms/translation.addTranslation/after',
				'action'  => 'event/translation'
			];

			$events[] = [
				'code'    => 'translation_edit',
				'trigger' => 'admin/model/cms/translation.editTranslation/after',
				'action'  => 'event/translation'
			];

			$events[] = [
				'code'    => 'translation_delete',
				'trigger' => 'admin/model/cms/translation.deleteTranslation/after',
				'action'  => 'event/translation'
			];

			// Weight Class
			$events[] = [
				'code'    => 'weight_class_add',
				'trigger' => 'admin/model/localisation/translation.addTranslation/after',
				'action'  => 'event/translation'
			];

			$events[] = [
				'code'    => 'weight_class_edit',
				'trigger' => 'admin/model/localisation/translation.editTranslation/after',
				'action'  => 'event/translation'
			];

			$events[] = [
				'code'    => 'weight_class_delete',
				'trigger' => 'admin/model/localisation/translation.deleteTranslation/after',
				'action'  => 'event/translation'
			];

			// Translation
			$events[] = [
				'code'    => 'translation_add',
				'trigger' => 'admin/model/design/translation.addTranslation/after',
				'action'  => 'event/translation'
			];

			$events[] = [
				'code'    => 'translation_edit',
				'trigger' => 'admin/model/design/translation.editTranslation/after',
				'action'  => 'event/translation'
			];

			$events[] = [
				'code'    => 'translation_delete',
				'trigger' => 'admin/model/design/translation.deleteTranslation/after',
				'action'  => 'event/translation'
			];

			// Zone
			$events[] = [
				'code'    => 'zone_add',
				'trigger' => 'admin/model/design/translation.addZone/after',
				'action'  => 'event/zone'
			];

			$events[] = [
				'code'    => 'zone_edit',
				'trigger' => 'admin/model/design/translation.editZone/after',
				'action'  => 'event/zone'
			];

			$events[] = [
				'code'    => 'zone_delete',
				'trigger' => 'admin/model/design/translation.deleteZone/after',
				'action'  => 'event/zone'
			];

			foreach ($events as $event) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($event['code']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($event['code']) . "', `trigger` = '" . $this->db->escape($event['trigger']) . "', `action` = '" . $this->db->escape($event['action']) . "', `status` = '1', `sort_order` = '0'");
				}
			}

			$this->load->model('upgrade/upgrade');

			$events = $this->model_upgrade_upgrade->getRecords('event');

			foreach ($events as $event) {
				if (!str_contains($event['trigger'], '.')) {
					$parts = explode('/', $event['trigger']);

					$string_1 = implode('/', array_slice($parts, 0, -2));
					$string_2 = implode('/', array_slice($parts, -2));

					$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = '" . $this->db->escape($string_1 . '.' . $string_2) . "' WHERE `event_id` = '" . (int)$event['event_id'] . "'");
				}
			}

			// Alter events table
			if ($this->model_upgrade_upgrade->hasField('event', 'date_added')) {
				$this->model_upgrade_upgrade->dropField('event', 'date_added');
			}

			// Event - Remove admin promotion from OC 3.x, since it is no longer required to have in OC v4.x releases.
			$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `action` = 'extension/extension/promotion.getList'");
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 8, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_9', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
