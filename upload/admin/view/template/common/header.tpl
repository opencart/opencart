<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script type="text/javascript" src="view/javascript/jquery/translit.js"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<?php foreach ($styles as $style) { ?>
<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
</head>
<body>
<div id="container">
<header id="header" class="navbar navbar-static-top">
  <div class="navbar-header">
    <?php if ($logged) { ?>
    <a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
    <?php } ?>
    <a href="<?php echo $home; ?>" class="navbar-brand"><img src="view/image/logo.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" /></a></div>
  <?php if ($logged) { ?>
   <ul class="nav pull-left">
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"  title="<?php echo $text_new; ?>"><i class="fa fa-plus fa-lg"></i> <span class="header-item"><?php echo $text_new; ?></span></a>
      <ul class="dropdown-menu dropdown-menu-left alerts-dropdown">
        <li><a href="<?php echo $new_product; ?>" style="display: block; overflow: auto;"><?php echo $text_new_product; ?></a></li>
        <li><a href="<?php echo $new_category; ?>" style="display: block; overflow: auto;"><?php echo $text_new_category; ?></a></li>
        <li><a href="<?php echo $new_manufacturer; ?>" style="display: block; overflow: auto;"><?php echo $text_new_manufacturer; ?></a></li>
        <li><a href="<?php echo $new_customer; ?>" style="display: block; overflow: auto;"><?php echo $text_new_customer; ?></a></li>
        <li><a href="<?php echo $new_download; ?>" style="display: block; overflow: auto;"><?php echo $text_new_download; ?></a></li>
      </ul>
    </li> 
  </ul>
  <div id="ocpro-search-div" class="col-sm-3 col-md-3 pull-left">
    <?php echo $search; ?>
  </div>
  <ul class="nav pull-right">
  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-refresh fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li><a href="<?php echo $clearcache; ?>"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;<?php echo $button_clearcache; ?></a></li>
        <li><a href="<?php echo $clearsystemcache; ?>"><i class="fa fa-cogs"></i>&nbsp;&nbsp;<?php echo $button_clearsystemcache; ?></a></li>
        <li><a href="<?php echo $clearallcache; ?>"><i class="fa fa-refresh"></i>&nbsp;&nbsp;<?php echo $button_clearallcache; ?></a></li>
      </ul>
    </li>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><?php if($alerts > 0) { ?><span class="label label-danger pull-left"><?php echo $alerts; ?></span><?php } ?> <i class="fa fa-bell fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header"><?php echo $text_order; ?></li>
        <li><a href="<?php echo $processing_status; ?>" style="display: block; overflow: auto;"><span class="label label-warning pull-right"><?php echo $processing_status_total; ?></span><?php echo $text_processing_status; ?></a></li>
        <li><a href="<?php echo $complete_status; ?>"><span class="label label-success pull-right"><?php echo $complete_status_total; ?></span><?php echo $text_complete_status; ?></a></li>
        <li><a href="<?php echo $return; ?>"><span class="label label-danger pull-right"><?php echo $return_total; ?></span><?php echo $text_return; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_customer; ?></li>
        <li><a href="<?php echo $online; ?>"><span class="label label-success pull-right"><?php echo $online_total; ?></span><?php echo $text_online; ?></a></li>
        <li><a href="<?php echo $customer_approval; ?>"><span class="label label-danger pull-right"><?php echo $customer_total; ?></span><?php echo $text_approval; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_product; ?></li>
        <li><a href="<?php echo $product; ?>"><span class="label label-danger pull-right"><?php echo $product_total; ?></span><?php echo $text_stock; ?></a></li>
        <li><a href="<?php echo $review; ?>"><span class="label label-danger pull-right"><?php echo $review_total; ?></span><?php echo $text_review; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_affiliate; ?></li>
        <li><a href="<?php echo $affiliate_approval; ?>"><span class="label label-danger pull-right"><?php echo $affiliate_total; ?></span><?php echo $text_approval; ?></a></li>
      </ul>
    </li>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><?php echo $text_store; ?></li>
        <?php foreach ($stores as $store) { ?>
        <li><a href="<?php echo $store['href']; ?>" target="_blank"><?php echo $store['name']; ?></a></li>
        <?php } ?>
      </ul>
    </li>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-life-ring fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><?php echo $text_help; ?></li>
        <li><a href="http://opencart.pro/blog/" target="_blank"><?php echo $text_homepage; ?></a></li>
        <li><a href="http://opencart.pro/wiki/" target="_blank"><?php echo $text_documentation; ?></a></li>
        <li><a href="http://forum.opencart.pro/" target="_blank"><?php echo $text_support; ?></a></li>
      </ul>
    </li>
    <li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fa fa-sign-out fa-lg"></i></a></li>
  </ul>
  <?php } ?>
</header>
