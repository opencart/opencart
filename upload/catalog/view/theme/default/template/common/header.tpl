<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<base href="<?php echo $base; ?>" />
<?php if ($icon) { ?>
<link href="image/<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/unitpngfix/unitpngfix.js"></script>
<![endif]-->
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/thickbox/thickbox-compressed.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/thickbox/thickbox.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/tab.js"></script>
</head>
<body>
<div id="container">
  <div id="header">
    <div class="div1">
      <div class="div2"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" /></a></div>
      <div class="div3"><?php echo $language; ?><?php echo $search; ?></div>
    </div>
    <div class="div4">
      <div class="div5"><img src="catalog/view/theme/default/image/icon_home.png" alt="" class="icon" /><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a><img src="catalog/view/theme/default/image/split.png" alt="" class="split" /><img src="catalog/view/theme/default/image/icon_special.png" alt="" class="icon" /><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a>
      <?php if (!$logged) { ?>
      <img src="catalog/view/theme/default/image/split.png" alt="" class="split" /><img src="catalog/view/theme/default/image/icon_login.png" alt="" class="icon" /><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a>
      <?php } else { ?>
      <img src="catalog/view/theme/default/image/split.png" alt="" class="split" /><img src="catalog/view/theme/default/image/icon_logout.png" alt="" class="icon" /><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a>
      <?php } ?>
      <img src="catalog/view/theme/default/image/split.png" alt="" class="split" /><img src="catalog/view/theme/default/image/icon_account.png" alt="" class="icon" /><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></div>
      <div class="div6"><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a><img src="catalog/view/theme/default/image/icon_checkout.png" alt="" class="icon" /><img src="catalog/view/theme/default/image/split.png" alt="" class="split" /><a href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a><img src="catalog/view/theme/default/image/icon_basket.png" alt="" class="icon" /></div>
    </div>
    <div id="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
    </div>  
  </div>