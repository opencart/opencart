<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/shipping.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_user_id; ?></td>
          <td><input type="text" name="usps_user_id" value="<?php echo $usps_user_id; ?>" />
            <?php if ($error_user_id) { ?>
            <span class="error"><?php echo $error_user_id; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="text" name="usps_password" value="<?php echo $usps_password; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="usps_postcode" value="<?php echo $usps_postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_domestic; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <div class="even">
                <?php if ($usps_domestic_0) { ?>
                <input type="checkbox" name="usps_domestic_0" value="1" checked="checked" />
                <?php echo $text_domestic_0; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_0" value="1" />
                <?php echo $text_domestic_0; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_domestic_1) { ?>
                <input type="checkbox" name="usps_domestic_1" value="1" checked="checked" />
                <?php echo $text_domestic_1; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_1" value="1" />
                <?php echo $text_domestic_1; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_domestic_2) { ?>
                <input type="checkbox" name="usps_domestic_2" value="1" checked="checked" />
                <?php echo $text_domestic_2; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_2" value="1" />
                <?php echo $text_domestic_2; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_domestic_3) { ?>
                <input type="checkbox" name="usps_domestic_3" value="1" checked="checked" />
                <?php echo $text_domestic_3; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_3" value="1" />
                <?php echo $text_domestic_3; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_domestic_4) { ?>
                <input type="checkbox" name="usps_domestic_4" value="1" checked="checked" />
                <?php echo $text_domestic_4; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_4" value="1" />
                <?php echo $text_domestic_4; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_domestic_5) { ?>
                <input type="checkbox" name="usps_domestic_5" value="1" checked="checked" />
                <?php echo $text_domestic_5; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_5" value="1" />
                <?php echo $text_domestic_5; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_domestic_6) { ?>
                <input type="checkbox" name="usps_domestic_6" value="1" checked="checked" />
                <?php echo $text_domestic_6; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_6" value="1" />
                <?php echo $text_domestic_6; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_domestic_7) { ?>
                <input type="checkbox" name="usps_domestic_7" value="1" checked="checked" />
                <?php echo $text_domestic_7; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_7" value="1" />
                <?php echo $text_domestic_7; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_domestic_12) { ?>
                <input type="checkbox" name="usps_domestic_12" value="1" checked="checked" />
                <?php echo $text_domestic_12; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_12" value="1" />
                <?php echo $text_domestic_12; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_domestic_13) { ?>
                <input type="checkbox" name="usps_domestic_13" value="1" checked="checked" />
                <?php echo $text_domestic_13; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_13" value="1" />
                <?php echo $text_domestic_13; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_domestic_16) { ?>
                <input type="checkbox" name="usps_domestic_16" value="1" checked="checked" />
                <?php echo $text_domestic_16; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_16" value="1" />
                <?php echo $text_domestic_16; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_domestic_17) { ?>
                <input type="checkbox" name="usps_domestic_17" value="1" checked="checked" />
                <?php echo $text_domestic_17; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_17" value="1" />
                <?php echo $text_domestic_17; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_domestic_18) { ?>
                <input type="checkbox" name="usps_domestic_18" value="1" checked="checked" />
                <?php echo $text_domestic_18; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_18" value="1" />
                <?php echo $text_domestic_18; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_domestic_19) { ?>
                <input type="checkbox" name="usps_domestic_19" value="1" checked="checked" />
                <?php echo $text_domestic_19; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_19" value="1" />
                <?php echo $text_domestic_19; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_domestic_22) { ?>
                <input type="checkbox" name="usps_domestic_22" value="1" checked="checked" />
                <?php echo $text_domestic_22; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_22" value="1" />
                <?php echo $text_domestic_22; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_domestic_23) { ?>
                <input type="checkbox" name="usps_domestic_23" value="1" checked="checked" />
                <?php echo $text_domestic_23; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_23" value="1" />
                <?php echo $text_domestic_23; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_domestic_25) { ?>
                <input type="checkbox" name="usps_domestic_25" value="1" checked="checked" />
                <?php echo $text_domestic_25; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_25" value="1" />
                <?php echo $text_domestic_25; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_domestic_27) { ?>
                <input type="checkbox" name="usps_domestic_27" value="1" checked="checked" />
                <?php echo $text_domestic_27; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_27" value="1" />
                <?php echo $text_domestic_27; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_domestic_28) { ?>
                <input type="checkbox" name="usps_domestic_28" value="1" checked="checked" />
                <?php echo $text_domestic_28; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_domestic_28" value="1" />
                <?php echo $text_domestic_28; ?>
                <?php } ?>
              </div>
            </div></td>
        </tr>
        <tr>
          <td><?php echo $entry_international; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <div class="even">
                <?php if ($usps_international_1) { ?>
                <input type="checkbox" name="usps_international_1" value="1" checked="checked" />
                <?php echo $text_international_1; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_1" value="1" />
                <?php echo $text_international_1; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_international_2) { ?>
                <input type="checkbox" name="usps_international_2" value="1" checked="checked" />
                <?php echo $text_international_2; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_2" value="1" />
                <?php echo $text_international_2; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_international_4) { ?>
                <input type="checkbox" name="usps_international_4" value="1" checked="checked" />
                <?php echo $text_international_4; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_4" value="1" />
                <?php echo $text_international_4; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_international_5) { ?>
                <input type="checkbox" name="usps_international_5" value="1" checked="checked" />
                <?php echo $text_international_5; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_5" value="1" />
                <?php echo $text_international_5; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_international_6) { ?>
                <input type="checkbox" name="usps_international_6" value="1" checked="checked" />
                <?php echo $text_international_6; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_6" value="1" />
                <?php echo $text_international_6; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_international_7) { ?>
                <input type="checkbox" name="usps_international_7" value="1" checked="checked" />
                <?php echo $text_international_7; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_7" value="1" />
                <?php echo $text_international_7; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_international_8) { ?>
                <input type="checkbox" name="usps_international_8" value="1" checked="checked" />
                <?php echo $text_international_8; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_8" value="1" />
                <?php echo $text_international_8; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_international_9) { ?>
                <input type="checkbox" name="usps_international_9" value="1" checked="checked" />
                <?php echo $text_international_9; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_9" value="1" />
                <?php echo $text_international_9; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_international_10) { ?>
                <input type="checkbox" name="usps_international_10" value="1" checked="checked" />
                <?php echo $text_international_10; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_10" value="1" />
                <?php echo $text_international_10; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_international_11) { ?>
                <input type="checkbox" name="usps_international_11" value="1" checked="checked" />
                <?php echo $text_international_11; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_11" value="1" />
                <?php echo $text_international_11; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_international_12) { ?>
                <input type="checkbox" name="usps_international_12" value="1" checked="checked" />
                <?php echo $text_international_12; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_12" value="1" />
                <?php echo $text_international_12; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_international_13) { ?>
                <input type="checkbox" name="usps_international_13" value="1" checked="checked" />
                <?php echo $text_international_13; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_13" value="1" />
                <?php echo $text_international_13; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_international_14) { ?>
                <input type="checkbox" name="usps_international_14" value="1" checked="checked" />
                <?php echo $text_international_14; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_14" value="1" />
                <?php echo $text_international_14; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_international_15) { ?>
                <input type="checkbox" name="usps_international_15" value="1" checked="checked" />
                <?php echo $text_international_15; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_15" value="1" />
                <?php echo $text_international_15; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($usps_international_16) { ?>
                <input type="checkbox" name="usps_international_16" value="1" checked="checked" />
                <?php echo $text_international_16; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_16" value="1" />
                <?php echo $text_international_16; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($usps_international_21) { ?>
                <input type="checkbox" name="usps_international_21" value="1" checked="checked" />
                <?php echo $text_international_21; ?>
                <?php } else { ?>
                <input type="checkbox" name="usps_international_21" value="1" />
                <?php echo $text_international_21; ?>
                <?php } ?>
              </div>
            </div></td>
        </tr>
        <tr>
          <td><?php echo $entry_size; ?></td>
          <td><select name="usps_size">
              <?php foreach ($sizes as $size) { ?>
              <?php if ($size['value'] == $usps_size) { ?>
              <option value="<?php echo $size['value']; ?>" selected="selected"><?php echo $size['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $size['value']; ?>"><?php echo $size['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_container; ?></td>
          <td><select name="usps_container">
              <?php foreach ($containers as $container) { ?>
              <?php if ($container['value'] == $usps_container) { ?>
              <option value="<?php echo $container['value']; ?>" selected="selected"><?php echo $container['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $container['value']; ?>"><?php echo $container['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_machinable; ?></td>
          <td><select name="usps_machinable">
              <?php if ($usps_machinable) { ?>
              <option value="1" selected="selected"><?php echo $text_yes; ?></option>
              <option value="0"><?php echo $text_no; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_yes; ?></option>
              <option value="0" selected="selected"><?php echo $text_no; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_dimension; ?></td>
          <td><input type="text" name="usps_length" value="<?php echo $usps_length; ?>" size="4" />
            <input type="text" name="usps_width" value="<?php echo $usps_width; ?>" size="4" />
            <input type="text" name="usps_height" value="<?php echo $usps_height; ?>" size="4" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_girth; ?></td>
          <td><input type="text" name="usps_girth" value="<?php echo $usps_girth; ?>" size="4" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_display_time; ?></td>
          <td><?php if ($usps_display_time) { ?>
            <input type="radio" name="usps_display_time" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="usps_display_time" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="usps_display_time" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="usps_display_time" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr> 
        <tr>
          <td><?php echo $entry_display_weight; ?></td>
          <td><?php if ($usps_display_weight) { ?>
            <input type="radio" name="usps_display_weight" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="usps_display_weight" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="usps_display_weight" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="usps_display_weight" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>        
        <tr>
          <td><?php echo $entry_weight_class; ?></td>
          <td><select name="usps_weight_class">
              <?php foreach ($weight_classes as $weight_class) { ?>
              <?php if ($weight_class['unit'] == $usps_weight_class) { ?>
              <option value="<?php echo $weight_class['unit']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $weight_class['unit']; ?>"><?php echo $weight_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>        
        <tr>
          <td><?php echo $entry_tax; ?></td>
          <td><select name="usps_tax_class_id">
              <option value="0"><?php echo $text_none; ?></option>
              <?php foreach ($tax_classes as $tax_class) { ?>
              <?php if ($tax_class['tax_class_id'] == $usps_tax_class_id) { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="usps_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $usps_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="usps_status">
              <?php if ($usps_status) { ?>
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
          <td><input type="text" name="usps_sort_order" value="<?php echo $usps_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>