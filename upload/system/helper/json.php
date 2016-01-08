<?php
if (!function_exists('json_encode')) {
	function json_encode($data) {
		switch (gettype($data)) {
			case 'boolean':
				return $data ? 'true' : 'false';
			case 'integer':
			case 'double':
				return $data;
			case 'resource':
			case 'string':
				# Escape non-printable or Non-ASCII characters.
				# I also put the \\ character first, as suggested in comments on the 'addclashes' page.
				$json = '';

				$string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';

				# Convert UTF-8 to Hexadecimal Codepoints.
				for ($i = 0; $i < strlen($string); $i++) {
					$char = $string[$i];
					$c1 = ord($char);

					# Single byte;
					if ($c1 < 128) {
						$json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);

						continue;
					}

					# Double byte
					$c2 = ord($string[++$i]);

					if (($c1 & 32) === 0) {
						$json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);

						continue;
					}

					# Triple
					$c3 = ord($string[++$i]);

					if (($c1 & 16) === 0) {
						$json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));

						continue;
					}

					# Quadruple
					$c4 = ord($string[++$i]);

					if (($c1 & 8 ) === 0) {
						$u = (($c1 & 15) << 2) + (($c2 >> 4) & 3) - 1;

						$w1 = (54 << 10) + ($u << 6) + (($c2 & 15) << 2) + (($c3 >> 4) & 3);
						$w2 = (55 << 10) + (($c3 & 15) << 6) + ($c4 - 128);

						$json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
					}
				}

				return $json;
			case 'array':
				if (empty($data) || array_keys($data) === range(0, sizeof($data) - 1)) {
					$output = array();

					foreach ($data as $value) {
						$output[] = json_encode($value);
					}

					return '[' . implode(',', $output) . ']';
				}
			case 'object':
				$output = array();

				foreach ($data as $key => $value) {
					$output[] = json_encode(strval($key)) . ':' . json_encode($value);
				}

				return '{' . implode(',', $output) . '}';
			default:
				return 'null';
		}
	}
}

if (!function_exists('json_decode')) {
	function json_decode($json, $assoc = false) {
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

		$json = strtr($json, array_map('stripcslashes', $m2s));

		$function = @create_function('', "return {$json};");
		$return = ($function) ? $function() : null;

		unset($s2m);
		unset($m2s);
		unset($function);

		return $return;
	}
}