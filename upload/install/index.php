<?php
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

session_start();

if (!isset($_GET['step'])) {
	$step = 1;
} else {
	$step = $_GET['step'];
}

switch ($step) {
	case '1':
		if ((strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')) {
  			$error = array();
  
  			if (phpversion() < '5.0') {
    			$error['message'] = 'Warning: You need to use PHP5 or above for OpenCart to work!';
  			}

  			if (!ini_get('file_uploads')) {
    			$error['message'] = 'Warning: file_uploads needs to be enabled!';
  			}

  			if (ini_get('session.auto_start')) {
    			$error['message'] = 'Warning: OpenCart will not work with session.auto_start enabled!';
  			}

  			if (!extension_loaded('mysql')) {
    			$error['message'] = 'Warning: MySQL extention needs to be loaded for OpenCart to work!';
  			}

  			if (!extension_loaded('gd')) {
    			$error['message'] = 'Warning: GD extention needs to be loaded for OpenCart to work!';
  			}

  			if (!extension_loaded('zlib')) {
    			$error['message'] = 'Warning: ZLIB extention needs to be loaded for OpenCart to work!';
  			}
			
  			if (!is_writable(dirname(__FILE__) . '/../config.php')) {
    			$error['message'] = 'Warning: config.php needs to be writable for OpenCart to be installed!';
  			}
						
  			if (!is_writable(dirname(__FILE__) . '/../admin/config.php')) {
    			$error['message'] = 'Warning: config.php needs to be writable for OpenCart to be installed!';
  			}

 			if (!is_writable(dirname(__FILE__) . '/../cache')) {
    			$error['message'] = 'Warning: Cache directory needs to be writable for OpenCart to work!';
  			}

 			if (!is_writable(dirname(__FILE__) . '/../image')) {
    			$error['message'] = 'Warning: Image directory needs to be writable for OpenCart to work!';
  			}

 			if (!is_writable(dirname(__FILE__) . '/../image')) {
    			$error['message'] = 'Warning: Image cache directory needs to be writable for OpenCart to work!';
  			}
			
 			if (!is_writable(dirname(__FILE__) . '/../download')) {
    			$error['message'] = 'Warning: Download directory needs to be writable for OpenCart to work!';
  			}
																						  
			if (!$error) {
				header('Location: index.php?step=2');
				exit();
			}
		}
		
		ob_start();
?>
<form action="index.php?step=1" method="post" enctype="multipart/form-data">
  <div id="header">Step 1 - Preinstallation</div>
  <div id="content">
    <?php if (isset($error['message'])) { ?>
    <div class="warning"><?php echo $error['message']; ?></div>
    <?php } ?>
    <p>1. Please configure your PHP settings to match requirements listed below.</p>
    <table width="100%">
      <tr>
        <th width="35%" align="left">PHP Settings</th>
        <th width="25%" align="left">Current</th>
        <th width="25%" align="left">Required</th>
        <th width="15%" align="left">Status</th>
      </tr>
      <tr>
        <td>PHP Version:</td>
        <td><?php echo phpversion(); ?></td>
        <td>5.0+</td>
        <td><?php echo (phpversion() >= '5.0' ? '<span class="good">Good</span>' : '<span class="bad">Error</span>'); ?></td>
      </tr>
      <tr>
        <td>Register Globals:</td>
        <td><?php echo (ini_get('register_globals') ? 'On' : 'Off'); ?></td>
        <td>Off</td>
        <td><?php echo (!ini_get('register_globals') ? '<span class="good">Good</span>' : '<span class="bad">Error</span>'); ?></td>
      </tr>  
      <tr>
        <td>Magic Quotes GPC:</td>
        <td><?php echo (ini_get('magic_quotes_gpc') ? 'On' : 'Off'); ?></td>
        <td>Off</td>
        <td><?php echo (!ini_get('magic_quotes_gpc') ? '<span class="good">Good</span>' : '<span class="bad">Error</span>'); ?></td>
      </tr>          
      <tr>
        <td>File Uploads:</td>
        <td><?php echo (ini_get('file_uploads') ? 'On' : 'Off'); ?></td>
        <td>On</td>
        <td><?php echo (ini_get('file_uploads') ? '<span class="good">Good</span>' : '<span class="bad">Error</span>'); ?></td>
      </tr>
      <tr>
        <td>Session Auto Start:</td>
        <td><?php echo (ini_get('session.auto_start') ? 'On' : 'Off'); ?></td>
        <td>Off</td>
        <td><?php echo (!ini_get('session.auto_start') ? '<span class="good">Good</span>' : '<span class="bad">Error</span>'); ?></td>
      </tr>
    </table>
    <p>2. Please make sure the extensions listed below are installed.</p>
    <table width="100%">
      <tr>
        <th width="35%" align="left">Extension</th>
        <th width="25%" align="left">Current</th>
        <th width="25%" align="left">Required</th>
        <th width="15%" align="left">Status</th>
      </tr>
      <tr>
        <td>MySQL:</td>
        <td><?php echo (extension_loaded('mysql') ? 'On' : 'Off'); ?></td>
        <td>On</td>
        <td><?php echo (extension_loaded('mysql') ? '<span class="good">Good</span>' : '<span class="bad">Error</span>'); ?></td>
      </tr>
      <tr>
        <td>GD:</td>
        <td><?php echo (extension_loaded('gd') ? 'On' : 'Off'); ?></td>
        <td>On</td>
        <td><?php echo (extension_loaded('gd') ? '<span class="good">Good</span>' : '<span class="bad">Error</span>'); ?></td>
      </tr>
      <tr>
        <td>ZIP:</td>
        <td><?php echo (extension_loaded('zlib') ? 'On' : 'Off'); ?></td>
        <td>On</td>
        <td><?php echo (extension_loaded('zlib') ? '<span class="good">Good</span>' : '<span class="bad">Error</span>'); ?></td>
      </tr>      
    </table>
    <p>3. Please make sure you have set the correct permissions on the files list below.</p>
    <table width="100%">
      <tr>
        <th align="left">File</th>
        <th width="15%" align="left">Status</th>
      </tr>
      <tr>
        <td><?php echo realpath(dirname(__FILE__) . '/../config.php'); ?></td>
        <td><?php echo (is_writable(dirname(__FILE__) . '/../config.php') ? '<span class="good">Writable</span>' : '<span class="bad">Unwritable</span>'); ?></td>
      </tr>
      <tr>
        <td><?php echo realpath(dirname(__FILE__) . '/../admin/config.php'); ?></td>
        <td><?php echo (is_writable(dirname(__FILE__) . '/../admin/config.php') ? '<span class="good">Writable</span>' : '<span class="bad">Unwritable</span>'); ?></td>
      </tr>
    </table>
    <p>4. Please make sure you have set the correct permissions on the directories list below.</p>
    <table width="100%">
      <tr>
        <th align="left">Directory</th>
        <th width="15%" align="left">Status</th>
      </tr>
      <tr>
        <td><?php echo realpath(dirname(__FILE__) . '/../cache') . '/'; ?></td>
        <td><?php echo (is_writable(dirname(__FILE__) . '/../cache') ? '<span class="good">Writable</span>' : '<span class="bad">Unwritable</span>'); ?></td>
      </tr>
      <tr>
        <td><?php echo realpath(dirname(__FILE__) . '/../image') . '/'; ?></td>
        <td><?php echo (is_writable(dirname(__FILE__) . '/../image') ? '<span class="good">Writable</span>' : '<span class="bad">Unwritable</span>'); ?></td>
      </tr>
      <tr>
        <td><?php echo realpath(dirname(__FILE__) . '/../image/cache') . '/'; ?></td>
        <td><?php echo (is_writable(dirname(__FILE__) . '/../image/cache') ? '<span class="good">Writable</span>' : '<span class="bad">Unwritable</span>'); ?></td>
      </tr>
      <tr>
        <td><?php echo realpath(dirname(__FILE__) . '/../download') . '/'; ?></td>
        <td><?php echo (is_writable(dirname(__FILE__) . '/../download') ? '<span class="good">Writable</span>' : '<span class="bad">Unwritable</span>'); ?></td>
      </tr>
    </table>
  </div>
  <div id="footer">
    <input type="submit" value="Continue" />
  </div>
</form>
<?php
	  	$content = ob_get_contents();

      	ob_end_clean();
		break;
	case '2':
		if ($_POST) {
  			$error = array();
  
  			if (!$_POST['db_host']) {
    			$error['db_host'] = '* Host required!';
  			}
  
  			if (!$_POST['db_user']) {
    			$error['db_user'] = '* User required!';
  			}
  
  			if (!$_POST['db_name']) {
    			$error['db_name'] = '* Database Name required!';
  			}
  
  			if (!$error) {
    			if (!$connection = @mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_password'])) {
					$error['message'] = 'Error: Could not connect to the database please make sure the database server, username and password is correct!' . "\n";
				} else {
					if (!@mysql_select_db($_POST['db_name'], $connection)) {
						$error['message'] = 'Error: Database does not exist!' . "\n";
					}			
				}
			}
			
			if (!$error) {
				mysql_query("SET NAMES 'utf8'", $connection);
				mysql_query("SET CHARATER SET utf8", $connection);
				
				$file = dirname(__FILE__) . '/opencart.sql';
			
		    	if ($sql = file($file)) {
	  				$query = '';
	  
	  				foreach($sql as $line) {
        				$tsl = trim($line);
        
						if (($sql != '') && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != '#')) {
          					$query .= $line;
          
		  					if (preg_match('/;\s*$/', $line)) {
            					$result = mysql_query($query, $connection);
          
		    					if (!$result) {
			  						die(mysql_error());
            					}
			
								$query = '';
          					}
        				}
      				}
	  							
					mysql_close($connection);
				} else {
					$error['message'] = 'Error: install SQL file ' . $file . ' does not exist!' . "\n";
				}			
			}
  		    
			if (!$error) {
				$_SESSION['db_host']     = $_POST['db_host'];
				$_SESSION['db_user']     = $_POST['db_user'];
				$_SESSION['db_password'] = $_POST['db_password'];
				$_SESSION['db_name']     = $_POST['db_name'];
				
				header('Location: index.php?step=3');
				exit();
			}
		}
		
		ob_start();
?>
<form action="index.php?step=2" method="post" enctype="multipart/form-data">
  <div id="header">Step 2 - Database</div>
  <div id="content">
    <?php if (isset($error['message'])) { ?>
    <div class="warning"><?php echo $error['message']; ?></div>
    <?php } ?>
    <p>Please enter your database connection details.</p>
    <table>
      <tr>
        <td width="185"><span class="required">*</span>Database Host:</td>
        <td><?php if (isset($_POST['db_host'])) { ?>
          <input type="text" name="db_host" value="<?php echo $_POST['db_host']; ?>" />
          <?php } elseif (isset($_SESSION['db_host'])) { ?>
          <input type="text" name="db_host" value="<?php echo $_SESSION['db_host']; ?>" />
          <?php } else {?>
          <input type="text" name="db_host" value="localhost" />
          <?php } ?>
          <?php if (isset($error['db_host'])) { ?>
          <span class="required"><?php echo $error['db_host']; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span>User:</td>
        <td><input type="text" name="db_user" value="<?php echo (isset($_POST['db_user']) ? $_POST['db_user'] : @$_SESSION['db_user']); ?>" />
          <?php if (isset($error['db_user'])) { ?>
          <span class="required"><?php echo $error['db_user']; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="text" name="db_password" value="<?php echo (isset($_POST['db_password']) ? $_POST['db_password'] : @$_SESSION['db_password']); ?>" />
          <?php if (isset($error['db_password'])) { ?>
          <span class="required"><?php echo $error['db_password']; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span>Database Name:</td>
        <td><input type="text" name="db_name" value="<?php echo (isset($_POST['db_name']) ? $_POST['db_name'] : @$_SESSION['db_name']); ?>" />
          <?php if (isset($error['db_name'])) { ?>
          <span class="required"><?php echo $error['db_name']; ?></span>
          <?php } ?></td>
      </tr>
    </table>
  </div>
  <div id="footer">
    <input type="submit" value="Continue" />
  </div>
</form>
<?php		
		$content = ob_get_contents();

      	ob_end_clean();
		break;
	case '3':
		if ($_POST) {
  			$error = array();
  
  			if (!$_POST['username']) {
    			$error['username'] = '* Username required!';
  			}
  
  			if (!$_POST['password']) {
    			$error['password'] = '* Password required!';
  			}

  			if (!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', @$_POST['email'])) {
    			$error['email'] = '* Invalid E-Mail!';
  			}
						
			$server = $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0 , (strpos($_SERVER['PHP_SELF'], 'install/index.php')));
			
			$config = realpath(dirname(__FILE__) . '/../config.php');

			if (is_writable($config)) {
				$output  = '<?php' . "\n";
				$output .= '// HTTP' . "\n";
				$output .= 'define(\'HTTP_SERVER\', \'http://' . $server . '\');' . "\n";
				$output .= 'define(\'HTTP_IMAGE\', \'http://' . $server . 'image/\');' . "\n\n";

				$output .= '// HTTPS' . "\n";
				$output .= 'define(\'HTTPS_SERVER\', \'\');' . "\n";
				$output .= 'define(\'HTTPS_IMAGE\', \'\');' . "\n\n";

				$output .= '// DIR' . "\n";
				
				$output .= 'define(\'DIR_APPLICATION\', \'' . realpath(dirname(__FILE__) . '/..') . '/catalog/\');' . "\n";
				$output .= 'define(\'DIR_SYSTEM\', \'' . realpath(dirname(__FILE__) . '/..') . '/system/\');' . "\n";
				$output .= 'define(\'DIR_DATABASE\', \'' . realpath(dirname(__FILE__) . '/..') . '/system/database/\');' . "\n";
				$output .= 'define(\'DIR_LANGUAGE\', \'' . realpath(dirname(__FILE__) . '/..') . '/catalog/language/\');' . "\n";
				$output .= 'define(\'DIR_TEMPLATE\', \'' . realpath(dirname(__FILE__) . '/..') . '/catalog/view/theme/\');' . "\n";
				$output .= 'define(\'DIR_CONFIG\', \'' . realpath(dirname(__FILE__) . '/..') . '/system/config/\');' . "\n";
				$output .= 'define(\'DIR_IMAGE\', \'' . realpath(dirname(__FILE__) . '/..') . '/image/\');' . "\n";
				$output .= 'define(\'DIR_CACHE\', \'' . realpath(dirname(__FILE__) . '/..') . '/cache/\');' . "\n";
				$output .= 'define(\'DIR_DOWNLOAD\', \'' . realpath(dirname(__FILE__) . '/..') . '/download/\');' . "\n\n";
				
				$output .= '// DB' . "\n";
				$output .= 'define(\'DB_DRIVER\', \'mysql\');' . "\n";
				$output .= 'define(\'DB_HOSTNAME\', \'' . $_SESSION['db_host'] . '\');' . "\n";
				$output .= 'define(\'DB_USERNAME\', \'' . $_SESSION['db_user'] . '\');' . "\n";
				$output .= 'define(\'DB_PASSWORD\', \'' . $_SESSION['db_password'] . '\');' . "\n";
				$output .= 'define(\'DB_DATABASE\', \'' . $_SESSION['db_name'] . '\');' . "\n";
				$output .= 'define(\'DB_PREFIX\', \'cart_\');' . "\n";
			
				$output .= '?>';				
    			
				$file = fopen($config, 'w');
				
				fwrite($file, $output);
		
    			fclose($file);
			} else {
				$error['message'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . $config . "\n";
			}
			 
			$config = realpath(dirname(__FILE__) . '/../admin/config.php');
			
			if (is_writable($config)) {
				$output  = '<?php' . "\n";
				$output .= '// HTTP' . "\n";
				$output .= 'define(\'HTTP_SERVER\', \'http://' . $server . 'admin/\');' . "\n";
				$output .= 'define(\'HTTP_CATALOG\', \'http://' . $server . '\');' . "\n";
				$output .= 'define(\'HTTP_IMAGE\', \'http://' . $server . 'image/\');' . "\n\n";

				$output .= '// HTTPS' . "\n";
				$output .= 'define(\'HTTPS_SERVER\', \'\');' . "\n";
				$output .= 'define(\'HTTPS_IMAGE\', \'\');' . "\n\n";

				$output .= '// DIR' . "\n";
				
				$output .= 'define(\'DIR_APPLICATION\', \'' . realpath(dirname(__FILE__) . '/..') . '/admin/\');' . "\n";
				$output .= 'define(\'DIR_SYSTEM\', \'' . realpath(dirname(__FILE__) . '/..') . '/system/\');' . "\n";
				$output .= 'define(\'DIR_DATABASE\', \'' . realpath(dirname(__FILE__) . '/..') . '/system/database/\');' . "\n";
				$output .= 'define(\'DIR_LANGUAGE\', \'' . realpath(dirname(__FILE__) . '/..') . '/admin/language/\');' . "\n";
				$output .= 'define(\'DIR_TEMPLATE\', \'' . realpath(dirname(__FILE__) . '/..') . '/admin/view/template/\');' . "\n";
				$output .= 'define(\'DIR_CONFIG\', \'' . realpath(dirname(__FILE__) . '/..') . '/system/config/\');' . "\n";
				$output .= 'define(\'DIR_IMAGE\', \'' . realpath(dirname(__FILE__) . '/..') . '/image/\');' . "\n";
				$output .= 'define(\'DIR_CACHE\', \'' . realpath(dirname(__FILE__) . '/..') . '/cache/\');' . "\n";
				$output .= 'define(\'DIR_DOWNLOAD\', \'' . realpath(dirname(__FILE__) . '/..') . '/download/\');' . "\n";
				$output .= 'define(\'DIR_CATALOG\', \'' . realpath(dirname(__FILE__) . '/..') . '/catalog/\');' . "\n\n";

				$output .= '// DB' . "\n";
				$output .= 'define(\'DB_DRIVER\', \'mysql\');' . "\n";
				$output .= 'define(\'DB_HOSTNAME\', \'' . $_SESSION['db_host'] . '\');' . "\n";
				$output .= 'define(\'DB_USERNAME\', \'' . $_SESSION['db_user'] . '\');' . "\n";
				$output .= 'define(\'DB_PASSWORD\', \'' . $_SESSION['db_password'] . '\');' . "\n";
				$output .= 'define(\'DB_DATABASE\', \'' . $_SESSION['db_name'] . '\');' . "\n";
				$output .= 'define(\'DB_PREFIX\', \'cart_\');' . "\n";
				$output .= '?>';	
				
				$file = fopen($config, 'w');
    			
				fwrite($file, $output);
		
    			fclose($file);						 
			} else {
				$error['message'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . $config . "\n";
			}

    		if (!$connection = @mysql_connect($_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_password'])) {
				$error['message'] = 'Error: Could not connect to the database please make sure the database server, username and password is correct!' . "\n";
			} else {
				if (!@mysql_select_db($_SESSION['db_name'], $connection)) {
					$error['message'] = 'Error: Database does not exist!' . "\n";
				}		
			}
									
			if (!$error) {
				mysql_query("SET CHARATER SET utf8", $connection);
				
				mysql_query("SET @@session.sql_mode = 'MYSQL40'", $connection);
				
				mysql_query("DELETE FROM from user WHERE user_id = '1'");
				
				mysql_query("INSERT INTO `user` SET user_id = '1', user_group_id = '1', username = '" . mysql_real_escape_string($_POST['username']) . "', password = '" . mysql_real_escape_string(md5($_POST['password'])) . "', date_added = NOW()", $connection);

				mysql_query("DELETE FROM `setting` WHERE `key` = 'config_email'", $connection);
				
				mysql_query("INSERT INTO `setting` SET `group` = 'config', `key` = 'config_email', value = '" . mysql_real_escape_string($_POST['email']) . "'", $connection);
								
				mysql_close($connection);
			
				header('Location: index.php?step=4');
				exit();
			}
		}
		
		ob_start();
?>
<form action="index.php?step=3" method="post" enctype="multipart/form-data">
  <div id="header">Step 3 - Administration</div>
  <div id="content">
    <?php if (isset($error['message'])) { ?>
    <div class="warning"><?php echo $error['message']; ?></div>
    <?php } ?>
    <p>Please enter a username and password for the administration.</p>
    <table>
      <tr>
        <td width="185"><span class="required">*</span>Username:</td>
        <td><input type="text" name="username" value="<?php echo (isset($_POST['username']) ? $_POST['username'] : 'admin'); ?>" />
          <?php if (isset($error['username'])) { ?>
          <span class="required"><?php echo $error['username']; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span>Password:</td>
        <td><input type="text" name="password" value="<?php echo @$_POST['password']; ?>" />
          <?php if (isset($error['password'])) { ?>
          <span class="required"><?php echo $error['password']; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span>E-Mail:</td>
        <td><input type="text" name="email" value="<?php echo @$_POST['email']; ?>" />
          <?php if (isset($error['email'])) { ?>
          <span class="required"><?php echo $error['email']; ?></span>
          <?php } ?></td>
      </tr>      
    </table>
  </div>
  <div id="footer">
    <input type="submit" value="Continue" />
  </div>
</form>
<?php	
	  	$content = ob_get_contents();

      	ob_end_clean();
		break;
	case '4':
		$server = substr($_SERVER['PHP_SELF'], 0 , (strpos($_SERVER['PHP_SELF'], 'install/index.php')));
		
		ob_start();
?>
<div id="header">Step 4 - Finished!</div>
<div id="content">
  <div class="warning">Don't forget to delete your installation directory!</div>
  <p>Congratulations! You have successfully installed OpenCart.</p>
</div>
<div id="footer">
  <input type="button" value="Online Shop" onClick="location='<?php echo $server; ?>';" />
  <input type="button" value="Administration" onClick="location='<?php echo $server . 'admin/'; ?>';" />
</div>
<?php	
	  	$content = ob_get_contents();

      	ob_end_clean();
		break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Installation</title>
<base href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0 , (strrpos($_SERVER['PHP_SELF'], "/") + 1)); ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
</head>
<body>
<div id="container"><?php echo $content; ?></div>
</body>
</html>
