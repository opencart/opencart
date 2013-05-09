<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="tabbable tabs-left">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-customer" data-toggle="tab"><?php echo $tab_customer; ?></a></li>
            <li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
            <li><a href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li>
            <li><a href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li>
            <li><a href="#tab-voucher" data-toggle="tab"><?php echo $tab_voucher; ?></a></li>
            <li><a href="#tab-total" data-toggle="tab"><?php echo $tab_total; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-customer">
              <div class="control-group">
                <label class="control-label" for="input-store"><?php echo $entry_store; ?></label>
                <div class="controls">
                  <select name="store_id" id="input-store">
                    <option value="0"><?php echo $text_default; ?></option>
                    <?php foreach ($stores as $store) { ?>
                    <?php if ($store['store_id'] == $store_id) { ?>
                    <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <div class="controls">
                  <input type="text" name="customer" value="<?php echo $customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" />
                  <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                  <input type="hidden" name="customer_group_id" value="<?php echo $customer_group_id; ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                <div class="controls">
                  <select name="customer_group_id" id="input-customer-group" <?php echo ($customer_id ? 'disabled="disabled"' : ''); ?>>
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-firstname"><span class="required">*</span> <?php echo $entry_firstname; ?></label>
                <div class="controls">
                  <input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-firstname" />
                  <?php if ($error_firstname) { ?>
                  <span class="error"><?php echo $error_firstname; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-lastname"><span class="required">*</span> <?php echo $entry_lastname; ?></label>
                <div class="controls">
                  <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-lastname" />
                  <?php if ($error_lastname) { ?>
                  <span class="error"><?php echo $error_lastname; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-email"><span class="required">*</span> <?php echo $entry_email; ?></label>
                <div class="controls">
                  <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" />
                  <?php if ($error_email) { ?>
                  <span class="error"><?php echo $error_email; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-telephone"><span class="required">*</span> <?php echo $entry_telephone; ?></label>
                <div class="controls">
                  <input type="text" name="telephone" value="<?php echo $telephone; ?>" id="input-telephone" />
                  <?php if ($error_telephone) { ?>
                  <span class="error"><?php echo $error_telephone; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                <div class="controls">
                  <input type="text" name="fax" value="<?php echo $fax; ?>" id="input-fax" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-payment">
              <div class="control-group">
                <label class="control-label" for="input-payment-address"><?php echo $entry_address; ?></label>
                <div class="controls">
                  <select name="payment_address" id="input-payment-address">
                    <option value="0" selected="selected"><?php echo $text_none; ?></option>
                    <?php foreach ($addresses as $address) { ?>
                    <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname'] . ' ' . $address['lastname'] . ', ' . $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-payment-firstname"><span class="required">*</span> <?php echo $entry_firstname; ?></label>
                <div class="controls">
                  <input type="text" name="payment_firstname" value="<?php echo $payment_firstname; ?>" id="input-payment-firstname" />
                  <?php if ($error_payment_firstname) { ?>
                  <span class="error"><?php echo $error_payment_firstname; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-payment-lastname"><span class="required">*</span> <?php echo $entry_lastname; ?></label>
                <div class="controls">
                  <input type="text" name="payment_lastname" value="<?php echo $payment_lastname; ?>" id="input-payment-lastname" />
                  <?php if ($error_payment_lastname) { ?>
                  <span class="error"><?php echo $error_payment_lastname; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-payment-company"><?php echo $entry_company; ?></label>
                <div class="controls">
                  <input type="text" name="payment_company" value="<?php echo $payment_company; ?>" id="input-payment-company" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-payment-address-1"><span class="required">*</span> <?php echo $entry_address_1; ?></label>
                <div class="controls">
                  <input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" id="input-payment-address-1" />
                  <?php if ($error_payment_address_1) { ?>
                  <span class="error"><?php echo $error_payment_address_1; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-payment-address-2"><?php echo $entry_address_2; ?></label>
                <div class="controls">
                  <input type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" id="input-payment-address-2" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-payment-city"><span class="required">*</span> <?php echo $entry_city; ?></label>
                <div class="controls">
                  <input type="text" name="payment_city" value="<?php echo $payment_city; ?>" id="input-payment-city" />
                  <?php if ($error_payment_city) { ?>
                  <span class="error"><?php echo $error_payment_city; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-payment-postcode"><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
                <div class="controls">
                  <input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" id="input-payment-postcode" />
                  <?php if ($error_payment_postcode) { ?>
                  <span class="error"><?php echo $error_payment_postcode; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-payment-country"><span class="required">*</span> <?php echo $entry_country; ?></label>
                <div class="controls">
                  <select name="payment_country_id" id="input-payment-country">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($countries as $country) { ?>
                    <?php if ($country['country_id'] == $payment_country_id) { ?>
                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php if ($error_payment_country) { ?>
                  <span class="error"><?php echo $error_payment_country; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-payment-zone"><span class="required">*</span> <?php echo $entry_zone; ?></label>
                <div class="controls">
                  <select name="payment_zone_id" id="input-payment-zone">
                  </select>
                  <?php if ($error_payment_zone) { ?>
                  <span class="error"><?php echo $error_payment_zone; ?></span>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-shipping">
              <div class="control-group">
                <label class="control-label" for="input-shipping-address"><?php echo $entry_address; ?></label>
                <div class="controls">
                  <select name="shipping_address" id="input-shipping-address">
                    <option value="0" selected="selected"><?php echo $text_none; ?></option>
                    <?php foreach ($addresses as $address) { ?>
                    <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname'] . ' ' . $address['lastname'] . ', ' . $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-shipping-firstname"><span class="required">*</span> <?php echo $entry_firstname; ?></label>
                <div class="controls">
                  <input type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" id="input-shipping-firstname" />
                  <?php if ($error_shipping_firstname) { ?>
                  <span class="error"><?php echo $error_shipping_firstname; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-shipping-lastname"><span class="required">*</span> <?php echo $entry_lastname; ?></label>
                <div class="controls">
                  <input type="text" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" id="input-shipping-lastname" />
                  <?php if ($error_shipping_lastname) { ?>
                  <span class="error"><?php echo $error_shipping_lastname; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-shipping-company"><?php echo $entry_company; ?></label>
                <div class="controls">
                  <input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" id="input-shipping-company" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-shipping-address-1"><span class="required">*</span> <?php echo $entry_address_1; ?></label>
                <div class="controls">
                  <input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" id="input-shipping-address-1" />
                  <?php if ($error_shipping_address_1) { ?>
                  <span class="error"><?php echo $error_shipping_address_1; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-shipping-address-2"><?php echo $entry_address_2; ?></label>
                <div class="controls">
                  <input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" id="input-shipping-address-2" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-shipping-city"><span class="required">*</span> <?php echo $entry_city; ?></label>
                <div class="controls">
                  <input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" id="input-shipping-city" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-shipping-postcode"><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
                <div class="controls">
                  <input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" id="input-shipping-postcode" />
                  <?php if ($error_shipping_postcode) { ?>
                  <span class="error"><?php echo $error_shipping_postcode; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-shipping-country"><span class="required">*</span> <?php echo $entry_country; ?></label>
                <div class="controls">
                  <select name="shipping_country_id" id="input-shipping-country">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($countries as $country) { ?>
                    <?php if ($country['country_id'] == $shipping_country_id) { ?>
                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php if ($error_shipping_country) { ?>
                  <span class="error"><?php echo $error_shipping_country; ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-shipping-zone"><span class="required">*</span> <?php echo $entry_zone; ?></label>
                <div class="controls">
                  <select name="shipping_zone_id" id="input-shipping-zone">
                  </select>
                  <?php if ($error_shipping_zone) { ?>
                  <span class="error"><?php echo $error_shipping_zone; ?></span>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-product">
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <td></td>
                    <td class="left"><?php echo $column_product; ?></td>
                    <td class="left"><?php echo $column_model; ?></td>
                    <td class="right"><?php echo $column_quantity; ?></td>
                    <td class="right"><?php echo $column_price; ?></td>
                    <td class="right"><?php echo $column_total; ?></td>
                  </tr>
                </thead>
                <?php $product_row = 0; ?>
                <?php $option_row = 0; ?>
                <?php $download_row = 0; ?>
                <tbody id="product">
                  <?php if ($order_products) { ?>
                  <?php foreach ($order_products as $order_product) { ?>
                  <tr id="product-row<?php echo $product_row; ?>">
                    <td class="center" style="width: 3px;"><i class="icon-minus-sign" onclick="$('#product-row<?php echo $product_row; ?>').remove(); $('#button-update').trigger('click');"></i></td>
                    <td class="left"><?php echo $order_product['name']; ?><br />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_product_id]" value="<?php echo $order_product['order_product_id']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $order_product['product_id']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][name]" value="<?php echo $order_product['name']; ?>" />
                      <?php foreach ($order_product['option'] as $option) { ?>
                      - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][order_option_id]" value="<?php echo $option['order_option_id']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][product_option_value_id]" value="<?php echo $option['product_option_value_id']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][name]" value="<?php echo $option['name']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][value]" value="<?php echo $option['value']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][type]" value="<?php echo $option['type']; ?>" />
                      <?php $option_row++; ?>
                      <?php } ?>
                      <?php foreach ($order_product['download'] as $download) { ?>
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][order_download_id]" value="<?php echo $download['order_download_id']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][name]" value="<?php echo $download['name']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][filename]" value="<?php echo $download['filename']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][mask]" value="<?php echo $download['mask']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][remaining]" value="<?php echo $download['remaining']; ?>" />
                      <?php $download_row++; ?>
                      <?php } ?></td>
                    <td class="left"><?php echo $order_product['model']; ?>
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][model]" value="<?php echo $order_product['model']; ?>" /></td>
                    <td class="right"><?php echo $order_product['quantity']; ?>
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>" /></td>
                    <td class="right"><?php echo $order_product['price']; ?>
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][price]" value="<?php echo $order_product['price']; ?>" /></td>
                    <td class="right"><?php echo $order_product['total']; ?>
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][total]" value="<?php echo $order_product['total']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][tax]" value="<?php echo $order_product['tax']; ?>" />
                      <input type="hidden" name="order_product[<?php echo $product_row; ?>][reward]" value="<?php echo $order_product['reward']; ?>" /></td>
                  </tr>
                  <?php $product_row++; ?>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              <fieldset>
                <legend><?php echo $text_product; ?></legend>
                <div class="control-group">
                  <label class="control-label" for="input-product"><?php echo $entry_product; ?></label>
                  <div class="controls">
                    <input type="text" name="product" value="" id="input-product" />
                    <input type="hidden" name="product_id" value="" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                  <div class="controls">
                    <input type="text" name="quantity" value="1" id="input-quantity" />
                  </div>
                </div>
                <div id="option"></div>
              </fieldset>
              <button type="button" id="button-product" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_product; ?></button>
            </div>
            <div class="tab-pane" id="tab-voucher">
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <td></td>
                    <td class="left"><?php echo $column_product; ?></td>
                    <td class="left"><?php echo $column_model; ?></td>
                    <td class="right"><?php echo $column_quantity; ?></td>
                    <td class="right"><?php echo $column_price; ?></td>
                    <td class="right"><?php echo $column_total; ?></td>
                  </tr>
                </thead>
                <tbody id="voucher">
                  <?php $voucher_row = 0; ?>
                  <?php if ($order_vouchers) { ?>
                  <?php foreach ($order_vouchers as $order_voucher) { ?>
                  <tr id="voucher-row<?php echo $voucher_row; ?>">
                    <td class="center" style="width: 3px;"><i class="icon-minus-sign" onclick="$('#voucher-row<?php echo $voucher_row; ?>').remove(); $('#button-update').trigger('click');"></i></td>
                    <td class="left"><?php echo $order_voucher['description']; ?>
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][order_voucher_id]" value="<?php echo $order_voucher['order_voucher_id']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][voucher_id]" value="<?php echo $order_voucher['voucher_id']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][description]" value="<?php echo $order_voucher['description']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][code]" value="<?php echo $order_voucher['code']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][from_name]" value="<?php echo $order_voucher['from_name']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][from_email]" value="<?php echo $order_voucher['from_email']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][to_name]" value="<?php echo $order_voucher['to_name']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][to_email]" value="<?php echo $order_voucher['to_email']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][voucher_theme_id]" value="<?php echo $order_voucher['voucher_theme_id']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][message]" value="<?php echo $order_voucher['message']; ?>" />
                      <input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][amount]" value="<?php echo $order_voucher['amount']; ?>" /></td>
                    <td class="left"></td>
                    <td class="right">1</td>
                    <td class="right"><?php echo $order_voucher['amount']; ?></td>
                    <td class="right"><?php echo $order_voucher['amount']; ?></td>
                  </tr>
                  <?php $voucher_row++; ?>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              <fieldset>
                <legend><?php echo $text_voucher; ?></legend>
                <div class="control-group">
                  <label class="control-label" for="input-to-name"><span class="required">*</span> <?php echo $entry_to_name; ?></label>
                  <div class="controls">
                    <input type="text" name="to_name" value="" id="input-to-name" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-to-name"><span class="required">*</span> <?php echo $entry_to_email; ?></label>
                  <div class="controls">
                    <input type="text" name="to_email" value="" id="input-to-email" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-from-name"><span class="required">*</span> <?php echo $entry_from_name; ?></label>
                  <div class="controls">
                    <input type="text" name="from_name" value="" id="input-from-name" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-from-email"><span class="required">*</span> <?php echo $entry_from_email; ?></label>
                  <div class="controls">
                    <input type="text" name="from_email" value="" id="input-from-email" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-theme"><span class="required">*</span> <?php echo $entry_theme; ?></label>
                  <div class="controls">
                    <select name="voucher_theme_id" id="input-theme">
                      <?php foreach ($voucher_themes as $voucher_theme) { ?>
                      <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>"><?php echo $voucher_theme['name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-message"><?php echo $entry_message; ?></label>
                  <div class="controls">
                    <textarea name="message" cols="40" rows="5" id="input-message"></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-amount"><span class="required">*</span> <?php echo $entry_amount; ?></label>
                  <div class="controls">
                    <input type="text" name="amount" value="25.00" id="input-amount" class="input-medium" />
                  </div>
                </div>
              </fieldset>
              <button type="button" id="button-voucher" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_voucher; ?></button>
            </div>
            <div class="tab-pane" id="tab-total">
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="left"><?php echo $column_product; ?></td>
                    <td class="left"><?php echo $column_model; ?></td>
                    <td class="right"><?php echo $column_quantity; ?></td>
                    <td class="right"><?php echo $column_price; ?></td>
                    <td class="right"><?php echo $column_total; ?></td>
                  </tr>
                </thead>
                <tbody id="total">
                  <?php $total_row = 0; ?>
                  <?php if ($order_products || $order_vouchers || $order_totals) { ?>
                  <?php foreach ($order_products as $order_product) { ?>
                  <tr>
                    <td class="left"><?php echo $order_product['name']; ?><br />
                      <?php foreach ($order_product['option'] as $option) { ?>
                      - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                      <?php } ?></td>
                    <td class="left"><?php echo $order_product['model']; ?></td>
                    <td class="right"><?php echo $order_product['quantity']; ?></td>
                    <td class="right"><?php echo $order_product['price']; ?></td>
                    <td class="right"><?php echo $order_product['total']; ?></td>
                  </tr>
                  <?php } ?>
                  <?php foreach ($order_vouchers as $order_voucher) { ?>
                  <tr>
                    <td class="left"><?php echo $order_voucher['description']; ?></td>
                    <td class="left"></td>
                    <td class="right">1</td>
                    <td class="right"><?php echo $order_voucher['amount']; ?></td>
                    <td class="right"><?php echo $order_voucher['amount']; ?></td>
                  </tr>
                  <?php } ?>
                  <?php foreach ($order_totals as $order_total) { ?>
                  <tr id="total-row<?php echo $total_row; ?>">
                    <td class="right" colspan="4"><?php echo $order_total['title']; ?>:
                      <input type="hidden" name="order_total[<?php echo $total_row; ?>][order_total_id]" value="<?php echo $order_total['order_total_id']; ?>" />
                      <input type="hidden" name="order_total[<?php echo $total_row; ?>][code]" value="<?php echo $order_total['code']; ?>" />
                      <input type="hidden" name="order_total[<?php echo $total_row; ?>][title]" value="<?php echo $order_total['title']; ?>" />
                      <input type="hidden" name="order_total[<?php echo $total_row; ?>][text]" value="<?php echo $order_total['text']; ?>" />
                      <input type="hidden" name="order_total[<?php echo $total_row; ?>][value]" value="<?php echo $order_total['value']; ?>" />
                      <input type="hidden" name="order_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $order_total['sort_order']; ?>" /></td>
                    <td class="right"><?php echo $order_total['value']; ?></td>
                  </tr>
                  <?php $total_row++; ?>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              <fieldset>
                <legend><?php echo $text_order; ?></legend>
                <div class="control-group">
                  <label class="control-label" for="input-shipping"><?php echo $entry_shipping; ?></label>
                  <div class="controls">
                    <select name="shipping" id="input-shipping">
                      <option value=""><?php echo $text_select; ?></option>
                      <?php if ($shipping_code) { ?>
                      <option value="<?php echo $shipping_code; ?>" selected="selected"><?php echo $shipping_method; ?></option>
                      <?php } ?>
                    </select>
                    <input type="hidden" name="shipping_method" value="<?php echo $shipping_method; ?>" />
                    <input type="hidden" name="shipping_code" value="<?php echo $shipping_code; ?>" />
                    <?php if ($error_shipping_method) { ?>
                    <span class="error"><?php echo $error_shipping_method; ?></span>
                    <?php } ?>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-payment"><?php echo $entry_payment; ?></label>
                  <div class="controls">
                    <select name="payment" id="input-payment">
                      <option value=""><?php echo $text_select; ?></option>
                      <?php if ($payment_code) { ?>
                      <option value="<?php echo $payment_code; ?>" selected="selected"><?php echo $payment_method; ?></option>
                      <?php } ?>
                    </select>
                    <input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>" />
                    <input type="hidden" name="payment_code" value="<?php echo $payment_code; ?>" />
                    <?php if ($error_payment_method) { ?>
                    <span class="error"><?php echo $error_payment_method; ?></span>
                    <?php } ?>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-coupon"><?php echo $entry_coupon; ?></label>
                  <div class="controls">
                    <input type="text" name="coupon" value="" id="input-coupon" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-voucher"><?php echo $entry_voucher; ?></label>
                  <div class="controls">
                    <input type="text" name="voucher" value="" id="input-voucher" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-reward"><?php echo $entry_reward; ?></label>
                  <div class="controls">
                    <input type="text" name="reward" value="" id="input-reward" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                  <div class="controls">
                    <select name="order_status_id" id="input-status">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                  <div class="controls">
                    <textarea name="comment" cols="40" rows="5" id="input-comment"><?php echo $comment; ?></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-affiliate"><?php echo $entry_affiliate; ?></label>
                  <div class="controls">
                    <input type="text" name="affiliate" value="<?php echo $affiliate; ?>" id="input-affiliate" />
                    <input type="hidden" name="affiliate_id" value="<?php echo $affiliate_id; ?>" />
                  </div>
                </div>
              </fieldset>
              <button type="button" id="button-update" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_update_total; ?></button>
            </div>
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      </form>
    </div>
  </div>
</div>
<div style="display: none;">
  <form enctype="multipart/form-data">
    <input type="file" name="file" id="file" />
  </form>
</div>
<script type="text/javascript"><!--
$('input[name=\'customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item['customer_group'],
						label: item['name'],
						value: item['customer_id'],
						customer_group_id: item['customer_group_id'],						
						firstname: item['firstname'],
						lastname: item['lastname'],
						email: item['email'],
						telephone: item['telephone'],
						fax: item['fax'],
						address: item['address']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'customer\']').val(item['label']);
		$('input[name=\'customer_id\']').val(item['value']);
		$('input[name=\'firstname\']').attr('value', item['firstname']);
		$('input[name=\'lastname\']').attr('value', item['lastname']);
		$('input[name=\'email\']').attr('value', item['email']);
		$('input[name=\'telephone\']').attr('value', item['telephone']);
		$('input[name=\'fax\']').attr('value', item['fax']);
		
		html = '<option value="0"><?php echo $text_none; ?></option>'; 
			
		for (i in  item['address']) {
			html += '<option value="' + item['address'][i]['address_id'] + '">' + item['address'][i]['firstname'] + ' ' + item['address'][i]['lastname'] + ', ' + item['address'][i]['address_1'] + ', ' + item['address'][i]['city'] + ', ' + item['address'][i]['country'] + '</option>';
		}
		
		$('select[name=\'shipping_address\']').html(html);
		$('select[name=\'payment_address\']').html(html);
		
		$('select[name=\'customer_group_id\']').prop('disabled', false);
		$('select[name=\'customer_group_id\']').prop('value', item['customer_group_id']);
		$('select[name=\'customer_group_id\']').trigger('change');
		$('select[name=\'customer_group_id\']').prop('disabled', true); 		
	}
});

// Customer Fields
$('#customer-group').on('change', function() {

});

$('#customer-group').trigger('change');

$('input[name=\'affiliate\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/affiliate/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['affiliate_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'affiliate\']').val(item['label']);
		$('input[name=\'affiliate_id\']').val(item['value']);		
	}	
});

