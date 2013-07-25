<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $invoice; ?>" target="_blank" class="button"><?php echo $button_invoice; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div class="vtabs"><a href="#tab-order"><?php echo $tab_order; ?></a><a href="#tab-payment"><?php echo $tab_payment; ?></a>
        <?php if ($shipping_method) { ?>
        <a href="#tab-shipping"><?php echo $tab_shipping; ?></a>
        <?php } ?>
        <a href="#tab-product"><?php echo $tab_product; ?></a><a href="#tab-history"><?php echo $tab_history; ?></a>
        <?php if ($maxmind_id) { ?>
        <a href="#tab-fraud"><?php echo $tab_fraud; ?></a>
        <?php } ?>
      </div>
      <div id="tab-order" class="vtabs-content">
        <table class="form">
          <tr>
            <td><?php echo $text_order_id; ?></td>
            <td>#<?php echo $order_id; ?></td>
          </tr>
          <?php if (!empty($amazon_order_id)) { ?>
          
          <tr>
            <td><?php echo $text_amazon_order_id; ?></td>
            <td><?php echo $amazon_order_id; ?></td>
          </tr>
          
          <?php } ?>
          <tr>
            <td><?php echo $text_invoice_no; ?></td>
            <td><?php if ($invoice_no) { ?>
              <?php echo $invoice_no; ?>
              <?php } else { ?>
              <span id="invoice"><b>[</b> <a id="invoice-generate"><?php echo $text_generate; ?></a> <b>]</b></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $text_store_name; ?></td>
            <td><?php echo $store_name; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_store_url; ?></td>
            <td><a href="<?php echo $store_url; ?>" target="_blank"><u><?php echo $store_url; ?></u></a></td>
          </tr>
          <?php if ($customer) { ?>
          <tr>
            <td><?php echo $text_customer; ?></td>
            <td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
          </tr>
          <?php } else { ?>
          <tr>
            <td><?php echo $text_customer; ?></td>
            <td><?php echo $firstname; ?> <?php echo $lastname; ?></td>
          </tr>
          <?php } ?>
          <?php if ($customer_group) { ?>
          <tr>
            <td><?php echo $text_customer_group; ?></td>
            <td><?php echo $customer_group; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_email; ?></td>
            <td><?php echo $email; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_telephone; ?></td>
            <td><?php echo $telephone; ?></td>
          </tr>
          <?php if ($fax) { ?>
          <tr>
            <td><?php echo $text_fax; ?></td>
            <td><?php echo $fax; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_total; ?></td>
            <td><?php echo $total; ?>
              <?php if ($credit && $customer) { ?>
              <?php if (!$credit_total) { ?>
              <span id="credit"><b>[</b> <a id="credit-add"><?php echo $text_credit_add; ?></a> <b>]</b></span>
              <?php } else { ?>
              <span id="credit"><b>[</b> <a id="credit-remove"><?php echo $text_credit_remove; ?></a> <b>]</b></span>
              <?php } ?>
              <?php } ?></td>
          </tr>
          <?php if ($reward && $customer) { ?>
          <tr>
            <td><?php echo $text_reward; ?></td>
            <td><?php echo $reward; ?>
              <?php if (!$reward_total) { ?>
              <span id="reward"><b>[</b> <a id="reward-add"><?php echo $text_reward_add; ?></a> <b>]</b></span>
              <?php } else { ?>
              <span id="reward"><b>[</b> <a id="reward-remove"><?php echo $text_reward_remove; ?></a> <b>]</b></span>
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php if ($order_status) { ?>
          <tr>
            <td><?php echo $text_order_status; ?></td>
            <td id="order-status"><?php echo $order_status; ?></td>
          </tr>
          <?php } ?>
          <?php if ($comment) { ?>
          <tr>
            <td><?php echo $text_comment; ?></td>
            <td><?php echo $comment; ?></td>
          </tr>
          <?php } ?>
          <?php if ($affiliate) { ?>
          <tr>
            <td><?php echo $text_affiliate; ?></td>
            <td><a href="<?php echo $affiliate; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a></td>
          </tr>
          <tr>
            <td><?php echo $text_commission; ?></td>
            <td><?php echo $commission; ?>
              <?php if (!$commission_total) { ?>
              <span id="commission"><b>[</b> <a id="commission-add"><?php echo $text_commission_add; ?></a> <b>]</b></span>
              <?php } else { ?>
              <span id="commission"><b>[</b> <a id="commission-remove"><?php echo $text_commission_remove; ?></a> <b>]</b></span>
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip) { ?>
          <tr>
            <td><?php echo $text_ip; ?></td>
            <td><?php echo $ip; ?></td>
          </tr>
          <?php } ?>
          <?php if ($forwarded_ip) { ?>
          <tr>
            <td><?php echo $text_forwarded_ip; ?></td>
            <td><?php echo $forwarded_ip; ?></td>
          </tr>
          <?php } ?>
          <?php if ($user_agent) { ?>
          <tr>
            <td><?php echo $text_user_agent; ?></td>
            <td><?php echo $user_agent; ?></td>
          </tr>
          <?php } ?>
          <?php if ($accept_language) { ?>
          <tr>
            <td><?php echo $text_accept_language; ?></td>
            <td><?php echo $accept_language; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_date_added; ?></td>
            <td><?php echo $date_added; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_date_modified; ?></td>
            <td><?php echo $date_modified; ?></td>
          </tr>
        </table>
      </div>
      <div id="tab-payment" class="vtabs-content">
        <table class="form">
          <tr>
            <td><?php echo $text_firstname; ?></td>
            <td><?php echo $payment_firstname; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_lastname; ?></td>
            <td><?php echo $payment_lastname; ?></td>
          </tr>
          <?php if ($payment_company) { ?>
          <tr>
            <td><?php echo $text_company; ?></td>
            <td><?php echo $payment_company; ?></td>
          </tr>
          <?php } ?>
          <?php if ($payment_company_id) { ?>
          <tr>
            <td><?php echo $text_company_id; ?></td>
            <td><?php echo $payment_company_id; ?></td>
          </tr>
          <?php } ?>          
          <?php if ($payment_tax_id) { ?>
          <tr>
            <td><?php echo $text_tax_id; ?></td>
            <td><?php echo $payment_tax_id; ?></td>
          </tr>
          <?php } ?>            
          <tr>
            <td><?php echo $text_address_1; ?></td>
            <td><?php echo $payment_address_1; ?></td>
          </tr>
          <?php if ($payment_address_2) { ?>
          <tr>
            <td><?php echo $text_address_2; ?></td>
            <td><?php echo $payment_address_2; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_city; ?></td>
            <td><?php echo $payment_city; ?></td>
          </tr>
          <?php if ($payment_postcode) { ?>
          <tr>
            <td><?php echo $text_postcode; ?></td>
            <td><?php echo $payment_postcode; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_zone; ?></td>
            <td><?php echo $payment_zone; ?></td>
          </tr>
          <?php if ($payment_zone_code) { ?>
          <tr>
            <td><?php echo $text_zone_code; ?></td>
            <td><?php echo $payment_zone_code; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_country; ?></td>
            <td><?php echo $payment_country; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_payment_method; ?></td>
            <td><?php echo $payment_method; ?></td>
          </tr>
        </table>
		<?php echo $payment_action; ?>
      </div>
      <?php if ($shipping_method) { ?>
      <div id="tab-shipping" class="vtabs-content">
        <table class="form">
          <?php if (!empty($amazon_order_id) && empty($shipping_lastname)) { ?> 
            
            <tr>
              <td><?php echo $text_name; ?></td>
              <td><?php echo $shipping_firstname; ?></td>
            </tr>
            
           <?php } else { ?>
            
            <tr>
              <td><?php echo $text_firstname; ?></td>
              <td><?php echo $shipping_firstname; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_lastname; ?></td>
              <td><?php echo $shipping_lastname; ?></td>
            </tr>
            
           <?php } ?>
          <?php if ($shipping_company) { ?>
          <tr>
            <td><?php echo $text_company; ?></td>
            <td><?php echo $shipping_company; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_address_1; ?></td>
            <td><?php echo $shipping_address_1; ?></td>
          </tr>
          <?php if ($shipping_address_2) { ?>
          <tr>
            <td><?php echo $text_address_2; ?></td>
            <td><?php echo $shipping_address_2; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_city; ?></td>
            <td><?php echo $shipping_city; ?></td>
          </tr>
          <?php if ($shipping_postcode) { ?>
          <tr>
            <td><?php echo $text_postcode; ?></td>
            <td><?php echo $shipping_postcode; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_zone; ?></td>
            <td><?php echo $shipping_zone; ?></td>
          </tr>
          <?php if ($shipping_zone_code) { ?>
          <tr>
            <td><?php echo $text_zone_code; ?></td>
            <td><?php echo $shipping_zone_code; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_country; ?></td>
            <td><?php echo $shipping_country; ?></td>
          </tr>
          <?php if ($shipping_method) { ?>
          <tr>
            <td><?php echo $text_shipping_method; ?></td>
            <td><?php echo $shipping_method; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php } ?>
      <div id="tab-product" class="vtabs-content">
        <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_product; ?></td>
              <td class="left"><?php echo $column_model; ?></td>
              <td class="right"><?php echo $column_quantity; ?></td>
              <td class="right"><?php echo $column_price; ?></td>
              <td class="right"><?php echo $column_total; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td class="left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                <?php if ($option['type'] != 'file') { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } else { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                <?php } ?>
                <?php } ?></td>
              <td class="left"><?php echo $product['model']; ?></td>
              <td class="right"><?php echo $product['quantity']; ?></td>
              <td class="right"><?php echo $product['price']; ?></td>
              <td class="right"><?php echo $product['total']; ?></td>
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <td class="left"><a href="<?php echo $voucher['href']; ?>"><?php echo $voucher['description']; ?></a></td>
              <td class="left"></td>
              <td class="right">1</td>
              <td class="right"><?php echo $voucher['amount']; ?></td>
              <td class="right"><?php echo $voucher['amount']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
          <?php foreach ($totals as $totals) { ?>
          <tbody id="totals">
            <tr>
              <td colspan="4" class="right"><?php echo $totals['title']; ?>:</td>
              <td class="right"><?php echo $totals['text']; ?></td>
            </tr>
          </tbody>
          <?php } ?>
        </table>
        <?php if ($downloads) { ?>
        <h3><?php echo $text_download; ?></h3>
        <table class="list">
          <thead>
            <tr>
              <td class="left"><b><?php echo $column_download; ?></b></td>
              <td class="left"><b><?php echo $column_filename; ?></b></td>
              <td class="right"><b><?php echo $column_remaining; ?></b></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($downloads as $download) { ?>
            <tr>
              <td class="left"><?php echo $download['name']; ?></td>
              <td class="left"><?php echo $download['filename']; ?></td>
              <td class="right"><?php echo $download['remaining']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } ?>
      </div>
      <div id="tab-history" class="vtabs-content">
        <div id="history"></div>
        <table class="form">
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td>
              <input type="hidden" name="old_order_status_id" value="<?php echo $order_status_id; ?>" id="old_order_status_id" />
              <select name="order_status_id">
                <?php foreach ($order_statuses as $order_statuses) { ?>
                <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
                <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_notify; ?></td>
            <td><input type="checkbox" name="notify" value="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_comment; ?></td>
            <td><textarea name="comment" cols="40" rows="8" style="width: 99%"></textarea>
              <div style="margin-top: 10px; text-align: right;"><a id="button-history" class="button"><?php echo $button_add_history; ?></a></div></td>
          </tr>
        </table>
      </div>
      <?php if ($maxmind_id) { ?>
      <div id="tab-fraud" class="vtabs-content">
        <table class="form">
          <?php if ($country_match) { ?>
          <tr>
            <td><?php echo $text_country_match; ?></td>
            <td><?php echo $country_match; ?></td>
          </tr>
          <?php } ?>
          <?php if ($country_code) { ?>
          <tr>
            <td><?php echo $text_country_code; ?></td>
            <td><?php echo $country_code; ?></td>
          </tr>
          <?php } ?>
          <?php if ($high_risk_country) { ?>
          <tr>
            <td><?php echo $text_high_risk_country; ?></td>
            <td><?php echo $high_risk_country; ?></td>
          </tr>
          <?php } ?>
          <?php if ($distance) { ?>
          <tr>
            <td><?php echo $text_distance; ?></td>
            <td><?php echo $distance; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_region) { ?>
          <tr>
            <td><?php echo $text_ip_region; ?></td>
            <td><?php echo $ip_region; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_city) { ?>
          <tr>
            <td><?php echo $text_ip_city; ?></td>
            <td><?php echo $ip_city; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_latitude) { ?>
          <tr>
            <td><?php echo $text_ip_latitude; ?></td>
            <td><?php echo $ip_latitude; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_longitude) { ?>
          <tr>
            <td><?php echo $text_ip_longitude; ?></td>
            <td><?php echo $ip_longitude; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_isp) { ?>
          <tr>
            <td><?php echo $text_ip_isp; ?></td>
            <td><?php echo $ip_isp; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_org) { ?>
          <tr>
            <td><?php echo $text_ip_org; ?></td>
            <td><?php echo $ip_org; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_asnum) { ?>
          <tr>
            <td><?php echo $text_ip_asnum; ?></td>
            <td><?php echo $ip_asnum; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_user_type) { ?>
          <tr>
            <td><?php echo $text_ip_user_type; ?></td>
            <td><?php echo $ip_user_type; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_country_confidence) { ?>
          <tr>
            <td><?php echo $text_ip_country_confidence; ?></td>
            <td><?php echo $ip_country_confidence; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_region_confidence) { ?>
          <tr>
            <td><?php echo $text_ip_region_confidence; ?></td>
            <td><?php echo $ip_region_confidence; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_city_confidence) { ?>
          <tr>
            <td><?php echo $text_ip_city_confidence; ?></td>
            <td><?php echo $ip_city_confidence; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_postal_confidence) { ?>
          <tr>
            <td><?php echo $text_ip_postal_confidence; ?></td>
            <td><?php echo $ip_postal_confidence; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_postal_code) { ?>
          <tr>
            <td><?php echo $text_ip_postal_code; ?></td>
            <td><?php echo $ip_postal_code; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_accuracy_radius) { ?>
          <tr>
            <td><?php echo $text_ip_accuracy_radius; ?></td>
            <td><?php echo $ip_accuracy_radius; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_net_speed_cell) { ?>
          <tr>
            <td><?php echo $text_ip_net_speed_cell; ?></td>
            <td><?php echo $ip_net_speed_cell; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_metro_code) { ?>
          <tr>
            <td><?php echo $text_ip_metro_code; ?></td>
            <td><?php echo $ip_metro_code; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_area_code) { ?>
          <tr>
            <td><?php echo $text_ip_area_code; ?></td>
            <td><?php echo $ip_area_code; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_time_zone) { ?>
          <tr>
            <td><?php echo $text_ip_time_zone; ?></td>
            <td><?php echo $ip_time_zone; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_region_name) { ?>
          <tr>
            <td><?php echo $text_ip_region_name; ?></td>
            <td><?php echo $ip_region_name; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_domain) { ?>
          <tr>
            <td><?php echo $text_ip_domain; ?></td>
            <td><?php echo $ip_domain; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_country_name) { ?>
          <tr>
            <td><?php echo $text_ip_country_name; ?></td>
            <td><?php echo $ip_country_name; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_continent_code) { ?>
          <tr>
            <td><?php echo $text_ip_continent_code; ?></td>
            <td><?php echo $ip_continent_code; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ip_corporate_proxy) { ?>
          <tr>
            <td><?php echo $text_ip_corporate_proxy; ?></td>
            <td><?php echo $ip_corporate_proxy; ?></td>
          </tr>
          <?php } ?>
          <?php if ($anonymous_proxy) { ?>
          <tr>
            <td><?php echo $text_anonymous_proxy; ?></td>
            <td><?php echo $anonymous_proxy; ?></td>
          </tr>
          <?php } ?>
          <?php if ($proxy_score) { ?>
          <tr>
            <td><?php echo $text_proxy_score; ?></td>
            <td><?php echo $proxy_score; ?></td>
          </tr>
          <?php } ?>
          <?php if ($is_trans_proxy) { ?>
          <tr>
            <td><?php echo $text_is_trans_proxy; ?></td>
            <td><?php echo $is_trans_proxy; ?></td>
          </tr>
          <?php } ?>
          <?php if ($free_mail) { ?>
          <tr>
            <td><?php echo $text_free_mail; ?></td>
            <td><?php echo $free_mail; ?></td>
          </tr>
          <?php } ?>
          <?php if ($carder_email) { ?>
          <tr>
            <td><?php echo $text_carder_email; ?></td>
            <td><?php echo $carder_email; ?></td>
          </tr>
          <?php } ?>
          <?php if ($high_risk_username) { ?>
          <tr>
            <td><?php echo $text_high_risk_username; ?></td>
            <td><?php echo $high_risk_username; ?></td>
          </tr>
          <?php } ?>
          <?php if ($high_risk_password) { ?>
          <tr>
            <td><?php echo $text_high_risk_password; ?></td>
            <td><?php echo $high_risk_password; ?></td>
          </tr>
          <?php } ?>
          <?php if ($bin_match) { ?>
          <tr>
            <td><?php echo $text_bin_match; ?></td>
            <td><?php echo $bin_match; ?></td>
          </tr>
          <?php } ?>
          <?php if ($bin_country) { ?>
          <tr>
            <td><?php echo $text_bin_country; ?></td>
            <td><?php echo $bin_country; ?></td>
          </tr>
          <?php } ?>
          <?php if ($bin_name_match) { ?>
          <tr>
            <td><?php echo $text_bin_name_match; ?></td>
            <td><?php echo $bin_name_match; ?></td>
          </tr>
          <?php } ?>
          <?php if ($bin_name) { ?>
          <tr>
            <td><?php echo $text_bin_name; ?></td>
            <td><?php echo $bin_name; ?></td>
          </tr>
          <?php } ?>
          <?php if ($bin_phone_match) { ?>
          <tr>
            <td><?php echo $text_bin_phone_match; ?></td>
            <td><?php echo $bin_phone_match; ?></td>
          </tr>
          <?php } ?>
          <?php if ($bin_phone) { ?>
          <tr>
            <td><?php echo $text_bin_phone; ?></td>
            <td><?php echo $bin_phone; ?></td>
          </tr>
          <?php } ?>
          <?php if ($customer_phone_in_billing_location) { ?>
          <tr>
            <td><?php echo $text_customer_phone_in_billing_location; ?></td>
            <td><?php echo $customer_phone_in_billing_location; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ship_forward) { ?>
          <tr>
            <td><?php echo $text_ship_forward; ?></td>
            <td><?php echo $ship_forward; ?></td>
          </tr>
          <?php } ?>
          <?php if ($city_postal_match) { ?>
          <tr>
            <td><?php echo $text_city_postal_match; ?></td>
            <td><?php echo $city_postal_match; ?></td>
          </tr>
          <?php } ?>
          <?php if ($ship_city_postal_match) { ?>
          <tr>
            <td><?php echo $text_ship_city_postal_match; ?></td>
            <td><?php echo $ship_city_postal_match; ?></td>
          </tr>
          <?php } ?>
          <?php if ($score) { ?>
          <tr>
            <td><?php echo $text_score; ?></td>
            <td><?php echo $score; ?></td>
          </tr>
          <?php } ?>
          <?php if ($explanation) { ?>
          <tr>
            <td><?php echo $text_explanation; ?></td>
            <td><?php echo $explanation; ?></td>
          </tr>
          <?php } ?>
          <?php if ($risk_score) { ?>
          <tr>
            <td><?php echo $text_risk_score; ?></td>
            <td><?php echo $risk_score; ?></td>
          </tr>
          <?php } ?>
          <?php if ($queries_remaining) { ?>
          <tr>
            <td><?php echo $text_queries_remaining; ?></td>
            <td><?php echo $queries_remaining; ?></td>
          </tr>
          <?php } ?>
          <?php if ($maxmind_id) { ?>
          <tr>
            <td><?php echo $text_maxmind_id; ?></td>
            <td><?php echo $maxmind_id; ?></td>
          </tr>
          <?php } ?>
          <?php if ($error) { ?>
          <tr>
            <td><?php echo $text_error; ?></td>
            <td><?php echo $error; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#invoice-generate').live('click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#invoice').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
		},
		success: function(json) {
			$('.success, .warning').remove();
						
			if (json['error']) {
				$('#tab-order').prepend('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json.invoice_no) {
				$('#invoice').fadeOut('slow', function() {
					$('#invoice').html(json['invoice_no']);
					
					$('#invoice').fadeIn('slow');
				});
			}
		}
	});
});

$('#credit-add').live('click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/addcredit&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#credit').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');			
		},
		complete: function() {
			$('.loading').remove();
		},			
		success: function(json) {
			$('.success, .warning').remove();
			
			if (json['error']) {
				$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json['success']) {
                $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
				
				$('#credit').html('<b>[</b> <a id="credit-remove"><?php echo $text_credit_remove; ?></a> <b>]</b>');
			}
		}
	});
});

$('#credit-remove').live('click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removecredit&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#credit').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');			
		},
		complete: function() {
			$('.loading').remove();
		},			
		success: function(json) {
			$('.success, .warning').remove();
						
			if (json['error']) {
				$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json['success']) {
                $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
				
				$('#credit').html('<b>[</b> <a id="credit-add"><?php echo $text_credit_add; ?></a> <b>]</b>');
			}
		}
	});
});

$('#reward-add').live('click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#reward').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');			
		},
		complete: function() {
			$('.loading').remove();
		},									
		success: function(json) {
			$('.success, .warning').remove();
						
			if (json['error']) {
				$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json['success']) {
                $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');

				$('#reward').html('<b>[</b> <a id="reward-remove"><?php echo $text_reward_remove; ?></a> <b>]</b>');
			}
		}
	});
});

