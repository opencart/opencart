<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Service\RegisterService;

class ControllerAccountTest extends Controller
{
	private $data = array();

	public function index() {

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->load->language('account/register');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->post();

		$this->assginLangErrorPost();

		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['column_right'] = $this->load->controller('common/column_right');
		$this->data['content_top'] = $this->load->controller('common/content_top');
		$this->data['content_bottom'] = $this->load->controller('common/content_bottom');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/register.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/register.tpl', $this->data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/register.tpl', $this->data));
		}
	}

	public function assginLangErrorPost()
	{
		$this->data += $this->language->load('account/register');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_register'),
			'href' => $this->url->link('account/register', '', 'SSL')
		);

		$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));

		$errors = array('warning', 'firstname', 'lastname', 'email', 'telephone', 'address_1', 'city', 'postcode', 'country', 'zone', 'custom_field', 'password', 'confirm');

		foreach ($errors as $value)
			$this->data['error_'.$value] = isset($this->error[$value]) ? $this->error[$value] : '';


		$this->data['action'] = $this->url->link('account/test', '', 'SSL');

		$this->data['customer_groups'] = array();

		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}


		$posts = array('firstname', 'lastname', 'email', 'telephone', 'fax', 'company', 'address_1', 'address_2', 'city', 'password', 'confirm', 'newsletter');

		foreach ($posts as $value)
			$this->data[$value] = isset($this->request->post[$value]) ? $this->request->post[$value] : '';

		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}


		if (isset($this->request->post['postcode'])) {
			$this->data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($this->session->data['shipping_address']['postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$this->data['postcode'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($this->session->data['shipping_address']['country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->request->post['zone_id'])) {
			$this->data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($this->session->data['shipping_address']['zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$this->data['zone_id'] = '';
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		// Custom Fields
		$this->load->model('account/custom_field');

		$this->data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

		if (isset($this->request->post['custom_field'])) {
			if (isset($this->request->post['custom_field']['account'])) {
				$account_custom_field = $this->request->post['custom_field']['account'];
			} else {
				$account_custom_field = array();
			}
			
			if (isset($this->request->post['custom_field']['address'])) {
				$address_custom_field = $this->request->post['custom_field']['address'];
			} else {
				$address_custom_field = array();
			}			
			
			$this->data['register_custom_field'] = $account_custom_field + $address_custom_field;
		} else {
			$this->data['register_custom_field'] = array();
		}

		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}

		if (isset($this->request->post['agree'])) {
			$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = false;
		}

	}

	public function post() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$service = new RegisterService($this, $this->request->post, $this->load->language('account/register'));

			if ($service->validate()) {
				$service->register();
			}

			$this->error = $service->errors;
		}
	}

	public function test() {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		// $register_data = array(
		// 	'firstname' => 'Steve',
		// 	'lastname' => 'Lee',
		// 	'email' => 'demo@gmail.com',
		// 	'password' => '12345678',
		// 	'ip' => $this->request->server['REMOTE_ADDR']
		// 	);
		// $customer = Customer::register($register_data);

		// $address = Address::find(1);
		// print_r($address->getData());

		// $customer = Customer::find(2);
		// print_r($customer->getAddresses());

		// $customer = new Customer;
		// $customer->email = 'John';
		// $customer->save();
		// $customer->addAddress(array('address_1'=>'Taipei'), true);

		// $customers = Customer::all();

		// foreach ($customers as $customer) {
		// 	echo 'firstname:'.$customer->firstname.'<br/>';
		// 	echo 'default address:'.$customer->address->address_1.'<br/>';

		// 	// Encapsulator::init()->connection()->enableQueryLog();

		// 	echo 'addresses:'.'<br/>';
		// 	foreach ($customer->addresses as $address) {
		// 		echo $address->address_1.'<br/>';
		// 	}
		// 	echo '<br/>';
		// }

		echo 'End';
	}
}