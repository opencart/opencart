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
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_rate; ?></td>
            <td><textarea name="parcelforce_48_rate" cols="40" rows="5"><?php echo $parcelforce_48_rate; ?></textarea></td>
          </tr>
          <tr>
            <td><?php echo $entry_insurance; ?></td>
            <td><textarea name="parcelforce_48_insurance" cols="40" rows="5"><?php echo $parcelforce_48_insurance; ?></textarea></td>
          </tr>
          <tr>
            <td><?php echo $entry_display_weight; ?></td>
            <td><?php if ($parcelforce_48_display_weight) { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="parcelforce_48_display_weight" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_weight" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="parcelforce_48_display_weight" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_display_insurance; ?></td>
            <td><?php if ($parcelforce_48_display_insurance) { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="parcelforce_48_display_insurance" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_display_time; ?></td>
            <td><?php if ($parcelforce_48_display_time) { ?>
              <input type="radio" name="parcelforce_48_display_time" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="parcelforce_48_display_time" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="parcelforce_48_display_time" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="parcelforce_48_display_time" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_tax_class; ?></td>
            <td><select name="parcelforce_48_tax_class_id">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $parcelforce_48_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="parcelforce_48_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $parcelforce_48_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="parcelforce_48_status">
                <?php if ($parcelforce_48_status) { ?>
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
            <td><input type="text" name="parcelforce_48_sort_order" value="<?php echo $parcelforce_48_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 