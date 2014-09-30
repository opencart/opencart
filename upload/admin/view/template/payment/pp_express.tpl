<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ppexpress" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> <a href="<?php echo $search; ?>" data-toggle="tooltip" title="<?php echo $button_search; ?>" class="btn btn-info"><i class="fa fa-search"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error['error_warning'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ppexpress" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-api-details" data-toggle="tab"><?php echo $tab_api_details; ?></a></li>
            <li><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
            <li><a href="#tab-customise" data-toggle="tab"><?php echo $tab_customise; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-api-details">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="entry-username"><?php echo $entry_username; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="pp_express_username" value="<?php echo $pp_express_username; ?>" placeholder="<?php echo $entry_username; ?>" id="entry-username" class="form-control" />
                  <?php if (isset($error['username'])) { ?>
                  <div class="text-danger"><?php echo $error['username']; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="entry-password"><?php echo $entry_password; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="pp_express_password" value="<?php echo $pp_express_password; ?>" placeholder="<?php echo $entry_password; ?>" id="entry-password" class="form-control" />
                  <?php if (isset($error['password'])) { ?>
                  <div class="text-danger"><?php echo $error['password']; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="entry-signature"><?php echo $entry_signature; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="pp_express_signature" value="<?php echo $pp_express_signature; ?>" placeholder="<?php echo $entry_signature; ?>" id="entry-signature" class="form-control" />
                  <?php if (isset($error['signature'])) { ?>
                  <div class="text-danger"><?php echo $error['signature']; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_ipn; ?>"><?php echo $text_ipn; ?></span></label>
                <div class="col-sm-10">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" value="<?php echo $text_ipn_url; ?>" class="form-control" />
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-live-demo"><?php echo $entry_test; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_test" id="input-live-demo" class="form-control">
                    <?php if ($pp_express_test) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-debug"><?php echo $entry_debug; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_debug" id="input-debug" class="form-control">
                    <?php if ($pp_express_debug) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-currency"><span data-toggle="tooltip" title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></label>
                <div class="col-sm-10">
                  <select name="pp_express_currency" id="input-currency" class="form-control">
                    <?php foreach ($currency_codes as $code) { ?>
                    <?php if ($code == $pp_express_currency) { ?>
                    <option value="<?php echo $code; ?>" selected="selected"><?php echo $code; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $code; ?>"><?php echo $code; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-recurring-cancel"><?php echo $entry_recurring_cancellation; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_recurring_cancel_status" id="input-recurring-cancel" class="form-control">
                    <?php if ($pp_express_recurring_cancel_status) { ?>
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
                <label class="col-sm-2 control-label" for="input-method"><?php echo $entry_method; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_method" id="input-method" class="form-control">
                    <option value="Sale" <?php  echo (($pp_express_method == '' || $pp_express_method == 'Sale') ? 'selected="selected"' : ''); ?>><?php echo $text_sale; ?></option>
                    <option value="Authorization" <?php echo ($pp_express_method == 'Authorization' ? 'selected="selected"' : ''); ?>><?php echo $text_authorization; ?></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="pp_express_total" value="<?php echo $pp_express_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="pp_express_sort_order" value="<?php echo $pp_express_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_geo_zone_id" id="input-geo-zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $pp_express_geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_status" id="input-status" class="form-control">
                    <?php if ($pp_express_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-status">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_canceled_reversal_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_canceled_reversal_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_canceled_reversal_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_completed_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_completed_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_completed_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_denied_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_denied_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_denied_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_expired_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_expired_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_expired_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_failed_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_failed_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_failed_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_pending_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_pending_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_pending_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_processed_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_processed_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_processed_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_refunded_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_refunded_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_refunded_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_reversed_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_reversed_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_reversed_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_voided_status; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_voided_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pp_express_voided_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-customise">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-notes"><?php echo $entry_allow_notes; ?></label>
                <div class="col-sm-10">
                  <select name="pp_express_allow_note" id="input-notes" class="form-control">
                    <?php if ($pp_express_allow_note) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-page-color"><span data-toggle="tooltip" title="<?php echo $help_colour; ?>"><?php echo $entry_page_colour; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="pp_express_page_colour" value="<?php echo $pp_express_page_colour; ?>" placeholder="<?php echo $entry_page_colour; ?>" id="input-page-color" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image"><span data-toggle="tooltip" title="<?php echo $help_logo; ?>"><?php echo $entry_logo; ?></span></label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="pp_express_logo" value="<?php echo $pp_express_logo; ?>" id="input-image" />
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 