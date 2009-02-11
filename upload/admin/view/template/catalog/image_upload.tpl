<?php echo '<?xml version="1.0"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/upload.css" />
<script type="text/javascript" src="view/javascript/jquery/jquery-1.3.min.js"></script>
</head>
<body>
<h1><?php echo $title; ?></h1>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div id="container">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="upload">
    <span class="required">*</span> <?php echo $entry_title; ?><br />
    <?php foreach ($languages as $language) { ?>
    <input name="image_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo @$image_description[$language['language_id']]['title']; ?>" />
    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@$error_title[$language['language_id']]) { ?>
    <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
    <?php } ?>
    <br />
    <?php } ?>
    <span class="required">*</span> <?php echo $entry_filename; ?><br />
    <input type="file" name="image" value="<?php echo $filename; ?>" />
    <br />
    <?php if ($error_file) { ?>
    <span class="error"><?php echo $error_file; ?></span>
    <?php } ?>
    <br />
    <a onclick="$('#upload').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a class="button" onclick="parent.modalWindow.close();" style="margin-left: 5px;"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_close; ?></span><span class="button_right"></span></a>
  </form>
</div>
<script type="text/javascript"><!--
parent.$('.image').load('index.php?route=catalog/image/image');
//--></script>
</body>
</html>
