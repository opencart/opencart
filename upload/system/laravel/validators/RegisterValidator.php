<?php namespace App\Validation;

use App\Validation\AbstractValidator;
use App\Eloquent\Country;
use App\Eloquent\Information;

class RegisterValidator extends AbstractValidator
{
	protected $rules = array(
		'firstname'    => 'required|between:1,32',
		'lastname'     => 'required|between:1,32',
		'email'        => 'required|email|max:96|unique:customer',
		'email_unique' => 'unique:customer,email',
		'telephone'    => 'required|between:3,32',
		'address_1'    => 'required|between:3,128',
		'city'         => 'required|between:2,128',
		'country_id'   => 'required',
		'zone_id'      => 'required',
		'password'     => 'required|between:4,20|confirmed',
		'password_confirmation' => 'required',
		);

	protected $messages = array(
		'firstname.required'  => 'error_firstname',
		'firstname.between'   => 'error_firstname',
		'lastname.required'   => 'error_lastname',
		'lastname.between'    => 'error_lastname',
		'email.max'           => 'error_email',
		'email.email'         => 'error_email',
		'email.required'      => 'error_email',
		'email_unique.unique' => 'error_exists',
		'telephone.required'  => 'error_telephone',
		'telephone.between'   => 'error_telephone',
		'address_1.required'  => 'error_address_1',
		'address_1.between'   => 'error_address_1',
		'city.required'       => 'error_city',
		'city.between'        => 'error_city',
		'country_id.required' => 'error_country',
		'zone_id.required'    => 'error_zone',
		'password.required'   => 'error_password',
		'password.between'    => 'error_password',
		'password.confirmed'  => 'error_confirm',
		'password_confirmation.required' => 'error_confirm',
	);

	protected $responses = array(
		'firstname'    => 'firstname',
		'lastname'     => 'lastname',
		'email'        => 'email',
		'email_unique' => 'warning',
		'telephone'    => 'telephone',
		'address_1'    => 'address_1',
		'city'         => 'city',
		'country_id'   => 'country',
		'zone_id'      => 'zone',
		'password'     => 'password',
		'password_confirmation' => 'confirm',
		'agree'        => 'warning',
	);

	protected function beforeExecute()
	{
		$country = Country::find($this->data['country_id']);
		if (!$country && $country->status == 1) {
			$this->rules['postcode'] = 'required|between:2,10';
		}
		if ($this->data['config_account_id']) {
			$information = Information::find($this->data['config_account_id']);
			$information->setLanguage(1);
			if ($information->status) {
				$this->rules['agree'] = 'required';
				$this->messages['agree.required'] = array('error_agree', $information->title);
			}
		}
	}
}