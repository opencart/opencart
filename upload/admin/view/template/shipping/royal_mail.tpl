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
          <td><?php echo $entry_service; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <div class="even">
                <?php if ($royal_mail_1st_class_standard) { ?>
                <input type="checkbox" name="royal_mail_1st_class_standard" value="1" checked="checked" />
                <?php echo $text_1st_class_standard; ?>
                <?php } else { ?>
                <input type="checkbox" name="royal_mail_1st_class_standard" value="1" />
                <?php echo $text_1st_class_standard; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($royal_mail_1st_class_recorded) { ?>
                <input type="checkbox" name="royal_mail_1st_class_recorded" value="1" checked="checked" />
                <?php echo $text_1st_class_recorded; ?>
                <?php } else { ?>
                <input type="checkbox" name="royal_mail_1st_class_recorded" value="1" />
                <?php echo $text_1st_class_recorded; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($royal_mail_2nd_class_standard) { ?>
                <input type="checkbox" name="royal_mail_2nd_class_standard" value="1" checked="checked" />
                <?php echo $text_2nd_class_standard; ?>
                <?php } else { ?>
                <input type="checkbox" name="royal_mail_2nd_class_standard" value="1" />
                <?php echo $text_2nd_class_standard; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($royal_mail_2nd_class_recorded) { ?>
                <input type="checkbox" name="royal_mail_2nd_class_recorded" value="1" checked="checked" />
                <?php echo $text_2nd_class_recorded; ?>
                <?php } else { ?>
                <input type="checkbox" name="royal_mail_2nd_class_recorded" value="1" />
                <?php echo $text_2nd_class_recorded; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($royal_mail_standard_parcels) { ?>
                <input type="checkbox" name="royal_mail_standard_parcels" value="1" checked="checked" />
                <?php echo $text_standard_parcels; ?>
                <?php } else { ?>
                <input type="checkbox" name="royal_mail_standard_parcels" value="1" />
                <?php echo $text_standard_parcels; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($royal_mail_airmail) { ?>
                <input type="checkbox" name="royal_mail_airmail" value="1" checked="checked" />
                <?php echo $text_airmail; ?>
                <?php } else { ?>
                <input type="checkbox" name="royal_mail_airmail" value="1" />
                <?php echo $text_airmail; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($royal_mail_international_signed) { ?>
                <input type="checkbox" name="royal_mail_international_signed" value="1" checked="checked" />
                <?php echo $text_international_signed; ?>
                <?php } else { ?>
                <input type="checkbox" name="royal_mail_international_signed" value="1" />
                <?php echo $text_international_signed; ?>
                <?php } ?>
              </div>
              <div class="odd">
                <?php if ($royal_mail_airsure) { ?>
                <input type="checkbox" name="royal_mail_airsure" value="1" checked="checked" />
                <?php echo $text_airsure; ?>
                <?php } else { ?>
                <input type="checkbox" name="royal_mail_airsure" value="1" />
                <?php echo $text_airsure; ?>
                <?php } ?>
              </div>
              <div class="even">
                <?php if ($royal_mail_surface) { ?>
                <input type="checkbox" name="royal_mail_surface" value="1" checked="checked" />
                <?php echo $text_surface; ?>
                <?php } else { ?>
                <input type="checkbox" name="royal_mail_surface" value="1" />
                <?php echo $text_surface; ?>
                <?php } ?>
              </div>
            </div></td>
        </tr>
        <tr>
          <td><?php echo $entry_display_weight; ?></td>
          <td><?php if ($royal_mail_display_weight) { ?>
            <input type="radio" name="royal_mail_display_weight" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="royal_mail_display_weight" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="royal_mail_display_weight" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="royal_mail_display_weight" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_display_insurance; ?></td>
          <td><?php if ($royal_mail_display_insurance) { ?>
            <input type="radio" name="royal_mail_display_insurance" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="royal_mail_display_insurance" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="royal_mail_display_insurance" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="royal_mail_display_insurance" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_display_time; ?></td>
          <td><?php if ($royal_mail_display_time) { ?>
            <input type="radio" name="royal_mail_display_time" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="royal_mail_display_time" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="royal_mail_display_time" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="royal_mail_display_time" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_weight_class; ?></td>
          <td><select name="royal_mail_weight_class">
              <?php foreach ($weight_classes as $weight_class) { ?>
              <?php if ($weight_class['unit'] == $royal_mail_weight_class) { ?>
              <option value="<?php echo $weight_class['unit']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $weight_class['unit']; ?>"><?php echo $weight_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>        
        <tr>
          <td><?php echo $entry_tax; ?></td>
          <td><select name="royal_mail_tax_class_id">
              <option value="0"><?php echo $text_none; ?></option>
              <?php foreach ($tax_classes as $tax_class) { ?>
              <?php if ($tax_class['tax_class_id'] == $royal_mail_tax_class_id) { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="royal_mail_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $royal_mail_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="royal_mail_status">
              <?php if ($royal_mail_status) { ?>
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
          <td><input type="text" name="royal_mail_sort_order" value="<?php echo $royal_mail_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>