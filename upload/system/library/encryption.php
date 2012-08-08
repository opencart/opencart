<?php
class Encryption {	
	private $key;
	private $bits;
	private $box = array(
		0x63,
		0x7c,
		0x77,
		0x7b,
		0xf2,
		0x6b,
		0x6f,
		0xc5,
		0x30,
		0x01,
		0x67,
		0x2b,
		0xfe,0xd7,0xab,0x76,
	0xca,0x82,0xc9,0x7d,0xfa,0x59,0x47,0xf0,0xad,0xd4,0xa2,0xaf,0x9c,0xa4,0x72,0xc0,
	0xb7,0xfd,0x93,0x26,0x36,0x3f,0xf7,0xcc,0x34,0xa5,0xe5,0xf1,0x71,0xd8,0x31,0x15,
	0x04,0xc7,0x23,0xc3,0x18,0x96,0x05,0x9a,0x07,0x12,0x80,0xe2,0xeb,0x27,0xb2,0x75,
	0x09,0x83,0x2c,0x1a,0x1b,0x6e,0x5a,0xa0,0x52,0x3b,0xd6,0xb3,0x29,0xe3,0x2f,0x84,
	0x53,0xd1,0x00,0xed,0x20,0xfc,0xb1,0x5b,0x6a,0xcb,0xbe,0x39,0x4a,0x4c,0x58,0xcf,
	0xd0,0xef,0xaa,0xfb,0x43,0x4d,0x33,0x85,0x45,0xf9,0x02,0x7f,0x50,0x3c,0x9f,0xa8,
	0x51,0xa3,0x40,0x8f,0x92,0x9d,0x38,0xf5,0xbc,0xb6,0xda,0x21,0x10,0xff,0xf3,0xd2,
	0xcd,0x0c,0x13,0xec,0x5f,0x97,0x44,0x17,0xc4,0xa7,0x7e,0x3d,0x64,0x5d,0x19,0x73,
	0x60,0x81,0x4f,0xdc,0x22,0x2a,0x90,0x88,0x46,0xee,0xb8,0x14,0xde,0x5e,0x0b,0xdb,
	0xe0,0x32,0x3a,0x0a,0x49,0x06,0x24,0x5c,0xc2,0xd3,0xac,0x62,0x91,0x95,0xe4,0x79,
	0xe7,0xc8,0x37,0x6d,0x8d,0xd5,0x4e,0xa9,0x6c,0x56,0xf4,0xea,0x65,0x7a,0xae,0x08,
	0xba,0x78,0x25,0x2e,0x1c,0xa6,0xb4,0xc6,0xe8,0xdd,0x74,0x1f,0x4b,0xbd,0x8b,0x8a,
	0x70,0x3e,0xb5,0x66,0x48,0x03,0xf6,0x0e,0x61,0x35,0x57,0xb9,0x86,0xc1,0x1d,0x9e,
	0xe1,0xf8,0x98,0x11,0x69,0xd9,0x8e,0x94,0x9b,0x1e,0x87,0xe9,0xce,0x55,0x28,0xdf,
	0x8c,0xa1,0x89,0x0d,0xbf,0xe6,0x42,0x68,0x41,0x99,0x2d,0x0f,0xb0,0x54,0xbb,0x16);
	private $round = array( 
		array(
			0x00, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x01, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x02, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x04, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x08, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x10, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x20, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x40, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x80, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x1b, 
			0x00, 
			0x00, 
			0x00
		),
		array(
			0x36, 
			0x00, 
			0x00, 
			0x00
		)
	); 

	function __construct($key, $bits = 128) {
        $this->key = $key;
		$this->bits = $bits;
	}
	
