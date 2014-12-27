<?php namespace App\Validation;

use Illuminate\Validation\Factory;
use Symfony\Component\Translation\Translator;
use App\Validation\ValidatorManager;

abstract class AbstractValidator {

	protected $validator;

	protected $data = array();

	protected $rules = array();

	protected $lang = array();

	protected $errors = array();

	protected $messages = array();

	protected $responses = array();

	protected function beforeExecute()
	{
	}

	public function with(array $data, array $lang, array $custom = null)
	{
		$this->data = $data;
		if ($custom)
			$lang = array_merge($lang, $custom);
		$this->lang = $lang;
		return $this;
	}

	public function errors()
	{
		return $this->errors;
	}

	public function getResponse()
	{

		if (empty($this->errors))
			return array();

		$responses = array();

		foreach ($this->errors->getMessages() as $key => $value) {
			$responses[$this->responses[$key]] = $value[0];
		}
		return $responses;
	}

	public function passes()
	{

		$manager = new ValidatorManager('en', __DIR__.'/lang');

		$manager->setConnection([
			'driver'    => 'mysql',
			'host'      => DB_HOSTNAME,
			'database'  => DB_DATABASE,
			'username'  => DB_USERNAME,
			'password'  => DB_PASSWORD,
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => DB_PREFIX,
		]);

		// $translator = new Translator('en');
		$factory = $manager->getValidator();

		$this->beforeExecute();

		$messages = array();
		foreach ($this->messages as $key => $value) {
			if (is_array($value)) {
				$lang_value = $value[0];
				unset($value[0]);
				$messages[$key] = vsprintf($this->lang[$lang_value], $value);
			}
			else {
				$messages[$key] = $this->lang[$value];
			}
		}

		$validator = $factory->make($this->data, $this->rules, $messages);

		if($validator->fails())
		{
			$this->errors = $validator->messages();
			return false;
		}

		return true;
	}
}