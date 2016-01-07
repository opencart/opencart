<?php $access = $user_group_info["permission"]["access"]; ?>
<?php 
	class _HelperMenu {
		protected $_access = array ();
		
		public function __construct($access)
		{
			$this->_access = $access;
		}
		
		public function renderLink ($href, $text, $route = NULL) {
			if($route) { 
			} else {
				$routeOffset = strpos($href, "route=") + 6;
				$route = substr($href, $routeOffset, strpos($href, "&amp")-$routeOffset);
			}
			
			if(is_array($this->_access) 
				&& in_array($route, $this->_access)) {
				$return = "<li><a href='{$href}'>{$text}</a></li>";
			} else {
				$return = NULL;
			}
			
			return $return;
		}
		
		public function renderLinksWithParent ($links, $parent)
		{
			$render = array ();
			
			foreach ($links as $link) {
				list($href, $text) = $link;
				$renderedLink = $this->renderLink($href, $text);
				if($renderedLink) {
					$render [] = $renderedLink;
				}
			}
			
			if(count($render) > 0) {
				$return = "<li><a class='parent'>{$parent}</a><ul>" . implode("\n", $render) . "</ul></li>";
			} else {
				$return = NULL;
			}
			
			return $return;
		}
	}
	
	$menu = new _HelperMenu ($access);
?>
<ul id="menu">
  <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
  <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
    <ul>
      <?php echo $menu->renderLink($category, $text_category) ?>
	  <?php echo $menu->renderLink($product, $text_product) ?>
	  <?php echo $menu->renderLink($recurring, $text_recurring) ?>
	  <?php echo $menu->renderLink($filter, $text_filter) ?>
      
	  <?php echo $menu->renderLinksWithParent( 
				array(
					array ($attribute, $text_attribute),
					array ($attribute_group, $text_attribute_group),
				), $text_attribute ); ?>
				
      <?php echo $menu->renderLink($option, $text_option) ?>
      <?php echo $menu->renderLink($manufacturer, $text_manufacturer) ?>
      <?php echo $menu->renderLink($download, $text_download) ?>
      <?php echo $menu->renderLink($review, $text_review)?>
      <?php echo $menu->renderLink($information, $text_information) ?>
    </ul>
  </li>
  <li id="extension"><a class="parent"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_extension; ?></span></a>
    <ul>
	  <?php echo $menu->renderLink($installer, $text_installer) ?>
      <?php echo $menu->renderLink($modification, $text_modification) ?>
	  <?php echo $menu->renderLink($theme, $text_theme) ?>
	  <?php echo $menu->renderLink($analytics, $text_analytics) ?>
	  <?php echo $menu->renderLink($captcha, $text_captcha) ?>
	  <?php echo $menu->renderLink($feed, $text_feed) ?>
	  <?php echo $menu->renderLink($fraud, $text_fraud) ?>
      <?php echo $menu->renderLink($module, $text_module) ?>
	  <?php echo $menu->renderLink($payment, $text_payment) ?>
      <?php echo $menu->renderLink($shipping, $text_shipping) ?>
      <?php echo $menu->renderLink($total, $text_total) ?>

      <?php if ($openbay_show_menu == 1) { ?>
      <li><a class="parent"><?php echo $text_openbay_extension; ?></a>
        <ul>
          <?php echo $menu->renderLink($openbay_link_extension, $text_openbay_dashboard) ?>
          <?php echo $menu->renderLink($openbay_link_orders, $text_openbay_orders) ?>
          <?php echo $menu->renderLink($openbay_link_items, $text_openbay_items) ?>
          <?php if ($openbay_markets['ebay'] == 1) { ?>
			  <?php echo $menu->renderLinksWithParent( 
					array(
						array ($openbay_link_ebay, $text_openbay_dashboard),
						array ($openbay_link_ebay_settings, $text_openbay_settings),
						array ($openbay_link_ebay_links, $text_openbay_links),
						array ($openbay_link_ebay_orderimport, $text_openbay_order_import),
					), $text_openbay_ebay ); ?>
			  <?php } ?>
          <?php } ?>
          <?php if ($openbay_markets['amazon'] == 1) { ?>
           <?php echo $menu->renderLinksWithParent( 
				array(
					array ($openbay_link_amazon, $text_openbay_dashboard),
					array ($openbay_link_amazon_settings, $text_openbay_settings),
					array ($openbay_link_amazon_links, $text_openbay_links),
				), $text_openbay_amazon ); ?>
          <?php } ?>
          <?php if ($openbay_markets['amazonus'] == 1) { ?>
          <?php echo $menu->renderLinksWithParent( 
				array(
					array ($openbay_link_amazonus, $text_openbay_dashboard),
					array ($openbay_link_amazonus_settings, $text_openbay_settings),
					array ($openbay_link_amazonus_links, $text_openbay_links),
				), $text_openbay_amazonus ); ?>
          <?php } ?>
          <?php } ?>
          <?php if ($openbay_markets['etsy'] == 1) { ?>
          <li><a class="parent"><?php echo $text_openbay_etsy; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_etsy; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_etsy_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
              <li><a href="<?php echo $openbay_link_etsy_links; ?>"><?php echo $text_openbay_links; ?></a></li>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <li id="design"><a class="parent"><i class="fa fa-television fa-fw"></i> <span><?php echo $text_design; ?></span></a>
    <ul>
      <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>
      <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
    </ul>
  </li>
  <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
    <ul>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $order_recurring; ?>"><?php echo $text_order_recurring; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a class="parent"><?php echo $text_voucher; ?></a>
        <ul>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_paypal ?></a>
        <ul>
          <li><a href="<?php echo $paypal_search ?>"><?php echo $text_paypal_search ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="customer"><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_customer; ?></span></a>
    <ul>
      <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
      <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
      <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
    </ul>
  </li>
  <li><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_marketing; ?></span></a>
    <ul>
      <li><a href="<?php echo $marketing; ?>"><?php echo $text_marketing; ?></a></li>
      <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
    </ul>
  </li>
  <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
    <ul>
      <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
      <li><a class="parent"><?php echo $text_users; ?></a>
        <ul>
          <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
          <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
          <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
          <li><a href="<?php echo $location; ?>"><?php echo $text_location; ?></a></li>
          <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
          <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
          <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
          <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
          <li><a class="parent"><?php echo $text_return; ?></a>
            <ul>
              <li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
              <li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
              <li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
          <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
          <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
          <li><a class="parent"><?php echo $text_tax; ?></a>
            <ul>
              <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
              <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
          <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_tools; ?></a>
        <ul>
          <li><a href="<?php echo $upload; ?>"><?php echo $text_upload; ?></a></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
    <ul>
      <li><a class="parent"><?php echo $text_sale; ?></a>
        <ul>
          <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
          <li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
          <li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
          <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
          <li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_product; ?></a>
        <ul>
          <li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
          <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_customer; ?></a>
        <ul>
          <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
          <li><a href="<?php echo $report_customer_activity; ?>"><?php echo $text_report_customer_activity; ?></a></li>
          <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
          <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
          <li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_marketing; ?></a>
        <ul>
          <li><a href="<?php echo $report_marketing; ?>"><?php echo $text_marketing; ?></a></li>
          <li><a href="<?php echo $report_affiliate; ?>"><?php echo $text_report_affiliate; ?></a></li>
          <li><a href="<?php echo $report_affiliate_activity; ?>"><?php echo $text_report_affiliate_activity; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
</ul>
