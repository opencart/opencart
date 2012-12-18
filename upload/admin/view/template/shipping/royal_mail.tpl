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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div class="vtabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-1st-class-standard"><?php echo $tab_1st_class_standard; ?></a><a href="#tab-1st-class-recorded"><?php echo $tab_1st_class_recorded; ?></a><a href="#tab-2nd-class-standard"><?php echo $tab_2nd_class_standard; ?></a><a href="#tab-2nd-class-recorded"><?php echo $tab_2nd_class_recorded; ?></a><a href="#tab-special-delivery-500"><?php echo $tab_special_delivery_500; ?></a><a href="#tab-special-delivery-1000"><?php echo $tab_special_delivery_1000; ?></a><a href="#tab-special-delivery-2500"><?php echo $tab_special_delivery_2500; ?></a><a href="#tab-standard-parcels"><?php echo $tab_standard_parcels; ?></a><a href="#tab-airmail"><?php echo $tab_airmail; ?></a><a href="#tab-international-signed"><?php echo $tab_international_signed; ?></a><a href="#tab-airsure"><?php echo $tab_airsure; ?></a><a href="#tab-surface"><?php echo $tab_surface; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general" class="vtabs-content">
          <table class="form">
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
              <td><?php echo $entry_weight_class; ?></td>
              <td><select name="royal_mail_weight_class_id">
                  <?php foreach ($weight_classes as $weight_class) { ?>
                  <?php if ($weight_class['weight_class_id'] == $royal_mail_weight_class_id) { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_tax_class; ?></td>
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
        </div>
        <div id="tab-1st-class-standard" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_rate; ?></td>
              <td><textarea name="royal_mail_1st_class_standard_rate" cols="40" rows="5"><?php echo $royal_mail_1st_class_standard_rate; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_insurance; ?></td>
              <td><textarea name="royal_mail_1st_class_standard_insurance" cols="40" rows="5"><?php echo $royal_mail_1st_class_standard_insurance; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_1st_class_standard_status">
                  <?php if ($royal_mail_1st_class_standard_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-1st-class-recorded" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_rate; ?></td>
              <td><textarea name="royal_mail_1st_class_recorded_rate" cols="40" rows="5"><?php echo $royal_mail_1st_class_recorded_rate; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_insurance; ?></td>
              <td><textarea name="royal_mail_1st_class_recorded_insurance" cols="40" rows="5"><?php echo $royal_mail_1st_class_recorded_insurance; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_1st_class_recorded_status">
                  <?php if ($royal_mail_1st_class_recorded_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-2nd-class-standard" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_rate; ?></td>
              <td><textarea name="royal_mail_2nd_class_standard_rate" cols="40" rows="5"><?php echo $royal_mail_2nd_class_standard_rate; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_2nd_class_standard_status">
                  <?php if ($royal_mail_2nd_class_standard_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-2nd-class-recorded" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_rate; ?></td>
              <td><textarea name="royal_mail_2nd_class_recorded_rate" cols="40" rows="5"><?php echo $royal_mail_2nd_class_recorded_rate; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_insurance; ?></td>
              <td><textarea name="royal_mail_2nd_class_recorded_insurance" cols="40" rows="5"><?php echo $royal_mail_2nd_class_recorded_insurance; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_2nd_class_recorded_status">
                  <?php if ($royal_mail_2nd_class_recorded_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-special-delivery-500" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_rate; ?></td>
              <td><textarea name="royal_mail_special_delivery_500_rate" cols="40" rows="5"><?php echo $royal_mail_special_delivery_500_rate; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_insurance; ?></td>
              <td><textarea name="royal_mail_special_delivery_500_insurance" cols="40" rows="5"><?php echo $royal_mail_special_delivery_500_insurance; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_special_delivery_500_status">
                  <?php if ($royal_mail_special_delivery_500_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-special-delivery-1000" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_rate; ?></td>
              <td><textarea name="royal_mail_special_delivery_1000_rate" cols="40" rows="5"><?php echo $royal_mail_special_delivery_1000_rate; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_insurance; ?></td>
              <td><textarea name="royal_mail_special_delivery_1000_insurance" cols="40" rows="5"><?php echo $royal_mail_special_delivery_1000_insurance; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_special_delivery_1000_status">
                  <?php if ($royal_mail_special_delivery_1000_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-special-delivery-2500" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_rate; ?></td>
              <td><textarea name="royal_mail_special_delivery_2500_rate" cols="40" rows="5"><?php echo $royal_mail_special_delivery_2500_rate; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_insurance; ?></td>
              <td><textarea name="royal_mail_special_delivery_2500_insurance" cols="40" rows="5"><?php echo $royal_mail_special_delivery_2500_insurance; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_special_delivery_2500_status">
                  <?php if ($royal_mail_special_delivery_2500_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-standard-parcels" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_rate; ?></td>
              <td><textarea name="royal_mail_standard_parcels_rate" cols="40" rows="5"><?php echo $royal_mail_standard_parcels_rate; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_insurance; ?></td>
              <td><textarea name="royal_mail_standard_parcels_insurance" cols="40" rows="5"><?php echo $royal_mail_standard_parcels_insurance; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_standard_parcels_status">
                  <?php if ($royal_mail_standard_parcels_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-airmail" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_airmail_rate_1; ?></td>
              <td><textarea name="royal_mail_airmail_rate_1" cols="40" rows="5"><?php echo $royal_mail_airmail_rate_1; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_airmail_rate_2; ?></td>
              <td><textarea name="royal_mail_airmail_rate_2" cols="40" rows="5"><?php echo $royal_mail_airmail_rate_2; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_airmail_status">
                  <?php if ($royal_mail_airmail_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-international-signed" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_international_signed_rate_1; ?></td>
              <td><textarea name="royal_mail_international_signed_rate_1" cols="40" rows="5"><?php echo $royal_mail_international_signed_rate_1; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_international_signed_insurance_1; ?></td>
              <td><textarea name="royal_mail_international_signed_insurance_1" cols="40" rows="5"><?php echo $royal_mail_international_signed_insurance_1; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_international_signed_rate_2; ?></td>
              <td><textarea name="royal_mail_international_signed_rate_2" cols="40" rows="5"><?php echo $royal_mail_international_signed_rate_2; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_international_signed_insurance_2; ?></td>
              <td><textarea name="royal_mail_international_signed_insurance_2" cols="40" rows="5"><?php echo $royal_mail_international_signed_insurance_2; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_international_signed_status">
                  <?php if ($royal_mail_international_signed_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-airsure" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_airsure_rate_1; ?></td>
              <td><textarea name="royal_mail_airsure_rate_1" cols="40" rows="5"><?php echo $royal_mail_airsure_rate_1; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_airsure_insurance_1; ?></td>
              <td><textarea name="royal_mail_airsure_insurance_1" cols="40" rows="5"><?php echo $royal_mail_airsure_insurance_1; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_airsure_rate_2; ?></td>
              <td><textarea name="royal_mail_airsure_rate_2" cols="40" rows="5"><?php echo $royal_mail_airsure_rate_2; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_airsure_insurance_2; ?></td>
              <td><textarea name="royal_mail_airsure_insurance_2" cols="40" rows="5"><?php echo $royal_mail_airsure_insurance_2; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_airsure_status">
                  <?php if ($royal_mail_airsure_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-surface" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_rate; ?></td>
              <td><textarea name="royal_mail_surface_rate" cols="40" rows="5"><?php echo $royal_mail_surface_rate; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="royal_mail_surface_status">
                  <?php if ($royal_mail_surface_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script> 
<?php echo $footer; ?> 