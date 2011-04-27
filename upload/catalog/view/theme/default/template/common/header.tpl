<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.9.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.9.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/thickbox/thickbox-compressed.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/thickbox/thickbox.css" />
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script>
DD_belatedPNG.fix('img, #header .div3 a, #content .left, #content .right, .box .top');
</script>
<![endif]-->
</head>
<body>
<?php foreach ($domains as $domain) { ?>
<img src="<?php echo $domain; ?>" style="display: none;" />
<?php } ?>
<div id="top">
  <div>
    <div id="links"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="bookmark(document.location, '<?php echo addslashes($title); ?>');"><?php echo $text_bookmark; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $special; ?>"><?php echo $text_special; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $wishlist; ?>" id="wishlist_total"><?php echo $text_wishlist; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></div>
    <div id="currency">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <?php echo $entry_currency; ?>
        <select name="currency_code" onchange="$(this).parent().submit();">
          <?php foreach ($currencies as $currency) { ?>
          <?php if ($currency['code'] == $currency_code) { ?>
          <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
          <?php } else { ?>
          <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
      </form>
    </div>
    <div id="language">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <?php echo $entry_language; ?>
        <?php foreach ($languages as $language) { ?>
        &nbsp;<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>').submit(); $(this).parent().submit();"/>
        <?php } ?>
        <input type="hidden" name="language_code" value="" />
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
      </form>
    </div>
  </div>
</div>
<div id="container">
<div id="header">
  <?php if ($logo) { ?>
  <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
  <?php } ?>
  <div id="cart">
    <div class="heading">
      <h4><?php echo $text_cart; ?></h4>
      <a><span id="cart_total"><?php echo $text_items; ?></span></a></div>
    <div class="content"></div>
  </div>
  <div id="search">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <?php if ($filter_name) { ?>
      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
      <?php } else { ?>
      <input type="text" name="filter_name" value="<?php echo $text_search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" />
      <?php } ?>
    </div>
  </div>
  <div id="welcome">
    <?php if (!$logged) { ?>
    <?php echo $text_welcome; ?>
    <?php } else { ?>
    <?php echo $text_logged; ?>
    <?php } ?>
  </div>
  <div id="menu"><a id="tab-home" href="<?php echo $home; ?>"><?php echo $text_home; ?></a>
    <?php if (!$logged) { ?>
    <a id="tab-login" href="<?php echo $login; ?>"><?php echo $text_login; ?></a>
    <?php } else { ?>
    <a id="tab-login" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a>
    <?php } ?>
    <a id="tab-account" href="<?php echo $account; ?>"><?php echo $text_account; ?></a> <a id="tab-cart" href="<?php echo $cart; ?>"><span><?php echo $text_basket; ?></span></a> <a id="tab-checkout" href="<?php echo $checkout; ?>"><span><?php echo $text_checkout; ?></span></a></div>
</div>
