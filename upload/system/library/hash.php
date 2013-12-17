<?php
//	Hash library for encoding keys
class Hash {
	// Function
	public function hmac_md5($data,$key) {
		if (phpversion() >= '5.1.2') {
			return hash_hmac('md5', $data, $key);
		} else {
			return bin2hex(mhash(MHASH_MD5, $data, $key));
		}
	}
}

?>