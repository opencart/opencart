<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="settings-form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $link_overview; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="" method="post" enctype="multipart/form-data" id="settings-form" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-settings" data-toggle="tab"><?php echo $text_main_settings; ?></a></li>
          <li><a href="#tab-product" data-toggle="tab"><?php echo $text_listing; ?></a></li>
          <li><a href="#tab-orders" data-toggle="tab"><?php echo $text_orders; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-settings">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="amazon_status"><?php echo $text_status; ?></label>
              <div class="col-sm-10">
                <select name="amazon_status" id="amazon_status" class="form-control">
                  <?php if ($amazon_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_amazon_token"><?php echo $text_token; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_amazon_token" value="<?php echo $openbay_amazon_token; ?>" placeholder="<?php echo $text_token; ?>" id="openbay_amazon_token" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_amazon_enc_string1"><?php echo $text_enc_string1; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_amazon_enc_string1" value="<?php echo $openbay_amazon_enc_string1; ?>" placeholder="<?php echo $text_enc_string1; ?>" id="openbay_amazon_enc_string1" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_amazon_enc_string2"><?php echo $text_enc_string2; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_amazon_enc_string2" value="<?php echo $openbay_amazon_enc_string2; ?>" placeholder="<?php echo $text_enc_string2; ?>" id="openbay_amazon_enc_string2" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_api_status; ?></label>
              <div class="col-sm-10">
                <?php if (!$API_status) { ?>
                <h4><span class="label label-danger"><i class="fa fa-minus-square"></i> <?php echo $text_api_error; ?></span></h4>
                <?php } else if (!$API_auth) { ?>
                <h4><span class="label label-danger"><i class="fa fa-minus-square"></i> <?php echo $text_api_auth_error; ?></span></h4>
                <?php } else { ?>
                <h4><span class="label label-success"><i class="fa fa-check-square-o"></i> <?php echo $text_api_ok; ?></span></h4>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-product">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_amazon_listing_tax_added"><?php echo $text_tax_percentage; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_amazon_listing_tax_added" value="<?php echo $openbay_amazon_listing_tax_added; ?>" placeholder="<?php echo $text_tax_percentage; ?>" id="openbay_amazon_listing_tax_added" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_default_mp; ?></label>
              <div class="col-sm-10">
                <?php foreach ($marketplaces as $marketplace) { ?>
                  <?php if ($marketplace['code'] == $openbay_amazon_default_listing_marketplace) { ?>
                    <?php echo '<input id="p_code_'.$marketplace['code'].'" type="radio" name="openbay_amazon_default_listing_marketplace" value="'.$marketplace['code'].'" checked="checked" />'; ?>
                  <?php } else { ?>
                    <?php echo '<input id="p_code_'.$marketplace['code'].'" type="radio" name="openbay_amazon_default_listing_marketplace" value="'.$marketplace['code'].'" />'; ?>
                  <?php } ?>

                  <label for="p_code_<?php echo $marketplace['code'] ?>"><?php echo $marketplace['name'] ?></label>
                  <br />
                <?php } ?>
                <span class="help-block"><?php echo $text_default_mp_help; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_amazon_listing_default_condition"><?php echo $text_default_condition; ?></label>
              <div class="col-sm-10">
                <select name="openbay_amazon_listing_default_condition" id="openbay_amazon_listing_default_condition" class="form-control">
                  <option></option>
                  <?php foreach ($conditions as $value => $condition) { ?>
                    <?php if ($value == $openbay_amazon_listing_default_condition) { ?>
                      <option selected="selected" value="<?php echo $value; ?>"><?php echo $condition; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $value; ?>"><?php echo $condition; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-orders">
            <fieldset>
              <legend><?php echo $text_order_statuses ?></legend>

              <?php foreach ($amazon_order_statuses as $key => $amazon_order_status) { ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $amazon_order_status['name'] ?></label>
                  <div class="col-sm-10">
                    <select name="openbay_amazon_order_status_<?php echo $key ?>" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if ($amazon_order_status['order_status_id'] == $order_status['order_status_id']) { ?>
                          <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                          <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              <?php } ?>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_marketplaces ?></legend>

              <?php foreach ($marketplaces as $marketplace) { ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="code_<?php echo $marketplace['code'] ?>"><?php echo $marketplace['name'] ?></label>
                  <div class="col-sm-10">
                    <?php if (in_array($marketplace['id'], $marketplace_ids)) { ?>
                      <?php echo '<input id="code_'.$marketplace['code'].'" type="checkbox" name="openbay_amazon_orders_marketplace_ids[]" value="'.$marketplace['id'].'" checked="checked" />'; ?>
                    <?php } else { ?>
                      <?php echo '<input id="code_'.$marketplace['code'].'" type="checkbox" name="openbay_amazon_orders_marketplace_ids[]" value="'.$marketplace['id'].'" />'; ?>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_other ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="openbay_amazon_order_tax"><?php echo $text_import_tax; ?></label>
                <div class="col-sm-10">
                  <div class="input-group col-xs-2">
                    <input type="text" name="openbay_amazon_order_tax" value="<?php echo $openbay_amazon_order_tax;?>" id="openbay_amazon_order_tax" class="form-control" placeholder="<?php echo $text_import_tax; ?>" />
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="openbay_amazon_order_customer_group"><?php echo $text_customer_group; ?></label>
                <div class="col-sm-10">
                  <select name="openbay_amazon_order_customer_group" id="openbay_amazon_order_customer_group" class="form-control">
                    <?php foreach($customer_groups as $customer_group) { ?>
                      <?php if ($openbay_amazon_order_customer_group == $customer_group['customer_group_id']) { ?>
                        <?php echo '<option value="'.$customer_group['customer_group_id'].'" selected="selected">'.$customer_group['name'].'</option>'; ?>
                      <?php } else { ?>
                        <?php echo '<option value="'.$customer_group['customer_group_id'].'">'.$customer_group['name'].'</option>'; ?>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="openbay_amazon_notify_admin"><?php echo $text_admin_notify; ?></label>
                <div class="col-sm-10">
                  <select name="openbay_amazon_notify_admin" id="openbay_amazon_notify_admin" class="form-control">
                    <?php if ($openbay_amazon_notify_admin) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes ?></option>
                    <option value="0"><?php echo $text_no ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes ?></option>
                    <option value="0" selected="selected"><?php echo $text_no ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="openbay_amazon_default_carrier"><?php echo $text_default_shipping; ?></label>
                <div class="col-sm-10">
                  <select name="openbay_amazon_default_carrier" id="openbay_amazon_default_carrier" class="form-control">
                    <?php foreach($carriers as $carrier) { ?>
                      <?php echo '<option'.($carrier == $openbay_amazon_default_carrier ? ' selected' : '').'>'.$carrier.'</option>'; ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>