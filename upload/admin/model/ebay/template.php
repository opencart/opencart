<?php
class ModelEbayTemplate extends Model{
    public function add($data){
        $this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_template` SET `name` = '".$this->db->escape($data['name'])."', `html` = '".$this->db->escape($data['html'])."'");
        return $this->db->getLastId();
    }
    
    public function edit($id, $data){
        $this->db->query("UPDATE `" . DB_PREFIX . "ebay_template` SET `name` = '".$this->db->escape($data['name'])."', `html` = '".$this->db->escape($data['html'])."' WHERE `template_id` = '".(int)$id."' LIMIT 1");
    }
    
    public function delete($id){
        $qry = $this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_template` WHERE `template_id` = '".(int)$id."' LIMIT 1");

        if($qry->countAffected > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public function get($id){
        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_template` WHERE `template_id` = '".(int)$id."' LIMIT 1");

        if($qry->num_rows){
            $row                = $qry->row;
            $row['link_edit']   = HTTPS_SERVER . 'index.php?route=ebay/template/edit&token=' . $this->session->data['token'].'&template_id='.$row['template_id'];
            $row['link_delete'] = HTTPS_SERVER . 'index.php?route=ebay/template/delete&token=' . $this->session->data['token'].'&template_id='.$row['template_id'];
            
            return $row;
        }else{
            return false;
        }
    }
    
    public function getAll(){
        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_template`");

        if($qry->num_rows){
            $templates = array();
            foreach($qry->rows as $row){
                $row['link_edit']   = HTTPS_SERVER . 'index.php?route=ebay/template/edit&token=' . $this->session->data['token'].'&template_id='.$row['template_id'];
                $row['link_delete'] = HTTPS_SERVER . 'index.php?route=ebay/template/delete&token=' . $this->session->data['token'].'&template_id='.$row['template_id'];
                $templates[]         = $row;
            }
            
            return $templates;
        }else{
            return false;
        }
    }
}