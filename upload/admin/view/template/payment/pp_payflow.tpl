<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-pp-payflow" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pp-payflow" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="entry-vender"><span data-toggle="tooltip" title="<?php echo $help_vendor; ?>"><?php echo $entry_vendor; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="pp_payflow_vendor" value="<?php echo $pp_payflow_vendor; ?>" placeholder="<?php echo $entry_vendor; ?>" id="entry-vender" class="form-control"/>
              <?php if (isset($error['vendor'])) { ?>
              <div class="text-danger"><?php echo $error['vendor']; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="entry-user"><span data-toggle="tooltip" title="<?php echo $help_usere; ?>"><?php echo $entry_user; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="pp_payflow_user" value="<?php echo $pp_payflow_user; ?>" placeholder="<?php echo $entry_user; ?>" id="entry-user" class="form-control"/>
              <?php if (isset($error['user'])) { ?>
              <div class="text-danger"><?php echo $error['user']; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="entry-password"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="pp_payflow_password" value="<?php echo $pp_payflow_password; ?>" placeholder="<?php echo $entry_password; ?>" id="entry-password" class="form-control"/>
              <?php if (isset($error['password'])) { ?>
              <div class="text-danger"><?php echo $error['password']; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="entry-partner"><span data-toggle="tooltip" title="<?php echo $help_partner; ?>"><?php echo $entry_partner; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="pp_payflow_partner" value="<?php echo $pp_payflow_partner; ?>" placeholder="<?php echo $entry_partner; ?>" id="entry-partner" class="form-control"/>
              <?php if (isset($error['partner'])) { ?>
              <div class="text-danger"><?php echo $error['partner']; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-live-demo"><span data-toggle="tooltip" title="<?php echo $help_test; ?>"><?php echo $entry_test; ?></span></label>
            <div class="col-sm-10">
              <select name="pp_payflow_test" id="input-live-demo" class="form-control">
                <?php if ($pp_payflow_test) { ?>
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
            <label class="col-sm-2 control-label" for="input-transaction"><?php echo $entry_transaction; ?></label>
            <div class="col-sm-10">
              <select name="pp_payflow_transaction" id="input-transaction" class="form-control">
                <?php if (!$pp_payflow_transaction) { ?>
                <option value="0" selected="selected"><?php echo $text_authorization; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_authorization; ?></option>
                <?php } ?>
                <?php if ($pp_payflow_transaction) { ?>
                <option value="1" selected="selected"><?php echo $text_sale; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_sale; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="pp_payflow_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $pp_payflow_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="pp_payflow_total" value="<?php echo $pp_payflow_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="pp_payflow_sort_order" value="<?php echo $pp_payflow_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="pp_payflow_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $pp_payflow_geo_zone_id) { ?>
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
              <select name="pp_payflow_status" id="input-status" class="form-control">
                <?php if ($pp_payflow_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>