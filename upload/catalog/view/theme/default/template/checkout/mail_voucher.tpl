<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
<style type="text/css">
body {
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
}
body, td, th, input, textarea, select, a {
	font-size: 12px;
}
p {
	margin-top: 0px;
	margin-bottom: 20px;
}
a, a:visited, a b {
	color: #378DC1;
	text-decoration: underline;
	cursor: pointer;
}
a:hover {
	text-decoration: none;
}
a img {
	border: none;
}
#container {
	width: 680px;
}
#logo {
	margin-bottom: 20px;
}
table.list {
	border-collapse: collapse;
	width: 100%;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;
	margin-bottom: 20px;
}
table.list td {
	border-right: 1px solid #DDDDDD;
	border-bottom: 1px solid #DDDDDD;
}
table.list thead td {
	background-color: #EFEFEF;
	padding: 0px 5px;
}
table.list thead td a, .list thead td {
	text-decoration: none;
	color: #222222;
	font-weight: bold;
}
table.list tbody td a {
	text-decoration: underline;
}
table.list tbody td {
	vertical-align: top;
	padding: 0px 5px;
}
table.list .left {
	text-align: left;
	padding: 7px;
}
table.list .right {
	text-align: right;
	padding: 7px;
}
table.list .center {
	text-align: center;
	padding: 7px;
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
