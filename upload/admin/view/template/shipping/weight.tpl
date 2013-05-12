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
        <div class="tabbable tabs-left">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <li><a href="#tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>" data-toggle="tab"><?php echo $geo_zone['name']; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="control-group">
                <label class="control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                <div class="controls">
                  <select name="weight_tax_class_id" id="input-tax-class">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <?php if ($tax_class['tax_class_id'] == $weight_tax_class_id) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="controls">
                  <select name="weight_status" id="input-status">
                    <?php if ($weight_status) { ?>
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
                  <input type="text" name="weight_sort_order" value="<?php echo $weight_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
                </div>
              </div>
            </div>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <div class="tab-pane" id="tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>">
              <div class="control-group">
                <label class="control-label" for="input-rate<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $entry_rate; ?></label>
                <div class="controls">
                  <textarea name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_rate" cols="40" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-rate<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo ${'weight_' . $geo_zone['geo_zone_id'] . '_rate'}; ?></textarea>

                  
                  <a data-toggle="tooltip" title="<?php echo $help_rate; ?>"><i class="icon-info-sign"></i></a>
                  </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-status<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $entry_status; ?></label>
                <div class="controls">
                  <select name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_status" id="input-status<?php echo $geo_zone['geo_zone_id']; ?>">
                    <?php if (${'weight_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>
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
            <?php } ?>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 