$('#reward-remove').live('click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#reward').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		},
		complete: function() {
			$('.loading').remove();
		},				
		success: function(json) {
			$('.success, .warning').remove();
						
			if (json['error']) {
				$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json['success']) {
                $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
				
				$('#reward').html('<b>[</b> <a id="reward-add"><?php echo $text_reward_add; ?></a> <b>]</b>');
			}
		}
	});
});

$('#commission-add').live('click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#commission').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');			
		},
		complete: function() {
			$('.loading').remove();
		},			
		success: function(json) {
			$('.success, .warning').remove();
						
			if (json['error']) {
				$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json['success']) {
                $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
                
				$('#commission').html('<b>[</b> <a id="commission-remove"><?php echo $text_commission_remove; ?></a> <b>]</b>');
			}
		}
	});
});

$('#commission-remove').live('click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#commission').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');			
		},
		complete: function() {
			$('.loading').remove();
		},			
		success: function(json) {
			$('.success, .warning').remove();
						
			if (json['error']) {
				$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json['success']) {
                $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
				
				$('#commission').html('<b>[</b> <a id="commission-add"><?php echo $text_commission_add; ?></a> <b>]</b>');
			}
		}
	});
});

$('#history .pagination a').live('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

$('#button-history').live('click', function() {

    if(typeof verifyStatusChange == 'function'){
        if(verifyStatusChange() == false){
            return false;
        }else{
            addOrderInfo();
        }
    }else{
        addOrderInfo();
    }

	$.ajax({
		url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0) + '&append=' + encodeURIComponent($('input[name=\'append\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-history').attr('disabled', true);
			$('#history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-history').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(html) {
			$('#history').html(html);
			
			$('textarea[name=\'comment\']').val('');
			
			$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<script type="text/javascript"><!--
    function orderStatusChange(){
        var status_id = $('select[name="order_status_id"]').val();

        $('#openbayInfo').remove();

        $.ajax({
            url: 'index.php?route=extension/openbay/ajaxOrderInfo&token=<?php echo $this->request->get['token']; ?>&order_id=<?php echo $this->request->get['order_id']; ?>&status_id='+status_id,
            type: 'post',
            dataType: 'html',
            beforeSend: function(){},
            success: function(html) {
                $('#history').after(html);
            },
            failure: function(){},
            error: function(){}
        });
    }

    function addOrderInfo(){
        var status_id = $('select[name="order_status_id"]').val();
        var old_status_id = $('#old_order_status_id').val();

        $('#old_order_status_id').val(status_id);

        $.ajax({
            url: 'index.php?route=extension/openbay/ajaxAddOrderInfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id='+status_id+'&old_status_id='+old_status_id,
            type: 'post',
            dataType: 'html',
            data: $(".openbayData").serialize(),
            beforeSend: function(){},
            success: function() {},
            failure: function(){},
            error: function(){}
        });
    }

    $(document).ready(function() {
        orderStatusChange();
    });

    $('select[name="order_status_id"]').change(function(){orderStatusChange();});
//--></script>
<?php echo $footer; ?>