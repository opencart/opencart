<?php
class ModelPlayCustomer extends Model {
    public function getCustomerId($email){
        $qry = $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "customer` 
            WHERE `email` = '".$this->db->escape($email)."' LIMIT 1");
        
        if($qry->num_rows > 0){
            $this->play->log('Found customer ID: '.$qry->row['customer_id']);
            return $qry->row['customer_id'];
        }else{
            $this->play->log('No customer ID found');
            return 0;
        }
    }

    public function getCustomer($customer_id){

        $this->play->log('Getting customer ID: '.$customer_id);

        $qry = $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "customer` 
            WHERE `customer_id` = '".(int)$customer_id."' LIMIT 1");
        
        if($qry->num_rows > 0){
            return $qry->row;
        }else{
            return false;
        }
    }

    public function createCustomer($email, $name){
        
        $name = explode(' ', $name);
        $fname = $name[0];
        unset($name[0]);
        $lname = implode(' ', $name);
        
        $this->db->query("
            INSERT INTO `" . DB_PREFIX . "customer`
            SET
                `email` = '".$this->db->escape($email)."',
                `firstname` = '".$this->db->escape($fname)."',
                `lastname` = '".$this->db->escape($lname)."'");

        $this->play->log('Created customer ID: '.$this->db->getLastId());
        
        return $this->db->getLastId();
    }
}