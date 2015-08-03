<?php
class ModelFraudIp extends Model {
    public function install() {
        // Ban IP
        $status = false;

        if ($order_info['customer_id']) {
            $results = $this->model_account_customer->getIps($order_info['customer_id']);

            foreach ($results as $result) {
                if ($this->model_account_customer->isBanIp($result['ip'])) {
                    $status = true;

                    break;
                }
            }
        } else {
            $status = $this->model_account_customer->isBanIp($order_info['ip']);
        }

        if ($status) {
            $order_status_id = $this->config->get('config_order_status_id');
        }
    }
}
