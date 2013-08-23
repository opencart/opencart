<?php echo $header; ?>
<div id="content" class="container">
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
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-tax-rate" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tax-rate" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
          <div class="col-sm-10">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
            <?php if ($error_name) { ?>
            <div class="text-danger"><?php echo $error_name; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-rate"><?php echo $entry_rate; ?></label>
          <div class="col-sm-10">
            <input type="text" name="rate" value="<?php echo $rate; ?>" placeholder="<?php echo $entry_rate; ?>" id="input-rate" class="form-control" />
            <?php if ($error_rate) { ?>
            <div class="text-danger"><?php echo $error_rate; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
          <div class="col-sm-10">
            <select name="type" id="input-type" class="form-control">
              <?php if ($type == 'P') { ?>
              <option value="P" selected="selected"><?php echo $text_percent; ?></option>
              <?php } else { ?>
              <option value="P"><?php echo $text_percent; ?></option>
              <?php } ?>
              <?php if ($type == 'F') { ?>
              <option value="F" selected="selected"><?php echo $text_amount; ?></option>
              <?php } else { ?>
              <option value="F"><?php echo $text_amount; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_customer_group; ?></label>
          <div class="col-sm-10">
            <?php foreach ($customer_groups as $customer_group) { ?>
            <div class="checkbox">
              <label>
                <?php if (in_array($customer_group['customer_group_id'], $tax_rate_customer_group)) { ?>
                <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                <?php echo $customer_group['name']; ?>
                <?php } else { ?>
                <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                <?php echo $customer_group['name']; ?>
                <?php } ?>
              </label>
            </div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
          <div class="col-sm-10">
            <select name="geo_zone_id" id="input-geo-zone" class="form-control">
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php  if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>