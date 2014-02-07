<nav id="column-left">
  <ul id="menu">
    <li>
      <div id="search">
        <button type="button" class="btn btn-link"><i class="fa fa-search fa-fw"></i></button>
        <input type="text" name="search" value="" placeholder="<?php echo $text_search; ?>" />
      </div>
    </li>
    <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-home fa-fw fa-lg"></i> <span><?php echo $text_dashboard; ?></span></a></li>
    <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw fa-lg"></i> <span><?php echo $text_catalog; ?></span></a>
      <ul>
        <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
        <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
        <li><a href="<?php echo $product_profile; ?>"><?php echo $text_product_profile; ?></a></li>
        <li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li>
        <li><a class="parent"><?php echo $text_attribute; ?></a>
          <ul>
            <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
            <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
          </ul>
        </li>
        <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>
        <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
        <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
        <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
        <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
      </ul>
    </li>
    <li id="extension"><a class="parent"><i class="fa fa-puzzle-piece fa-fw fa-lg"></i> <span><?php echo $text_extension; ?></span></a>
      <ul>
        <li><a href="<?php echo $installer; ?>"><?php echo $text_installer; ?></a></li>
        <li><a href="<?php echo $modification; ?>"><?php echo $text_modification; ?></a></li>
        <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
        <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
        <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
        <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
        <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
      </ul>
    </li>
    <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw fa-lg"></i> <span><?php echo $text_sale; ?></span></a>
      <ul>
        <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        <li><a href="<?php echo $recurring_profile; ?>"><?php echo $text_recurring_profile; ?></a></li>
        <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
        <li><a class="parent"><?php echo $text_customer; ?></a>
          <ul>
            <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
            <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
            <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
            <li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>
          </ul>
        </li>
        <li><a class="parent"><?php echo $text_voucher; ?></a>
          <ul>
            <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
            <li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
          </ul>
        </li>
        <li><a class="parent"><?php echo $text_marketing; ?></a>
          <ul>
            <li><a href="<?php echo $marketing; ?>"><?php echo $text_marketing; ?></a></li>
            <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
            <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
            <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          </ul>
        </li>
        <li><a class="parent"><?php echo $text_paypal ?></a>
          <ul>
            <li><a href="<?php echo $paypal_search ?>"><?php echo $text_paypal_search ?></a></li>
          </ul>
        </li>
      </ul>
    </li>
    <li id="system"><a class="parent"><i class="fa fa-cog fa-fw fa-lg"></i> <span><?php echo $text_system; ?></span></a>
      <ul>
        <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
        <li><a class="parent"><?php echo $text_design; ?></a>
          <ul>
            <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>
            <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
          </ul>
        </li>
        <li><a class="parent"><?php echo $text_users; ?></a>
          <ul>
            <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
            <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
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
        <li class="divider"></li>
        <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
        <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
      </ul>
    </li>
    <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw fa-lg"></i> <span><?php echo $text_reports; ?></span></a>
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
            <li><a href="<?php echo $report_affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
            <li><a href="<?php echo $report_affiliate_activity; ?>"><?php echo $text_affiliate_activity; ?></a></li>
          </ul>
        </li>
      </ul>
    </li>
  </ul>
</nav>
