<?php
namespace Session;
class Native extends \SessionHandler {
    private $session;

    public function __construct($session) {
        $this->session = $session;
    }

    public function close() {
        return parent::close();
    }

    public function create_sid() {
        return parent::create_sid();
    }

    public function destroy($session_id) {
        return parent::destroy($this->session->getId());
    }

    public function gc($maxlifetime) {
        return parent::gc($maxlifetime);
    }

    public function open($path, $name) {
        return parent::open($path, $name);
    }

    public function read($session_id) {
        return parent::read($this->session->getId());
    }

    public function write($session_id, $data) {
		return parent::write($this->session->getId(), serialize($this->session->data));
    }
}