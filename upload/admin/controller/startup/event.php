<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Event
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Event extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		// Add events from the DB
		$this->load->model('setting/event');

		$results = $this->model_setting_event->getEvents();

		foreach ($results as $result) {
			if ($result['status']) {
				$part = explode('/', $result['trigger']);

				if ($part[0] == 'admin') {
					array_shift($part);

					$this->event->register(implode('/', $part), new \Opencart\System\Engine\Action($result['action']), $result['sort_order']);
				}

				if ($part[0] == 'system') {
					$this->event->register($result['trigger'], new \Opencart\System\Engine\Action($result['action']), $result['sort_order']);
				}
			}
		}

		/*
		 * Event Trigger
		 *
		attribute.editAttribute
		attribute.deleteAttribute

		category.add
		category.edit
		category.delete

		filter.edit
		filter.delete

		information.add
		information.edit
		information.delete

		manufacturer.add
		manufacturer.edit
		manufacturer.delete

		option.edit
		option.delete

		product.add
		product.edit
		product.delete

		review.add
		review.edit
		review.delete

		// cms

		article.add
		article.edit
		article.delete

		topic.add
		topic.edit
		topic.delete

		comment

		custom_field.add
		custom_field.edit
		custom_field.delete

		customer_group.add
		customer_group.edit
		customer_group.delete

		banner.add
		banner.edit
		banner.delete

		translation.add
		translation.edit
		translation.delete

		country.add
		country.edit
		country.delete

		currency.add
		currency.edit
		currency.delete

		geo_zone.add
		geo_zone.edit
		geo_zone.delete

		language.add
		language.edit
		language.delete

		location.add
		location.edit
		location.delete

		return_reason.add
		return_reason.edit
		return_reason.delete

		tax_class.add
		tax_class.edit
		tax_class.delete

		tax_rate.add
		tax_rate.edit
		tax_rate.delete

		zone.add
		zone.edit
		zone.delete

		setting.delete

		store.add
		store.edit
		store.delete
		*/
	}
}