var payment_zone_id = '<?php echo $payment_zone_id; ?>';

$('select[name=\'payment_country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/order/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'payment_country_id\']').after(' <i class="icon-spinner icon-spin"></i>');
		},
		complete: function() {
			$('.icon-spinner').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#payment-postcode-required').show();
			} else {
				$('#payment-postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json != '' && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == payment_zone_id) {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'payment_zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'payment_country_id\']').trigger('change');

$('select[name=\'payment_address\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/address&token=<?php echo $token; ?>&address_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'payment_address\']').after(' <i class="icon-spinner icon-spin"></i>');
		},
		complete: function() {
			$('.icon-spinner').remove();
		},		
		success: function(json) {
			if (json != '') {	
				$('input[name=\'payment_firstname\']').attr('value', json['firstname']);
				$('input[name=\'payment_lastname\']').attr('value', json['lastname']);
				$('input[name=\'payment_company\']').attr('value', json['company']);
				$('input[name=\'payment_address_1\']').attr('value', json['address_1']);
				$('input[name=\'payment_address_2\']').attr('value', json['address_2']);
				$('input[name=\'payment_city\']').attr('value', json['city']);
				$('input[name=\'payment_postcode\']').attr('value', json['postcode']);
				$('select[name=\'payment_country_id\']').prop('value', json['country_id']);
				
				payment_zone_id = json['zone_id'];
				
				$('select[name=\'payment_country_id\']').trigger('change');
			}
		}
	});	
});

