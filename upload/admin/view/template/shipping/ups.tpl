<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_postcode; ?></td>
        <td><input type="text" name="ups_postcode" value="<?php echo $ups_postcode; ?>" />
          <br />
          <?php if ($error_postcode) { ?>
          <span class="error"><?php echo $error_postcode; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_packaging; ?></td>
        <td><input type="text" name="ups_packaging" value="<?php echo $ups_packaging; ?>" />
          <br />
          <?php if ($error_packaging) { ?>
          <span class="error"><?php echo $error_packaging; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_rate; ?></td>
        <td><select name="ups_rate">
            <?php foreach ($rates as $rate) { ?>
            <?php if ($rate['code'] == $ups_rate) { ?>
            <option value="<?php echo $rate['code']; ?>" selected="selected"><?php echo $rate['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $rate['code']; ?>"><?php echo $rate['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_type; ?></td>
        <td><select name="ups_type">
            <?php foreach ($types as $type) { ?>
            <?php if ($type['code'] == $ups_type) { ?>
            <option value="<?php echo $type['code']; ?>" selected="selected"><?php echo $type['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $type['code']; ?>"><?php echo $type['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_service; ?></td>
        <td><div class="scrollbox">
            <?php $class = 'odd'; ?>
            <div class="even">
              <?php if ($ups_1dm) { ?>
              <input type="checkbox" name="ups_1dm" value="1" checked="checked" />
              <?php echo $text_1dm; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_1dm" value="1" />
              <?php echo $text_1dm; ?>
              <?php } ?>
            </div>
            <div class="odd">
              <?php if ($ups_1da) { ?>
              <input type="checkbox" name="ups_1da" value="1" checked="checked" />
              <?php echo $text_1da; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_1da" value="1" />
              <?php echo $text_1da; ?>
              <?php } ?>
            </div>
            <div class="even">
              <?php if ($ups_1dp) { ?>
              <input type="checkbox" name="ups_1dp" value="1" checked="checked" />
              <?php echo $text_1dp; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_1dp" value="1" />
              <?php echo $text_1dp; ?>
              <?php } ?>
            </div>
            <div class="odd">
              <?php if ($ups_2dm) { ?>
              <input type="checkbox" name="ups_2dm" value="1" checked="checked" />
              <?php echo $text_2dm; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_2dm" value="1" />
              <?php echo $text_2dm; ?>
              <?php } ?>
            </div>
            <div class="even">
              <?php if ($ups_2da) { ?>
              <input type="checkbox" name="ups_2da" value="1" checked="checked" />
              <?php echo $text_2da; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_2da" value="1" />
              <?php echo $text_2da; ?>
              <?php } ?>
            </div>
            <div class="odd">
              <?php if ($ups_3ds) { ?>
              <input type="checkbox" name="ups_3ds" value="1" checked="checked" />
              <?php echo $text_3ds; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_3ds" value="1" />
              <?php echo $text_3ds; ?>
              <?php } ?>
            </div>
            <div class="even">
              <?php if ($ups_gnd) { ?>
              <input type="checkbox" name="ups_gnd" value="1" checked="checked" />
              <?php echo $text_gnd; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_gnd" value="1" />
              <?php echo $text_gnd; ?>
              <?php } ?>
            </div>
            <div class="odd">
              <?php if ($ups_std) { ?>
              <input type="checkbox" name="ups_std" value="1" checked="checked" />
              <?php echo $text_std; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_std" value="1" />
              <?php echo $text_std; ?>
              <?php } ?>
            </div>
            <div class="even">
              <?php if ($ups_xpr) { ?>
              <input type="checkbox" name="ups_xpr" value="1" checked="checked" />
              <?php echo $text_xpr; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_xpr" value="1" />
              <?php echo $text_xpr; ?>
              <?php } ?>
            </div>
            <div class="odd">
              <?php if ($ups_xdm) { ?>
              <input type="checkbox" name="ups_xdm" value="1" checked="checked" />
              <?php echo $text_xdm; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_xdm" value="1" />
              <?php echo $text_xdm; ?>
              <?php } ?>
            </div>
            <div class="even">
              <?php if ($ups_xpd) { ?>
              <input type="checkbox" name="ups_xpd" value="1" checked="checked" />
              <?php echo $text_xpd; ?>
              <?php } else { ?>
              <input type="checkbox" name="ups_xpdd" value="1" />
              <?php echo $text_xpd; ?>
              <?php } ?>
            </div>
          </div></td>
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
        <td width="25%"><?php echo $entry_status; ?></td>
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
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; ?>