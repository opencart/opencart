<ul id="menu">
  <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-home fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
  <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
    <ul>
      <li><a href="<?php echo $category; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_category; ?></a></li>
      <li><a href="<?php echo $product; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_product; ?></a></li>
      <li><a href="<?php echo $recurring; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_recurring; ?></a></li>
      <li><a href="<?php echo $filter; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_filter; ?></a></li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_attribute; ?></a>
        <ul>
          <li><a href="<?php echo $attribute; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_attribute; ?></a></li>
          <li><a href="<?php echo $attribute_group; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_attribute_group; ?></a></li>
        </ul>
      </li>
      <li><a href="<?php echo $option; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_option; ?></a></li>
      <li><a href="<?php echo $manufacturer; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_manufacturer; ?></a></li>
      <li><a href="<?php echo $download; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_download; ?></a></li>
      <li><a href="<?php echo $review; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_review; ?></a></li>
      <li><a href="<?php echo $information; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_information; ?></a></li>
    </ul>
  </li>
  <li id="extension"><a class="parent"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_extension; ?></span></a>
    <ul>
      <li><a href="<?php echo $installer; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_installer; ?></a></li>
      <li><a href="<?php echo $modification; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_modification; ?></a></li>
      <li><a href="<?php echo $module; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_module; ?></a></li>
      <li><a href="<?php echo $shipping; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_shipping; ?></a></li>
      <li><a href="<?php echo $payment; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_payment; ?></a></li>
      <li><a href="<?php echo $total; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_total; ?></a></li>
      <li><a href="<?php echo $feed; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_feed; ?></a></li>
      <?php if ($openbay_show_menu == 1) { ?>
      <li><a class="parent"><?php echo $text_openbay_extension; ?></a>
        <ul>
          <li><a href="<?php echo $openbay_link_extension; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_dashboard; ?></a></li>
          <li><a href="<?php echo $openbay_link_orders; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_orders; ?></a></li>
          <li><a href="<?php echo $openbay_link_items; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_items; ?></a></li>
          <?php if ($openbay_markets['ebay'] == 1) { ?>
          <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_ebay; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_ebay; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_ebay_settings; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_settings; ?></a></li>
              <li><a href="<?php echo $openbay_link_ebay_links; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_links; ?></a></li>
              <li><a href="<?php echo $openbay_link_ebay_orderimport; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_order_import; ?></a></li>
            </ul>
          </li>
          <?php } ?>
          <?php if ($openbay_markets['amazon'] == 1) { ?>
          <li><a class="parent"><?php echo $text_openbay_amazon; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_amazon; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_amazon_settings; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_settings; ?></a></li>
              <li><a href="<?php echo $openbay_link_amazon_links; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_links; ?></a></li>
            </ul>
          </li>
          <?php } ?>
          <?php if ($openbay_markets['amazonus'] == 1) { ?>
          <li><a class="parent"><?php echo $text_openbay_amazonus; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_amazonus; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_amazonus_settings; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_settings; ?></a></li>
              <li><a href="<?php echo $openbay_link_amazonus_links; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_openbay_links; ?></a></li>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
    <ul>
      <li><a href="<?php echo $order; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $order_recurring; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_order_recurring; ?></a></li>
      <li><a href="<?php echo $return; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_return; ?></a></li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_customer; ?></a>
        <ul>
          <li><a href="<?php echo $customer; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_customer; ?></a></li>
          <li><a href="<?php echo $customer_group; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_customer_group; ?></a></li>
          <li><a href="<?php echo $custom_field; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_custom_field; ?></a></li>
          <li><a href="<?php echo $customer_ban_ip; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_customer_ban_ip; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_voucher; ?></a>
        <ul>
          <li><a href="<?php echo $voucher; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $voucher_theme; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_voucher_theme; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_paypal ?></a>
        <ul>
          <li><a href="<?php echo $paypal_search ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_paypal_search ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_marketing; ?></span></a>
    <ul>
      <li><a href="<?php echo $marketing; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_marketing; ?></a></li>
      <li><a href="<?php echo $affiliate; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $coupon; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_coupon; ?></a></li>
      <li><a href="<?php echo $contact; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_contact; ?></a></li>
    </ul>
  </li>
  <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
    <ul>
      <li><a href="<?php echo $setting; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_setting; ?></a></li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_design; ?></a>
        <ul>
          <li><a href="<?php echo $layout; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_layout; ?></a></li>
          <li><a href="<?php echo $banner; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_banner; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_users; ?></a>
        <ul>
          <li><a href="<?php echo $user; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_user; ?></a></li>
          <li><a href="<?php echo $user_group; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_user_group; ?></a></li>
          <li><a href="<?php echo $api; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_api; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_localisation; ?></a>
        <ul>
          <li><a href="<?php echo $location; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_location; ?></a></li>
          <li><a href="<?php echo $language; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_language; ?></a></li>
          <li><a href="<?php echo $currency; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_currency; ?></a></li>
          <li><a href="<?php echo $stock_status; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_stock_status; ?></a></li>
          <li><a href="<?php echo $order_status; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_order_status; ?></a></li>
          <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_return; ?></a>
            <ul>
              <li><a href="<?php echo $return_status; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_return_status; ?></a></li>
              <li><a href="<?php echo $return_action; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_return_action; ?></a></li>
              <li><a href="<?php echo $return_reason; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_return_reason; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $country; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_country; ?></a></li>
          <li><a href="<?php echo $zone; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_zone; ?></a></li>
          <li><a href="<?php echo $geo_zone; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_geo_zone; ?></a></li>
          <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_tax; ?></a>
            <ul>
              <li><a href="<?php echo $tax_class; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_tax_class; ?></a></li>
              <li><a href="<?php echo $tax_rate; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_tax_rate; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $length_class; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_length_class; ?></a></li>
          <li><a href="<?php echo $weight_class; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_weight_class; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li id="tools"><a class="parent"><i class="fa fa-wrench fa-fw"></i> <span><?php echo $text_tools; ?></span></a>
    <ul>
      <li><a href="<?php echo $upload; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_upload; ?></a></li>
      <li><a href="<?php echo $backup; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_backup; ?></a></li>
      <li><a href="<?php echo $error_log; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_error_log; ?></a></li>
    </ul>
  </li>
  <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
    <ul>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_sale; ?></a>
        <ul>
          <li><a href="<?php echo $report_sale_order; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_sale_order; ?></a></li>
          <li><a href="<?php echo $report_sale_tax; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_sale_tax; ?></a></li>
          <li><a href="<?php echo $report_sale_shipping; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_sale_shipping; ?></a></li>
          <li><a href="<?php echo $report_sale_return; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_sale_return; ?></a></li>
          <li><a href="<?php echo $report_sale_coupon; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_sale_coupon; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_product; ?></a>
        <ul>
          <li><a href="<?php echo $report_product_viewed; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_product_viewed; ?></a></li>
          <li><a href="<?php echo $report_product_purchased; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_product_purchased; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_customer; ?></a>
        <ul>
          <li><a href="<?php echo $report_customer_online; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_customer_online; ?></a></li>
          <li><a href="<?php echo $report_customer_activity; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_customer_activity; ?></a></li>
          <li><a href="<?php echo $report_customer_order; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_customer_order; ?></a></li>
          <li><a href="<?php echo $report_customer_reward; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_customer_reward; ?></a></li>
          <li><a href="<?php echo $report_customer_credit; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_report_customer_credit; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><i class="fa fa-angle-double-right"></i> <?php echo $text_marketing; ?></a>
        <ul>
          <li><a href="<?php echo $report_marketing; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_marketing; ?></a></li>
          <li><a href="<?php echo $report_affiliate; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $report_affiliate_activity; ?>"><i class="fa fa-angle-double-right"></i> <?php echo $text_affiliate_activity; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
</ul>
