<?php
class ModelAccountRecurring extends Model {

    private $recurring_status = array(
        0 => 'Inactive',
        1 => 'Active',
        2 => 'Suspended',
        3 => 'Cancelled',
        4 => 'Expired / Complete'
    );

    private $transaction_type = array(
        0 => 'Created',
        1 => 'Payment',
        2 => 'Outstanding payment',
        3 => 'Payment skipped',
        4 => 'Payment failed',
        5 => 'Cancelled',
        6 => 'Suspended',
        7 => 'Suspended from failed payment',
        8 => 'Outstanding payment failed',
        9 => 'Expired',
    );

    public function getProfile($id){
        $result = $this->db->query("SELECT `or`.*,`o`.`payment_method`,`o`.`payment_code`,`o`.`currency_code` FROM `" . DB_PREFIX . "order_recurring` `or` LEFT JOIN `" . DB_PREFIX . "order` `o` ON `or`.`order_id` = `o`.`order_id` WHERE `or`.`order_recurring_id` = '".(int)$id."' AND `o`.`customer_id` = '".(int)$this->customer->getId()."' LIMIT 1");

        if($result->num_rows > 0){
            $profile = $result->row;

            return $profile;
        }else{
            return false;
        }
    }

    public function getProfileByRef($ref){
        $profile = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_recurring` WHERE `profile_reference` = '".$this->db->escape($ref)."' LIMIT 1");

        if($profile->num_rows > 0){
            return $profile->row;
        }else{
            return false;
        }
    }

    public function getProfileTransactions($id){

        $profile = $this->getProfile($id);

        $results = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_recurring_transaction` WHERE `order_recurring_id` = '".(int)$id."'");

        if($results->num_rows > 0){
            $transactions = array();

            foreach($results->rows as $transaction){

                $transaction['amount'] = $this->currency->format($transaction['amount'], $profile['currency_code'], 1);

                $transactions[] = $transaction;
            }

            return $transactions;
        }else{
            return false;
        }
    }

    public function getAllProfiles($start = 0, $limit = 20){
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $result = $this->db->query("SELECT `or`.*,`o`.`payment_method`,`o`.`currency_id`,`o`.`currency_value` FROM `" . DB_PREFIX . "order_recurring` `or` LEFT JOIN `" . DB_PREFIX . "order` `o` ON `or`.`order_id` = `o`.`order_id` WHERE `o`.`customer_id` = '".(int)$this->customer->getId()."' ORDER BY `o`.`order_id` DESC LIMIT " . (int)$start . "," . (int)$limit);

        if($result->num_rows > 0){
            $profiles = array();

            foreach($result->rows as $profile){
                $profiles[] = $profile;
            }

            return $profiles;
        }else{
            return false;
        }
    }

    public function getTotalRecurring(){
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_recurring` `or` LEFT JOIN `" . DB_PREFIX . "order` `o` ON `or`.`order_id` = `o`.`order_id` WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "'");

        return $query->row['total'];
    }
}