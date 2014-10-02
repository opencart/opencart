<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-realex" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $text_ip_message; ?></div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-realex" class="form-horizontal">
          <ul class="nav nav-tabs" id="tabs">
            <li class="active"><a href="#tab-account" data-toggle="tab"><?php echo $tab_account; ?></a></li>
            <li><a href="#tab-sub-account" data-toggle="tab"><?php echo $tab_sub_account; ?></a></li>
            <li><a href="#tab-order-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
            <li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-account">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-merchant-id"><?php echo $entry_merchant_id; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="realex_remote_merchant_id" value="<?php echo $realex_remote_merchant_id; ?>" placeholder="<?php echo $entry_merchant_id; ?>" id="input-merchant-id" class="form-control" />
                  <?php if ($error_merchant_id) { ?>
                  <div class="text-danger"><?php echo $error_merchant_id; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-secret"><?php echo $entry_secret; ?></label>
                <div class="col-sm-10">
                  <input type="password" name="realex_remote_secret" value="<?php echo $realex_remote_secret; ?>" placeholder="<?php echo $entry_secret; ?>" id="input-secret" class="form-control" />
                  <?php if ($error_secret) { ?>
                  <div class="text-danger"><?php echo $error_secret; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-secret"><?php echo $entry_rebate_password; ?></label>
                <div class="col-sm-10">
                  <input type="password" name="realex_remote_rebate_password" value="<?php echo $realex_remote_rebate_password; ?>" placeholder="<?php echo $entry_rebate_password; ?>" id="input-rebate-password" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_geo_zone_id" id="input-geo-zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $realex_remote_geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-debug"><span data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
                <div class="col-sm-10">
                  <select name="realex_remote_debug" id="input-debug" class="form-control">
                    <?php if ($realex_remote_debug) { ?>
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
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_status" id="input-status" class="form-control">
                    <?php if ($realex_remote_status) { ?>
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
                <label class="col-sm-2 control-label" for="input-card"><span data-toggle="tooltip" title="<?php echo $help_card_data_status; ?>"><?php echo $entry_card_data_status; ?></span></label>
                <div class="col-sm-10">
                  <select name="realex_remote_card_data_status" id="input-card" class="form-control">
                    <?php if ($realex_remote_card_data_status) { ?>
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
                <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="realex_remote_total" value="<?php echo $realex_remote_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="realex_remote_sort_order" value="<?php echo $realex_remote_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-sub-account">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $text_card_type; ?></td>
                      <td class="text-center"><?php echo $text_enabled; ?></td>
                      <td class="text-center"><?php echo $text_use_default; ?></td>
                      <td class="text-left"><?php echo $text_subaccount; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $text_card_visa; ?></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[visa][enabled]" value="1" <?php if (isset($realex_remote_account['visa']['enabled']) && $realex_remote_account['visa']['enabled'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[visa][default]" value="1" <?php if (isset($realex_remote_account['visa']['default']) && $realex_remote_account['visa']['default'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-right"><input type="text" name="realex_remote_account[visa][merchant_id]" value="<?php echo isset($realex_remote_account['visa']['merchant_id']) ? $realex_remote_account['visa']['merchant_id'] : ''; ?>" placeholder="<?php echo $text_subaccount; ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                      <td class="text-left"><?php echo $text_card_master; ?></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[mc][enabled]" value="1" <?php if (isset($realex_remote_account['mc']['enabled']) && $realex_remote_account['mc']['enabled'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[mc][default]" value="1" <?php if (isset($realex_remote_account['mc']['default']) && $realex_remote_account['mc']['default'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-right"><input type="text" name="realex_remote_account[mc][merchant_id]" value="<?php echo isset($realex_remote_account['mc']['merchant_id']) ? $realex_remote_account['mc']['merchant_id'] : ''; ?>" placeholder="<?php echo $text_subaccount; ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                      <td class="text-left"><?php echo $text_card_amex; ?></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[amex][enabled]" value="1" <?php if (isset($realex_remote_account['amex']['enabled']) && $realex_remote_account['amex']['enabled'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[amex][default]" value="1" <?php if (isset($realex_remote_account['amex']['default']) && $realex_remote_account['amex']['default'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-right"><input type="text" name="realex_remote_account[amex][merchant_id]" value="<?php echo isset($realex_remote_account['amex']['merchant_id']) ? $realex_remote_account['amex']['merchant_id'] : ''; ?>" placeholder="<?php echo $text_subaccount; ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                      <td class="text-left"><?php echo $text_card_switch; ?></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[switch][enabled]" value="1" <?php if (isset($realex_remote_account['switch']['enabled']) && $realex_remote_account['switch']['enabled'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[switch][default]" value="1" <?php if (isset($realex_remote_account['switch']['default']) && $realex_remote_account['switch']['default'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-right"><input type="text" name="realex_remote_account[switch][merchant_id]" value="<?php echo isset($realex_remote_account['switch']['merchant_id']) ? $realex_remote_account['switch']['merchant_id'] : ''; ?>" placeholder="<?php echo $text_subaccount; ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                      <td class="text-left"><?php echo $text_card_laser; ?></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[laser][enabled]" value="1" <?php if (isset($realex_remote_account['laser']['enabled']) && $realex_remote_account['laser']['enabled'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[laser][default]" value="1" <?php if (isset($realex_remote_account['laser']['default']) && $realex_remote_account['laser']['default'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-right"><input type="text" name="realex_remote_account[laser][merchant_id]" value="<?php echo isset($realex_remote_account['laser']['merchant_id']) ? $realex_remote_account['laser']['merchant_id'] : ''; ?>" placeholder="<?php echo $text_subaccount; ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                      <td class="text-left"><?php echo $text_card_diners; ?></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[diners][enabled]" value="1" <?php if (isset($realex_remote_account['diners']['enabled']) && $realex_remote_account['diners']['enabled'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-center"><input type="checkbox" name="realex_remote_account[diners][default]" value="1" <?php if (isset($realex_remote_account['diners']['default']) && $realex_remote_account['diners']['default'] == 1) { echo 'checked="checked" '; } ?>/></td>
                      <td class="text-right"><input type="text" name="realex_remote_account[diners][merchant_id]" value="<?php echo isset($realex_remote_account['diners']['merchant_id']) ? $realex_remote_account['diners']['merchant_id'] : ''; ?>" placeholder="<?php echo $text_subaccount; ?>" class="form-control" /></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-order-status">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-success-settled"><?php echo $entry_status_success_settled; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_order_status_success_settled_id" id="input-order-status-success-settled" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $realex_remote_order_status_success_settled_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-success-unsettled"><?php echo $entry_status_success_unsettled; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_order_status_success_unsettled_id" id="input-order-status-success-unsettled" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $realex_remote_order_status_success_unsettled_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-decline"><?php echo $entry_status_decline; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_order_status_decline_id" id="input-order-status-decline" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $realex_remote_order_status_decline_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-decline-pending"><?php echo $entry_status_decline_pending; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_order_status_decline_pending_id" id="input-order-status-decline-pending" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $realex_remote_order_status_decline_pending_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-decline-stolen"><?php echo $entry_status_decline_stolen; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_order_status_decline_stolen_id" id="input-order-status-decline-stolen" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $realex_remote_order_status_decline_stolen_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status-decline-bank"><?php echo $entry_status_decline_bank; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_order_status_decline_bank_id" id="input-order-status-decline-bank" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $realex_remote_order_status_decline_bank_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-payment">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-auto-settle"><?php echo $entry_auto_settle; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_auto_settle" id="input-auto-settle" class="form-control">
                    <option value="0"<?php echo ($realex_remote_auto_settle == 0 ? ' selected' : ''); ?>><?php echo $text_settle_delayed; ?></option>
                    <option value="1"<?php echo ($realex_remote_auto_settle == 1 ? ' selected' : ''); ?>><?php echo $text_settle_auto; ?></option>
                    <option value="2"<?php echo ($realex_remote_auto_settle == 2 ? ' selected' : ''); ?>><?php echo $text_settle_multi; ?></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tss-check"><?php echo $entry_tss_check; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_tss_check" id="input-tss-check" class="form-control">
                    <?php if ($realex_remote_tss_check) { ?>
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
                <label class="col-sm-2 control-label" for="input-3d"><?php echo $entry_3d; ?></label>
                <div class="col-sm-10">
                  <select name="realex_remote_3d" id="input-3d" class="form-control">
                    <?php if ($realex_remote_3d) { ?>
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
                <label class="col-sm-2 control-label" for="input-liability"><span data-toggle="tooltip" title="<?php echo $help_liability; ?>"><?php echo $entry_liability_shift; ?></span></label>
                <div class="col-sm-10">
                  <select name="realex_remote_liability" id="input-liability" class="form-control">
                    <?php if ($realex_remote_liability) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#tabs a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>