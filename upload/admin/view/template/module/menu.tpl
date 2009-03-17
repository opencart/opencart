<ul id="nav" style="display: none;">
  <li id="admin"><a class="top"><?php echo $text_admin; ?></a>
    <ul>
      <li><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a></li>
      <li><a href="<?php echo $shop; ?>"><?php echo $text_shop; ?></a></li>
      <li><a class="parent"><?php echo $text_configuration; ?></a>
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
              <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
        </ul>
      </li>
      <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
    </ul>
  </li>
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
      <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
      <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
      <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
    </ul>
  </li>
  <li id="customers"><a class="top"><?php echo $text_customers; ?></a>
    <ul>
      <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
      <li><a href="<?php echo $mail; ?>"><?php echo $text_mail; ?></a></li>
    </ul>
  </li>
  <li id="reports"><a class="top"><?php echo $text_reports; ?></a>
    <ul>
      <li><a href="<?php echo $report_sale; ?>"><?php echo $text_report_sale; ?></a></li>
      <li><a href="<?php echo $report_viewed; ?>"><?php echo $text_report_viewed; ?></a></li>
      <li><a href="<?php echo $report_purchased; ?>"><?php echo $text_report_purchased; ?></a></li>
    </ul>
  </li>
</ul>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#nav').superfish({
		hoverClass	 : 'sfHover',
		pathClass	 : 'overideThisToUse',
		delay		 : 0,
		animation	 : {opacity: 'show', height: 'show'},
		speed		 : 'normal',
		autoArrows   : false,
		dropShadows  : false, 
		disableHI	 : false, /* set to true to disable hoverIntent detection */
		onInit		 : function(){},
		onBeforeShow : function(){},
		onShow		 : function(){},
		onHide		 : function(){}
	});
	
	$('#nav').css('display', 'block');
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
		$('#admin').addClass('selected');
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
