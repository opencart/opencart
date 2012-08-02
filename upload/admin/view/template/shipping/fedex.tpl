<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_key; ?></td>
            <td><input type="text" name="fedex_key" value="<?php echo $fedex_key; ?>" />
              <?php if ($error_key) { ?>
              <span class="error"><?php echo $error_key; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_password; ?></td>
            <td><input type="text" name="fedex_password" value="<?php echo $fedex_password; ?>" />
              <?php if ($error_password) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_account; ?></td>
            <td><input type="text" name="fedex_account" value="<?php echo $fedex_account; ?>" />
              <?php if ($error_account) { ?>
              <span class="error"><?php echo $error_account; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_meter; ?></td>
            <td><input type="text" name="fedex_meter" value="<?php echo $fedex_meter; ?>" />
              <?php if ($error_meter) { ?>
              <span class="error"><?php echo $error_meter; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_test; ?></td>
            <td><?php if ($fedex_test) { ?>
              <input type="radio" name="fedex_test" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fedex_test" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="fedex_test" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fedex_test" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>          
          <tr>
            <td><?php echo $entry_service; ?></td>
            <td><div class="scrollbox">
                <div class="odd">
                  <?php if ($fedex_priority_overnight) { ?>
                  <input type="checkbox" name="fedex_priority_overnight" value="1" checked="checked" />
                  <?php echo $text_priority_overnight; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_priority_overnight" value="1" />
                  <?php echo $text_priority_overnight; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_standard_overnight) { ?>
                  <input type="checkbox" name="fedex_standard_overnight" value="1" checked="checked" />
                  <?php echo $text_standard_overnight; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_standard_overnight" value="1" />
                  <?php echo $text_standard_overnight; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_first_overnight) { ?>
                  <input type="checkbox" name="fedex_first_overnight" value="1" checked="checked" />
                  <?php echo $text_first_overnight; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_first_overnight" value="1" />
                  <?php echo $text_first_overnight; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_2_day) { ?>
                  <input type="checkbox" name="fedex_2_day" value="1" checked="checked" />
                  <?php echo $text_2_day; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_2_day" value="1" />
                  <?php echo $text_2_day; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_express_saver) { ?>
                  <input type="checkbox" name="fedex_express_saver" value="1" checked="checked" />
                  <?php echo $text_express_saver; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_express_saver" value="1" />
                  <?php echo $text_express_saver; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_international_priority) { ?>
                  <input type="checkbox" name="fedex_international_priority" value="1" checked="checked" />
                  <?php echo $text_international_priority; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_international_priority" value="1" />
                  <?php echo $text_international_priority; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_international_economy) { ?>
                  <input type="checkbox" name="fedex_international_economy" value="1" checked="checked" />
                  <?php echo $text_international_economy; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_international_economy" value="1" />
                  <?php echo $text_international_economy; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_international_first) { ?>
                  <input type="checkbox" name="fedex_international_first" value="1" checked="checked" />
                  <?php echo $text_international_first; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_international_first" value="1" />
                  <?php echo $text_international_first; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_1_day_freight) { ?>
                  <input type="checkbox" name="fedex_1_day_freight" value="1" checked="checked" />
                  <?php echo $text_1_day_freight; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_1_day_freight" value="1" />
                  <?php echo $text_1_day_freight; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_2_day_freight) { ?>
                  <input type="checkbox" name="fedex_2_day_freight" value="1" checked="checked" />
                  <?php echo $text_2_day_freight; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_2_day_freight" value="1" />
                  <?php echo $text_2_day_freight; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_3_day_freight) { ?>
                  <input type="checkbox" name="fedex_3_day_freight" value="1" checked="checked" />
                  <?php echo $text_3_day_freight; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_3_day_freight" value="1" />
                  <?php echo $text_3_day_freight; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_ground) { ?>
                  <input type="checkbox" name="fedex_ground" value="1" checked="checked" />
                  <?php echo $text_ground; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_ground" value="1" />
                  <?php echo $text_ground; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_ground_home) { ?>
                  <input type="checkbox" name="fedex_ground_home" value="1" checked="checked" />
                  <?php echo $text_ground_home; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_ground_home" value="1" />
                  <?php echo $text_ground_home; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_international_priority_freight) { ?>
                  <input type="checkbox" name="fedex_international_priority_freight" value="1" checked="checked" />
                  <?php echo $text_international_priority_freight; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_international_priority_freight" value="1" />
                  <?php echo $text_international_priority_freight; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_international_economy_freight) { ?>
                  <input type="checkbox" name="fedex_international_economy_freight" value="1" checked="checked" />
                  <?php echo $text_international_economy_freight; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_international_economy_freight" value="1" />
                  <?php echo $text_international_economy_freight; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_europe_first_international_priority) { ?>
                  <input type="checkbox" name="fedex_europe_first_international_priority" value="1" checked="checked" />
                  <?php echo $text_europe_first_international_priority; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_europe_first_international_priority" value="1" />
                  <?php echo $text_europe_first_international_priority; ?>
                  <?php } ?>
                </div>
              </div>
              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
          </tr>
          <tr>
            <td><?php echo $entry_tax_class; ?></td>
            <td><select name="fedex_tax_class_id">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $fedex_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="fedex_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $fedex_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status ?></td>
            <td><select name="fedex_status">
                <?php if ($fedex_status) { ?>
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
            <td><input type="text" name="fedex_sort_order" value="<?php echo $fedex_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>