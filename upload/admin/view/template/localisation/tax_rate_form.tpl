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
        <button type="submit" form="form-tax-rate" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tax-rate" class="form-horizontal">
      <div class="form-group required">
        <label class="col-lg-3 control-label" for="input-name"><?php echo $entry_name; ?></label>
        <div class="col-lg-9">
          <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" />
          <?php if ($error_name) { ?>
          <span class="error"><?php echo $error_name; ?></span>
          <?php } ?>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-lg-3 control-label" for="input-rate"><?php echo $entry_rate; ?></label>
        <div class="col-lg-9">
          <input type="text" name="rate" value="<?php echo $rate; ?>" placeholder="<?php echo $entry_rate; ?>" id="input-rate" />
          <?php if ($error_rate) { ?>
          <span class="error"><?php echo $error_rate; ?></span>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-type"><?php echo $entry_type; ?></label>
        <div class="col-lg-9">
          <select name="type" id="input-type">
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
        <div class="col-lg-3 control-label"><?php echo $entry_customer_group; ?></div>
        <div class="col-lg-9">
          <?php foreach ($customer_groups as $customer_group) { ?>
          <label class="checkbox">
            <?php if (in_array($customer_group['customer_group_id'], $tax_rate_customer_group)) { ?>
            <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
            <?php echo $customer_group['name']; ?>
            <?php } else { ?>
            <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
            <?php echo $customer_group['name']; ?>
            <?php } ?>
          </label>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
        <div class="col-lg-9">
          <select name="geo_zone_id" id="input-geo-zone">
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
<?php echo $footer; ?>