var shipping_zone_id = '<?php echo $shipping_zone_id; ?>';

$('select[name=\'shipping_country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/order/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'shipping_country_id\']').after(' <i class="icon-spinner icon-spin"></i>');
		},
		complete: function() {
			$('.icon-spinner').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#shipping-postcode-required').show();
			} else {
				$('#shipping-postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json != '' && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == shipping_zone_id) {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'shipping_zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'shipping_country_id\']').trigger('change');

$('select[name=\'shipping_address\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/address&token=<?php echo $token; ?>&address_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'shipping_address\']').after(' <i class="icon-spinner icon-spin"></i>');
		},
		complete: function() {
			$('.icon-spinner').remove();
		},		
		success: function(json) {
			if (json != '') {	
				$('input[name=\'shipping_firstname\']').attr('value', json['firstname']);
				$('input[name=\'shipping_lastname\']').attr('value', json['lastname']);
				$('input[name=\'shipping_company\']').attr('value', json['company']);
				$('input[name=\'shipping_address_1\']').attr('value', json['address_1']);
				$('input[name=\'shipping_address_2\']').attr('value', json['address_2']);
				$('input[name=\'shipping_city\']').attr('value', json['city']);
				$('input[name=\'shipping_postcode\']').attr('value', json['postcode']);
				$('select[name=\'shipping_country_id\']').prop('value', json['country_id']);
				
				shipping_zone_id = json['zone_id'];
				
				$('select[name=\'shipping_country_id\']').trigger('change');
			}
		}
	});	
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id'],
						model: item['model'],
						option: item['option'],
						price: item['price']						
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'product\']').val(item['label']);
		$('input[name=\'product_id\']').val(item['value']);
		
		if (item['option'] != '') {
 			html  = '<fieldset>';
            html += '  <legend><?php echo $entry_option; ?></legend>';
			  
			for (i = 0; i < item['option'].length; i++) {
				option = item['option'][i];
				
				if (option['type'] == 'select') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="controls">';
					html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '">';
					html += '      <option value=""><?php echo $text_select; ?></option>';
				
					for (j = 0; j < option['product_option_value'].length; j++) {
						option_value = option['product_option_value'][j];
						
						html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</option>';
					}
						
					html += '    </select>';
					html += '  </div>';
					html += '</div>';
				}
				
				if (option['type'] == 'radio') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="controls">';
					html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '">';
					html += '      <option value=""><?php echo $text_select; ?></option>';
				
					for (j = 0; j < option['product_option_value'].length; j++) {
						option_value = option['product_option_value'][j];
						
						html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</option>';
					}
						
					html += '    </select>';
					html += '  </div>';
					html += '</div>';
				}
					
				if (option['type'] == 'checkbox') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <div class="control-label">' + option['name'] + '</div>';
					html += '  <div class="controls">';
					html += '    <div id="input-option' + option['product_option_id'] + '">';
					
					for (j = 0; j < option['product_option_value'].length; j++) {
						option_value = option['product_option_value'][j];
						
						html += '<label class="checkbox"><input type="checkbox" name="option[' + option['product_option_id'] + '][]" value="' + option_value['product_option_value_id'] + '" /> ' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</label>';
					}
										
					html += '    </div>';
					html += '  </div>';
					html += '</div>';
				}
			
				if (option['type'] == 'image') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="controls">';
					html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '">';
					html += '      <option value=""><?php echo $text_select; ?></option>';
				
					for (j = 0; j < option['product_option_value'].length; j++) {
						option_value = option['product_option_value'][j];
						
						html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</option>';
					}
						
					html += '    </select>';					
					html += '  </div>';
					html += '</div>';
				}
						
				if (option['type'] == 'text') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="controls"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" /></div>';
					html += '</div>';					
				}
				
				if (option['type'] == 'textarea') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="controls"><textarea name="option[' + option['product_option_id'] + ']" cols="40" rows="5" id="input-option' + option['product_option_id'] + '">' + option['value'] + '</textarea></div>';
					html += '</div>';
				}
				
				if (option['type'] == 'file') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <div class="control-label">' + option['name'] + '</div>';
					html += '  <div class="controls">';
					html += '    <button type="button" id="button-option' + option['product_option_id'] + '" class="btn" onclick="upload(\'' + option['product_option_id'] + '\');"><i class="icon-upload"></i> <?php echo $button_upload; ?></button>';
					html += '    <input type="hidden" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" />';
					html += '  </div>';
					html += '</div>';
				}
				
				if (option['type'] == 'date') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="controls"><input type="date" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" class="input-medium" /></div>';
					html += '</div>';
				}
				
				if (option['type'] == 'datetime') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="controls"><input type="datetime-local" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" /></div>';
					html += '</div>';					
				}
				
				if (option['type'] == 'time') {
					html += '<div class="control-group' + (option['required'] ? ' required' : '') + '">';
					html += '  <label class="control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
					html += '  <div class="controls"><input type="time" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" class="input-mini" /></div>';
					html += '</div>';					
				}
			}
			
			html += '</fieldset>';
			
			$('#option').html(html);			
		} else {
			$('#option').html('');
		}		
	}	
});

