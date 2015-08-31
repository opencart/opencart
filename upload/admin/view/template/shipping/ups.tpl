<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ups" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ups" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-key"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_key; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ups_key" value="<?php echo $ups_key; ?>" placeholder="<?php echo $entry_key; ?>" id="input-key" class="form-control" />
              <?php if ($error_key) { ?>
              <div class="text-danger"><?php echo $error_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-username"><span data-toggle="tooltip" title="<?php echo $help_username; ?>"><?php echo $entry_username; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ups_username" value="<?php echo $ups_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
              <?php if ($error_username) { ?>
              <div class="text-danger"><?php echo $error_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ups_password" value="<?php echo $ups_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup"><span data-toggle="tooltip" title="<?php echo $help_pickup; ?>"><?php echo $entry_pickup; ?></span></label>
            <div class="col-sm-10">
              <select name="ups_pickup" id="input-pickup" class="form-control">
                <?php foreach ($pickups as $pickup) { ?>
                <?php if ($pickup['value'] == $ups_pickup) { ?>
                <option value="<?php echo $pickup['value']; ?>" selected="selected"><?php echo $pickup['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $pickup['value']; ?>"><?php echo $pickup['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-packaging"><span data-toggle="tooltip" title="<?php echo $help_packaging; ?>"><?php echo $entry_packaging; ?></span></label>
            <div class="col-sm-10">
              <select name="ups_packaging" id="input-packaging" class="form-control">
                <?php foreach ($packages as $package) { ?>
                <?php if ($package['value'] == $ups_packaging) { ?>
                <option value="<?php echo $package['value']; ?>" selected="selected"><?php echo $package['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $package['value']; ?>"><?php echo $package['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-classification"><span data-toggle="tooltip" title="<?php echo $help_classification; ?>"><?php echo $entry_classification; ?></span></label>
            <div class="col-sm-10">
              <select name="ups_classification" id="input-classification" class="form-control">
                <?php foreach ($classifications as $classification) { ?>
                <?php if ($classification['value'] == $ups_classification) { ?>
                <option value="<?php echo $classification['value']; ?>" selected="selected"><?php echo $classification['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $classification['value']; ?>"><?php echo $classification['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-origin"><span data-toggle="tooltip" title="<?php echo $help_origin; ?>"><?php echo $entry_origin; ?></span></label>
            <div class="col-sm-10">
              <select name="ups_origin" id="input-origin" class="form-control">
                <?php foreach ($origins as $origin) { ?>
                <?php if ($origin['value'] == $ups_origin) { ?>
                <option value="<?php echo $origin['value']; ?>" selected="selected"><?php echo $origin['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $origin['value']; ?>"><?php echo $origin['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-city"><span data-toggle="tooltip" title="<?php echo $help_city; ?>"><?php echo $entry_city; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ups_city" value="<?php echo $ups_city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
              <?php if ($error_city) { ?>
              <div class="text-danger"><?php echo $error_city; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-state"><span data-toggle="tooltip" title="<?php echo $help_state; ?>"><?php echo $entry_state; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ups_state" value="<?php echo $ups_state; ?>" placeholder="<?php echo $entry_state; ?>" id="input-state" class="form-control" maxlength="2" />
              <?php if ($error_state) { ?>
              <div class="text-danger"><?php echo $error_state; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-country"><span data-toggle="tooltip" title="<?php echo $help_country; ?>"><?php echo $entry_country; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ups_country" value="<?php echo $ups_country; ?>" placeholder="<?php echo $entry_country; ?>" id="input-country" class="form-control" maxlength="2" />
              <?php if ($error_country) { ?>
              <div class="text-danger"><?php echo $error_country; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-postcode"><span data-toggle="tooltip" title="<?php echo $help_postcode; ?>"><?php echo $entry_postcode; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ups_postcode" value="<?php echo $ups_postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_test; ?>"><?php echo $entry_test; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($ups_test) { ?>
                <input type="radio" name="ups_test" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="ups_test" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$ups_test) { ?>
                <input type="radio" name="ups_test" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="ups_test" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-quote-type"><span data-toggle="tooltip" title="<?php echo $help_quote_type; ?>"><?php echo $entry_quote_type; ?></span></label>
            <div class="col-sm-10">
              <select name="ups_quote_type" id="input-quote-type" class="form-control">
                <?php foreach ($quote_types as $quote_type) { ?>
                <?php if ($quote_type['value'] == $ups_quote_type) { ?>
                <option value="<?php echo $quote_type['value']; ?>" selected="selected"><?php echo $quote_type['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $quote_type['value']; ?>"><?php echo $quote_type['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_service; ?>"><?php echo $entry_service; ?></span></label>
            <div class="col-sm-10">
              <div id="service" class="well well-sm" style="height: 150px; overflow: auto;">
                <div id="US">
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_01) { ?>
                      <input type="checkbox" name="ups_us_01" value="1" checked="checked" />
                      <?php echo $text_next_day_air; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_01" value="1" />
                      <?php echo $text_next_day_air; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_02) { ?>
                      <input type="checkbox" name="ups_us_02" value="1" checked="checked" />
                      <?php echo $text_2nd_day_air; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_02" value="1" />
                      <?php echo $text_2nd_day_air; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_03) { ?>
                      <input type="checkbox" name="ups_us_03" value="1" checked="checked" />
                      <?php echo $text_ground; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_03" value="1" />
                      <?php echo $text_ground; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_07) { ?>
                      <input type="checkbox" name="ups_us_07" value="1" checked="checked" />
                      <?php echo $text_worldwide_express; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_07" value="1" />
                      <?php echo $text_worldwide_express; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_08) { ?>
                      <input type="checkbox" name="ups_us_08" value="1" checked="checked" />
                      <?php echo $text_worldwide_expedited; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_08" value="1" />
                      <?php echo $text_worldwide_expedited; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_11) { ?>
                      <input type="checkbox" name="ups_us_11" value="1" checked="checked" />
                      <?php echo $text_standard; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_11" value="1" />
                      <?php echo $text_standard; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_12) { ?>
                      <input type="checkbox" name="ups_us_12" value="1" checked="checked" />
                      <?php echo $text_3_day_select; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_12" value="1" />
                      <?php echo $text_3_day_select; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_13) { ?>
                      <input type="checkbox" name="ups_us_13" value="1" checked="checked" />
                      <?php echo $text_next_day_air_saver; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_13" value="1" />
                      <?php echo $text_next_day_air_saver; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_14) { ?>
                      <input type="checkbox" name="ups_us_14" value="1" checked="checked" />
                      <?php echo $text_next_day_air_early_am; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_14" value="1" />
                      <?php echo $text_next_day_air_early_am; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_54) { ?>
                      <input type="checkbox" name="ups_us_54" value="1" checked="checked" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_54" value="1" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_59) { ?>
                      <input type="checkbox" name="ups_us_59" value="1" checked="checked" />
                      <?php echo $text_2nd_day_air_am; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_59" value="1" />
                      <?php echo $text_2nd_day_air_am; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_us_65) { ?>
                      <input type="checkbox" name="ups_us_65" value="1" checked="checked" />
                      <?php echo $text_saver; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_us_65" value="1" />
                      <?php echo $text_saver; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div id="PR">
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_pr_01) { ?>
                      <input type="checkbox" name="ups_pr_01" value="1" checked="checked" />
                      <?php echo $text_next_day_air; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_pr_01" value="1" />
                      <?php echo $text_next_day_air; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_pr_02) { ?>
                      <input type="checkbox" name="ups_pr_02" value="1" checked="checked" />
                      <?php echo $text_2nd_day_air; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_pr_02" value="1" />
                      <?php echo $text_2nd_day_air; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_pr_03) { ?>
                      <input type="checkbox" name="ups_pr_03" value="1" checked="checked" />
                      <?php echo $text_ground; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_pr_03" value="1" />
                      <?php echo $text_ground; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_pr_07) { ?>
                      <input type="checkbox" name="ups_pr_07" value="1" checked="checked" />
                      <?php echo $text_worldwide_express; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_pr_07" value="1" />
                      <?php echo $text_worldwide_express; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_pr_08) { ?>
                      <input type="checkbox" name="ups_pr_08" value="1" checked="checked" />
                      <?php echo $text_worldwide_expedited; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_pr_08" value="1" />
                      <?php echo $text_worldwide_expedited; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_pr_14) { ?>
                      <input type="checkbox" name="ups_pr_14" value="1" checked="checked" />
                      <?php echo $text_next_day_air_early_am; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_pr_14" value="1" />
                      <?php echo $text_next_day_air_early_am; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_pr_54) { ?>
                      <input type="checkbox" name="ups_pr_54" value="1" checked="checked" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_pr_54" value="1" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_pr_65) { ?>
                      <input type="checkbox" name="ups_pr_65" value="1" checked="checked" />
                      <?php echo $text_saver; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_pr_65" value="1" />
                      <?php echo $text_saver; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div id="CA">
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_01) { ?>
                      <input type="checkbox" name="ups_ca_01" value="1" checked="checked" />
                      <?php echo $text_express; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_01" value="1" />
                      <?php echo $text_express; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_02) { ?>
                      <input type="checkbox" name="ups_ca_02" value="1" checked="checked" />
                      <?php echo $text_expedited; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_02" value="1" />
                      <?php echo $text_expedited; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_07) { ?>
                      <input type="checkbox" name="ups_ca_07" value="1" checked="checked" />
                      <?php echo $text_worldwide_express; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_07" value="1" />
                      <?php echo $text_worldwide_express; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_08) { ?>
                      <input type="checkbox" name="ups_ca_08" value="1" checked="checked" />
                      <?php echo $text_worldwide_expedited; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_08" value="1" />
                      <?php echo $text_worldwide_expedited; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_11) { ?>
                      <input type="checkbox" name="ups_ca_11" value="1" checked="checked" />
                      <?php echo $text_standard; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_11" value="1" />
                      <?php echo $text_standard; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_12) { ?>
                      <input type="checkbox" name="ups_ca_12" value="1" checked="checked" />
                      <?php echo $text_3_day_select; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_12" value="1" />
                      <?php echo $text_3_day_select; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_13) { ?>
                      <input type="checkbox" name="ups_ca_13" value="1" checked="checked" />
                      <?php echo $text_saver; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_13" value="1" />
                      <?php echo $text_saver; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_14) { ?>
                      <input type="checkbox" name="ups_ca_14" value="1" checked="checked" />
                      <?php echo $text_express_early_am; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_14" value="1" />
                      <?php echo $text_express_early_am; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_54) { ?>
                      <input type="checkbox" name="ups_ca_54" value="1" checked="checked" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_54" value="1" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_ca_65) { ?>
                      <input type="checkbox" name="ups_ca_65" value="1" checked="checked" />
                      <?php echo $text_saver; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_ca_65" value="1" />
                      <?php echo $text_saver; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div id="MX">
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_mx_07) { ?>
                      <input type="checkbox" name="ups_mx_07" value="1" checked="checked" />
                      <?php echo $text_worldwide_express; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_mx_07" value="1" />
                      <?php echo $text_worldwide_express; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_mx_08) { ?>
                      <input type="checkbox" name="ups_mx_08" value="1" checked="checked" />
                      <?php echo $text_worldwide_expedited; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_mx_08" value="1" />
                      <?php echo $text_worldwide_expedited; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_mx_54) { ?>
                      <input type="checkbox" name="ups_mx_54" value="1" checked="checked" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_mx_54" value="1" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_mx_65) { ?>
                      <input type="checkbox" name="ups_mx_65" value="1" checked="checked" />
                      <?php echo $text_saver; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_mx_65" value="1" />
                      <?php echo $text_saver; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div id="EU">
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_07) { ?>
                      <input type="checkbox" name="ups_eu_07" value="1" checked="checked" />
                      <?php echo $text_express; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_07" value="1" />
                      <?php echo $text_express; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_08) { ?>
                      <input type="checkbox" name="ups_eu_08" value="1" checked="checked" />
                      <?php echo $text_expedited; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_08" value="1" />
                      <?php echo $text_expedited; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_11) { ?>
                      <input type="checkbox" name="ups_eu_11" value="1" checked="checked" />
                      <?php echo $text_standard; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_11" value="1" />
                      <?php echo $text_standard; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_54) { ?>
                      <input type="checkbox" name="ups_eu_54" value="1" checked="checked" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_54" value="1" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_65) { ?>
                      <input type="checkbox" name="ups_eu_65" value="1" checked="checked" />
                      <?php echo $text_saver; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_65" value="1" />
                      <?php echo $text_saver; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_82) { ?>
                      <input type="checkbox" name="ups_eu_82" value="1" checked="checked" />
                      <?php echo $text_today_standard; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_82" value="1" />
                      <?php echo $text_today_standard; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_83) { ?>
                      <input type="checkbox" name="ups_eu_83" value="1" checked="checked" />
                      <?php echo $text_today_dedicated_courier; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_83" value="1" />
                      <?php echo $text_today_dedicated_courier; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_84) { ?>
                      <input type="checkbox" name="ups_eu_84" value="1" checked="checked" />
                      <?php echo $text_today_intercity; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_84" value="1" />
                      <?php echo $text_today_intercity; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_85) { ?>
                      <input type="checkbox" name="ups_eu_85" value="1" checked="checked" />
                      <?php echo $text_today_express; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_85" value="1" />
                      <?php echo $text_today_express; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_eu_86) { ?>
                      <input type="checkbox" name="ups_eu_86" value="1" checked="checked" />
                      <?php echo $text_today_express_saver; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_eu_86" value="1" />
                      <?php echo $text_today_express_saver; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div id="other">
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_other_07) { ?>
                      <input type="checkbox" name="ups_other_07" value="1" checked="checked" />
                      <?php echo $text_express; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_other_07" value="1" />
                      <?php echo $text_express; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_other_08) { ?>
                      <input type="checkbox" name="ups_other_08" value="1" checked="checked" />
                      <?php echo $text_expedited; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_other_08" value="1" />
                      <?php echo $text_expedited; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_other_11) { ?>
                      <input type="checkbox" name="ups_other_11" value="1" checked="checked" />
                      <?php echo $text_standard; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_other_11" value="1" />
                      <?php echo $text_standard; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_other_54) { ?>
                      <input type="checkbox" name="ups_other_54" value="1" checked="checked" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_other_54" value="1" />
                      <?php echo $text_worldwide_express_plus; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <?php if ($ups_other_65) { ?>
                      <input type="checkbox" name="ups_other_65" value="1" checked="checked" />
                      <?php echo $text_saver; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="ups_other_65" value="1" />
                      <?php echo $text_saver; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_insurance; ?>"><?php echo $entry_insurance; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($ups_insurance) { ?>
                <input type="radio" name="ups_insurance" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="ups_insurance" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$ups_insurance) { ?>
                <input type="radio" name="ups_insurance" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="ups_insurance" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_display_weight; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($ups_display_weight) { ?>
                <input type="radio" name="ups_display_weight" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="ups_display_weight" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$ups_display_weight) { ?>
                <input type="radio" name="ups_display_weight" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="ups_display_weight" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-weight-class"><span data-toggle="tooltip" title="<?php echo $help_weight_class; ?>"><?php echo $entry_weight_class; ?></span></label>
            <div class="col-sm-10">
              <select name="ups_weight_class_id" id="input-weight-class" class="form-control">
                <?php foreach ($weight_classes as $weight_class) { ?>
                <?php if ($weight_class['weight_class_id'] == $ups_weight_class_id) { ?>
                <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-length-class"><span data-toggle="tooltip" title="<?php echo $help_length_class; ?>"><?php echo $entry_length_class; ?></span></label>
            <div class="col-sm-10">
              <select name="ups_length_class_id" id="input-length-class" class="form-control">
                <?php foreach ($length_classes as $length_class) { ?>
                <?php if ($length_class['length_class_id'] == $ups_length_class_id) { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-length"><span data-toggle="tooltip" title="<?php echo $help_dimension; ?>"><?php echo $entry_dimension; ?></span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-4">
                  <input type="text" name="ups_length" value="<?php echo $ups_length; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length" class="form-control" />
                </div>
                <div class="col-sm-4">
                  <input type="text" name="ups_width" value="<?php echo $ups_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                </div>
                <div class="col-sm-4">
                  <input type="text" name="ups_height" value="<?php echo $ups_height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                </div>
              </div>
              <?php if ($error_dimension) { ?>
              <div class="text-danger"><?php echo $error_dimension; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
            <div class="col-sm-10">
              <select name="ups_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $ups_tax_class_id) { ?>
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
              <select name="ups_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $ups_geo_zone_id) { ?>
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
              <select name="ups_status" id="input-status" class="form-control">
                <?php if ($ups_status) { ?>
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
              <input type="text" name="ups_sort_order" value="<?php echo $ups_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-debug"><span data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
            <div class="col-sm-10">
              <select name="ups_debug" id="input-debug" class="form-control">
                <?php if ($ups_debug) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('select[name=\'ups_origin\']').on('change', function() {
	$('#service > div').hide();	
										 
	$('#' + this.value).show();	
});

$('select[name=\'ups_origin\']').trigger('change');
//--></script></div>
<?php echo $footer; ?>