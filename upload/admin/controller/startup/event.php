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

		$events = [];

		// Article
		$events[] = [
			'code'    => 'article.add',
			'trigger' => 'admin/model/cms/article.addArticle/after',
			'action'  => 'event/article.add'
		];

		$events[] = [
			'code'    => 'article.edit-12',
			'trigger' => 'admin/model/cms/article.editArticle/after',
			'action'  => 'event/article.edit'
		];

		$events[] = [
			'code'    => 'article.delete',
			'trigger' => 'admin/model/cms/article.deleteArticle/after',
			'action'  => 'event/article.delete'
		];

		//




		//('country_add', 'Updates country data when a new country is added.', 'admin/model/localisation/country.addCountry/after', 'event/country', 1),
       //('country_edit', 'Updates country data when a country is edited.', 'admin/model/localisation/country.editCountry/after', 'event/country', 1),
       //('country_delete', 'Updates country data when a country is deleted.', 'admin/model/localisation/country.deleteCountry/after', 'event/country', 1),


	}
}
