<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-store" data-toggle="tab"><?php echo $tab_store; ?></a></li>
          <li><a href="#tab-local" data-toggle="tab"><?php echo $tab_local; ?></a></li>
          <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
          <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
          <li><a href="#tab-ftp" data-toggle="tab"><?php echo $tab_ftp; ?></a></li>
          <li><a href="#tab-mail" data-toggle="tab"><?php echo $tab_mail; ?></a></li>
          <li><a href="#tab-fraud" data-toggle="tab"><?php echo $tab_fraud; ?></a></li>
          <li><a href="#tab-server" data-toggle="tab"><?php echo $tab_server; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_name" value="<?php echo $config_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                <?php if ($error_name) { ?>
                <div class="text-danger"><?php echo $error_name; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-owner"><?php echo $entry_owner; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_owner" value="<?php echo $config_owner; ?>" placeholder="<?php echo $entry_owner; ?>" id="input-owner" class="form-control" />
                <?php if ($error_owner) { ?>
                <div class="text-danger"><?php echo $error_owner; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
              <div class="col-sm-10">
                <textarea name="config_address" placeholder="<?php echo $entry_address; ?>" rows="5" id="input-address" class="form-control"><?php echo $config_address; ?></textarea>
                <?php if ($error_address) { ?>
                <div class="text-danger"><?php echo $error_address; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-geocode"><span data-toggle="tooltip" title="<?php echo $help_geocode; ?>"><?php echo $entry_geocode; ?></span></label>
              <div class="col-sm-10">
                <input type="text" name="config_geocode" value="<?php echo $config_geocode; ?>" placeholder="<?php echo $entry_geocode; ?>" id="input-geocode" class="form-control" /></div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_email" value="<?php echo $config_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                <?php if ($error_email) { ?>
                <div class="text-danger"><?php echo $error_email; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                <?php if ($error_telephone) { ?>
                <div class="text-danger"><?php echo $error_telephone; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_fax" value="<?php echo $config_fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
              <div class="col-sm-10">
                <?php if ($thumb) { ?>
                <a href="" id="thumb-image" class="img-thumbnail img-edit"><img src="<?php echo $thumb; ?>" alt="" title="" /></a>
                <?php } else { ?>
                <a href="" id="thumb-image" class="img-thumbnail img-edit"><i class="fa fa-camera fa-5x"></i></a>
                <?php } ?>
                <input type="hidden" name="config_image" value="<?php echo $config_image; ?>" id="input-image" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-open"><span data-toggle="tooltip" title="<?php echo $help_open; ?>"><?php echo $entry_open; ?></span></label>
              <div class="col-sm-10">
                <textarea name="config_open" rows="5" placeholder="<?php echo $entry_open; ?>" id="input-open" class="form-control"><?php echo $config_open; ?></textarea></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-comment"><span data-toggle="tooltip" title="<?php echo $help_comment; ?>"><?php echo $entry_comment; ?></span></label>
              <div class="col-sm-10">
                <textarea name="config_comment" rows="5" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control"><?php echo $config_comment; ?></textarea></div>
            </div>
            <?php if ($locations) { ?>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_location; ?>"><?php echo $entry_location; ?></span></label>
              <div class="col-sm-10">
                <?php foreach ($locations as $location) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($location['location_id'], $config_location)) { ?>
                    <input type="checkbox" name="config_location[]" value="<?php echo $location['location_id']; ?>" checked="checked" />
                    <?php echo $location['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="config_location[]" value="<?php echo $location['location_id']; ?>" />
                    <?php echo $location['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
          </div>
          <div class="tab-pane" id="tab-store">
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-meta-title"><?php echo $entry_meta_title; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_meta_title" value="<?php echo $config_meta_title; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                <?php if ($error_meta_title) { ?>
                <div class="text-danger"><?php echo $error_meta_title; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-meta-description"><?php echo $entry_meta_description; ?></label>
              <div class="col-sm-10">
                <textarea name="config_meta_description" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description" class="form-control"><?php echo $config_meta_description; ?></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-template"><?php echo $entry_template; ?></label>
              <div class="col-sm-10">
                <select name="config_template" id="input-template" class="form-control">
                  <?php foreach ($templates as $template) { ?>
                  <?php if ($template == $config_template) { ?>
                  <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <br />
                <img src="" alt="" id="template" class="img-thumbnail" /></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-layout"><?php echo $entry_layout; ?></label>
              <div class="col-sm-10">
                <select name="config_layout_id" id="input-layout" class="form-control">
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
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
              <div class="col-sm-10">
                <select name="config_country_id" id="input-country" class="form-control">
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
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
              <div class="col-sm-10">
                <select name="config_zone_id" id="input-zone" class="form-control">
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-language"><?php echo $entry_language; ?></label>
              <div class="col-sm-10">
                <select name="config_language" id="input-language" class="form-control">
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
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-admin-language"><?php echo $entry_admin_language; ?></label>
              <div class="col-sm-10">
                <select name="config_admin_language" id="input-admin-language" class="form-control">
                  <?php foreach ($languages as $language) { ?>
                  <?php if ($language['code'] == $config_admin_language) { ?>
                  <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-currency"><span data-toggle="tooltip" title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></label>
              <div class="col-sm-10">
                <select name="config_currency" id="input-currency" class="form-control">
                  <?php foreach ($currencies as $currency) { ?>
                  <?php if ($currency['code'] == $config_currency) { ?>
                  <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="help-block"><?php echo $help_currency_auto; ?><?php echo $entry_currency_auto; ?></span></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_currency_auto) { ?>
                  <input type="radio" name="config_currency_auto" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_currency_auto" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_currency_auto) { ?>
                  <input type="radio" name="config_currency_auto" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_currency_auto" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-length-class"><?php echo $entry_length_class; ?></label>
              <div class="col-sm-10">
                <select name="config_length_class_id" id="input-length-class" class="form-control">
                  <?php foreach ($length_classes as $length_class) { ?>
                  <?php if ($length_class['length_class_id'] == $config_length_class_id) { ?>
                  <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
              <div class="col-sm-10">
                <select name="config_weight_class_id" id="input-weight-class" class="form-control">
                  <?php foreach ($weight_classes as $weight_class) { ?>
                  <?php if ($weight_class['weight_class_id'] == $config_weight_class_id) { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-option">
            <fieldset>
              <legend><?php echo $text_product; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_product_count; ?>"><?php echo $entry_product_count; ?></span></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_product_count) { ?>
                    <input type="radio" name="config_product_count" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_product_count" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_product_count) { ?>
                    <input type="radio" name="config_product_count" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_product_count" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label></div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-catalog-limit"><?php echo $entry_product_limit; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="config_product_limit" value="<?php echo $config_product_limit; ?>" placeholder="<?php echo $entry_product_limit; ?>" id="input-catalog-limit" class="form-control" />
                  <span class="help-block"><?php echo $help_product_limit; ?></span>
                  <?php if ($error_product_limit) { ?>
                  <div class="text-danger"><?php echo $error_product_limit; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-list-description-limit"><?php echo $entry_product_description_length; ?> </label>
                <div class="col-sm-10">
                  <input type="text" name="config_product_description_length" value="<?php echo $config_product_description_length; ?>" placeholder="<?php echo $entry_product_description_length; ?>" id="input-list-description-limit" class="form-control" />
                  <span class="help-block"><?php echo $help_product_description_length; ?></span>
                  <?php if ($error_product_description_length) { ?>
                  <div class="text-danger"><?php echo $error_product_description_length; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-admin-limit"><?php echo $entry_limit_admin; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="config_limit_admin" value="<?php echo $config_limit_admin; ?>" placeholder="<?php echo $entry_limit_admin; ?>" id="input-admin-limit" class="form-control" />
                  <span class="help-block"><?php echo $help_limit_admin; ?></span>
                  <?php if ($error_limit_admin) { ?>
                  <div class="text-danger"><?php echo $error_limit_admin; ?></div>
                  <?php } ?>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_review; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_review; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_review_status) { ?>
                    <input type="radio" name="config_review_status" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_review_status" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_review_status) { ?>
                    <input type="radio" name="config_review_status" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_review_status" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_review; ?></span></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_review_guest; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_review_guest) { ?>
                    <input type="radio" name="config_review_guest" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_review_guest" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_review_guest) { ?>
                    <input type="radio" name="config_review_guest" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_review_guest" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_review_guest; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_review_mail; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_review_mail) { ?>
                    <input type="radio" name="config_review_mail" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_review_mail" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_review_mail) { ?>
                    <input type="radio" name="config_review_mail" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_review_mail" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_review_mail; ?></span> </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_voucher; ?></legend>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-voucher-min"><?php echo $entry_voucher_min; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="config_voucher_min" value="<?php echo $config_voucher_min; ?>" placeholder="<?php echo $entry_voucher_min; ?>" id="input-voucher-min" class="form-control" />
                  <span class="help-block"><?php echo $help_voucher_min; ?></span>
                  <?php if ($error_voucher_min) { ?>
                  <div class="text-danger"><?php echo $error_voucher_min; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-voucher-max"><?php echo $entry_voucher_max; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="config_voucher_max" value="<?php echo $config_voucher_max; ?>" placeholder="<?php echo $entry_voucher_max; ?>" id="input-voucher-max" class="form-control" />
                  <span class="help-block"><?php echo $help_voucher_max; ?></span>
                  <?php if ($error_voucher_max) { ?>
                  <div class="text-danger"><?php echo $error_voucher_max; ?></div>
                  <?php } ?>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_tax; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_tax; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_tax) { ?>
                    <input type="radio" name="config_tax" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_tax" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_tax) { ?>
                    <input type="radio" name="config_tax" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_tax" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tax-default"><?php echo $entry_tax_default; ?></label>
                <div class="col-sm-10">
                  <select name="config_tax_default" id="input-tax-default" class="form-control">
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
                  </select>
                  <span class="help-block"><?php echo $help_tax_default; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tax-customer"><?php echo $entry_tax_customer; ?></label>
                <div class="col-sm-10">
                  <select name="config_tax_customer" id="input-tax-customer" class="form-control">
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
                  </select>
                  <span class="help-block"><?php echo $help_tax_customer; ?></span> </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_account; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_customer_online; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_customer_online) { ?>
                    <input type="radio" name="config_customer_online" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_customer_online" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_customer_online) { ?>
                    <input type="radio" name="config_customer_online" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_customer_online" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_customer_online; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                <div class="col-sm-10">
                  <select name="config_customer_group_id" id="input-customer-group" class="form-control">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $config_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_customer_group; ?></span></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_customer_group_display; ?> </label>
                <div class="col-sm-10">
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <div class="checkbox">
                    <label>
                      <?php if (in_array($customer_group['customer_group_id'], $config_customer_group_display)) { ?>
                      <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                      <?php echo $customer_group['name']; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                      <?php echo $customer_group['name']; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <?php } ?>
                  <span class="help-block"><?php echo $help_customer_group_display; ?></span>
                  <?php if ($error_customer_group_display) { ?>
                  <div class="text-danger"><?php echo $error_customer_group_display; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_customer_price; ?>s</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_customer_price) { ?>
                    <input type="radio" name="config_customer_price" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_customer_price" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_customer_price) { ?>
                    <input type="radio" name="config_customer_price" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_customer_price" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_customer_price; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-account"><?php echo $entry_account; ?></label>
                <div class="col-sm-10">
                  <select name="config_account_id" id="input-account" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($informations as $information) { ?>
                    <?php if ($information['information_id'] == $config_account_id) { ?>
                    <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_account; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_account_mail; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_account_mail) { ?>
                    <input type="radio" name="config_account_mail" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_account_mail" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_account_mail) { ?>
                    <input type="radio" name="config_account_mail" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_account_mail" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_account_mail; ?></span> </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_checkout; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_cart_weight; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_cart_weight) { ?>
                    <input type="radio" name="config_cart_weight" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_cart_weight" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_cart_weight) { ?>
                    <input type="radio" name="config_cart_weight" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_cart_weight" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_cart_weight; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_checkout_guest; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_checkout_guest) { ?>
                    <input type="radio" name="config_checkout_guest" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_checkout_guest" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_checkout_guest) { ?>
                    <input type="radio" name="config_checkout_guest" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_checkout_guest" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_checkout_guest; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-checkout"><?php echo $entry_checkout; ?></label>
                <div class="col-sm-10">
                  <select name="config_checkout_id" id="input-checkout" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($informations as $information) { ?>
                    <?php if ($information['information_id'] == $config_checkout_id) { ?>
                    <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_checkout; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-edit"><?php echo $entry_order_edit; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="config_order_edit" value="<?php echo $config_order_edit; ?>" placeholder="<?php echo $entry_order_edit; ?>" id="input-order-edit" class="form-control" />
                  <span class="help-block"><?php echo $help_order_edit; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-invoice-prefix"><?php echo $entry_invoice_prefix; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="config_invoice_prefix" value="<?php echo $config_invoice_prefix; ?>" placeholder="<?php echo $entry_invoice_prefix; ?>" id="input-invoice-prefix" class="form-control" />
                  <span class="help-block"><?php echo $help_invoice_prefix; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <div class="col-sm-10">
                  <select name="config_order_status_id" id="input-order-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_order_status; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-complete-status"><?php echo $entry_complete_status; ?></label>
                <div class="col-sm-10">
                  <select name="config_complete_status_id" id="input-complete-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $config_complete_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_complete_status; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_order_mail; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_order_mail) { ?>
                    <input type="radio" name="config_order_mail" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_order_mail" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_order_mail) { ?>
                    <input type="radio" name="config_order_mail" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_order_mail" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_order_mail; ?></span> </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_stock; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_stock_display; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_stock_display) { ?>
                    <input type="radio" name="config_stock_display" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_stock_display" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_stock_display) { ?>
                    <input type="radio" name="config_stock_display" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_stock_display" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_stock_display; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_stock_warning; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_stock_warning) { ?>
                    <input type="radio" name="config_stock_warning" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_stock_warning" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_stock_warning) { ?>
                    <input type="radio" name="config_stock_warning" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_stock_warning" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_stock_warning; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_stock_checkout; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_stock_checkout) { ?>
                    <input type="radio" name="config_stock_checkout" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_stock_checkout" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_stock_checkout) { ?>
                    <input type="radio" name="config_stock_checkout" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_stock_checkout" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_stock_checkout; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-stock-status"><?php echo $entry_stock_status; ?></label>
                <div class="col-sm-10">
                  <select name="config_stock_status_id" id="input-stock-status" class="form-control">
                    <?php foreach ($stock_statuses as $stock_status) { ?>
                    <?php if ($stock_status['stock_status_id'] == $config_stock_status_id) { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_stock_status; ?></span> </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_affiliate; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_affiliate_approval; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_affiliate_approval) { ?>
                    <input type="radio" name="config_affiliate_approval" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_affiliate_approval" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_affiliate_approval) { ?>
                    <input type="radio" name="config_affiliate_approval" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_affiliate_approval" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_affiliate_approval; ?></span></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_affiliate_auto; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_stock_checkout) { ?>
                    <input type="radio" name="config_affiliate_auto" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_affiliate_auto" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_stock_checkout) { ?>
                    <input type="radio" name="config_affiliate_auto" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_affiliate_auto" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_affiliate_auto; ?></span></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-affiliate-commission"><?php echo $entry_affiliate_commission; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="config_affiliate_commission" value="<?php echo $config_affiliate_commission; ?>" placeholder="<?php echo $entry_affiliate_commission; ?>" id="input-affiliate-commission" class="form-control" />
                  <span class="help-block"><?php echo $help_affiliate_commission; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-affiliate"><?php echo $entry_affiliate; ?></label>
                <div class="col-sm-10">
                  <select name="config_affiliate_id" id="input-affiliate" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($informations as $information) { ?>
                    <?php if ($information['information_id'] == $config_affiliate_id) { ?>
                    <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_affiliate; ?></span></div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_affiliate_mail; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($config_affiliate_mail) { ?>
                    <input type="radio" name="config_affiliate_mail" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_affiliate_mail" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$config_affiliate_mail) { ?>
                    <input type="radio" name="config_affiliate_mail" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="config_affiliate_mail" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                  <span class="help-block"><?php echo $help_affiliate_mail; ?></span> </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_return; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-return"><?php echo $entry_return; ?></label>
                <div class="col-sm-10">
                  <select name="config_return_id" id="input-return" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($informations as $information) { ?>
                    <?php if ($information['information_id'] == $config_return_id) { ?>
                    <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_return; ?></span> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-return-status"><?php echo $entry_return_status; ?></label>
                <div class="col-sm-10">
                  <select name="config_return_status_id" id="input-return-status" class="form-control">
                    <?php foreach ($return_statuses as $return_status) { ?>
                    <?php if ($return_status['return_status_id'] == $config_return_status_id) { ?>
                    <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <span class="help-block"><?php echo $help_return_status; ?></span> </div>
              </div>
            </fieldset>
          </div>
          <div class="tab-pane" id="tab-image">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_logo; ?></label>
              <div class="col-sm-10">
                <?php if ($logo) { ?>
                <a href="" id="thumb-logo" class="img-thumbnail img-edit"><img src="<?php echo $logo; ?>" alt="" title="" /></a>
                <?php } else { ?>
                <a href="" id="thumb-logo" class="img-thumbnail img-edit"><i class="fa fa-camera fa-5x"></i></a>
                <?php } ?>
                <input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="input-logo" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-icon"><?php echo $entry_icon; ?></label>
              <div class="col-sm-10">
                <?php if ($icon) { ?>
                <a href="" id="thumb-icon" class="img-thumbnail img-edit"><img src="<?php echo $icon; ?>" alt="" title="" /></a>
                <?php } else { ?>
                <a href="" id="thumb-icon" class="img-thumbnail img-edit"><i class="fa fa-camera fa-5x"></i></a>
                <?php } ?>
                <input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="input-icon" />
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-category-width"><?php echo $entry_image_category; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-category-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_category) { ?>
                <div class="text-danger"><?php echo $error_image_category; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-thumb-width"><?php echo $entry_image_thumb; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-thumb-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_thumb) { ?>
                <div class="text-danger"><?php echo $error_image_thumb; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-popup-width"><?php echo $entry_image_popup; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-popup-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_popup) { ?>
                <div class="text-danger"><?php echo $error_image_popup; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-product-width"><?php echo $entry_image_product; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-product-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_product) { ?>
                <div class="text-danger"><?php echo $error_image_product; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-additional-width"><?php echo $entry_image_additional; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-additional-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_additional) { ?>
                <div class="text-danger"><?php echo $error_image_additional; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-related"><?php echo $entry_image_related; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-related" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_related) { ?>
                <div class="text-danger"><?php echo $error_image_related; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-compare"><?php echo $entry_image_compare; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-compare" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_compare) { ?>
                <div class="text-danger"><?php echo $error_image_compare; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-wishlist"><?php echo $entry_image_wishlist; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-wishlist" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_wishlist) { ?>
                <div class="text-danger"><?php echo $error_image_wishlist; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-cart"><?php echo $entry_image_cart; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-cart" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_cart) { ?>
                <div class="text-danger"><?php echo $error_image_cart; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-location"><?php echo $entry_image_location; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="config_image_location_width" value="<?php echo $config_image_location_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-location" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="config_image_location_height" value="<?php echo $config_image_location_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($error_image_location) { ?>
                <div class="text-danger"><?php echo $error_image_location; ?></div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-ftp">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-ftp-host"><?php echo $entry_ftp_hostname; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_ftp_hostname" value="<?php echo $config_ftp_hostname; ?>" placeholder="<?php echo $entry_ftp_hostname; ?>" id="input-ftp-host" class="form-control" />
                <?php if ($error_ftp_hostname) { ?>
                <div class="text-danger"><?php echo $error_ftp_hostname; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-ftp-port"><?php echo $entry_ftp_port; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_ftp_port" value="<?php echo $config_ftp_port; ?>" placeholder="<?php echo $entry_ftp_port; ?>" id="input-ftp-port" class="form-control" />
                <?php if ($error_ftp_port) { ?>
                <div class="text-danger"><?php echo $error_ftp_port; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-ftp-username"><?php echo $entry_ftp_username; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_ftp_username" value="<?php echo $config_ftp_username; ?>" placeholder="<?php echo $entry_ftp_username; ?>" id="input-ftp-username" class="form-control" />
                <?php if ($error_ftp_username) { ?>
                <div class="text-danger"><?php echo $error_ftp_username; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-ftp-password"><?php echo $entry_ftp_password; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_ftp_password" value="<?php echo $config_ftp_password; ?>" placeholder="<?php echo $entry_ftp_password; ?>" id="input-ftp-password" class="form-control" />
                <?php if ($error_ftp_password) { ?>
                <div class="text-danger"><?php echo $error_ftp_password; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-ftp-root"><?php echo $entry_ftp_root; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_ftp_root" value="<?php echo $config_ftp_root; ?>" placeholder="<?php echo $entry_ftp_root; ?>" id="input-ftp-root" class="form-control" />
                <span class="help-block"><?php echo $help_ftp_root; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_ftp_status; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_ftp_status) { ?>
                  <input type="radio" name="config_ftp_status" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_ftp_status" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_ftp_status) { ?>
                  <input type="radio" name="config_ftp_status" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_ftp_status" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-mail">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-mail-protocol"><?php echo $entry_mail_protocol; ?></label>
              <div class="col-sm-10">
                <select name="config_mail[protocol]" id="input-mail-protocol" class="form-control">
                  <?php if ($config_mail_protocol == 'mail') { ?>
                  <option value="mail" selected="selected"><?php echo $text_mail; ?></option>
                  <?php } else { ?>
                  <option value="mail"><?php echo $text_mail; ?></option>
                  <?php } ?>
                  <?php if ($config_mail_protocol == 'smtp') { ?>
                  <option value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
                  <?php } else { ?>
                  <option value="smtp"><?php echo $text_smtp; ?></option>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $help_mail_protocol; ?></span></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-mail-parameter"><?php echo $entry_mail_parameter; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_mail[parameter]" value="<?php echo $config_mail_parameter; ?>" placeholder="<?php echo $entry_mail_parameter; ?>" id="input-mail-parameter" class="form-control" />
                <span class="help-block"><?php echo $help_mail_parameter; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-smtp-host"><?php echo $entry_smtp_hostname; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_mail[smtp_hostname]" value="<?php echo $config_smtp_hostname; ?>" placeholder="<?php echo $entry_smtp_hostname; ?>" id="input-smtp-host" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-smtp-username"><?php echo $entry_smtp_username; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_mail[smtp_username]" value="<?php echo $config_smtp_username; ?>" placeholder="<?php echo $entry_smtp_username; ?>" id="input-smtp-username" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-smtp-password"><?php echo $entry_smtp_password; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_mail[smtp_password]" value="<?php echo $config_smtp_password; ?>" placeholder="<?php echo $entry_smtp_password; ?>" id="input-smtp-password" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-smtp-port"><?php echo $entry_smtp_port; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_mail[smtp_port]" value="<?php echo $config_smtp_port; ?>" placeholder="<?php echo $entry_smtp_port; ?>" id="input-smtp-port" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-smtp-timeout"><?php echo $entry_smtp_timeout; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_mail[smtp_timeout]" value="<?php echo $config_smtp_timeout; ?>" placeholder="<?php echo $entry_smtp_timeout; ?>" id="input-smtp-timeout" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-alert-email"><?php echo $entry_mail_alert; ?></label>
              <div class="col-sm-10">
                <textarea name="config_mail_alert" rows="5" placeholder="<?php echo $entry_mail_alert; ?>" id="input-alert-email" class="form-control"><?php echo $config_mail_alert; ?></textarea>
                <span class="help-block"><?php echo $help_mail_alert; ?></span> </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-fraud">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_fraud_detection; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_fraud_detection) { ?>
                  <input type="radio" name="config_fraud_detection" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_fraud_detection" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_fraud_detection) { ?>
                  <input type="radio" name="config_fraud_detection" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_fraud_detection" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
                <span class="help-block"><?php echo $help_fraud_detection; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-fraud-key"><?php echo $entry_fraud_key; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_fraud_key" value="<?php echo $config_fraud_key; ?>" placeholder="<?php echo $entry_fraud_key; ?>" id="input-fraud-key" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-fraud-score"><?php echo $entry_fraud_score; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_fraud_score" value="<?php echo $config_fraud_score; ?>" placeholder="<?php echo $entry_fraud_score; ?>" id="input-fraud-score" class="form-control" />
                <span class="help-block"><?php echo $help_fraud_score; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-fraud-status"><?php echo $entry_fraud_status; ?></label>
              <div class="col-sm-10">
                <select name="config_fraud_status_id" id="input-fraud-status" class="form-control">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $config_fraud_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $help_fraud_status; ?></span> </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-server">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_secure; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_secure) { ?>
                  <input type="radio" name="config_secure" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_secure" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_secure) { ?>
                  <input type="radio" name="config_secure" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_secure" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
                <span class="help-block"><?php echo $help_secure; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_shared; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_shared) { ?>
                  <input type="radio" name="config_shared" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_shared" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_shared) { ?>
                  <input type="radio" name="config_shared" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_shared" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
                <span class="help-block"><?php echo $help_shared; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-robots"><?php echo $entry_robots; ?></label>
              <div class="col-sm-10">
                <textarea name="config_robots" rows="5" placeholder="<?php echo $entry_robots; ?>" id="input-robots" class="form-control"><?php echo $config_robots; ?></textarea>
                <span class="help-block"><?php echo $help_robots; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_seo_url; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_seo_url) { ?>
                  <input type="radio" name="config_seo_url" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_seo_url" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_seo_url) { ?>
                  <input type="radio" name="config_seo_url" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_seo_url" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
                <span class="help-block"><?php echo $help_seo_url; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-image-file-size"><?php echo $entry_image_file_size; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_image_file_size" value="<?php echo $config_image_file_size; ?>" placeholder="<?php echo $entry_image_file_size; ?>" id="input-image-file-size" class="form-control" />
                <span class="help-block"><?php echo $help_image_file_size; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-file-extension-allowed"><?php echo $entry_file_extension_allowed; ?></label>
              <div class="col-sm-10">
                <textarea name="config_file_extension_allowed" rows="5" placeholder="<?php echo $entry_file_extension_allowed; ?>" id="input-file-extension-allowed" class="form-control"><?php echo $config_file_extension_allowed; ?></textarea>
                <span class="help-block"><?php echo $help_file_extension_allowed; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-file-mime-allowed"><?php echo $entry_file_mime_allowed; ?></label>
              <div class="col-sm-10">
                <textarea name="config_file_mime_allowed" cols="60" rows="5" placeholder="<?php echo $entry_file_mime_allowed; ?>" id="input-file-mime-allowed" class="form-control"><?php echo $config_file_mime_allowed; ?></textarea>
                <span class="help-block"><?php echo $help_file_mime_allowed; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_maintenance; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_maintenance) { ?>
                  <input type="radio" name="config_maintenance" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_maintenance" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_maintenance) { ?>
                  <input type="radio" name="config_maintenance" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_maintenance" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
                <span class="help-block"><?php echo $help_maintenance; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_password; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_password) { ?>
                  <input type="radio" name="config_password" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_password" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_password) { ?>
                  <input type="radio" name="config_password" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_password" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
                <span class="help-block"><?php echo $help_password; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-encryption"><?php echo $entry_encryption; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_encryption" value="<?php echo $config_encryption; ?>" placeholder="<?php echo $entry_encryption; ?>" id="input-encryption" class="form-control" />
                <span class="help-block"><?php echo $help_encryption; ?></span>
                <?php if ($error_encryption) { ?>
                <div class="text-danger"><?php echo $error_encryption; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-compression"><?php echo $entry_compression; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_compression" value="<?php echo $config_compression; ?>" placeholder="<?php echo $entry_compression; ?>" id="input-compression" class="form-control" />
                <span class="help-block"><?php echo $help_compression; ?></span></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_error_display; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_error_display) { ?>
                  <input type="radio" name="config_error_display" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_error_display" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_error_display) { ?>
                  <input type="radio" name="config_error_display" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_error_display" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_error_log; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($config_error_log) { ?>
                  <input type="radio" name="config_error_log" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_error_log" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$config_error_log) { ?>
                  <input type="radio" name="config_error_log" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="config_error_log" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-error-filename"><?php echo $entry_error_filename; ?></label>
              <div class="col-sm-10">
                <input type="text" name="config_error_filename" value="<?php echo $config_error_filename; ?>" placeholder="<?php echo $entry_error_filename; ?>" id="input-error-filename" class="form-control" />
                <?php if ($error_error_filename) { ?>
                <div class="text-danger"><?php echo $error_error_filename; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-google-analytics"><?php echo $entry_google_analytics; ?></label>
              <div class="col-sm-10">
                <textarea name="config_google_analytics" rows="5" placeholder="<?php echo $entry_google_analytics; ?>" id="input-google-analytics" class="form-control"><?php echo $config_google_analytics; ?></textarea>
                <span class="help-block"><?php echo $help_google_analytics; ?></span> </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'config_template\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=setting/setting/template&token=<?php echo $token; ?>&template=' + encodeURIComponent(this.value),
		dataType: 'html',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-spinner fa-spin"></i>');
		},		
		complete: function() {
			$('.fa-spinner').remove();
		},			
		success: function(html) {
			$('#template').attr('src', html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'config_template\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'config_country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=setting/setting/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'config_country_id\']').after(' <i class="fa fa-spinner fa-spin"></i>');
		},		
		complete: function() {
			$('.fa-spinner').remove();
		},			
		success: function(json) {
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
<?php echo $footer; ?>