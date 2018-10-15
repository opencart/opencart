<?php
class ControllerCronGdrp extends Controller {
	public function index($cron_id, $code, $cycle, $date_added) {
		$this->load->model('customer/gdrp');
		$this->load->model('customer/customer');

		$results = $this->model_customer_gdrp->getGdrps();

		foreach ($results as $result) {
			if ($result['status'] && (strtotime($result['date_added']) >= strtotime('+' . $this->config->get('config_gdrp_limit') . ' days'))) {
				$this->model_customer_gdrp->deleteGdrp($result['customer_id']);

				$this->model_customer_customer->deleteCustomer($result['customer_id']);
			}
		}
	}
}