<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" type="text/css" rel="stylesheet">
<script src="catalog/view/javascript/jquery/jquery-2.0.0.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
<link href="catalog/view/javascript/bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php echo $google_analytics; ?>
</head>
<body>
<div class="topbar navbar navbar-static-top">
  <div class="navbar-inner">
    <div class="container">
      <ul class="nav">
        <li><?php echo $currency; ?></li>
        <li><?php echo $language; ?></li>
      </ul>
      <ul class="nav pull-right">
        <li><a href="tel:<?php echo $telephone; ?>"><i class="icon-phone"></i> <span class="hidden-phone hidden-tablet"><?php echo $telephone; ?></span></a></li>
        <li><a href="<?php echo $account; ?>"><i class="icon-user"></i> <span class="hidden-phone hidden-tablet"><?php echo $text_account; ?></span></a></li>
        <li><a href="<?php echo $wishlist; ?>" id="wishlist-total"> <i class="icon-heart"></i> <span class="hidden-phone hidden-tablet"><?php echo $text_wishlist; ?></span></a></li>
        <li><a href="<?php echo $shopping_cart; ?>"><i class="icon-shopping-cart"></i> <span class="hidden-phone hidden-tablet"><?php echo $text_shopping_cart; ?></span></a></li>
        <li><a href="<?php echo $checkout; ?>"> <i class="icon-share-alt"></i> <span class="hidden-phone hidden-tablet"><?php echo $text_checkout; ?></span></a></li>
      </ul>
    </div>
  </div>
</div>
<div class="container">
<header>
  <div class="row">
    <div class="span4">
      <div class="logo">
        <?php if ($logo) { ?>
        <a href="<?php echo $home; ?>" class="logo"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
        <?php } else { ?>
        <h1><a href="<?php echo $home; ?>" class="logo"><?php echo $name; ?></a></h1>
        <?php } ?>
      </div>
    </div>
    <div class="span8">
      <div class="row">
        <div class="span5">
          <div id="search" class="input-append">
            <input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
            <div class="btn button-search"><i class="icon-search"></i></div>
          </div>
        </div>
        <div class="span3"><?php echo $cart; ?></div>
      </div>
    </div>
  </div>
  <?php if ($categories) { ?>
  <div class="main-navbar navbar navbar-inverse">
    <div class="navbar-inner">
      <div class="container"><span class="categories hidden-desktop"><?php echo $text_category; ?></span> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a>
        <div class="nav-collapse collapse">
          <ul class="nav">
            <?php foreach ($categories as $category) { ?>
            <?php if ($category['children']) { ?>
            <li class="dropdown"> <a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
              <div class="dropdown-menu animated fadeIn">
                <div class="dropdown-inner">
                  <?php for ($i = 0; $i < count($category['children']);) { ?>
                  <ul class="unstyled">
                    <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
                    <?php for (; $i < $j; $i++) { ?>
                    <?php if (isset($category['children'][$i])) { ?>
                    <li> <a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
                    <?php } ?>
                    <?php } ?>
                  </ul>
                  <?php } ?>
                </div>
                <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a></div>
            </li>
            <?php } else { ?>
            <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
</header>
