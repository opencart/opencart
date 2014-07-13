<?php
class Event {
    private $data = array();
    private $load;
    
    public function __construct($loader) {
        $this->load = $loader;
    }
    
    public function register($event, $handler) {
        if (!array_key_exists($event, $this->data)) {
            $this->data[$event] = array();
        }
        
        if (is_string($handler)) {
            $this->data[$event][] = $handler;
        } else {
            return false;
        }
        
        return true;
    }
    
    public function unregister($event, $handler) {
        if (!array_key_exists($event, $this->data)) {
            return true;
        }
        
        if (in_array($handler, $this->data[$event])) {
            $key = array_search($handler, $this->data[$event]);
            unset($this->data[$event][$key]);
        }
        
        return true;
    }
    
    public function trigger($event, $data = array(), $return_output = false) {
        if (!array_key_exists($event, $this->data)) {
            return true;
        }
        
        $output = '';
        
        foreach ($this->data[$event] as $handler) {
            if ($return_output) {
                $output .= $this->load->controller($handler, $data);
            } else {
                $this->load->controller($handler, $data);
            }
        }
        
        return $return_output ? $output : true;
    }
}