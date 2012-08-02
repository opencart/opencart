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
            <td><?php echo $entry_domestic; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <div class="even">
                  <?php if ($fedex_domestic_00) { ?>
                  <input type="checkbox" name="fedex_domestic_00" value="1" checked="checked" />
                  <?php echo $text_domestic_00; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_00" value="1" />
                  <?php echo $text_domestic_00; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_01) { ?>
                  <input type="checkbox" name="fedex_domestic_01" value="1" checked="checked" />
                  <?php echo $text_domestic_01; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_01" value="1" />
                  <?php echo $text_domestic_01; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_02) { ?>
                  <input type="checkbox" name="fedex_domestic_02" value="1" checked="checked" />
                  <?php echo $text_domestic_02; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_02" value="1" />
                  <?php echo $text_domestic_02; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_03) { ?>
                  <input type="checkbox" name="fedex_domestic_03" value="1" checked="checked" />
                  <?php echo $text_domestic_03; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_03" value="1" />
                  <?php echo $text_domestic_03; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_domestic_1) { ?>
                  <input type="checkbox" name="fedex_domestic_1" value="1" checked="checked" />
                  <?php echo $text_domestic_1; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_1" value="1" />
                  <?php echo $text_domestic_1; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_2) { ?>
                  <input type="checkbox" name="fedex_domestic_2" value="1" checked="checked" />
                  <?php echo $text_domestic_2; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_2" value="1" />
                  <?php echo $text_domestic_2; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_domestic_3) { ?>
                  <input type="checkbox" name="fedex_domestic_3" value="1" checked="checked" />
                  <?php echo $text_domestic_3; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_3" value="1" />
                  <?php echo $text_domestic_3; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_4) { ?>
                  <input type="checkbox" name="fedex_domestic_4" value="1" checked="checked" />
                  <?php echo $text_domestic_4; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_4" value="1" />
                  <?php echo $text_domestic_4; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_domestic_5) { ?>
                  <input type="checkbox" name="fedex_domestic_5" value="1" checked="checked" />
                  <?php echo $text_domestic_5; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_5" value="1" />
                  <?php echo $text_domestic_5; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_6) { ?>
                  <input type="checkbox" name="fedex_domestic_6" value="1" checked="checked" />
                  <?php echo $text_domestic_6; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_6" value="1" />
                  <?php echo $text_domestic_6; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_domestic_7) { ?>
                  <input type="checkbox" name="fedex_domestic_7" value="1" checked="checked" />
                  <?php echo $text_domestic_7; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_7" value="1" />
                  <?php echo $text_domestic_7; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_12) { ?>
                  <input type="checkbox" name="fedex_domestic_12" value="1" checked="checked" />
                  <?php echo $text_domestic_12; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_12" value="1" />
                  <?php echo $text_domestic_12; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_domestic_13) { ?>
                  <input type="checkbox" name="fedex_domestic_13" value="1" checked="checked" />
                  <?php echo $text_domestic_13; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_13" value="1" />
                  <?php echo $text_domestic_13; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_16) { ?>
                  <input type="checkbox" name="fedex_domestic_16" value="1" checked="checked" />
                  <?php echo $text_domestic_16; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_16" value="1" />
                  <?php echo $text_domestic_16; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_domestic_17) { ?>
                  <input type="checkbox" name="fedex_domestic_17" value="1" checked="checked" />
                  <?php echo $text_domestic_17; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_17" value="1" />
                  <?php echo $text_domestic_17; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_18) { ?>
                  <input type="checkbox" name="fedex_domestic_18" value="1" checked="checked" />
                  <?php echo $text_domestic_18; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_18" value="1" />
                  <?php echo $text_domestic_18; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_domestic_19) { ?>
                  <input type="checkbox" name="fedex_domestic_19" value="1" checked="checked" />
                  <?php echo $text_domestic_19; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_19" value="1" />
                  <?php echo $text_domestic_19; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_22) { ?>
                  <input type="checkbox" name="fedex_domestic_22" value="1" checked="checked" />
                  <?php echo $text_domestic_22; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_22" value="1" />
                  <?php echo $text_domestic_22; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_domestic_23) { ?>
                  <input type="checkbox" name="fedex_domestic_23" value="1" checked="checked" />
                  <?php echo $text_domestic_23; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_23" value="1" />
                  <?php echo $text_domestic_23; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_25) { ?>
                  <input type="checkbox" name="fedex_domestic_25" value="1" checked="checked" />
                  <?php echo $text_domestic_25; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_25" value="1" />
                  <?php echo $text_domestic_25; ?>
                  <?php } ?>
                </div>
                <div class="odd">
                  <?php if ($fedex_domestic_27) { ?>
                  <input type="checkbox" name="fedex_domestic_27" value="1" checked="checked" />
                  <?php echo $text_domestic_27; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_27" value="1" />
                  <?php echo $text_domestic_27; ?>
                  <?php } ?>
                </div>
                <div class="even">
                  <?php if ($fedex_domestic_28) { ?>
                  <input type="checkbox" name="fedex_domestic_28" value="1" checked="checked" />
                  <?php echo $text_domestic_28; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="fedex_domestic_28" value="1" />
                  <?php echo $text_domestic_28; ?>
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