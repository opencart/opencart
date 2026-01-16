<?php
function token($length = 32) {
	// Create random token
	$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	$max = strlen($string) - 1;
	
	$token = '';
	
	for ($i = 0; $i < $length; $i++) {
		$token .= $string[random_int(0, $max)];
	}	
	
	return $token;
}

/**
 * Backwards support for timing safe hash string comparisons
 * 
 * http://php.net/manual/en/function.hash-equals.php
 */

if(!function_exists('hash_equals')) {
	function hash_equals($known_string, $user_string) {
		$known_string = (string)$known_string;
		$user_string = (string)$user_string;

		if(strlen($known_string) != strlen($user_string)) {
			return false;
		} else {
			$res = $known_string ^ $user_string;
			$ret = 0;

			for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);

			return !$ret;
		}
	}
}

/**
 * A cross-platform wrapper for glob() that simulates GLOB_BRACE 
 * on systems that do not support it natively.
 */
if (!defined('GLOB_BRACE')) {
    define('GLOB_BRACE', 0);
}
function safe_glob(string $pattern, int $flags = 0):array|false {
    // 1. If GLOB_BRACE is supported and provided, use native glob
    if ((GLOB_BRACE !== 0) && ($flags & GLOB_BRACE)) {
        return glob($pattern, $flags);
    }

    // 2. Fallback: Manually parse {a,b,c} patterns
    if (preg_match('/\{([^}]+)\}/', $pattern, $matches)) {
        $files = [];
        $parts = explode(',', $matches[1]);
        
        foreach ($parts as $part) {
            // Replace the braced section with the current variation
            $subPattern = str_replace($matches[0], trim($part), $pattern);
            
            // Recursively call to handle multiple sets of braces
            $result = safe_glob($subPattern, $flags);
            
            if (is_array($result)) {
                $files = array_merge($files, $result);
            }
        }
        
        // Remove duplicates and sort if GLOB_NOSORT isn't set
        $files = array_unique($files);
        if (!($flags & GLOB_NOSORT)) {
            sort($files);
        }
        
        return $files;
    }

    // 3. Normal glob behavior for non-braced patterns
    return glob($pattern, $flags);
}