function upload(product_option_id) {
	$('#file').off();
	
	$('#file').on('change', function() {
		$.ajax({
			url: 'index.php?route=sale/order/upload&token=<?php echo $token; ?>',
			type: 'post',		
			dataType: 'json',
			data: new FormData($(this).parent()[0]),
			beforeSend: function() {
				$('#button-option' + product_option_id).after(' <i class="icon-spinner icon-spin"></i>');
				$('#button-option' + product_option_id).prop('disabled', true);
				$('#option' + product_option_id + ' + .error').remove();
			},	
			complete: function() {
				$('.icon-spinner').remove();
				
				$('#button-option' + product_option_id).prop('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					$('#option' + product_option_id).after('<span class="error">' + json['error'] + '</span>');
				}
							
				if (json['success']) {
					alert(json['success']);
					
					$('input[name=\'option[' + product_option_id + ']\']').attr('value', json['file']);
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});		
	
	$('input[name=\'file\']').click();
}
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'payment\']').on('change', function() {
	if (this.value) {
		$('input[name=\'payment_method\']').attr('value', $('select[name=\'payment\'] option:selected').text());
	} else {
		$('input[name=\'payment_method\']').attr('value', '');
	}
	
	$('input[name=\'payment_code\']').attr('value', this.value);
});

$('select[name=\'shipping\']').on('change', function() {
	if (this.value) {
		$('input[name=\'shipping_method\']').attr('value', $('select[name=\'shipping\'] option:selected').text());
	} else {
		$('input[name=\'shipping_method\']').attr('value', '');
	}
	
	$('input[name=\'shipping_code\']').attr('value', this.value);
});
//--></script> 
<script type="text/javascript"><!--
$('#button-product, #button-voucher, #button-update').on('click', function() {	
	data  = '#tab-customer input, #tab-customer select, #tab-customer textarea, ';
	data += '#tab-payment input, #tab-payment select, #tab-payment textarea, ';
	data += '#tab-shipping input, #tab-shipping select, #tab-shipping textarea, ';
	
	if ($(this).attr('id') == 'button-product') {
		data += '#tab-product input, #tab-product select, #tab-product textarea, ';
	} else {
		data += '#product input, #product select, #product textarea, ';
	}
	
	if ($(this).attr('id') == 'button-voucher') {
		data += '#tab-voucher input, #tab-voucher select, #tab-voucher textarea, ';
	} else {
		data += '#voucher input, #voucher select, #voucher textarea, ';
	}
	
	data += '#tab-total input, #tab-total select, #tab-total textarea';

	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=checkout/manual&token=<?php echo $token; ?>',
		type: 'post',
		data: $(data).serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#button-product i, #button-voucher i, #button-update i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-product, #button-voucher , #button-update').prop('disabled', true);
		},	
		complete: function() {
			$('#button-product i, #button-voucher i, #button-update i').replaceWith('<i class="icon-plus-sign"></i>');
			$('#button-product, #button-voucher , #button-update').prop('disabled', false);
		},		
		success: function(json) {
			$('.alert, .error .help-block').remove();
			$('.error').removeClass('error');
			
			// Check for errors
			if (json['error']) {
				if (json['error']['warning']) {
					$('.box').before('<div class="alert alert-error">' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
							
				// Order Details
				if (json['error']['customer']) {
					$('.box').before('<span class="error">' + json['error']['customer'] + '</span>');
				}	
								
				if (json['error']['firstname']) {
					$('input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				
				if (json['error']['lastname']) {
					$('input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}	
				
				if (json['error']['email']) {
					$('input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
				}
				
				if (json['error']['telephone']) {
					$('input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}	
			
				// Payment Address
				if (json['error']['payment']) {	
					if (json['error']['payment']['country']) {
						$('select[name=\'payment_country_id\']').after('<span class="error">' + json['error']['payment']['country'] + '</span>');
					}	
					
					if (json['error']['payment']['zone']) {
						$('select[name=\'payment_zone_id\']').after('<span class="error">' + json['error']['payment']['zone'] + '</span>');
					}
					
					if (json['error']['payment']['postcode']) {
						$('input[name=\'payment_postcode\']').after('<span class="error">' + json['error']['payment']['postcode'] + '</span>');
					}						
				}
			
				// Shipping	Address
				if (json['error']['shipping']) {		
					if (json['error']['shipping']['country']) {
						$('select[name=\'shipping_country_id\']').after('<span class="error">' + json['error']['shipping']['country'] + '</span>');
					}	
					
					if (json['error']['shipping_zone']) {
						$('select[name=\'shipping_zone_id\']').after('<span class="error">' + json['error']['shipping']['zone'] + '</span>');
					}
					
					if (json['error']['shipping']['postcode']) {
						$('input[name=\'shipping_postcode\']').after('<span class="error">' + json['error']['shipping']['postcode'] + '</span>');
					}	
				}
				
				// Products
				if (json['error']['product']) {
					if (json['error']['product']['option']) {	
						for (i in json['error']['product']['option']) {
							$('[for="input-option' + i + ']').parent().parent().addClass('error');
							
							$('#input-option' + i).after('<span class="help-block">' + json['error']['product']['option'][i] + '</span>');
						}
					}
					
					if (json['error']['product']['stock']) {
						$('.box').before('<div class="alert alert-error">' + json['error']['product']['stock'] + '</div>');
					}	
					
					if (json['error']['product']['store']) {
						$('.box').before('<div class="alert alert-error">' + json['error']['product']['store'] + '</div>');
					}	
																
					if (json['error']['product']['minimum']) {	
						for (i in json['error']['product']['minimum']) {
							$('.box').before('<div class="alert alert-error">' + json['error']['product']['minimum'][i] + '</div>');
						}						
					}
				} else {
					$('input[name=\'product\']').val('');
					$('input[name=\'product_id\']').val('');
					$('#option').html('');			
					$('input[name=\'quantity\']').val('1');		
				}
				
				// Voucher
				if (json['error']['vouchers']) {
					if (json['error']['vouchers']['from_name']) {
						$('input[name=\'from_name\']').after('<span class="error">' + json['error']['vouchers']['from_name'] + '</span>');
					}	
					
					if (json['error']['vouchers']['from_email']) {
						$('input[name=\'from_email\']').after('<span class="error">' + json['error']['vouchers']['from_email'] + '</span>');
					}	
								
					if (json['error']['vouchers']['to_name']) {
						$('input[name=\'to_name\']').after('<span class="error">' + json['error']['vouchers']['to_name'] + '</span>');
					}	
					
					if (json['error']['vouchers']['to_email']) {
						$('input[name=\'to_email\']').after('<span class="error">' + json['error']['vouchers']['to_email'] + '</span>');
					}	
					
					if (json['error']['vouchers']['amount']) {
						$('input[name=\'amount\']').after('<span class="error">' + json['error']['vouchers']['amount'] + '</span>');
					}	
				} else {
					$('input[name=\'from_name\']').attr('value', '');	
					$('input[name=\'from_email\']').attr('value', '');	
					$('input[name=\'to_name\']').attr('value', '');
					$('input[name=\'to_email\']').attr('value', '');	
					$('textarea[name=\'message\']').attr('value', '');	
					$('input[name=\'amount\']').attr('value', '25.00');
				}
				
				// Shipping Method	
				if (json['error']['shipping_method']) {
					$('.box').before('<div class="alert alert-error">' + json['error']['shipping_method'] + '</div>');
				}	
				
				// Payment Method
				if (json['error']['payment_method']) {
					$('.box').before('<div class="alert alert-error">' + json['error']['payment_method'] + '</div>');
				}	
															
				// Coupon
				if (json['error']['coupon']) {
					$('.box').before('<div class="alert alert-error">' + json['error']['coupon'] + '</div>');
				}
				
				// Voucher
				if (json['error']['voucher']) {
					$('.box').before('<div class="alert alert-error">' + json['error']['voucher'] + '</div>');
				}
				
				// Reward Points		
				if (json['error']['reward']) {
					$('.box').before('<div class="alert alert-error">' + json['error']['reward'] + '</div>');
				}	
			} else {
				$('input[name=\'product\']').val('');
				$('input[name=\'product_id\']').val('');
				$('#option').html('');	
				$('input[name=\'quantity\']').val('1');	
				
				$('input[name=\'from_name\']').val('');	
				$('input[name=\'from_email\']').val('');	
				$('input[name=\'to_name\']').val('');
				$('input[name=\'to_email\']').val('');	
				$('textarea[name=\'message\']').val('');	
				$('input[name=\'amount\']').val('25.00');								
			}

			if (json['success']) {
				$('.box').before('<div class="alert alert-success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');				
			}
			
			if (json['order_product'] != '') {
				var product_row = 0;
				var option_row = 0;
				var download_row = 0;
	
				html = '';
				
				for (i = 0; i < json['order_product'].length; i++) {
					product = json['order_product'][i];
					
					html += '<tr id="product-row' + product_row + '">';
					html += '  <td class="center" style="width: 3px;"><i class="icon-minus-sign" onclick="$(\'#product-row' + product_row + '\').remove(); $(\'#button-update\').trigger(\'click\');"></i></td>';
					html += '  <td class="left">' + product['name'] + '<br /><input type="hidden" name="order_product[' + product_row + '][order_product_id]" value="" /><input type="hidden" name="order_product[' + product_row + '][product_id]" value="' + product['product_id'] + '" /><input type="hidden" name="order_product[' + product_row + '][name]" value="' + product['name'] + '" />';
					
					if (product['option']) {
						for (j = 0; j < product['option'].length; j++) {
							option = product['option'][j];
							
							html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][order_option_id]" value="' + option['order_option_id'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][product_option_id]" value="' + option['product_option_id'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][product_option_value_id]" value="' + option['product_option_value_id'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][name]" value="' + option['name'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][value]" value="' + option['value'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][type]" value="' + option['type'] + '" />';
							
							option_row++;
						}
					}
					
					if (product['download']) {
						for (j = 0; j < product['download'].length; j++) {
							download = product['download'][j];
							
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][order_download_id]" value="' + download['order_download_id'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][name]" value="' + download['name'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][filename]" value="' + download['filename'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][mask]" value="' + download['mask'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][remaining]" value="' + download['remaining'] + '" />';
							
							download_row++;
						}
					}
					
					html += '  </td>';
					html += '  <td class="left">' + product['model'] + '<input type="hidden" name="order_product[' + product_row + '][model]" value="' + product['model'] + '" /></td>';
					html += '  <td class="right">' + product['quantity'] + '<input type="hidden" name="order_product[' + product_row + '][quantity]" value="' + product['quantity'] + '" /></td>';
					html += '  <td class="right">' + product['price'] + '<input type="hidden" name="order_product[' + product_row + '][price]" value="' + product['price'] + '" /></td>';
					html += '  <td class="right">' + product['total'] + '<input type="hidden" name="order_product[' + product_row + '][total]" value="' + product['total'] + '" /><input type="hidden" name="order_product[' + product_row + '][tax]" value="' + product['tax'] + '" /><input type="hidden" name="order_product[' + product_row + '][reward]" value="' + product['reward'] + '" /></td>';
					html += '</tr>';
					
					product_row++;			
				}
				
				$('#product').html(html);
			} else {				
				html  = '<tr>';
				html += '  <td colspan="6" class="center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	

				$('#product').html(html);
			}
						
			// Vouchers
			if (json['order_voucher'] != '') {
				var voucher_row = 0;
				
				 html = '';
				 
				 for (i in json['order_voucher']) {
					voucher = json['order_voucher'][i];
					 
					html += '<tr id="voucher-row' + voucher_row + '">';
					html += '  <td class="center" style="width: 3px;"><i class="icon-minus-sign" onclick="$(\'#voucher-row' + voucher_row + '\').remove(); $(\'#button-update\').trigger(\'click\');"></i></td>';
					html += '  <td class="left">' + voucher['description'];
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][order_voucher_id]" value="" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][voucher_id]" value="' + voucher['voucher_id'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][description]" value="' + voucher['description'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][code]" value="' + voucher['code'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][from_name]" value="' + voucher['from_name'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][from_email]" value="' + voucher['from_email'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][to_name]" value="' + voucher['to_name'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][to_email]" value="' + voucher['to_email'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][voucher_theme_id]" value="' + voucher['voucher_theme_id'] + '" />';	
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][message]" value="' + voucher['message'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][amount]" value="' + voucher['amount'] + '" />';
					html += '  </td>';
					html += '  <td class="left"></td>';
					html += '  <td class="right">1</td>';
					html += '  <td class="right">' + voucher['amount'] + '</td>';
					html += '  <td class="right">' + voucher['amount'] + '</td>';
					html += '</tr>';	
				  
					voucher_row++;
				}
				  
				$('#voucher').html(html);				
			} else {
				html  = '<tr>';
				html += '  <td colspan="6" class="center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	

				$('#voucher').html(html);	
			}
						
			// Totals
			if (json['order_product'] != '' || json['order_voucher'] != '' || json['order_total'] != '') {
				html = '';
				
				if (json['order_product'] != '') {
					for (i = 0; i < json['order_product'].length; i++) {
						product = json['order_product'][i];
						
						html += '<tr>';
						html += '  <td class="left">' + product['name'] + '<br />';
						
						if (product['option']) {
							for (j = 0; j < product['option'].length; j++) {
								option = product['option'][j];
								
								html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
							}
						}
						
						html += '  </td>';
						html += '  <td class="left">' + product['model'] + '</td>';
						html += '  <td class="right">' + product['quantity'] + '</td>';
						html += '  <td class="right">' + product['price'] + '</td>';
						html += '  <td class="right">' + product['total'] + '</td>';
						html += '</tr>';
					}				
				}
				
				if (json['order_voucher'] != '') {
					for (i in json['order_voucher']) {
						voucher = json['order_voucher'][i];
						 
						html += '<tr>';
						html += '  <td class="left">' + voucher['description'] + '</td>';
						html += '  <td class="left"></td>';
						html += '  <td class="right">1</td>';
						html += '  <td class="right">' + voucher['amount'] + '</td>';
						html += '  <td class="right">' + voucher['amount'] + '</td>';
						html += '</tr>';	
					}	
				}
				
				var total_row = 0;
				
				for (i in json['order_total']) {
					total = json['order_total'][i];
					
					html += '<tr id="total-row' + total_row + '">';
					html += '  <td class="right" colspan="4"><input type="hidden" name="order_total[' + total_row + '][order_total_id]" value="" /><input type="hidden" name="order_total[' + total_row + '][code]" value="' + total['code'] + '" /><input type="hidden" name="order_total[' + total_row + '][title]" value="' + total['title'] + '" /><input type="hidden" name="order_total[' + total_row + '][text]" value="' + total['text'] + '" /><input type="hidden" name="order_total[' + total_row + '][value]" value="' + total['value'] + '" /><input type="hidden" name="order_total[' + total_row + '][sort_order]" value="' + total['sort_order'] + '" />' + total['title'] + ':</td>';
					html += '  <td class="right">' + total['value'] + '</td>';
					html += '</tr>';
					
					total_row++;
				}
				
				$('#total').html(html);
			} else {
				html  = '<tr>';
				html += '  <td colspan="5" class="center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	

				$('#total').html(html);					
			}
			
			// Shipping Methods
			if (json['shipping_method']) {
				html = '<option value=""><?php echo $text_select; ?></option>';

				for (i in json['shipping_method']) {
					html += '<optgroup label="' + json['shipping_method'][i]['title'] + '">';
				
					if (!json['shipping_method'][i]['error']) {
						for (j in json['shipping_method'][i]['quote']) {
							if (json['shipping_method'][i]['quote'][j]['code'] == $('input[name=\'shipping_code\']').val()) {
								html += '<option value="' + json['shipping_method'][i]['quote'][j]['code'] + '" selected="selected">' + json['shipping_method'][i]['quote'][j]['title'] + '</option>';
							} else {
								html += '<option value="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['title'] + '</option>';
							}
						}		
					} else {
						html += '<option value="" style="color: #F00;" disabled="disabled">' + json['shipping_method'][i]['error'] + '</option>';
					}
					
					html += '</optgroup>';
				}
		
				$('select[name=\'shipping\']').html(html);	
				
				if ($('select[name=\'shipping\']').val()) {
					$('input[name=\'shipping_method\']').attr('value', $('select[name=\'shipping\']').text());
				} else {
					$('input[name=\'shipping_method\']').attr('value', '');
				}
				
				$('input[name=\'shipping_code\']').attr('value', $('select[name=\'shipping\']').val());	
			}
						
			// Payment Methods
			if (json['payment_method']) {
				html = '<option value=""><?php echo $text_select; ?></option>';
				
				for (i in json['payment_method']) {
					if (json['payment_method'][i]['code'] == $('input[name=\'payment_code\']').val()) {
						html += '<option value="' + json['payment_method'][i]['code'] + '" selected="selected">' + json['payment_method'][i]['title'] + '</option>';
					} else {
						html += '<option value="' + json['payment_method'][i]['code'] + '">' + json['payment_method'][i]['title'] + '</option>';
					}		
				}
		
				$('select[name=\'payment\']').html(html);
				
				if ($('select[name=\'payment\']').val()) {
					$('input[name=\'payment_method\']').attr('value', $('select[name=\'payment\']').text());
				} else {
					$('input[name=\'payment_method\']').attr('value', '');
				}
				
				$('input[name=\'payment_code\']').attr('value', $('select[name=\'payment\']').val());
			}	
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
//--></script> 
<?php echo $footer; ?>