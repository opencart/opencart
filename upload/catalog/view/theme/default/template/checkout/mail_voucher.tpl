<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
<style type="text/css">
#container {
}
</style>
</head>
<body>
<div>
  <div id="container"><a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>"><img src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" /></a></div>
  <div><?php echo $mail_greeting; ?> <?php echo $mail_from; ?> <?php echo $mail_redeem; ?> <?php echo $mail_message; ?> <?php echo $message; ?> <?php echo $mail_problem; ?> <?php echo $mail_footer; ?> </div>
</div>
</body>
</html>
