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
        <button type="submit" form="form-royal-mail" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-royal-mail" class="form-horizontal">
        <div class="row">
          <div class="col-sm-2">
            <ul class="nav nav-pills nav-stacked">
              <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
              <li><a href="#tab-1st-class-standard" data-toggle="tab"><?php echo $tab_1st_class_standard; ?></a></li>
              <li><a href="#tab-1st-class-recorded" data-toggle="tab"><?php echo $tab_1st_class_recorded; ?></a></li>
              <li><a href="#tab-2nd-class-standard" data-toggle="tab"><?php echo $tab_2nd_class_standard; ?></a></li>
              <li><a href="#tab-2nd-class-recorded" data-toggle="tab"><?php echo $tab_2nd_class_recorded; ?></a></li>
              <li><a href="#tab-special-delivery-500" data-toggle="tab"><?php echo $tab_special_delivery_500; ?></a></li>
              <li><a href="#tab-special-delivery-1000" data-toggle="tab"><?php echo $tab_special_delivery_1000; ?></a></li>
              <li><a href="#tab-special-delivery-2500" data-toggle="tab"><?php echo $tab_special_delivery_2500; ?></a></li>
              <li><a href="#tab-standard-parcels" data-toggle="tab"><?php echo $tab_standard_parcels; ?></a></li>
              <li><a href="#tab-airmail" data-toggle="tab"><?php echo $tab_airmail; ?></a></li>
              <li><a href="#tab-international-signed" data-toggle="tab"><?php echo $tab_international_signed; ?></a></li>
              <li><a href="#tab-airsure" data-toggle="tab"><?php echo $tab_airsure; ?></a></li>
              <li><a href="#tab-surface" data-toggle="tab"><?php echo $tab_surface; ?></a></li>
            </ul>
          </div>
          <div class="col-sm-10">
            <div class="tab-content">
              <div class="tab-pane active" id="tab-general">
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_display_weight; ?></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($royal_mail_display_weight) { ?>
                      <input type="radio" name="royal_mail_display_weight" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="royal_mail_display_weight" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$royal_mail_display_weight) { ?>
                      <input type="radio" name="royal_mail_display_weight" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="royal_mail_display_weight" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                    <span class="help-block"><?php echo $help_display_weight; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_display_insurance; ?></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($royal_mail_display_insurance) { ?>
                      <input type="radio" name="royal_mail_display_insurance" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="royal_mail_display_insurance" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$royal_mail_display_insurance) { ?>
                      <input type="radio" name="royal_mail_display_insurance" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="royal_mail_display_insurance" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                    <span class="help-block"><?php echo $help_display_insurance; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_weight_class_id" id="input-weight-class" class="form-control">
                      <?php foreach ($weight_classes as $weight_class) { ?>
                      <?php if ($weight_class['weight_class_id'] == $royal_mail_weight_class_id) { ?>
                      <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_tax_class_id" id="input-tax-class" class="form-control">
                      <option value="0"><?php echo $text_none; ?></option>
                      <?php foreach ($tax_classes as $tax_class) { ?>
                      <?php if ($tax_class['tax_class_id'] == $royal_mail_tax_class_id) { ?>
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
                    <select name="royal_mail_geo_zone_id" id="input-geo-zone" class="form-control">
                      <option value="0"><?php echo $text_all_zones; ?></option>
                      <?php foreach ($geo_zones as $geo_zone) { ?>
                      <?php if ($geo_zone['geo_zone_id'] == $royal_mail_geo_zone_id) { ?>
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
                    <select name="royal_mail_status" id="input-status" class="form-control">
                      <?php if ($royal_mail_status) { ?>
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
                    <input type="text" name="royal_mail_sort_order" value="<?php echo $royal_mail_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="tab-1st-class-standard">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-1st-class-standard-rate"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_1st_class_standard_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-1st-class-standard-rate" class="form-control"><?php echo $royal_mail_1st_class_standard_rate; ?></textarea>
                    <span class="help-block"><?php echo $help_rate; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-1st-class-standard-insurance"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_1st_class_standard_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-1st-class-standard-insurance" class="form-control"><?php echo $royal_mail_1st_class_standard_insurance; ?></textarea>
                    <span class="help-block"><?php echo $help_insurance; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-input-1st-class-standard-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_1st_class_standard_status" id="input-input-1st-class-standard-status" class="form-control">
                      <?php if ($royal_mail_1st_class_standard_status) { ?>
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
              <div class="tab-pane" id="tab-1st-class-recorded">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-1st-class-recorded-rate"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_1st_class_recorded_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-1st-class-recorded-rate" class="form-control"><?php echo $royal_mail_1st_class_recorded_rate; ?></textarea>
                    <span class="help-block"><?php echo $help_rate; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-1st-class-recorded-insurance"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_1st_class_recorded_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-1st-class-recorded-insurance" class="form-control"><?php echo $royal_mail_1st_class_recorded_insurance; ?></textarea>
                    <span class="help-block"><?php echo $help_insurance; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-1st-class-recorded-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_1st_class_recorded_status" id="input-1st-class-recorded-status" class="form-control">
                      <?php if ($royal_mail_1st_class_recorded_status) { ?>
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
              <div class="tab-pane" id="tab-2nd-class-standard">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-2nd-class-standard-rate"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_2nd_class_standard_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-2nd-class-standard-rate" class="form-control"><?php echo $royal_mail_2nd_class_standard_rate; ?></textarea>
                    <span class="help-block"><?php echo $help_rate; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-2nd-class-standard-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_2nd_class_standard_status" id="input-2nd-class-standard-status" class="form-control">
                      <?php if ($royal_mail_2nd_class_standard_status) { ?>
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
              <div class="tab-pane" id="tab-2nd-class-recorded">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-2nd-class-recorded-rate"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_2nd_class_recorded_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-2nd-class-recorded-rate" class="form-control"><?php echo $royal_mail_2nd_class_recorded_rate; ?></textarea>
                    <span class="help-block"><?php echo $help_rate; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-2nd-class-recorded-insurance"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_2nd_class_recorded_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-2nd-class-recorded-insurance" class="form-control"s><?php echo $royal_mail_2nd_class_recorded_insurance; ?></textarea>
                    <span class="help-block"><?php echo $help_insurance; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-2nd-class-recorded-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_2nd_class_recorded_status" id="input-2nd-class-recorded-status" class="form-control">
                      <?php if ($royal_mail_2nd_class_recorded_status) { ?>
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
              <div class="tab-pane" id="tab-special-delivery-500">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-special-delivery-500-rate"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_special_delivery_500_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-special-delivery-500-rate" class="form-control"><?php echo $royal_mail_special_delivery_500_rate; ?></textarea>
                    <span class="help-block"><?php echo $help_rate; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-special-delivery-500-insurance"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_special_delivery_500_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-special-delivery-500-insurance" class="form-control"><?php echo $royal_mail_special_delivery_500_insurance; ?></textarea>
                    <span class="help-block"><?php echo $help_insurance; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-special-delivery-500-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_special_delivery_500_status" id="input-special-delivery-500-status" class="form-control">
                      <?php if ($royal_mail_special_delivery_500_status) { ?>
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
              <div class="tab-pane" id="tab-special-delivery-1000">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-special-delivery-1000-rate"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_special_delivery_1000_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-special-delivery-1000-rate" class="form-control"><?php echo $royal_mail_special_delivery_1000_rate; ?></textarea>
                    <span class="help-block"><?php echo $help_rate; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-special-delivery-1000-insurance"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_special_delivery_1000_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-special-delivery-1000-insurance" class="form-control"><?php echo $royal_mail_special_delivery_1000_insurance; ?></textarea>
                    <span class="help-block"><?php echo $help_insurance; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-special-delivery-1000-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_special_delivery_1000_status" id="input-special-delivery-1000-status" class="form-control">
                      <?php if ($royal_mail_special_delivery_1000_status) { ?>
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
              <div class="tab-pane" id="tab-special-delivery-2500">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-special-delivery-2500-rate"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_special_delivery_2500_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-special-delivery-2500-rate" class="form-control"><?php echo $royal_mail_special_delivery_2500_rate; ?></textarea>
                    <span class="help-block"><?php echo $help_rate; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-special-delivery-2500-insurance"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_special_delivery_2500_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-special-delivery-2500-insurance" class="form-control"><?php echo $royal_mail_special_delivery_2500_insurance; ?></textarea>
                    <span class="help-block"><?php echo $help_insurance; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-special-delivery-2500-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_special_delivery_2500_status" id="input-special-delivery-2500-status" class="form-control">
                      <?php if ($royal_mail_special_delivery_2500_status) { ?>
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
              <div class="tab-pane" id="tab-standard-parcels">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-standard-parcels-rate"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_standard_parcels_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-standard-parcels-rate" class="form-control"><?php echo $royal_mail_standard_parcels_rate; ?></textarea>
                    <span class="help-block"><?php echo $help_rate; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-standard-parcels-insurance"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_standard_parcels_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-standard-parcels-insurance" class="form-control"><?php echo $royal_mail_standard_parcels_insurance; ?></textarea>
                    <span class="help-block"><?php echo $help_insurance; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-standard-parcels-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_standard_parcels_status" id="input-standard-parcels-status" class="form-control">
                      <?php if ($royal_mail_standard_parcels_status) { ?>
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
              <div class="tab-pane" id="tab-airmail">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-airmail-rate-1"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_airmail_rate_1" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-airmail-rate-1" class="form-control"><?php echo $royal_mail_airmail_rate_1; ?></textarea>
                    <span class="help-block"><?php echo $help_airmail_rate_1; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-airmail-rate-2"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_airmail_rate_2" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-airmail-rate-2" class="form-control"><?php echo $royal_mail_airmail_rate_2; ?></textarea>
                    <span class="help-block"><?php echo $help_airmail_rate_2; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-airmail-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_airmail_status" id="input-airmail-status" class="form-control">
                      <?php if ($royal_mail_airmail_status) { ?>
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
              <div class="tab-pane" id="tab-international-signed">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-international-signed-rate-1"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_international_signed_rate_1" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-international-signed-rate-1" class="form-control"><?php echo $royal_mail_international_signed_rate_1; ?></textarea>
                    <span class="help-block"><?php echo $help_international_signed_rate_1; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-international-signed-insurance-1"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_international_signed_insurance_1" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-international-signed-insurance-1" class="form-control"><?php echo $royal_mail_international_signed_insurance_1; ?></textarea>
                    <span class="help-block"><?php echo $help_international_signed_insurance_1; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-international-signed-rate-2"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_international_signed_rate_2" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-international-signed-rate-2" class="form-control"><?php echo $royal_mail_international_signed_rate_2; ?></textarea>
                    <span class="help-block"><?php echo $help_international_signed_rate_2; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-international-signed-insurance-2"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_international_signed_insurance_2" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-international-signed-insurance-2" class="form-control"><?php echo $royal_mail_international_signed_insurance_2; ?></textarea>
                    <span class="help-block"><?php echo $help_international_signed_insurance_2; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-international-signed-rate-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_international_signed_status" id="input-international-signed-rate-status" class="form-control">
                      <?php if ($royal_mail_international_signed_status) { ?>
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
              <div class="tab-pane" id="tab-airsure">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-airsure-rate-1"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_airsure_rate_1" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-airsure-rate-1" class="form-control"><?php echo $royal_mail_airsure_rate_1; ?></textarea>
                    <span class="help-block"><?php echo $help_airsure_rate_1; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-airsure-insurance-1"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_airsure_insurance_1" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-airsure-insurance-1" class="form-control"><?php echo $royal_mail_airsure_insurance_1; ?></textarea>
                    <span class="help-block"><?php echo $help_airsure_insurance_1; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-airsure-rate-2"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_airsure_rate_2" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-airsure-rate-2" class="form-control"><?php echo $royal_mail_airsure_rate_2; ?></textarea>
                    <span class="help-block"><?php echo $help_airsure_rate_2; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-airsure-insurance-2"><?php echo $entry_insurance; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_airsure_insurance_2" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-airsure-insurance-2" class="form-control"><?php echo $royal_mail_airsure_insurance_2; ?></textarea>
                    <span class="help-block"><?php echo $help_airsure_insurance_2; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-airsure-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_airsure_status" id="input-airsure-status" class="form-control">
                      <?php if ($royal_mail_airsure_status) { ?>
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
              <div class="tab-pane" id="tab-surface">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-surface-rate"><?php echo $entry_rate; ?></label>
                  <div class="col-sm-10">
                    <textarea name="royal_mail_surface_rate" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-surface-rate" class="form-control"><?php echo $royal_mail_surface_rate; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-surface-status"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="royal_mail_surface_status" id="input-surface-status" class="form-control">
                      <?php if ($royal_mail_surface_status) { ?>
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
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 