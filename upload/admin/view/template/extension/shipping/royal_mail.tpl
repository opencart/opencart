<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-royal-mail" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-royal-mail" class="form-horizontal">
          <div class="row">
            <div class="col-sm-2">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                <li><a href="#tab-special-delivery-500" data-toggle="tab"><?php echo $tab_special_delivery_500; ?></a></li>
                <li><a href="#tab-special-delivery-1000" data-toggle="tab"><?php echo $tab_special_delivery_1000; ?></a></li>
                <li><a href="#tab-special-delivery-2500" data-toggle="tab"><?php echo $tab_special_delivery_2500; ?></a></li>
                <li><a href="#tab-1st-class-signed" data-toggle="tab"><?php echo $tab_1st_class_signed; ?></a></li>
                <li><a href="#tab-2nd-class-signed" data-toggle="tab"><?php echo $tab_2nd_class_signed; ?></a></li>
                <li><a href="#tab-1st-class-standard" data-toggle="tab"><?php echo $tab_1st_class_standard; ?></a></li>
                <li><a href="#tab-2nd-class-standard" data-toggle="tab"><?php echo $tab_2nd_class_standard; ?></a></li>
                <li><a href="#tab-international-standard" data-toggle="tab"><?php echo $tab_international_standard; ?></a></li>
                <li><a href="#tab-international-tracked-signed" data-toggle="tab"><?php echo $tab_international_tracked_signed; ?></a></li>
                <li><a href="#tab-international-tracked" data-toggle="tab"><?php echo $tab_international_tracked; ?></a></li>
                <li><a href="#tab-international-signed" data-toggle="tab"><?php echo $tab_international_signed; ?></a></li>
                <li><a href="#tab-international-economy" data-toggle="tab"><?php echo $tab_international_economy; ?></a></li>
              </ul>
            </div>
            <div class="col-sm-10">
              <div class="tab-content">
                <div class="tab-pane active" id="tab-general">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_display_weight; ?>"><?php echo $entry_display_weight; ?></span></label>
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
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_display_insurance; ?>"><?php echo $entry_display_insurance; ?></span></label>
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
                    </div>
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
                <div class="tab-pane" id="tab-special-delivery-500">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-special-delivery-500-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_special_delivery_500_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-special-delivery-500-rate" class="form-control"><?php echo $royal_mail_special_delivery_500_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-special-delivery-500-insurance"><span data-toggle="tooltip" title="<?php echo $help_insurance; ?>"><?php echo $entry_insurance; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_special_delivery_500_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-special-delivery-500-insurance" class="form-control"><?php echo $royal_mail_special_delivery_500_insurance; ?></textarea>
                    </div>
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
                    <label class="col-sm-2 control-label" for="input-special-delivery-1000-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_special_delivery_1000_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-special-delivery-1000-rate" class="form-control"><?php echo $royal_mail_special_delivery_1000_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-special-delivery-1000-insurance"><span data-toggle="tooltip" title="<?php echo $help_insurance; ?>"><?php echo $entry_insurance; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_special_delivery_1000_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-special-delivery-1000-insurance" class="form-control"><?php echo $royal_mail_special_delivery_1000_insurance; ?></textarea>
                    </div>
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
                    <label class="col-sm-2 control-label" for="input-special-delivery-2500-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_special_delivery_2500_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-special-delivery-2500-rate" class="form-control"><?php echo $royal_mail_special_delivery_2500_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-special-delivery-2500-insurance"><span data-toggle="tooltip" title="<?php echo $help_insurance; ?>"><?php echo $entry_insurance; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_special_delivery_2500_insurance" rows="5" placeholder="<?php echo $entry_insurance; ?>" id="input-special-delivery-2500-insurance" class="form-control"><?php echo $royal_mail_special_delivery_2500_insurance; ?></textarea>
                    </div>
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
                <div class="tab-pane" id="tab-1st-class-signed">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-1st-class-signed-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_1st_class_signed_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-1st-class-signed-rate" class="form-control"><?php echo $royal_mail_1st_class_signed_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-1st-class-signed-status"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <select name="royal_mail_1st_class_signed_status" id="input-1st-class-signed-status" class="form-control">
                        <?php if ($royal_mail_1st_class_signed_status) { ?>
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
                <div class="tab-pane" id="tab-2nd-class-signed">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-2nd-class-signed-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_2nd_class_signed_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-2nd-class-signed-rate" class="form-control"><?php echo $royal_mail_2nd_class_signed_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-2nd-class-signed-status"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <select name="royal_mail_2nd_class_signed_status" id="input-2nd-class-signed-status" class="form-control">
                        <?php if ($royal_mail_2nd_class_signed_status) { ?>
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
                <div class="tab-pane" id="tab-1st-class-standard">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-1st-class-standard-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_1st_class_standard_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-1st-class-standard-rate" class="form-control"><?php echo $royal_mail_1st_class_standard_rate; ?></textarea>
                    </div>
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
                <div class="tab-pane" id="tab-2nd-class-standard">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-2nd-class-standard-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_2nd_class_standard_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-2nd-class-standard-rate" class="form-control"><?php echo $royal_mail_2nd_class_standard_rate; ?></textarea>
                    </div>
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
                <div class="tab-pane" id="tab-international-standard">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-standard-eu-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_eu; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_standard_eu_rate" rows="5" placeholder="<?php echo $entry_rate_eu; ?>" id="input-international-standard-eu-rate" class="form-control"><?php echo $royal_mail_international_standard_eu_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-standard-zone-1-rate"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_zone_1; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_standard_zone_1_rate" rows="5" placeholder="<?php echo $entry_rate_zone_1; ?>" id="input-international-standard-zone-1-rate" class="form-control"><?php echo $royal_mail_international_standard_zone_1_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-standard-zone-2-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_zone_2; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_standard_zone_2_rate" rows="5" placeholder="<?php echo $entry_rate_zone_2; ?>" id="input-international-standard-zone-2-rate" class="form-control"><?php echo $royal_mail_international_standard_zone_2_rate; ?></textarea>
                    </div>
                  </div>             
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-standard-status"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <select name="royal_mail_international_standard_status" id="input-international-standard-status" class="form-control">
                        <?php if ($royal_mail_international_standard_status) { ?>
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
                <div class="tab-pane" id="tab-international-tracked-signed">
                  <div class="alert alert-info"><?php echo $help_international; ?></div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-tracked-signed-eu-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_eu; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_tracked_signed_eu_rate" rows="5" placeholder="<?php echo $entry_rate_eu; ?>" id="input-international-tracked-signed-eu-rate" class="form-control"><?php echo $royal_mail_international_tracked_signed_eu_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-tracked-signed-zone-1-rate"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_zone_1; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_tracked_signed_zone_1_rate" rows="5" placeholder="<?php echo $entry_rate_zone_1; ?>" id="input-international-standard-zone-1-rate" class="form-control"><?php echo $royal_mail_international_tracked_signed_zone_1_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-tracked-signed-zone-2-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_zone_2; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_tracked_signed_zone_2_rate" rows="5" placeholder="<?php echo $entry_rate_zone_2; ?>" id="input-international-tracked-signed-zone-2-rate" class="form-control"><?php echo $royal_mail_international_tracked_signed_zone_2_rate; ?></textarea>
                    </div>
                  </div>                     
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-tracked-signed-status"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <select name="royal_mail_international_tracked_signed_status" id="input-international-tracked-signed-status" class="form-control">
                        <?php if ($royal_mail_international_tracked_signed_status) { ?>
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
                <div class="tab-pane" id="tab-international-tracked">
                  <div class="alert alert-info"><?php echo $help_international; ?></div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-tracked-eu-rate"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_eu; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_tracked_eu_rate" rows="5" placeholder="<?php echo $entry_rate_eu; ?>" id="input-international-tracked-eu-rate" class="form-control"><?php echo $royal_mail_international_tracked_eu_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-tracked-non-eu-rate"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_non_eu; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_tracked_non_eu_rate" rows="5" placeholder="<?php echo $entry_rate_non_eu; ?>" id="input-international-tracked-non-eu-rate" class="form-control"><?php echo $royal_mail_international_tracked_non_eu_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-tracked-zone-1-rate"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_zone_1; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_tracked_zone_1_rate" rows="5" placeholder="<?php echo $entry_rate_zone_1; ?>" id="input-international-tracked-zone-1-rate" class="form-control"><?php echo $royal_mail_international_tracked_zone_1_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-tracked-zone-2-rate"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_zone_2; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_tracked_zone_2_rate" rows="5" placeholder="<?php echo $entry_rate_zone_2; ?>" id="input-international-tracked-zone-2-rate" class="form-control"><?php echo $royal_mail_international_tracked_zone_2_rate; ?></textarea>
                    </div>
                  </div>                   
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-tracked-status"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <select name="royal_mail_international_tracked_status" id="input-international-tracked-status" class="form-control">
                        <?php if ($royal_mail_international_tracked_status) { ?>
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
                  <div class="alert alert-info"><?php echo $help_international; ?></div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-signed-eu-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_eu; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_signed_eu_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-international-signed-eu-rate" class="form-control"><?php echo $royal_mail_international_signed_eu_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-signed-zone-1-rate"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_zone_1; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_signed_zone_1_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-international-signed-zone-1-rate" class="form-control"><?php echo $royal_mail_international_signed_zone_1_rate; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-signed-zone-2-rate"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_rate; ?>"><?php echo $entry_rate_zone_2; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_signed_zone_2_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-international-signed-zone-2-rate" class="form-control"><?php echo $royal_mail_international_signed_zone_2_rate; ?></textarea>
                    </div>
                  </div>                   
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-signed-status"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <select name="royal_mail_international_signed_status" id="input-international-signed-status" class="form-control">
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
                <div class="tab-pane" id="tab-international-economy">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-economy-rate"><?php echo $entry_rate; ?></label>
                    <div class="col-sm-10">
                      <textarea name="royal_mail_international_economy_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-international-economy-rate" class="form-control"><?php echo $royal_mail_international_economy_rate; ?></textarea>
                    </div>
                  </div>                 
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-international-economy-status"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <select name="royal_mail_international_economy_status" id="input-international-economy-status" class="form-control">
                        <?php if ($royal_mail_international_economy_status) { ?>
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
</div>
<?php echo $footer; ?> 