<?php
class ControllerModuleEventDemo extends Controller {
    public function install() {
        $this->load->model('event/event');
        $this->model_event_event->setHandler('customer_register', array(
            'type' => 'module',
            'code' => 'event_demo',
            'method' => 'pointsOnRegister'
        ));
    }
    
    public function uninstall() {
        $this->load->model('event/event');
        $this->model_event_event->removeHandler('customer_register', array(
            'type' => 'module',
            'code' => 'event_demo',
            'method' => 'pointsOnRegister'
        ));
    }
}