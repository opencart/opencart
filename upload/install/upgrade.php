<?php
// Run full install if config doesn't exist
if (!file_exists('../config.php')) {
	header('Location: ./index.php');
	exit;
}

// Configuration
require_once('../config.php');
   
// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Get Path & Url
$errors = array();
$baseurl=(isset($_SERVER['HTTPS']) ? 'https' :'http'). '://' . $_SERVER['HTTP_HOST'] . str_replace('/install','',dirname($_SERVER['REQUEST_URI']));
chdir('..');
$basepath=getcwd(); 
chdir(dirname(__FILE__));

if (!$link = @mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD)) {
	$errors[] = 'Could not connect to the database server using the username and password provided.';
} else {
	if (!@mysql_select_db(DB_DATABASE, $link)) {
		$errors[] = 'The database could selected, check you have permissions, and check it exists on the server.';
	}			
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Installation</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
		<h1>OpenCart 1.x Upgrade Script (BETA)</h1>
		<div id="container">
<?php 
	if (empty($errors)) {
		// Run upgrade script
		$file='upgrade.sql';
		if (!file_exists($file)) {
			$errors[] = 'Upgrade SQL file '.$file.' could not be found.';
		} else {
			mysql_query('set character set utf8', $link);
			if ($sql=file($file)) {
				$query = '';
				foreach($sql as $line) {
					
					// Hacks for compatibility (needs to be improved)
					$line = str_replace("oc_", DB_PREFIX, $line);
					$line = str_replace(" order ", " `order` ", $line);
					$line = str_replace(" ssl ", " `ssl` ", $line);
					$line = str_replace("NOT NULL DEFAULT ''", "NOT NULL", $line);
					$line = str_replace("NOT NULL DEFAULT NULL", "NOT NULL", $line);
					$line = str_replace("NOT NULL DEFAULT 0 COMMENT '' auto_increment", "NOT NULL COMMENT '' auto_increment", $line);
					
					if ((substr(trim($line), 0, 2) == '--') || (substr(trim($line), 0, 1) == '#')) { continue; }
					if (preg_match('/^ALTER TABLE (.+?) ADD PRIMARY KEY/', $line, $matches)) {
						$res = mysql_query(sprintf("SHOW KEYS FROM %s",$matches[1]), $link);
						$info = mysql_fetch_assoc(mysql_query(sprintf("SHOW KEYS FROM %s",$matches[1]), $link));
						if ($info['Key_name'] == 'PRIMARY') { continue; }
					}
					if (preg_match('/^ALTER TABLE (.+?) ADD (.+?) /', $line, $matches)) {
						if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $matches[1],str_replace('`', '', $matches[2])), $link)) > 0) { continue; }
					}
					if (preg_match('/^ALTER TABLE (.+?) DROP (.+?) /', $line, $matches)) {
						if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $matches[1],str_replace('`', '', $matches[2])), $link)) <= 0) { continue; }
					}
					if (strpos($line, 'ALTER TABLE') !== FALSE && strpos($line, 'DROP') !== FALSE && strpos($line, 'PRIMARY') === FALSE) {
						$params = explode(' ', $line);
						if ($params[3] == 'DROP') {
							if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM $params[2] LIKE '$params[4]'", $matches[1],str_replace('`', '', $matches[2])), $link)) <= 0) { continue; }
						}
					}
					if (preg_match('/^ALTER TABLE (.+?) MODIFY (.+?) /', $line, $matches)) {
						if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $matches[1],str_replace('`', '', $matches[2])), $link)) <= 0) { continue; }
					}
					//if (preg_match('/^ALTER TABLE (.+?) DEFAULT (.+?) /',$line,$matches)) {
					//	if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'",$matches[1],str_replace('`','',$matches[2])), $link)) <= 0) { continue; }
					//}
					//if (preg_match('/^ALTER TABLE (.+?) ALTER (.+?) /',$line,$matches)) {
					//	if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'",$matches[1],str_replace('`','',$matches[2])), $link)) <= 0) { continue; }
					//}
					
					if (!empty($line)) {
						$query .= $line;
						if (preg_match('/;\s*$/', $line)) {
							if (mysql_query($query, $link) === false) {
								$errors[] = 'Could not execute this query: ' . $query;
							}
							$query = '';
						}
					}
				}
			}
		}
	}
	
	// Check if there are any products associated with a store (pre-1.4.1)
	$info = mysql_fetch_assoc(mysql_query("SELECT * FROM " . DB_PREFIX . "product_to_store", $link));
	
	// If not, then add them all to the default
	if (!$info) {
		$resource = mysql_query("SELECT product_id FROM " . DB_PREFIX . "product", $link);
		$data = array();
		$i = 0;
		while ($result = mysql_fetch_assoc($resource)) {
			$data[$i] = $result;

			$i++;
		}
		
		foreach ($data as $product) {
			mysql_query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '".$product['product_id']."', store_id = '0'", $link);
		}
	}
	
	// Check if there are any categories associated with a store (pre-1.4.1)
	$info = mysql_fetch_assoc(mysql_query("SELECT * FROM " . DB_PREFIX . "information_to_store", $link));
	
	// If not, then add them all to the default
	if (!$info) {
		$resource = mysql_query("SELECT information_id FROM " . DB_PREFIX . "information", $link);
		$data = array();
		$i = 0;
		while ($result = mysql_fetch_assoc($resource)) {
			$data[$i] = $result;

			$i++;
		}
		
		foreach ($data as $information) {
			mysql_query("INSERT INTO " . DB_PREFIX . "information_to_store SET information_id = '".$information['information_id']."', store_id = '0'", $link);
		}
	}
	
	// Check if there are any categories associated with a store (pre-1.4.1)
	$info = mysql_fetch_assoc(mysql_query("SELECT * FROM " . DB_PREFIX . "category_to_store", $link));
	
	// If not, then add them all to the default
	if (!$info) {
		$resource = mysql_query("SELECT category_id FROM " . DB_PREFIX . "category", $link);
		$data = array();
		$i = 0;
		while ($result = mysql_fetch_assoc($resource)) {
			$data[$i] = $result;

			$i++;
		}
		
		foreach ($data as $category) {
			mysql_query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '".$category['category_id']."', store_id = '0'", $link);
		}
	}
	
	// Check if there are any categories associated with a store (pre-1.4.1)
	$info = mysql_fetch_assoc(mysql_query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store", $link));
	
	// If not, then add them all to the default
	if (!$info) {
		$resource = mysql_query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer", $link);
		$data = array();
		$i = 0;
		while ($result = mysql_fetch_assoc($resource)) {
			$data[$i] = $result;

			$i++;
		}
		
		foreach ($data as $manufacturer) {
			mysql_query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '".$manufacturer['manufacturer_id']."', store_id = '0'", $link);
		}
	}
	
	if (!empty($errors)) { //has to be a separate if
		?>
		<p>The following errors occured:</p>
		<?php foreach ($errors as $error) {?>
		<div class="warning"><?php echo $error;?></div><br />
		<?php } ?>
		<p>The above errors occurred because the script could not properly determine the existing state of those db elements. Your store may not need those changes. Please post any errors on the forums to ensure that they can be addressed in future versions!</p>
		</div>
<?php } else { ?>
		<h2>SUCCESS!!! Click <a href="<?php echo $baseurl; ?>">here</a> to goto your store</h2>
<?php } ?>
		<div class="center"><a href="http://www.opencart.com/">OpenCart.com</a></div>
	</body>
</html>