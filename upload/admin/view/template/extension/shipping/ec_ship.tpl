<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ec_ship" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ec_ship" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-key"><span data-toggle="tooltip" title="<?php echo $help_api_key; ?>"><?php echo $entry_api_key; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ec_ship_api_key" value="<?php echo $ec_ship_api_key; ?>" placeholder="<?php echo $entry_api_key; ?>" id="input-key" class="form-control" />
              <?php if ($error_api_key) { ?>
              <div class="text-danger"><?php echo $error_api_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-api_username"><span data-toggle="tooltip" title="<?php echo $help_api_username; ?>"><?php echo $entry_api_username; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ec_ship_api_username" value="<?php echo $ec_ship_api_username; ?>" placeholder="<?php echo $entry_api_username; ?>" id="input-api_username" class="form-control" />
              <?php if ($error_api_username) { ?>
              <div class="text-danger"><?php echo $error_api_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-username"><span data-toggle="tooltip" title="<?php echo $help_username; ?>"><?php echo $entry_username; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ec_ship_username" value="<?php echo $ec_ship_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
              <?php if ($error_username) { ?>
              <div class="text-danger"><?php echo $error_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_test; ?>"><?php echo $entry_test; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($ec_ship_test) { ?>
                <input type="radio" name="ec_ship_test" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="ec_ship_test" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$ec_ship_test) { ?>
                <input type="radio" name="ec_ship_test" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="ec_ship_test" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_service; ?>"><?php echo $entry_service; ?></span></label>
            <div class="col-sm-10">
              <div id="service" class="well well-sm" style="height: 150px; overflow: auto;">
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_air_registered_mail) { ?>
                      <input type="checkbox" name="ec_ship_air_registered_mail" value="1" checked="checked" />
                      <?php echo $text_air_registered_mail; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_air_registered_mail" value="1" />
                      <?php echo $text_air_registered_mail; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_air_parcel) { ?>
                      <input type="checkbox" name="ec_ship_air_parcel" value="1" checked="checked" />
                      <?php echo $text_air_parcel; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_air_parcel" value="1" />
                      <?php echo $text_air_parcel; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_e_express_service_to_us) { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_to_us" value="1" checked="checked" />
                      <?php echo $text_e_express_service_to_us; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_to_us" value="1" />
                      <?php echo $text_e_express_service_to_us; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_e_express_service_to_canada) { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_to_canada" value="1" checked="checked" />
                      <?php echo $text_e_express_service_to_canada; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_to_canada" value="1" />
                      <?php echo $text_e_express_service_to_canada; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_e_express_service_to_united_kingdom) { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_to_united_kingdom" value="1" checked="checked" />
                      <?php echo $text_e_express_service_to_united_kingdom; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_to_united_kingdom" value="1" />
                      <?php echo $text_e_express_service_to_united_kingdom; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_e_express_service_to_russia) { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_to_russia" value="1" checked="checked" />
                      <?php echo $text_e_express_service_to_russia; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_to_russia" value="1" />
                      <?php echo $text_e_express_service_to_russia; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_e_express_service_one) { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_one" value="1" checked="checked" />
                      <?php echo $text_e_express_service_one; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_one" value="1" />
                      <?php echo $text_e_express_service_one; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_e_express_service_two) { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_two" value="1" checked="checked" />
                      <?php echo $text_e_express_service_two; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_e_express_service_two" value="1" />
                      <?php echo $text_e_express_service_two; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_speed_post) { ?>
                      <input type="checkbox" name="ec_ship_speed_post" value="1" checked="checked" />
                      <?php echo $text_speed_post; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_speed_post" value="1" />
                      <?php echo $text_speed_post; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_smart_post) { ?>
                      <input type="checkbox" name="ec_ship_smart_post" value="1" checked="checked" />
                      <?php echo $text_smart_post; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_smart_post" value="1" />
                      <?php echo $text_smart_post; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ec_ship_local_courier_post) { ?>
                      <input type="checkbox" name="ec_ship_local_courier_post" value="1" checked="checked" />
                      <?php echo $text_local_courier_post; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ec_ship_local_courier_post" value="1" />
                      <?php echo $text_local_courier_post; ?>
                      <?php } ?>
                    </label>
                  </div>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-weight-class"><span data-toggle="tooltip" title="<?php echo $help_weight_class; ?>"><?php echo $entry_weight_class; ?></span></label>
            <div class="col-sm-10">
              <select name="ec_ship_weight_class_id" id="input-weight-class" class="form-control">
                <?php foreach ($weight_classes as $weight_class) { ?>
                <?php if ($weight_class['weight_class_id'] == $ec_ship_weight_class_id) { ?>
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
              <select name="ec_ship_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $ec_ship_tax_class_id) { ?>
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
              <select name="ec_ship_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $ec_ship_geo_zone_id) { ?>
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
              <select name="ec_ship_status" id="input-status" class="form-control">
                <?php if ($ec_ship_status) { ?>
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
              <input type="text" name="ec_ship_sort_order" value="<?php echo $ec_ship_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