	public function encrypt($value) {
		$blockSize = 16;  // block size fixed at 16 bytes / 128 bits (Nb=4) for AES
		
		 // standard allows 128/192/256 bit keys
		// note PHP (5) gives us value and password in UTF8 encoding!
		
		// use AES itself to encrypt password to get cipher key (using plain password as source for  
		// key expansion) - gives us well encrypted key
		$nBytes = $this->bits / 8;  // no bytes in key
		
		$password_bytes = array();
		
		for ($i = 0; $i < $nBytes; $i++) {
			$password_bytes[$i] = ord(substr($this->key, $i, 1)) & 0xff;
		}
		
		$key = $this->cipher($password_bytes, $this->keyExpansion($password_bytes));
		
		$key = array_merge($key, array_slice($key, 0, $nBytes - 16));  // expand key to 16/24/32 bytes long 
		
		// initialise 1st 8 bytes of counter block with nonce (NIST SP800-38A §B.2): [0-1] = millisec, 
		// [2-3] = random, [4-7] = seconds, giving guaranteed sub-ms uniqueness up to Feb 2106
		$counter = array();
		
		$time = floor(microtime(true) * 1000);   // timestamp: milliseconds since 1-Jan-1970
		$millisecond = $time % 1000;
		$second = floor($time / 1000);
		$random = floor(rand(0, 0xffff));
		
		for ($i = 0; $i < 2; $i++) {
			$counter[$i] = $this->urs($millisecond,  $i * 8) & 0xff;
		}
		
		for ($i = 0; $i < 2; $i++) {
			$counter[$i + 2] = $this->urs($random, $i * 8) & 0xff;
		}
		
		for ($i = 0; $i < 4; $i++) {
			$counter[$i + 4] = $this->urs($second, $i * 8) & 0xff;
		}
		
		// and convert it to a string to go on the front of the value
		$ctrTxt = '';
		
		for ($i = 0; $i < 8; $i++) {
			$ctrTxt .= chr($counter[$i]);
		}
		
		// generate key schedule - an expansion of the key into distinct Key Rounds for each round
		$keySchedule = $this->keyExpansion($key);
		
		$blockCount = ceil(strlen($value) / $blockSize);
		
		$ciphertxt = array();  // value as array of strings
		
		for ($b = 0; $b < $blockCount; $b++) {
			for ($c = 0; $c < 4; $c++) {
				$counter[15 - $c] = $this->urs($b, $c * 8) & 0xff;
			}
			
			for ($c = 0; $c < 4; $c++) {
				$counter[15 - $c - 4] = $this->urs($b / 0x100000000, $c * 8);
			}
			
			$cipherCntr = $this->cipher($counter, $keySchedule);  // -- encrypt counter block --
		
			// block size is reduced on final block
			$blockLength = $b < $blockCount - 1 ? $blockSize : (strlen($value) - 1) % $blockSize + 1;
		
			$cipherByte = array();
		
			for ($i = 0; $i < $blockLength; $i++) {  // -- xor plaintext with ciphered counter byte-by-byte --
				$cipherByte[$i] = $cipherCntr[$i] ^ ord(substr($value, $b * $blockSize + $i, 1));
				$cipherByte[$i] = chr($cipherByte[$i]);
			}
			
			$ciphertxt[$b] = implode('', $cipherByte);  // escape troublesome characters in value
		}
		
		// implode is more efficient than repeated string concatenation
		$value = $ctrTxt . implode('', $ciphertxt);
		$value = base64_encode($value);
		
		return $value;
	}
  
	public function decrypt($value, $nBits = 128) {
		$blockSize = 16;  // block size fixed at 16 bytes / 128 bits (Nb=4) for AES
				
		$value = base64_decode($value);
		
		// use AES to encrypt password (mirroring encrypt routine)
		$nBytes = $this->bits / 8;  // no bytes in key
		
		$password_bytes = array();
		
		for ($i = 0; $i < $nBytes; $i++) {
			$password_bytes[$i] = ord(substr($this->key, $i, 1)) & 0xff;
		}
		
		$key = $this->cipher($password_bytes, $this->keyExpansion($password_bytes));
		$key = array_merge($key, array_slice($key, 0, $nBytes - 16));  // expand key to 16/24/32 bytes long
		
		// recover nonce from 1st element of ciphertext
		$counter = array();
		
		$ctrTxt = substr($value, 0, 8);
		
		for ($i = 0; $i < 8; $i++) {
			$counter[$i] = ord(substr($ctrTxt, $i, 1));
		}
		
		// generate key schedule
		$keySchedule = $this->keyExpansion($key);
		
		// separate value into blocks (skipping past initial 8 bytes)
		$nBlocks = ceil((strlen($value) - 8) / $blockSize);
		
		$ct = array();
		
		for ($b = 0; $b < $nBlocks; $b++) {
			$ct[$b] = substr($value, 8 + $b * $blockSize, 16);
		}
		
		$value = $ct;  // value is now array of block-length strings
		
		// value will get generated block-by-block into array of block-length strings
		$plaintxt = array();
		
		for ($b = 0; $b < $nBlocks; $b++) {
			// set counter (block #) in last 8 bytes of counter block (leaving nonce in 1st 8 bytes)
			for ($c = 0; $c < 4; $c++) {
				$counter[15 - $c] = $this->urs($b, $c * 8) & 0xff;
			}
			
			for ($c = 0; $c < 4; $c++) {
				$counter[15 - $c - 4] = $this->urs(($b + 1) / 0x100000000 - 1, $c * 8) & 0xff;
			}
			
			$cipherCntr = $this->cipher($counter, $keySchedule);  // encrypt counter block
		
			$plaintxtByte = array();
			
			for ($i=0; $i<strlen($value[$b]); $i++) {
				// -- xor value with ciphered counter byte-by-byte --
				$plaintxtByte[$i] = $cipherCntr[$i] ^ ord(substr($value[$b], $i, 1));
				$plaintxtByte[$i] = chr($plaintxtByte[$i]);
			}
		
			$plaintxt[$b] = implode('', $plaintxtByte); 
		}
		
		// join array of blocks into single value string
		$value = implode('', $plaintxt);
		
		return $value;
	}
  
