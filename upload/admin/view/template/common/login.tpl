<?php echo '<?xml version="1.0"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $charset; ?>">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<script type="text/javascript" src="view/javascript/jquery/jquery-1.3.2.min.js"></script>
</head>
<body>
<div id="header">
  <div class="div1">
    <div class="div2"><?php echo $text_heading; ?></div>
  </div>
</div>
<div id="menu"></div>
<div id="login">
  <div class="div1"><?php echo $text_login; ?></div>
  <div class="div2">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table>
        <tr>
          <td align="center" rowspan="3"><img src="view/image/login.png" alt="<?php echo $text_login; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_username; ?><br />
            <input type="text" name="username" style="margin-top: 4px;" />
            <br />
            <br />
            <?php echo $entry_password; ?><br />
            <input type="password" name="password" style="margin-top: 4px;" /></td>
        </tr>
        <tr>
          <td align="right"><a onclick="$('#form').submit();" class="button"><span class="button_left button_login"></span><span class="button_middle"><?php echo $button_login; ?></span><span class="button_right"></span></a></td>
        </tr>
      </table>
    </form>
    <script type="text/javascript"><!--
	$('#form input').keydown(function(e) {
		if (e.keyCode == 13) {
			$('#form').submit();
		}
	});
	//--></script>
  </div>
  <div class="div3"></div>
</div>
</body>
</html>
