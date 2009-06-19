<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OpenCart - Installation</title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
</head>
<body>
<div id="container">
  <div id="header"><img src="view/image/logo.png" alt="OpenCart" title="OpenCart" /></div>
  <div id="column">
    <div id="column_top"></div>
    <div id="column_middle">
      <ol>
        <?php if (!isset($_GET['step']) || ((isset($_GET['step'])) && ($_GET['step'] == 1))) { ?>
        <li><b>Pre-Installation</b></li>
        <?php } else { ?>
        <li>Pre-Installation</li>
        <?php } ?>
        <?php if ((isset($_GET['step'])) && ($_GET['step'] == 2)) { ?>
        <li><b>Configuration</b></li>
        <?php } else { ?>
        <li>Configuration</li>
        <?php } ?>
        <?php if ((isset($_GET['step'])) && ($_GET['step'] == 3)) { ?>
        <li><b>Finished</b></li>
        <?php } else { ?>
        <li>Finished</li>
        <?php } ?>
      </ol>
    </div>
    <div id="column_bottom"></div>
  </div>
  <div id="content">
    <div id="content_top"></div>
    <div id="content_middle"><?php echo $content; ?></div>
    <div id="content_bottom"></div>
  </div>
  <div id="footer"><a onclick="window.open('http://www.opencart.com');">Project Homepage</a>|<a onclick="window.open('http://wiki.opencart.com');">Documentation</a>|<a onclick="window.open('http://forum.opencart.com');">Support Forums</a></div>
</div>
</body>
</html>
