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
        <td width="25%"><span class="required">*</span> <?php echo $entry_title; ?></td>
        <td><input type="text" name="title" value="<?php echo $title; ?>" />
          <br />
          <?php if ($error_title) { ?>
          <span class="error"><?php echo $error_title; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_description; ?></td>
        <td><input type="text" name="description" value="<?php echo $description; ?>" />
          <?php if ($error_description) { ?>
          <br />
          <span class="error"><?php echo $error_description; ?></span>
          <?php } ?></td>
      </tr>
    </table>
    <br />
    <div id="tax_rate">
      <?php $tax_rate_row = 0; ?>
      <?php foreach ($tax_rates as $tax_rate) { ?>
      <table class="green" id="tax_rate_row<?php echo $tax_rate_row; ?>">
        <tr>
          <td><?php echo $entry_geo_zone; ?><br />
            <select name="tax_rate[<?php echo $tax_rate_row; ?>][geo_zone_id]" id="geo_zone_id<?php echo $tax_rate_row; ?>">
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php  if ($geo_zone['geo_zone_id'] == $tax_rate['geo_zone_id']) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td><span class="required">*</span> <?php echo $entry_description; ?><br />
            <input type="text" name="tax_rate[<?php echo $tax_rate_row; ?>][description]" value="<?php echo $tax_rate['description']; ?>" /></td>
          <td><span class="required">*</span> <?php echo $entry_rate; ?><br />
            <input type="text" name="tax_rate[<?php echo $tax_rate_row; ?>][rate]" value="<?php echo $tax_rate['rate']; ?>" /></td>
          <td><span class="required">*</span> <?php echo $entry_priority; ?><br />
            <input type="text" name="tax_rate[<?php echo $tax_rate_row; ?>][priority]" value="<?php echo $tax_rate['priority']; ?>" size="1" /></td>
          <td><a onclick="$('#tax_rate_row<?php echo $tax_rate_row; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
      </table>
      <?php $tax_rate_row++; ?>
      <?php } ?>
    </div>
    <a onclick="addRate();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_rate; ?></span><span class="button_right"></span></a></div>
</form>
<script type="text/javascript"><!--
var tax_rate_row = <?php echo $tax_rate_row; ?>;

function addRate() {
	html  = '<table class="green" id="tax_rate_row' + tax_rate_row + '">';
	html += '<tr>';
	html += '<td><?php echo $entry_geo_zone; ?><br /><select name="tax_rate[' + tax_rate_row + '][geo_zone_id]" id="geo_zone_id' + tax_rate_row + '">';
    <?php foreach ($geo_zones as $geo_zone) { ?>
    html += '<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>';
    <?php } ?>
	html += '</select></td>';
	html += '<td><span class="required">*</span> <?php echo $entry_description; ?><br /><input type="text" name="tax_rate[' + tax_rate_row + '][description]" value="" /></td>';
	html += '<td><span class="required">*</span> <?php echo $entry_rate; ?><br /><input type="text" name="tax_rate[' + tax_rate_row + '][rate]" value="" /></td>';
	html += '<td><span class="required">*</span> <?php echo $entry_priority; ?><br /><input type="text" name="tax_rate[' + tax_rate_row + '][priority]" value="" size="1" /></td>';
	html += '<td><a onclick="$(\'#tax_rate_row' + tax_rate_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
	html += '</table>';
	
	$('#tax_rate').append(html);
			
	tax_rate_row++;
}
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; ?>