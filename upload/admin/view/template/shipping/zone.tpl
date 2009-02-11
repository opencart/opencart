<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"><?php echo $entry_status; ?><br />
    <select name="zone_status">
      <?php if ($zone_status) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php foreach ($geo_zones as $geo_zone) { ?>
    <?php echo $geo_zone['name']; ?> <?php echo $entry_status; ?><br />
    <select name="zone_<?php echo $geo_zone['geo_zone_id']; ?>_status">
      <?php if (${'zone_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $geo_zone['name']; ?> <?php echo $entry_cost; ?><br />
    <input type="text" name="zone_<?php echo $geo_zone['geo_zone_id']; ?>_cost" value="<?php echo ${'zone_' . $geo_zone['geo_zone_id'] . '_cost'}; ?>" />
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $text_example; ?>')" onmouseout="toolTip()" /><br />
    <br />
    <?php } ?>
    <?php echo $entry_tax; ?><br />
    <select name="zone_tax_class_id">
      <option value="0"><?php echo $text_none; ?></option>
      <?php foreach ($tax_classes as $tax_class) { ?>
      <?php if ($tax_class['tax_class_id'] == $zone_tax_class_id) { ?>
      <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_sort_order; ?><br />
    <input type="text" name="zone_sort_order" value="<?php echo $zone_sort_order; ?>" size="1" />
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>