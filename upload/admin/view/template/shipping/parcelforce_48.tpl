<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-rate"><?php echo $entry_rate; ?></label>
          <div class="controls">
            <textarea name="parcelforce_48_rate" cols="40" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-rate"><?php echo $parcelforce_48_rate; ?></textarea>

            
            <a data-toggle="tooltip" title="<?php echo $help_rate; ?>"><i class="icon-info-sign"></i></a>
            </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-insurance"><?php echo $entry_insurance; ?></label>
          <div class="controls">
            <textarea name="parcelforce_48_insurance" cols="40" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-insurance"><?php echo $parcelforce_48_insurance; ?></textarea>

            
            <a data-toggle="tooltip" title="<?php echo $help_insurance; ?>"><i class="icon-info-sign"></i></a>
            </div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $entry_display_weight; ?></div>
          <div class="controls">
            <label class="radio inline">
              <?php if ($parcelforce_48_display_weight) { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if (!$parcelforce_48_display_weight) { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>

            
            <a data-toggle="tooltip" title="<?php echo $help_display_weight; ?>"><i class="icon-info-sign"></i></a>
            
            </div>
        </div>
        <div class="control-group">
          <div class="control-label" for="input-display-insurance"><?php echo $entry_display_insurance; ?></div>
          <div class="controls">
            <label class="radio inline" id="input-display-insurance">
              <?php if ($parcelforce_48_display_insurance) { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if (!$parcelforce_48_display_insurance) { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>

            <a data-toggle="tooltip" title="<?php echo $help_display_insurance; ?>"><i class="icon-info-sign"></i></a>
            
            </div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $entry_display_time; ?></div>
          <div class="controls">
            <label class="radio inline">
              <?php if ($parcelforce_48_display_time) { ?>
              <input type="radio" name="parcelforce_48_display_time" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_time" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if (!$parcelforce_48_display_time) { ?>
              <input type="radio" name="parcelforce_48_display_time" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_time" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>

            
            <a data-toggle="tooltip" title="<?php echo $help_display_time; ?>"><i class="icon-info-sign"></i></a>
            
            </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
          <div class="controls">
            <select name="parcelforce_48_tax_class_id" id="input-tax-class">
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
        <div class="control-group">
          <label class="control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
          <div class="controls">
            <select name="parcelforce_48_geo_zone_id" id="input-geo-zone">
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
        <div class="control-group">
          <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
          <div class="controls">
            <select name="parcelforce_48_status" id="input-status">
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
        <div class="control-group">
          <label class="control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
          <div class="controls">
            <input type="text" name="parcelforce_48_sort_order" value="<?php echo $parcelforce_48_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 