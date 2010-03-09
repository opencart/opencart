<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/shipping.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_key; ?></td>
          <td><input type="text" name="ups_key" value="<?php echo $ups_key; ?>" />
            <?php if ($error_key) { ?>
            <span class="error"><?php echo $error_key; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_username; ?></td>
          <td><input type="text" name="ups_username" value="<?php echo $ups_username; ?>" />
            <?php if ($error_username) { ?>
            <span class="error"><?php echo $error_username; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="text" name="ups_password" value="<?php echo $ups_password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_pickup; ?></td>
          <td><select name="ups_pickup">
              <?php foreach ($pickups as $pickup) { ?>
              <?php if ($pickup['value'] == $ups_pickup) { ?>
              <option value="<?php echo $pickup['value']; ?>" selected="selected"><?php echo $pickup['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $pickup['value']; ?>"><?php echo $pickup['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_packaging; ?></td>
          <td><select name="ups_packaging">
              <?php foreach ($packages as $package) { ?>
              <?php if ($package['value'] == $ups_packaging) { ?>
              <option value="<?php echo $package['value']; ?>" selected="selected"><?php echo $package['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $package['value']; ?>"><?php echo $package['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_classification; ?></td>
          <td><select name="ups_classification">
              <?php foreach ($classifications as $classification) { ?>
              <?php if ($classification['value'] == $ups_classification) { ?>
              <option value="<?php echo $classification['value']; ?>" selected="selected"><?php echo $classification['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $classification['value']; ?>"><?php echo $classification['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_origin; ?></td>
          <td><select name="ups_origin">
              <?php foreach ($origins as $origin) { ?>
              <?php if ($origin['value'] == $ups_origin) { ?>
              <option value="<?php echo $origin['value']; ?>" selected="selected"><?php echo $origin['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $origin['value']; ?>"><?php echo $origin['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="ups_city" value="<?php echo $ups_city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_state; ?></td>
          <td><input type="text" name="ups_state" value="<?php echo $ups_state; ?>" />
            <?php if ($error_state) { ?>
            <span class="error"><?php echo $error_state; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td><input type="text" name="ups_country" value="<?php echo $ups_country; ?>" />
            <?php if ($error_country) { ?>
            <span class="error"><?php echo $error_country; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_postcode; ?></td>
          <td><input type="text" name="ups_postcode" value="<?php echo $ups_postcode; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_test; ?></td>
          <td><?php if ($ups_test) { ?>
            <input type="radio" name="ups_test" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_test" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="ups_test" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_test" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_quote_type; ?></td>
          <td><select name="ups_quote_type">
              <?php foreach ($quote_types as $quote_type) { ?>
              <?php if ($quote_type['value'] == $ups_quote_type) { ?>
              <option value="<?php echo $quote_type['value']; ?>" selected="selected"><?php echo $quote_type['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $quote_type['value']; ?>"><?php echo $quote_type['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_negotiated_rates; ?></td>
          <td><?php if ($ups_negotiated_rates) { ?>
            <input type="radio" name="ups_negotiated_rates" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_negotiated_rates" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="ups_negotiated_rates" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_negotiated_rates" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_account_number; ?></td>
          <td><input type="text" name="ups_account_number" value="<?php echo $ups_account_number; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_manual_rate; ?></td>
          <td><input type="text" name="ups_manual_rate" value="<?php echo $ups_manual_rate; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_insurance; ?></td>
          <td><?php if ($ups_insurance) { ?>
            <input type="radio" name="ups_insurance" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_insurance" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="ups_insurance" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_insurance" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_service; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <div class="even">
                <?php if ($ups_next_day_air) { ?>
                <input type="checkbox" name="ups_next_day_air" value="1" checked="checked" />
                <?php echo $text_next_day_air; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_next_day_air" value="1" />
                <?php echo $text_next_day_air; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_2nd_day_air) { ?>
                <input type="checkbox" name="ups_2nd_day_air" value="1" checked="checked" />
                <?php echo $text_2nd_day_air; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_2nd_day_air" value="1" />
                <?php echo $text_2nd_day_air; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_ground) { ?>
                <input type="checkbox" name="ups_ground" value="1" checked="checked" />
                <?php echo $text_ground; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_ground" value="1" />
                <?php echo $text_ground; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_worldwide_express) { ?>
                <input type="checkbox" name="ups_worldwide_express" value="1" checked="checked" />
                <?php echo $text_worldwide_express; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_worldwide_express" value="1" />
                <?php echo $text_worldwide_express; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_worldwide_express_plus) { ?>
                <input type="checkbox" name="ups_worldwide_express_plus" value="1" checked="checked" />
                <?php echo $text_worldwide_express_plus; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_worldwide_express_plus" value="1" />
                <?php echo $text_worldwide_express_plus; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_worldwide_expedited) { ?>
                <input type="checkbox" name="ups_worldwide_expedited" value="1" checked="checked" />
                <?php echo $text_worldwide_expedited; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_worldwide_expedited" value="1" />
                <?php echo $text_worldwide_expedited; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_express) { ?>
                <input type="checkbox" name="ups_express" value="1" checked="checked" />
                <?php echo $text_express; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_express" value="1" />
                <?php echo $text_express; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_standard) { ?>
                <input type="checkbox" name="ups_standard" value="1" checked="checked" />
                <?php echo $text_standard; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_standard" value="1" />
                <?php echo $text_standard; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_3_day_select) { ?>
                <input type="checkbox" name="ups_3_day_select" value="1" checked="checked" />
                <?php echo $text_3_day_select; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_3_day_select" value="1" />
                <?php echo $text_3_day_select; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_next_day_air_saver) { ?>
                <input type="checkbox" name="ups_next_day_air_saver" value="1" checked="checked" />
                <?php echo $text_next_day_air_saver; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_next_day_air_saver" value="1" />
                <?php echo $text_next_day_air_saver; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_next_day_air_early_am) { ?>
                <input type="checkbox" name="ups_next_day_air_early_am" value="1" checked="checked" />
                <?php echo $text_next_day_air_early_am; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_next_day_air_early_am" value="1" />
                <?php echo $text_next_day_air_early_am; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_expedited) { ?>
                <input type="checkbox" name="ups_expedited" value="1" checked="checked" />
                <?php echo $text_expedited; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_expedited" value="1" />
                <?php echo $text_expedited; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_2nd_day_air_am) { ?>
                <input type="checkbox" name="ups_2nd_day_air_am" value="1" checked="checked" />
                <?php echo $text_2nd_day_air_am; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_2nd_day_air_am" value="1" />
                <?php echo $text_2nd_day_air_am; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_saver) { ?>
                <input type="checkbox" name="ups_saver" value="1" checked="checked" />
                <?php echo $text_saver; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_saver" value="1" />
                <?php echo $text_saver; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_express_early_am) { ?>
                <input type="checkbox" name="ups_express_early_am" value="1" checked="checked" />
                <?php echo $text_express_early_am; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_express_early_am" value="1" />
                <?php echo $text_express_early_am; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_express_plus) { ?>
                <input type="checkbox" name="ups_express_plus" value="1" checked="checked" />
                <?php echo $text_express_plus; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_express_plus" value="1" />
                <?php echo $text_express_plus; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_today_standard) { ?>
                <input type="checkbox" name="ups_today_standard" value="1" checked="checked" />
                <?php echo $text_today_standard; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_today_standard" value="1" />
                <?php echo $text_today_standard; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_today_dedicated_courier) { ?>
                <input type="checkbox" name="ups_today_dedicated_courier" value="1" checked="checked" />
                <?php echo $text_today_dedicated_courier; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_today_dedicated_courier" value="1" />
                <?php echo $text_today_dedicated_courier; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_today_intercity) { ?>
                <input type="checkbox" name="ups_today_intercity" value="1" checked="checked" />
                <?php echo $text_today_intercity; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_today_intercity" value="1" />
                <?php echo $text_today_intercity; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($ups_today_express) { ?>
                <input type="checkbox" name="ups_today_express" value="1" checked="checked" />
                <?php echo $text_today_express; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_today_express" value="1" />
                <?php echo $text_today_express; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($ups_today_express_saver) { ?>
                <input type="checkbox" name="ups_today_express_saver" value="1" checked="checked" />
                <?php echo $text_today_express_saver; ?>
                <?php } else { ?>
                <input type="checkbox" name="ups_today_express_saver" value="1" />
                <?php echo $text_today_express_saver; ?>
                <?php } ?>
              </div>
            </div></td>
        </tr>
        <tr>
          <td><?php echo $entry_display_time; ?></td>
          <td><?php if ($ups_display_time) { ?>
            <input type="radio" name="ups_display_time" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_display_time" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="ups_display_time" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_display_time" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_display_weight; ?></td>
          <td><?php if ($ups_display_weight) { ?>
            <input type="radio" name="ups_display_weight" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_display_weight" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="ups_display_weight" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="ups_display_weight" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_tax; ?></td>
          <td><select name="ups_tax_class_id">
              <option value="0"><?php echo $text_none; ?></option>
              <?php foreach ($tax_classes as $tax_class) { ?>
              <?php if ($tax_class['tax_class_id'] == $ups_tax_class_id) { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="ups_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $ups_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="ups_status">
              <?php if ($ups_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="ups_sort_order" value="<?php echo $ups_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>