	public function cipher($input, $word) {
		$block = 4;                
		$rounds = count($word) / $block - 1;
	
		$state = array();
		
		for ($i = 0; $i < 4 * $block; $i++) {
			$state[$i % 4][floor($i / 4)] = $input[$i];
		}
		
		$state = $this->addRoundKey($state, $word, 0, $block);
		
		for ($i = 1; $i < $rounds; $i++) {
			$state = $this->subBytes($state, $block);
			$state = $this->shiftRows($state, $block);
			$state = $this->mixColumns($state, $block);
			$state = $this->addRoundKey($state, $word, $i, $block);
		}
		
		$state = $this->subBytes($state, $block);
		$state = $this->shiftRows($state, $block);
		$state = $this->addRoundKey($state, $word, $rounds, $block);
		
		$output = array(4 * $block);  // convert state to 1-d array before returning [§3.4]
		
		for ($i = 0; $i < 4 * $block; $i++) {
			$output[$i] = $state[$i % 4][floor($i / 4)];
		}
		
		return $output;
	}
		
	private function urs($a, $b) {
		$a &= 0xffffffff; 
		$b &= 0x1f;  // (bounds check)
			
		if ($a & 0x80000000 && $b > 0) {   // if left-most bit set
			$a = ($a >> 1) & 0x7fffffff;   //   right-shift one bit & clear left-most bit
			$a = $a >> ($b - 1);           //   remaining right-shifts
		} else {                           // otherwise
			$a = ($a >> $b);               //   use normal right-shift
		}
			 
		return $a; 
	}
	  
	private function addRoundKey($state, $word, $round, $block) {  // xor Round Key into state S [§5.1.4]
		for ($i = 0; $i < 4; $i++) {
			for ($c = 0; $c < $block; $c++) {
				$state[$i][$c] ^= $word[$round * 4 + $c][$i];
			}
		}
		
		return $state;
	}
	
	private function subBytes($s, $block) {
		for ($r = 0; $r < 4; $r++) {
			for ($c = 0; $c < $block; $c++) {
				$s[$r][$c] = $this->box[$s[$r][$c]];
			}
		}
		
		return $s;
	}
	
	private function shiftRows($state, $block) {
		$t = array(4);
		
		for ($r = 1; $r < 4; $r++) {
			for ($c = 0; $c < 4; $c++) {
				$t[$c] = $state[$r][($c + $r) % $block];
			}
			
			for ($c = 0; $c < 4; $c++) {
				$state[$r][$c] = $t[$c];
			}
		}
		
		return $state;
	}
	
	private function mixColumns($state, $block) {
		for ($c = 0; $c < 4; $c++) {
			$a = array(4);
			$b = array(4);
			
			for ($i = 0; $i < 4; $i++) {
				$a[$i] = $state[$i][$c];
				$b[$i] = $state[$i][$c] & 0x80 ? $state[$i][$c] << 1 ^ 0x011b : $state[$i][$c] << 1;
			}
			
			$state[0][$c] = $b[0] ^ $a[1] ^ $b[1] ^ $a[2] ^ $a[3];
			$state[1][$c] = $a[0] ^ $b[1] ^ $a[2] ^ $b[2] ^ $a[3];
			$state[2][$c] = $a[0] ^ $a[1] ^ $b[2] ^ $a[3] ^ $b[3];
			$state[3][$c] = $a[0] ^ $b[0] ^ $a[1] ^ $a[2] ^ $b[3];
		}
		
		return $state;
	}
	
	public function keyExpansion($key) {
		$block = 4;
		$length = count($key) / 4;
		$rounds = $length + 6;
		
		$word = array();
		$temp = array();
		
		for ($i = 0; $i < $length; $i++) {
			$word[$i] = array(
				$key[4 * $i], 
				$key[4 * $i + 1], 
				$key[4 * $i + 2], 
				$key[4 * $i + 3]
			);
		}
		
		for ($i = $length; $i < ($block * ($rounds + 1)); $i++) {
			$word[$i] = array();
			
			for ($j = 0; $j < 4; $j++) {
				$temp[$j] = $word[$i - 1][$j];
			}
			
			if ($i % $length == 0) {
				$temp = $this->subWord($this->rotateWord($temp));
			
				for ($j = 0; $j < 4; $j++) {
					$temp[$j] ^= $this->round[$i / $length][$j];
				}
			} elseif ($length > 6 && $i % $length == 4) {
				$temp = $this->subWord($temp);
			}
			
			for ($j = 0; $j < 4; $j++) {
				$word[$i][$j] = $word[$i - $length][$j] ^ $temp[$j];
			}
		}
		
		return $word;
	}
	
	private function subWord($word) {
		for ($i = 0; $i < 4; $i++) {
			$word[$i] = $this->box[$word[$i]];
		}
		
		return $word;
	}
	
	private function rotateWord($word) {
		$string = $word[0];
		
		for ($i = 0; $i < 3; $i++) {
			$word[$i] = $word[$i + 1];
		}
		
		$word[3] = $string;
		
		return $word;
	}
}

$encryption = new Encryption('test123');

$string = $encryption->encrypt('this is a test');

echo $string . '<br />';

echo $encryption->decrypt($string);
?>