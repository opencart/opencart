<?php
class ModelPlayPatch extends Model{
    public function runPatch($manual = true){
        $this->load->model('setting/setting');

        return true;
    } 
}