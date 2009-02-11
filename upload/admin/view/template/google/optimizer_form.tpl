<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"><?php echo $entry_name; ?><br />
    <input type="text" name="name" value="<?php echo $name; ?>" />
    <br />
    <?php if ($error_name) { ?>
    <span class="error"><?php echo $error_name; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_url; ?><br />
    <input type="text" name="url" value="<?php echo $url; ?>" />
    <br />
    <?php if ($error_url) { ?>
    <span class="error"><?php echo $error_url; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_variation; ?><br />
    <div id="variation" style="margin-top: 3px;">
      <?php $i = 0; ?>
      <?php foreach ($variations as $variation) { ?>
      <div id="variation<?php echo $i; ?>" style="width: 400px; background: #E4F1C9; padding: 3px; margin-bottom: 10px;">
        <table width="100%">
          <tr>
            <td><input type="text" name="variation[<?php echo $i; ?>][url]" value="<?php echo $variation['url']; ?>" /></td>
            <td align="right"><a onclick="deleteVariation('<?php echo $i; ?>');" class="remove"><?php echo $button_remove; ?></a></td>
          </tr>
        </table>
      </div>
      <?php $i++; ?>
      <?php } ?>
    </div>
    <a onclick="addVariation();" class="add"><?php echo $button_add_variation; ?></a><br />
    <br />
    <span class="required">*</span> <?php echo $entry_control; ?><br />
    <textarea name="control" cols="60" rows="8"><?php echo $control; ?></textarea>
    <br />
    <?php if ($error_control) { ?>
    <span class="error"><?php echo $error_control; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_tracking; ?><br />
    <textarea name="tracking" cols="60" rows="8"><?php echo $tracking; ?></textarea>
    <br />
    <?php if ($error_tracking) { ?>
    <span class="error"><?php echo $error_tracking; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_conversion; ?><br />
    <textarea name="conversion" cols="60" rows="8"><?php echo $conversion; ?></textarea>
    <br />
    <?php if ($error_conversion) { ?>
    <span class="error"><?php echo $error_conversion; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_status; ?><br />
    <select name="status">
      <?php if ($status) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
  </div>
</form>
<script type="text/javascript"><!--
variation_row = <?php echo $i; ?>;

function addVariation() {
    row  = '<div id="variation' + variation_row + '" style="display: none; width: 400px; background: #E4F1C9; padding: 3px; margin-bottom: 10px;">';
	row += '<table width="100%">';
	row += '<tr>';
	row += '<td><input type="text" name="variation[' + variation_row + '][url]" value="" /></td>';
	row += '<td align="right"><a onclick="deleteVariation(\'' + variation_row + '\');" class="remove"><?php echo $button_remove; ?></a></td>';
	row += '</tr>';
	row += '</table>';
	row += '</div>';
	
	$('#variation').append(row);
	
	$('#variation' + variation_row).slideDown('slow');
	
	variation_row++;
}

function deleteVariation(variation_id) {
	$('#variation' + variation_id).slideUp('slow', function() {
		$('#variation' + variation_id).remove();
	});
}
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
