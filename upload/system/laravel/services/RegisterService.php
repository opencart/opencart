<?php namespace App\Service;

use App\Eloquent\Customer;
use App\Eloquent\Address;
use App\Eloquent\CustomerLogin;
use App\Eloquent\CustomerActivity;
use App\Eloquent\CustomerGroup;

use App\Validation\RegisterValidator;

class RegisterService
{
	protected $opencart;

	protected $params;

	protected $error_messages;

	public $errors;

	public function __construct($opencart, $params, $error_messages)
	{
		$this->opencart = $opencart;
		$this->params = $params;
		$this->error_messages = $error_messages;
	}

	public function validate() {

		$validator = new RegisterValidator();
		
		$opencart = $this->opencart;

		$this->params['password_confirmation'] = $this->params['confirm'];
		if ($opencart->config->get('config_account_id')) {
			$this->params['config_account_id'] = $opencart->config->get('config_account_id');
			$this->params['config_store_id'] = $opencart->config->get('config_store_id');
		}

		$validator->with($this->params, $this->error_messages)->passes();
		$this->errors = $validator->getResponse();

		return !$this->errors;
	}

	public function register()
	{
		$params = $this->params;
		$opencart = $this->opencart;

		if (isset($params['customer_group_id']) && is_array($opencart->config->get('config_customer_group_display')) && in_array($params['customer_group_id'], $opencart->config->get('config_customer_group_display'))) {
			$customer_group_id = $params['customer_group_id'];
		} else {
			$customer_group_id = $opencart->config->get('config_customer_group_id');
		}

		$group = CustomerGroup::find($customer_group_id);

		$customer = Customer::create($params);
		$customer->customer_group_id = $customer_group_id;
		$customer->approved          = !$group->approval;
		$customer->store_id          = $opencart->config->get('config_store_id');
		$customer->ip                = $opencart->request->server['REMOTE_ADDR'];
		$customer->setPassword($params['password']);
		$customer->save();

		$customer->addAddress($params, true);

		// Send mail...

		CustomerLogin::where('email', $params['email'])->delete();
		
		$opencart->customer->login($opencart->request->post['email'], $opencart->request->post['password']);

		unset($opencart->session->data['guest']);

		$activity_data = array(
			'customer_id' => $opencart->customer->getId(),
			'name'        => $opencart->request->post['firstname'] . ' ' . $opencart->request->post['lastname']
		);

		$activity = new CustomerActivity;
		$activity->customer_id = $opencart->customer->getId();
		$activity->key         = 'register';
		$activity->data        = serialize($activity_data);
		$activity->ip          = $opencart->request->server['REMOTE_ADDR'];

		$opencart->response->redirect($opencart->url->link('account/success'));
	}
}