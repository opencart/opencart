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
        <button type="submit" form="form-weight" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-weight" class="form-horizontal">
        <div class="row">
          <div class="col-sm-2">
            <ul class="nav nav-pills nav-stacked">
              <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <li><a href="#tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>" data-toggle="tab"><?php echo $geo_zone['name']; ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <div class="col-sm-10">
            <div class="tab-content">
              <div class="tab-pane active" id="tab-general">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                  <div class="col-sm-10">
                    <select name="weight_tax_class_id" id="input-tax-class" class="form-control">
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
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="weight_status" id="input-status" class="form-control">
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
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="weight_sort_order" value="<?php echo $weight_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                  </div>
                </div>
              </div>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <div class="tab-pane" id="tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-rate<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-rate<?php echo $geo_zone['geo_zone_id']; ?>" class="form-control"><?php echo ${'weight_' . $geo_zone['geo_zone_id'] . '_rate'}; ?></textarea>
                    <span class="help-block"><?php echo $help_rate; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-status<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_status" id="input-status<?php echo $geo_zone['geo_zone_id']; ?>" class="form-control">
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
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 