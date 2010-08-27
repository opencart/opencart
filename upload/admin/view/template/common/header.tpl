<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/ui.all.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="view/javascript/jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.js"></script>
<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>
<script type="text/javascript" src="view/javascript/jquery/tab.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript">
//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------
$(document).ready(function(){
	
    // Confirm Delete
    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
    	
    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $(".scrollbox").each(function(i) {
    	$(this).attr('id', 'scrollbox_' + i);
		sbox = '#' + $(this).attr('id');
    	$(this).after('<span><a onclick="$(\'' + sbox + ' :checkbox\').attr(\'checked\', \'checked\');"><u><?php echo $text_select_all; ?></u></a> / <a onclick="$(\'' + sbox + ' :checkbox\').attr(\'checked\', \'\');"><u><?php echo $text_unselect_all; ?></u></a></span>');
	});
});
</script>
</head>
<body>
<div id="container">
<div id="header">
  <div class="div1"><img src="view/image/logo.png" title="<?php echo $heading_title; ?>" onclick="location = '<?php echo $home; ?>'" /></div>
  <?php if ($logged) { ?>
  <div class="div2"><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
  <?php } ?>
</div>
<?php if ($logged) { ?>
<div id="menu">
  <ul class="nav left" style="display: none;">
    <li id="dashboard"><a href="<?php echo $home; ?>" class="top"><?php echo $text_dashboard; ?></a></li>
    <li id="catalog"><a class="top"><?php echo $text_catalog; ?></a>
      <ul>
        <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
        <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
        <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
        <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
        <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
        <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
      </ul>
    </li>
    <li id="extension"><a class="top"><?php echo $text_extension; ?></a>
      <ul>
        <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
        <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
        <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
        <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
        <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
      </ul>
    </li>
    <li id="sale"><a class="top"><?php echo $text_sale; ?></a>
      <ul>
        <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
        <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
        <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
        <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      </ul>
    </li>
    <li id="system"><a class="top"><?php echo $text_system; ?></a>
      <ul>
        <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
        <li><a class="parent"><?php echo $text_users; ?></a>
          <ul>
            <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
            <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
          </ul>
        </li>
        <li><a class="parent"><?php echo $text_localisation; ?></a>
          <ul>
            <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
            <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
            <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
            <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
            <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
            <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
            <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
            <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
            <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
            <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
          </ul>
        </li>
        <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
        <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
      </ul>
    </li>
    <li id="reports"><a class="top"><?php echo $text_reports; ?></a>
      <ul>
        <li><a href="<?php echo $report_sale; ?>"><?php echo $text_report_sale; ?></a></li>
        <li><a href="<?php echo $report_viewed; ?>"><?php echo $text_report_viewed; ?></a></li>
        <li><a href="<?php echo $report_purchased; ?>"><?php echo $text_report_purchased; ?></a></li>
      </ul>
    </li>
    <li id="help"><a class="top"><?php echo $text_help; ?></a>
      <ul>
        <li><a onclick="window.open('http://www.opencart.com');"><?php echo $text_opencart; ?></a></li>
        <li><a onclick="window.open('http://www.opencart.com/index.php?route=documentation/introduction');"><?php echo $text_documentation; ?></a></li>
        <li><a onclick="window.open('http://forum.opencart.com');"><?php echo $text_support; ?></a></li>
      </ul>
    </li>
  </ul>
  <ul class="nav right">
    <li id="store"><a onclick="window.open('<?php echo $store; ?>');" class="top"><?php echo $text_front; ?></a>
      <ul>
        <?php foreach ($stores as $stores) { ?>
        <li><a onclick="window.open('<?php echo $stores['href']; ?>');"><?php echo $stores['name']; ?></a></li>
        <?php } ?>
      </ul>
    </li>
    <li id="store"><a class="top" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
  </ul>
  <script type="text/javascript"><!--
$(document).ready(function() {
	$('.nav').superfish({
		hoverClass	 : 'sfHover',
		pathClass	 : 'overideThisToUse',
		delay		 : 0,
		animation	 : {height: 'show'},
		speed		 : 'normal',
		autoArrows   : false,
		dropShadows  : false, 
		disableHI	 : false, /* set to true to disable hoverIntent detection */
		onInit		 : function(){},
		onBeforeShow : function(){},
		onShow		 : function(){},
		onHide		 : function(){}
	});
	
	$('.nav').css('display', 'block');
});
//--></script>
  <script type="text/javascript"><!-- 
function getURLVar(urlVarName) {
	var urlHalves = String(document.location).toLowerCase().split('?');
	var urlVarValue = '';
	
	if (urlHalves[1]) {
		var urlVars = urlHalves[1].split('&');

		for (var i = 0; i <= (urlVars.length); i++) {
			if (urlVars[i]) {
				var urlVarPair = urlVars[i].split('=');
				
				if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
					urlVarValue = urlVarPair[1];
				}
			}
		}
	}
	
	return urlVarValue;
} 

$(document).ready(function() {
	route = getURLVar('route');
	
	if (!route) {
		$('#dashboard').addClass('selected');
	} else {
		part = route.split('/');
		
		url = part[0];
		
		if (part[1]) {
			url += '/' + part[1];
		}
		
		$('a[href*=\'' + url + '\']').parents('li[id]').addClass('selected');
	}
});
//--></script>
</div>
<?php } ?>
<div id="content">
<?php if ($breadcrumbs) { ?>
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php } ?>
<?php if (isset($install) && $install) { ?>
<div class="warning"><?php echo $error_install; ?></div>
<?php } ?>