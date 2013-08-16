<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-edit icon-large"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <button type="submit" form="form-pp-express" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pp-express" class="form-horizontal">
        <div class="form-group required">
          <label class="col-lg-3 control-label" for="input-username"><?php echo $entry_username; ?></label>
          <div class="col-lg-9">
            <input type="text" name="pp_express_username" value="<?php echo $pp_express_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
            <?php if ($error_username) { ?>
            <span class="text-error"><?php echo $error_username; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-lg-3 control-label" for="input-password"><?php echo $entry_password; ?></label>
          <div class="col-lg-9">
            <input type="text" name="pp_express_password" value="<?php echo $pp_express_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
            <?php if ($error_password) { ?>
            <span class="text-error"><?php echo $error_password; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-lg-3 control-label" for="input-signature"><?php echo $entry_signature; ?></label>
          <div class="col-lg-9">
            <input type="text" name="pp_express_signature" value="<?php echo $pp_express_signature; ?>" placeholder="<?php echo $entry_signature; ?>" id="input-signature" class="form-control" />
            <?php if ($error_signature) { ?>
            <span class="text-error"><?php echo $error_signature; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-3 control-label"><?php echo $entry_test; ?></div>
          <div class="col-lg-9">
            <label class="radio inline">
              <?php if ($pp_express_test) { ?>
              <input type="radio" name="pp_express_test" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="pp_express_test" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if (!$pp_express_test) { ?>
              <input type="radio" name="pp_express_test" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="pp_express_test" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label" for="input-method"><?php echo $entry_method; ?></label>
          <div class="col-lg-9">
            <select name="pp_express_method" id="input-method" class="form-control">
              <?php if (!$pp_express_method) { ?>
              <option value="0" selected="selected"><?php echo $text_authorization; ?></option>
              <?php } else { ?>
              <option value="0"><?php echo $text_authorization; ?></option>
              <?php } ?>
              <?php if ($pp_express_method) { ?>
              <option value="1" selected="selected"><?php echo $text_sale; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_sale; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label" for="input-total"><?php echo $entry_total; ?> <span class="help-block"><?php echo $help_total; ?></span></label>
          <div class="col-lg-9">
            <input type="text" name="pp_express_total" value="<?php echo $pp_express_total; ?>" id="input-total" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
          <div class="col-lg-9">
            <select name="pp_express_order_status_id" id="input-order-status" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_express_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
          <div class="col-lg-9">
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
          <label class="col-lg-3 control-label" for="input-status"><?php echo $entry_status; ?></label>
          <div class="col-lg-9">
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
        <div class="form-group">
          <label class="col-lg-3 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
          <div class="col-lg-9">
            <input type="text" name="pp_express_sort_order" value="<?php echo $pp_express_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 