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
    <select name="chronopay_status">
      <?php if ($chronopay_status) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_geo_zone; ?><br />
    <select name="chronopay_geo_zone_id">
      <option value="0"><?php echo $text_all_zones; ?></option>
      <?php foreach ($geo_zones as $geo_zone) { ?>
      <?php if ($geo_zone['geo_zone_id'] == $chronopay_geo_zone_id) { ?>
      <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_order_status; ?><br />
    <select name="chronopay_order_status_id">
      <?php foreach ($order_statuses as $order_status) { ?>
      <?php if ($order_status['order_status_id'] == $chronopay_order_status_id) { ?>
      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_product_id; ?><br />
    <input type="text" name="chronopay_product_id" value="<?php echo $chronopay_product_id; ?>" />
    <br />
    <?php if ($error_product_id) { ?>
    <span class="error"><?php echo $error_product_id; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_product_name; ?><br />
    <input type="text" name="chronopay_product_name" value="<?php echo $chronopay_product_name; ?>" />
    <br />
    <?php if ($error_product_name) { ?>
    <span class="error"><?php echo $error_product_name; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_callback; ?><br />
    <div class="help"><?php echo $help_callback; ?></div>
    <textarea cols="40" rows="5"><?php echo $callback; ?></textarea>
    <br />
    <br />    
    <?php echo $entry_sort_order; ?><br />
    <input type="text" name="chronopay_sort_order" value="<?php echo $chronopay_sort_order; ?>" size="1" />
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
