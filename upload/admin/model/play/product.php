<?php
class ModelPlayProduct extends Model{
    public function add($data){
        $this->db->query("
            INSERT INTO `" . DB_PREFIX . "play_product_insert` SET
                `play_product_id`       = '".$this->db->escape((string)$data['product_id'])."',
                `play_product_id_type`  = '".(int)$data['product_id_type']."',
                `product_id`            = '".(int)$data['sku']."',
                `dispatch_to`           = '".(int)$data['dispatch_to']."',
                `price_gb`              = '".(double)$data['price_uk']."',
                `price_eu`              = '".(double)$data['price_euro']."',
                `condition`             = '".(int)$data['condition']."',
                `comment`               = '".$this->db->escape((string)$data['comment'])."',
                `dispatch_from`         = '".(int)$data['dispatch_from']."',
                `created`               = now(),
                `action`                = '".(string)$data['add_delete']."',
                `status`                = 1");
    }

    public function edit($data){
        $this->db->query("
            UPDATE `" . DB_PREFIX . "play_product_insert` SET
                `dispatch_to`           = '".(int)$data['dispatch_to']."',
                `price_gb`              = '".(double)$data['price_uk']."',
                `price_eu`              = '".(double)$data['price_euro']."',
                `condition`             = '".(int)$data['condition']."',
                `comment`               = '".$this->db->escape((string)$data['comment'])."',
                `dispatch_from`         = '".(int)$data['dispatch_from']."',
                `action`                = '".(string)$data['add_delete']."',
                `status`                = 5
            WHERE `product_id`          = '".(int)$data['sku']."' LIMIT 1");
    }

    public function getPending(){
        $data = array();

        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "play_product_insert` `pi` LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p`.`product_id` = `pi`.`product_id`) WHERE `pi`.`status` = 1");

        if($qry->num_rows > 0){
            foreach($qry->rows as $p){
                $data[] = $p;
            }

            return $data;
        }else{
            return 0;
        }
    }

    public function getStatus($id){
        $qry = $this->db->query("SELECT status FROM `" . DB_PREFIX . "play_product_insert` WHERE product_id = '".(int)$id."'");

        if($qry->num_rows > 0){
            return $qry->row['status'];
        }else{
            return 0;
        }
    }

    public function getListing($id){
        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "play_product_insert` WHERE product_id = '".(int)$id."'");

        if($qry->num_rows > 0){
            return $qry->row;
        }else{
            return 0;
        }
    }

    public function getListingErrors($id){
        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "play_product_insert_error` WHERE product_id = '".(int)$id."'");

        if($qry->num_rows > 0){

            $rows = array();

            foreach($qry->rows as $d){
                $rows[] = $d;
            }

            return $rows;
        }else{
            return 0;
        }
    }

    public function getPricingReport(){
        return $this->play->call('product/pricingReport');
    }
    
    public function delete($product_id){
        $this->db->query("
            UPDATE `" . DB_PREFIX . "play_product_insert` SET
                `action`                = 'd',
                `status`                = 7
            WHERE
                `product_id`            = '".(int)$product_id."'");
    }
}