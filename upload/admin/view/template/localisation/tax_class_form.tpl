<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/tax.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_title; ?></td>
          <td><input type="text" name="title" value="<?php echo $title; ?>" />
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
      <table id="tax_rate" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_geo_zone; ?></td>
            <td class="left"><span class="required">*</span> <?php echo $entry_description; ?></td>
            <td class="left"><span class="required">*</span> <?php echo $entry_rate; ?></td>
            <td class="left"><span class="required">*</span> <?php echo $entry_priority; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $tax_rate_row = 0; ?>
        <?php foreach ($tax_rates as $tax_rate) { ?>
        <tbody id="tax_rate_row<?php echo $tax_rate_row; ?>">
          <tr>
            <td class="left"><select name="tax_rate[<?php echo $tax_rate_row; ?>][geo_zone_id]" id="geo_zone_id<?php echo $tax_rate_row; ?>">
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php  if ($geo_zone['geo_zone_id'] == $tax_rate['geo_zone_id']) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="left"><input type="text" name="tax_rate[<?php echo $tax_rate_row; ?>][description]" value="<?php echo $tax_rate['description']; ?>" /></td>
            <td class="left"><input type="text" name="tax_rate[<?php echo $tax_rate_row; ?>][rate]" value="<?php echo $tax_rate['rate']; ?>" /></td>
            <td class="left"><input type="text" name="tax_rate[<?php echo $tax_rate_row; ?>][priority]" value="<?php echo $tax_rate['priority']; ?>" size="1" /></td>
            <td class="left"><a onclick="$('#tax_rate_row<?php echo $tax_rate_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $tax_rate_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="4"></td>
            <td class="left"><a onclick="addRate();" class="button"><span><?php echo $button_add_rate; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var tax_rate_row = <?php echo $tax_rate_row; ?>;

function addRate() {
	html  = '<tbody id="tax_rate_row' + tax_rate_row + '">';
	html += '<tr>';
	html += '<td class="left"><select name="tax_rate[' + tax_rate_row + '][geo_zone_id]">';
    <?php foreach ($geo_zones as $geo_zone) { ?>
    html += '<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>';
    <?php } ?>
	html += '</select></td>';
	html += '<td class="left"><input type="text" name="tax_rate[' + tax_rate_row + '][description]" value="" /></td>';
	html += '<td class="left"><input type="text" name="tax_rate[' + tax_rate_row + '][rate]" value="" /></td>';
	html += '<td class="left"><input type="text" name="tax_rate[' + tax_rate_row + '][priority]" value="" size="1" /></td>';
	html += '<td class="left"><a onclick="$(\'#tax_rate_row' + tax_rate_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>';
	
	$('#tax_rate > tfoot').before(html);
	
	tax_rate_row++;
}
//--></script>
<?php echo $footer; ?>