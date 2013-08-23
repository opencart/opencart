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
        <button type="submit" form="form-parcelforce-48" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-parcelforce-48" class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-rate"><?php echo $entry_rate; ?></label>
          <div class="col-sm-10">
            <textarea name="parcelforce_48_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-rate" class="form-control"><?php echo $parcelforce_48_rate; ?></textarea>
            <span class="help-block"><?php echo $help_rate; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-insurance"><?php echo $entry_insurance; ?></label>
          <div class="col-sm-10">
            <textarea name="parcelforce_48_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-insurance" class="form-control"><?php echo $parcelforce_48_insurance; ?></textarea>
            <span class="help-block"><?php echo $help_insurance; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_display_weight; ?></label>
          <div class="col-sm-10">
            <label class="radio-inline">
              <?php if ($parcelforce_48_display_weight) { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio-inline">
              <?php if (!$parcelforce_48_display_weight) { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
            <span class="help-block"><?php echo $help_display_weight; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-display-insurance"><?php echo $entry_display_insurance; ?></label>
          <div class="col-sm-10">
            <label class="radio-inline" id="input-display-insurance">
              <?php if ($parcelforce_48_display_insurance) { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio-inline">
              <?php if (!$parcelforce_48_display_insurance) { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
            <span class="help-block"><?php echo $help_display_insurance; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_display_time; ?></label>
          <div class="col-sm-10">
            <label class="radio-inline">
              <?php if ($parcelforce_48_display_time) { ?>
              <input type="radio" name="parcelforce_48_display_time" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_time" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio-inline">
              <?php if (!$parcelforce_48_display_time) { ?>
              <input type="radio" name="parcelforce_48_display_time" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_time" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
            <span class="help-block"><?php echo $help_display_time; ?></span> </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
          <div class="col-sm-10">
            <select name="parcelforce_48_tax_class_id" id="input-tax-class" class="form-control">
              <option value="0"><?php echo $text_none; ?></option>
              <?php foreach ($tax_classes as $tax_class) { ?>
              <?php if ($tax_class['tax_class_id'] == $parcelforce_48_tax_class_id) { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
          <div class="col-sm-10">
            <select name="parcelforce_48_geo_zone_id" id="input-geo-zone" class="form-control">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $parcelforce_48_geo_zone_id) { ?>
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
            <select name="parcelforce_48_status" id="input-status" class="form-control">
              <?php if ($parcelforce_48_status) { ?>
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
          <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
          <div class="col-sm-10">
            <input type="text" name="parcelforce_48_sort_order" value="<?php echo $parcelforce_48_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 