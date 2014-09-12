<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="settings-form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <form action="" method="post" enctype="multipart/form-data" id="settings-form" class="form-horizontal">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
        <li><a href="#tab-product" data-toggle="tab"><?php echo $tab_listing; ?></a></li>
        <li><a href="#tab-orders" data-toggle="tab"><?php echo $tab_orders; ?></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-settings">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="amazonus-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="amazonus_status" id="amazonus-status" class="form-control">
                <?php if ($amazonus_status) { ?>
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
            <label class="col-sm-2 control-label" for="entry-token"><?php echo $entry_token; ?></label>
            <div class="col-sm-10">
              <input type="text" name="openbay_amazonus_token" value="<?php echo $openbay_amazonus_token; ?>" placeholder="<?php echo $entry_token; ?>" id="entry-token" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="entry-string1"><?php echo $entry_string1; ?></label>
            <div class="col-sm-10">
              <input type="text" name="openbay_amazonus_enc_string1" value="<?php echo $openbay_amazonus_enc_string1; ?>" placeholder="<?php echo $entry_string1; ?>" id="entry-string1" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="entry-string2"><?php echo $entry_string2; ?></label>
            <div class="col-sm-10">
              <input type="text" name="openbay_amazonus_enc_string2" value="<?php echo $openbay_amazonus_enc_string2; ?>" placeholder="<?php echo $entry_string2; ?>" id="entry-string2" class="form-control" />
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
            <label class="col-sm-2 control-label" for="entry-tax-percentage"><span data-toggle="tooltip" data-container="#tab-product" title="<?php echo $help_tax_percentage; ?>"><?php echo $entry_tax_percentage; ?></span></label>
            <div class="col-sm-10">
              <div class="input-group col-xs-2">
                <input type="text" name="openbay_amazonus_listing_tax_added" value="<?php echo $openbay_amazonus_listing_tax_added; ?>" placeholder="<?php echo $entry_tax_percentage; ?>" id="entry-tax-percentage" class="form-control" />
                <span class="input-group-addon">%</span> </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="entry-default-condition"><?php echo $entry_default_condition; ?></label>
            <div class="col-sm-10">
              <select name="openbay_amazonus_listing_default_condition" id="entry-default-condition" class="form-control">
                <option></option>
                <?php foreach ($conditions as $value => $condition) { ?>
                <?php if ($value == $openbay_amazonus_listing_default_condition) { ?>
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
          <h4><?php echo $text_order_statuses ?></h4>
          <?php foreach ($amazonus_order_statuses as $key => $amazonus_order_status) { ?>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $amazonus_order_status['name'] ?></label>
            <div class="col-sm-10">
              <select name="openbay_amazonus_order_status_<?php echo $key ?>" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($amazonus_order_status['order_status_id'] == $order_status['order_status_id']) { ?>
                <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <?php } ?>
          <h4><?php echo $text_other ?></h4>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="entry-import-tax"><span data-toggle="tooltip" data-container="#tab-orders" title="<?php echo $help_import_tax; ?>"><?php echo $entry_import_tax; ?></span></label>
            <div class="col-sm-10">
              <div class="input-group col-xs-2">
                <input type="text" name="openbay_amazonus_order_tax" value="<?php echo $openbay_amazonus_order_tax;?>" id="entry-import-tax" class="form-control" placeholder="<?php echo $entry_import_tax; ?>" />
                <span class="input-group-addon">%</span> </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="entry-customer-group"><span data-toggle="tooltip" data-container="#tab-orders" title="<?php echo $help_customer_group; ?>"><?php echo $entry_customer_group; ?></span></label>
            <div class="col-sm-10">
              <select name="openbay_amazonus_order_customer_group" id="entry-customer-group" class="form-control">
                <?php foreach($customer_groups as $customer_group) { ?>
                <?php if ($openbay_amazonus_order_customer_group == $customer_group['customer_group_id']) { ?>
                <?php echo '<option value="'.$customer_group['customer_group_id'].'" selected="selected">'.$customer_group['name'].'</option>'; ?>
                <?php } else { ?>
                <?php echo '<option value="'.$customer_group['customer_group_id'].'">'.$customer_group['name'].'</option>'; ?>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="entry-notify-admin"><?php echo $entry_notify_admin; ?></label>
            <div class="col-sm-10">
              <select name="openbay_amazonus_notify_admin" id="entry-notify-admin" class="form-control">
                <?php if ($openbay_amazonus_notify_admin) { ?>
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
            <label class="col-sm-2 control-label" for="entry-default-shipping"><span data-toggle="tooltip" data-container="#tab-orders" title="<?php echo $help_default_shipping; ?>"><?php echo $entry_default_shipping; ?></span></label>
            <div class="col-sm-10">
              <select name="openbay_amazonus_default_carrier" id="entry-default-shipping" class="form-control">
                <?php foreach($carriers as $carrier) { ?>
                <?php echo '<option'.($carrier == $openbay_amazonus_default_carrier ? ' selected' : '').'>'.$carrier.'</option>'; ?>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>