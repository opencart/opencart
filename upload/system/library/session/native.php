<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

namespace Session;
class Native extends \SessionHandler {
    public function create_sid() {
        return parent::create_sid();
    }

    public function open($path, $name) {
        return parent::open($path, $name);
    }

    public function close() {
        return parent::close();
    }
	
    public function read($session_id) {
        return parent::read($session_id);
    }

    public function write($session_id, $data) {
		return parent::write($session_id, $data);
    }

    public function destroy($session_id) {
        return parent::destroy($session_id);
    }

    public function gc($maxlifetime) {
        return parent::gc($maxlifetime);
    }	
}