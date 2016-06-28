<?php
namespace Session;
class Native extends \SessionHandler {
    private $session_id;

    public function __construct($session_id) {
        $this->session_id = $session_id;
    }

    public function close() {
        return parent::close();
    }

    public function create_sid() {
        return parent::create_sid();
    }

    public function destroy($session_id) {
        return parent::destroy($this->session_id);
    }

    public function gc($maxlifetime) {
        return parent::gc($maxlifetime);
    }

    public function open($path, $name) {
        return parent::open($path, $name);
    }

    public function read($session_id) {
        return parent::read($this->session_id);
    }

    public function write($session_id, $data) {
        return parent::write($this->session_id, $data);
    }
}