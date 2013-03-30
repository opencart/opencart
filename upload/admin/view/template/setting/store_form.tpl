<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><a onclick="$('#form').submit();" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></a> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-store" data-toggle="tab"><?php echo $tab_store; ?></a></li>
          <li><a href="#tab-local" data-toggle="tab"><?php echo $tab_local; ?></a></li>
          <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
          <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
          <li><a href="#tab-server" data-toggle="tab"><?php echo $tab_server; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_url; ?></label>
              <div class="controls">
                <input type="text" name="config_url" value="<?php echo $config_url; ?>" size="40" /> <span class="help-block"><?php echo $help_url; ?></span>
                <?php if ($error_url) { ?>
                <span class="error"><?php echo $error_url; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_ssl; ?></label>
              <div class="controls">
                <input type="text" name="config_ssl" value="<?php echo $config_ssl; ?>" size="40" /> <span class="help-block"><?php echo $help_ssl; ?></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_name; ?></label>
              <div class="controls">
                <input type="text" name="config_name" value="<?php echo $config_name; ?>" size="40" />
                <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_owner; ?></label>
              <div class="controls">
                <input type="text" name="config_owner" value="<?php echo $config_owner; ?>" size="40" />
                <?php if ($error_owner) { ?>
                <span class="error"><?php echo $error_owner; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_address; ?></label>
              <div class="controls">
                <textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
                <?php if ($error_address) { ?>
                <span class="error"><?php echo $error_address; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_email; ?></label>
              <div class="controls">
                <input type="text" name="config_email" value="<?php echo $config_email; ?>" size="40" />
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_telephone; ?></label>
              <div class="controls">
                <input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" />
                <?php if ($error_telephone) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_fax; ?></label>
              <div class="controls">
                <input type="text" name="config_fax" value="<?php echo $config_fax; ?>" />
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-store">
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_title; ?></label>
              <div class="controls">
                <input type="text" name="config_title" value="<?php echo $config_title; ?>" />
                <?php if ($error_title) { ?>
                <span class="error"><?php echo $error_title; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_meta_description; ?></label>
              <div class="controls">
                <textarea name="config_meta_description" cols="40" rows="5"><?php echo $config_meta_description; ?></textarea>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_template; ?></label>
              <div class="controls">
                <select name="config_template" onchange="$('#template').load('index.php?route=setting/store/template&token=<?php echo $token; ?>&template=' + encodeURIComponent(this.value));">
                  <?php foreach ($templates as $template) { ?>
                  <?php if ($template == $config_template) { ?>
                  <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <div id="template"> </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_layout; ?></label>
              <div class="controls">
                <select name="config_layout_id">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $config_layout_id) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-local">
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_country; ?></label>
              <div class="controls">
                <select name="config_country_id">
                  <?php foreach ($countries as $country) { ?>
                  <?php if ($country['country_id'] == $config_country_id) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_zone; ?></label>
              <div class="controls">
                <select name="config_zone_id">
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_language; ?></label>
              <div class="controls">
                <select name="config_language">
                  <?php foreach ($languages as $language) { ?>
                  <?php if ($language['code'] == $config_language) { ?>
                  <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_currency; ?></label>
              <div class="controls">
                <select name="config_currency">
                  <?php foreach ($currencies as $currency) { ?>
                  <?php if ($currency['code'] == $config_currency) { ?>
                  <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-option">
            <h2><?php echo $text_items; ?></h2>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_catalog_limit; ?></label>
              <div class="controls">
                <input type="text" name="config_catalog_limit" value="<?php echo $config_catalog_limit; ?>" class="input-small" /> <span class="help-block"><?php echo $help_catalog_limit; ?></span>
                <?php if ($error_catalog_limit) { ?>
                <span class="error"><?php echo $error_catalog_limit; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_list_description_limit; ?></label>
              <div class="controls">
                <input type="text" name="config_list_description_limit" value="<?php echo $config_list_description_limit; ?>" class="input-small" /> <span class="help-block"><?php echo $help_list_description_limit; ?></span>
                <?php if ($error_list_description_limit) { ?>
                <span class="error"><?php echo $error_list_description_limit; ?></span>
                <?php } ?>
              </div>
            </div>
            <h2><?php echo $text_tax; ?></h2>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_tax; ?></label>
              <div class="controls">
                <?php if ($config_tax) { ?>
                <input type="radio" name="config_tax" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_tax" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_tax" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_tax" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_tax_default; ?></label>
              <div class="controls">
                <select name="config_tax_default">
                  <option value=""><?php echo $text_none; ?></option>
                  <?php  if ($config_tax_default == 'shipping') { ?>
                  <option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
                  <?php } else { ?>
                  <option value="shipping"><?php echo $text_shipping; ?></option>
                  <?php } ?>
                  <?php  if ($config_tax_default == 'payment') { ?>
                  <option value="payment" selected="selected"><?php echo $text_payment; ?></option>
                  <?php } else { ?>
                  <option value="payment"><?php echo $text_payment; ?></option>
                  <?php } ?>
                </select> <span class="help-block"><?php echo $help_tax_default; ?></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_tax_customer; ?></label>
              <div class="controls">
                <select name="config_tax_customer">
                  <option value=""><?php echo $text_none; ?></option>
                  <?php  if ($config_tax_customer == 'shipping') { ?>
                  <option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
                  <?php } else { ?>
                  <option value="shipping"><?php echo $text_shipping; ?></option>
                  <?php } ?>
                  <?php  if ($config_tax_customer == 'payment') { ?>
                  <option value="payment" selected="selected"><?php echo $text_payment; ?></option>
                  <?php } else { ?>
                  <option value="payment"><?php echo $text_payment; ?></option>
                  <?php } ?>
                </select> <span class="help-block"><?php echo $help_tax_customer; ?></span>
              </div>
            </div>
            <h2><?php echo $text_account; ?></h2>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_customer_group; ?></label>
              <div class="controls">
                <select name="config_customer_group_id">
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $config_customer_group_id) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select> <span class="help-block"><?php echo $help_customer_group; ?></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_customer_group_display; ?></label>
              <div class="controls">
                <div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($customer_group['customer_group_id'], $config_customer_group_display)) { ?>
                    <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                    <?php echo $customer_group['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                    <?php echo $customer_group['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div> <span class="help-block"><?php echo $help_customer_group_display; ?></span>
                <?php if ($error_customer_group_display) { ?>
                <span class="error"><?php echo $error_customer_group_display; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_customer_price; ?></label>
              <div class="controls">
                <?php if ($config_customer_price) { ?>
                <input type="radio" name="config_customer_price" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_price" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_customer_price" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_price" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?> <span class="help-block"><?php echo $help_customer_price; ?></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_account; ?></label>
              <div class="controls">
                <select name="config_account_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($informations as $information) { ?>
                  <?php if ($information['information_id'] == $config_account_id) { ?>
                  <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select> <span class="help-block"><?php echo $help_account; ?></span>
              </div>
            </div>
            <h2><?php echo $text_checkout; ?></h2>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_cart_weight; ?></label>
              <div class="controls">
                <?php if ($config_cart_weight) { ?>
                <input type="radio" name="config_cart_weight" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_cart_weight" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_cart_weight" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_cart_weight" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_guest_checkout; ?></label>
              <div class="controls">
                <?php if ($config_guest_checkout) { ?>
                <input type="radio" name="config_guest_checkout" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_guest_checkout" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_guest_checkout" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_guest_checkout" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?> <span class="help-block"><?php echo $help_guest_checkout; ?></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_checkout; ?></label>
              <div class="controls">
                <select name="config_checkout_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($informations as $information) { ?>
                  <?php if ($information['information_id'] == $config_checkout_id) { ?>
                  <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $help_checkout; ?></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_order_status; ?></label>
              <div class="controls">
                <select name="config_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $help_order_status; ?></span>
              </div>
            </div>
            <h2><?php echo $text_stock; ?></h2>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_stock_display; ?></label>
              <div class="controls">
                <?php if ($config_stock_display) { ?>
                <input type="radio" name="config_stock_display" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_stock_display" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_stock_display" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_stock_display" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?>
                <span class="help-block"><?php echo $help_stock_display; ?></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_stock_checkout; ?></label>
              <div class="controls">
                <?php if ($config_stock_checkout) { ?>
                <input type="radio" name="config_stock_checkout" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_stock_checkout" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_stock_checkout" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_stock_checkout" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?>
                <span class="help-block"><?php echo $help_stock_checkout; ?></span>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-image">
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_logo; ?></label>
              <div class="controls">
                <div class="image"><img src="<?php echo $logo; ?>" alt="" id="thumb-logo" />
                  <input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="logo" />
                  <br />
                  <a onclick="image_upload('logo', 'thumb-logo');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-logo').attr('src', '<?php echo $no_image; ?>'); $('#logo').attr('value', '');"><?php echo $text_clear; ?></a></div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_icon; ?></label>
              <div class="controls">
                <div class="image"><img src="<?php echo $icon; ?>" alt="" id="thumb-icon" />
                  <input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="icon" /> <span class="help-block"><?php echo $help_icon; ?></span>
                  <br />
                  <a onclick="image_upload('icon', 'thumb-icon');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-icon').attr('src', '<?php echo $no_image; ?>'); $('#icon').attr('value', '');"><?php echo $text_clear; ?></a></div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image_category; ?></label>
              <div class="controls">
                <input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" class="input-small" />
                x
                <input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" class="input-small" />
                <?php if ($error_image_category) { ?>
                <span class="error"><?php echo $error_image_category; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image_thumb; ?></label>
              <div class="controls">
                <input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" class="input-small" />
                x
                <input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" class="input-small" />
                <?php if ($error_image_thumb) { ?>
                <span class="error"><?php echo $error_image_thumb; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image_popup; ?></label>
              <div class="controls">
                <input type="text" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" class="input-small" />
                x
                <input type="text" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" class="input-small" />
                <?php if ($error_image_popup) { ?>
                <span class="error"><?php echo $error_image_popup; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image_product; ?></label>
              <div class="controls">
                <input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" class="input-small" />
                x
                <input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" class="input-small" />
                <?php if ($error_image_product) { ?>
                <span class="error"><?php echo $error_image_product; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image_additional; ?></label>
              <div class="controls">
                <input type="text" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" class="input-small" />
                x
                <input type="text" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" class="input-small" />
                <?php if ($error_image_additional) { ?>
                <span class="error"><?php echo $error_image_additional; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image_related; ?></label>
              <div class="controls">
                <input type="text" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" class="input-small" />
                x
                <input type="text" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" class="input-small" />
                <?php if ($error_image_related) { ?>
                <span class="error"><?php echo $error_image_related; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image_compare; ?></label>
              <div class="controls">
                <input type="text" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" class="input-small" />
                x
                <input type="text" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" class="input-small" />
                <?php if ($error_image_compare) { ?>
                <span class="error"><?php echo $error_image_compare; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image_wishlist; ?></label>
              <div class="controls">
                <input type="text" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" class="input-small" />
                x
                <input type="text" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" class="input-small" />
                <?php if ($error_image_wishlist) { ?>
                <span class="error"><?php echo $error_image_wishlist; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_image_cart; ?></label>
              <div class="controls">
                <input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" class="input-small" />
                x
                <input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" class="input-small" />
                <?php if ($error_image_cart) { ?>
                <span class="error"><?php echo $error_image_cart; ?></span>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-server">
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_secure; ?></label>
              <div class="controls">
                <?php if ($config_secure) { ?>
                <input type="radio" name="config_secure" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_secure" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_secure" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_secure" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?>
                <span class="help-block"><?php echo $help_secure; ?></span>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#template').load('index.php?route=setting/store/template&token=<?php echo $token; ?>&template=' + encodeURIComponent($('select[name=\'config_template\']').attr('value')));
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'config_country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=setting/store/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		},		
		complete: function() {
			$('.loading').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $config_zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'config_zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'config_country_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<?php echo $footer; ?>