<?php
final class Json {
	static public function encode($data) {
		if (function_exists('json_encode')) {
			return json_encode($data) . "\n\n\n\n\n\n";
		} else {
			switch (gettype($data)) {
				case 'boolean':
					return $data ? 'true' : 'false';
    			case 'integer':
    			case 'double':
      				return $data;
    			case 'resource':
    			case 'string':
      				return '"' . str_replace(array("\r", "\n", "/", "\""), array('\r', '\n', '\/', '\\"'), $data) . '"';
				case 'array':
					if (empty($data) || array_keys($data) === range(0, sizeof($data) - 1)) {
						$output = array();
						
						foreach ($data as $value) {
							$output[] = Json::encode($value);
						}
						
						return '[' . implode(',', $output) . ']';
					}
    			case 'object':
      				$output = array();
      				
					foreach ($data as $key => $value) {
        				$output[] = Json::encode(strval($key)) . ':' . Json::encode($value);
					}
					
					return '{' . implode(',', $output) . '}';
				default:
					return 'null';
			}
		}
	}
	
	static public function decode($json, $assoc = false) {
		if (function_exists('json_decode')) {
			return json_decode($json, true);
		} else {
			$match = '/".*?(?<!\\\\)"/';

			$string = preg_replace($match, '', $json);
			$string = preg_replace('/[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/', '', $string);

			if ($string != '') {
				return null;
			}

			$s2m = array();
			$m2s = array();

			preg_match_all($match, $json, $m);
			
			foreach ($m[0] as $s) {
				$hash = '"' . md5($s) . '"';
				$s2m[$s] = $hash;
				$m2s[$hash] = str_replace('$', '\$', $s);
			}

			$json = strtr($json, $s2m);

			$a = ($assoc) ? '' : '(object) ';
			
			$data = array(
				':' => '=>', 
				'[' => 'array(', 
				'{' => "{$a}array(", 
				']' => ')', 
				'}' => ')'
			);
			
			$json = strtr($json, $data);
  
  			$json = preg_replace('~([\s\(,>])(-?)0~', '$1$2', $json);
  
 			$json = strtr($json, $m2s);

  			$function = @create_function('', "return {$json};");
  			$return = ($function) ? $function() : null;

  			unset($s2m); 
			unset($m2s); 
			unset($function);

  			return $return;
		}
	}
}
?>