<?php
class ModelEbayCustomer extends Model{
    public function getByEmail($email){
        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `email` = '".$this->db->escape($email)."'");

        if($qry->num_rows){
            return $qry->row['customer_id'];
        }else{
            return false;
        }
    }
}