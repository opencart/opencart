<?php
class ModelPlayProduct extends Model
{
    public function updateProduct($product_id, $status){
        $this->db->query("
            UPDATE `" . DB_PREFIX . "play_product_insert` 
            SET `status` = '".(int)$status."' 
            WHERE `product_id` = '".(int)$product_id."' LIMIT 1");

        $this->play->log('updateProduct() - $product_id: '.$product_id.', $status: '.$status);
    }
    
    public function addWarning($msg, $product_id){
        $this->db->query("
            INSERT INTO `" . DB_PREFIX . "play_product_insert_error`
            SET
                `status_msg` = '".$this->db->escape($msg)."',
                `product_id` = '".(int)$product_id."'");

        $this->play->log('addWarning() - $product_id: '.$product_id.', msg: '.$msg);
    }
    
    public function removeAllWarnings($product_id){
        $this->db->query("
            DELETE FROM `" . DB_PREFIX . "play_product_insert_error`
            WHERE `product_id` = '".(int)$product_id."'");

        $this->play->log('removeAllWarnings() - $product_id: '.$product_id);
    }

    public function getProduct($sku){
        $qry = $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "product` `p`
            LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`)
            WHERE `p`.`product_id` = '".(int)$sku."' AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1");
        
        if($qry->num_rows > 0){
            $this->play->log('getProduct() - $sku: '.$sku);
            return $qry->row;
        }else{
            $this->play->log('getProduct() - $sku: '.$sku.' not found');
            return 0;
        }
    }
}