<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if (isset($error['error_warning'])) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-pp-payflow-iframe" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="fa fa-times"></i> <?php echo $button_cancel; ?></a> </div>
      <h1 class="panel-title"><i class="fa fa-credit-card fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pp-payflow-iframe" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="entry-vender"><?php echo $entry_vendor; ?></label>
          <div class="col-sm-10">
            <input type="text" name="pp_payflow_iframe_vendor" value="<?php echo $pp_payflow_iframe_vendor; ?>" placeholder="<?php echo $entry_vendor; ?>" id="entry-vender" class="form-control"/>
            <span class="help-block"><?php echo $entry_vendor_help; ?></span>
            <?php if (isset($error['vendor'])) { ?>
            <div class="text-danger"><?php echo $error['vendor']; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="entry-user"><?php echo $entry_user; ?></label>
          <div class="col-sm-10">
            <input type="text" name="pp_payflow_iframe_user" value="<?php echo $pp_payflow_iframe_user; ?>" placeholder="<?php echo $entry_user; ?>" id="entry-user" class="form-control"/>
            <?php if (isset($error['user'])) { ?>
            <div class="text-danger"><?php echo $error['user']; ?></div>
            <?php } ?>
            <span class="help-block"><?php echo $entry_user_help; ?></span> </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="entry-password"><?php echo $entry_password; ?></label>
          <div class="col-sm-10">
            <input type="text" name="pp_payflow_iframe_password" value="<?php echo $pp_payflow_iframe_password; ?>" placeholder="<?php echo $entry_password; ?>" id="entry-password" class="form-control"/>
            <?php if (isset($error['password'])) { ?>
            <div class="text-danger"><?php echo $error['password']; ?></div>
            <?php } ?>
            <span class="help-block"><?php echo $entry_password_help; ?></span> </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="entry-partner"><?php echo $entry_partner; ?></label>
          <div class="col-sm-10">
            <input type="text" name="pp_payflow_iframe_partner" value="<?php echo $pp_payflow_iframe_partner; ?>" placeholder="<?php echo $entry_partner; ?>" id="entry-partner" class="form-control"/>
            <?php if (isset($error['partner'])) { ?>
            <div class="text-danger"><?php echo $error['partner']; ?></div>
            <?php } ?>
            <span class="help-block"><?php echo $entry_partner_help; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-live-demo"><?php echo $entry_test; ?></label>
          <div class="col-sm-10">
            <select name="pp_payflow_iframe_test" id="input-live-demo" class="form-control">
              <?php if ($pp_payflow_iframe_test) { ?>
              <option value="1" selected="selected"><?php echo $text_yes; ?></option>
              <option value="0"><?php echo $text_no; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_yes; ?></option>
              <option value="0" selected="selected"><?php echo $text_no; ?></option>
              <?php } ?>
            </select>
            <span class="help-block"><?php echo $entry_test_help; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-transaction"><?php echo $entry_transaction; ?></label>
          <div class="col-sm-10">
            <select name="pp_payflow_iframe_transaction_method" id="input-transaction" class="form-control">
              <?php if ($pp_payflow_iframe_transaction_method == 'authorization') { ?>
              <option value="sale"><?php echo $text_sale; ?></option>
              <option value="authorization" selected="selected"><?php echo $text_authorization; ?></option>
              <?php } else { ?>
              <option value="sale" selected="selected"><?php echo $text_sale; ?></option>
              <option value="authorization"><?php echo $text_authorization; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-debug"><?php echo $entry_debug; ?></label>
          <div class="col-sm-10">
            <select name="pp_payflow_iframe_debug" id="input-debug" class="form-control">
              <?php if ($pp_payflow_iframe_debug) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
            <span class="help-block"><?php echo $entry_debug_help; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-checkout-method"><?php echo $entry_checkout_method; ?></label>
          <div class="col-sm-10">
            <select name="pp_payflow_iframe_checkout_method" id="input-checkout-method" class="form-control">
              <?php if ($pp_payflow_iframe_checkout_method == 'iframe') { ?>
              <option value="iframe" selected="selected"><?php echo $text_iframe ?></option>
              <option value="redirect"><?php echo $text_redirect ?></option>
              <?php } else { ?>
              <option value="iframe"><?php echo $text_iframe ?></option>
              <option value="redirect" selected="selected"><?php echo $text_redirect ?></option>
              <?php } ?>
            </select>
            <span class="help-block"><?php echo $help_checkout_method; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
          <div class="col-sm-10">
            <select name="pp_payflow_iframe_order_status_id" id="input-order-status" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_payflow_iframe_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-total"><?php echo $entry_total; ?></label>
          <div class="col-sm-10">
            <input type="text" name="pp_payflow_iframe_total" value="<?php echo $pp_payflow_iframe_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            <span class="help-block"><?php echo $entry_total_help; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
          <div class="col-sm-10">
            <input type="text" name="pp_payflow_iframe_sort_order" value="<?php echo $pp_payflow_iframe_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
          <div class="col-sm-10">
            <select name="pp_payflow_iframe_geo_zone_id" id="input-geo-zone" class="form-control">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $pp_payflow_iframe_geo_zone_id) { ?>
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
            <select name="pp_payflow_iframe_status" id="input-status" class="form-control">
              <?php if ($pp_payflow_iframe_status) { ?>
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
          <label class="col-sm-2 control-label"><?php echo $entry_cancel_url; ?></label>
          <div class="col-sm-10">
            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-link"></i></span>
              <input type="text" value="<?php echo $cancel_url ?>" class="form-control" />
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_error_url; ?></label>
          <div class="col-sm-10">
            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-link"></i></span>
              <input type="text" value="<?php echo $error_url ?>" class="form-control" />
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_return_url; ?></label>
          <div class="col-sm-10">
            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-link"></i></span>
              <input type="text" value="<?php echo $return_url ?>" class="form-control" />
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_post_url; ?></label>
          <div class="col-sm-10">
            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-link"></i></span>
              <input type="text" value="<?php echo $post_url ?>" class="form-control" />
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>