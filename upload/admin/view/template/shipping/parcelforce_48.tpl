<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><a onclick="$('#form').submit();" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></a> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_rate; ?></label>
          <div class="controls">
            <textarea name="parcelforce_48_rate" cols="40" rows="5"><?php echo $parcelforce_48_rate; ?></textarea>
            <span class="help-inline"><i data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $help_rate; ?>" class="icon-question-sign"></i></span>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_insurance; ?></label>
          <div class="controls">
            <textarea name="parcelforce_48_insurance" cols="40" rows="5"><?php echo $parcelforce_48_insurance; ?></textarea>
            <span class="help-inline"><i data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $help_insurance; ?>" class="icon-question-sign"></i></span>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_display_weight; ?></label>
          <div class="controls">
            <?php if ($parcelforce_48_display_weight) { ?>
            <input type="radio" name="parcelforce_48_display_weight" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="parcelforce_48_display_weight" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="parcelforce_48_display_weight" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="parcelforce_48_display_weight" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?>
            <span class="help-inline"><i data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $help_display_weight; ?>" class="icon-question-sign"></i></span>
            
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_display_insurance; ?></label>
          <div class="controls">
            <?php if ($parcelforce_48_display_insurance) { ?>
            <input type="radio" name="parcelforce_48_display_insurance" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="parcelforce_48_display_insurance" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="parcelforce_48_display_insurance" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="parcelforce_48_display_insurance" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?>
            
            <span class="help-inline"><i data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $help_display_insurance; ?>" class="icon-question-sign"></i></span>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_display_time; ?></label>
          <div class="controls">
            <?php if ($parcelforce_48_display_time) { ?>
            <input type="radio" name="parcelforce_48_display_time" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="parcelforce_48_display_time" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="parcelforce_48_display_time" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="parcelforce_48_display_time" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?>
            
            <span class="help-inline"><i data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $help_display_time; ?>" class="icon-question-sign"></i></span>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_tax_class; ?></label>
          <div class="controls">
            <select name="parcelforce_48_tax_class_id">
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
          <label class="control-label" for="input-name"><?php echo $entry_geo_zone; ?></label>
          <div class="controls">
            <select name="parcelforce_48_geo_zone_id">
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
          <label class="control-label" for="input-name"><?php echo $entry_status; ?></label>
          <div class="controls">
            <select name="parcelforce_48_status">
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
          <label class="control-label" for="input-name"><?php echo $entry_sort_order; ?></label>
          <div class="controls">
            <input type="text" name="parcelforce_48_sort_order" value="<?php echo $parcelforce_48_sort_order; ?>" size="1" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 