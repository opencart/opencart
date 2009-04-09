<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.3.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/thickbox/thickbox-compressed.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/thickbox/thickbox.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/tab.js"></script>
</head>
<body>
<div id="container">
  <div id="header"><?php echo $header; ?></div>
  <div id="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div id="column_left">
    <?php foreach ($modules as $module) { ?>
    <?php if ($module['position'] == 'left') { ?>
    <?php echo ${$module['code']}; ?>
    <?php } ?>
    <?php } ?>
  </div>
  <div id="column_right">
    <?php foreach ($modules as $module) { ?>
    <?php if ($module['position'] == 'right') { ?>
    <?php echo ${$module['code']}; ?>
    <?php } ?>
    <?php } ?>
  </div>
  <div id="content"><?php echo $content; ?></div>
  <div id="footer"><?php echo $footer; ?></div>
</div>
</body>
</html>