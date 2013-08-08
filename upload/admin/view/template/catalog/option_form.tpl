<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-edit icon-large"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <button type="submit" form="form-option" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-option" class="form-horizontal">
      <div class="form-group required">
        <div class="col-lg-3 control-label"><?php echo $entry_name; ?></div>
        <div class="col-lg-9">
          <?php foreach ($languages as $language) { ?>
          <input type="text" name="option_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_description[$language['language_id']]) ? $option_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (isset($error_name[$language['language_id']])) { ?>
          <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
          <?php } ?>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-type"><?php echo $entry_type; ?></label>
        <div class="col-lg-9">
          <select name="type" id="input-type">
            <optgroup label="<?php echo $text_choose; ?>">
            <?php if ($type == 'select') { ?>
            <option value="select" selected="selected"><?php echo $text_select; ?></option>
            <?php } else { ?>
            <option value="select"><?php echo $text_select; ?></option>
            <?php } ?>
            <?php if ($type == 'radio') { ?>
            <option value="radio" selected="selected"><?php echo $text_radio; ?></option>
            <?php } else { ?>
            <option value="radio"><?php echo $text_radio; ?></option>
            <?php } ?>
            <?php if ($type == 'checkbox') { ?>
            <option value="checkbox" selected="selected"><?php echo $text_checkbox; ?></option>
            <?php } else { ?>
            <option value="checkbox"><?php echo $text_checkbox; ?></option>
            <?php } ?>
            <?php if ($type == 'image') { ?>
            <option value="image" selected="selected"><?php echo $text_image; ?></option>
            <?php } else { ?>
            <option value="image"><?php echo $text_image; ?></option>
            <?php } ?>
            </optgroup>
            <optgroup label="<?php echo $text_input; ?>">
            <?php if ($type == 'text') { ?>
            <option value="text" selected="selected"><?php echo $text_text; ?></option>
            <?php } else { ?>
            <option value="text"><?php echo $text_text; ?></option>
            <?php } ?>
            <?php if ($type == 'textarea') { ?>
            <option value="textarea" selected="selected"><?php echo $text_textarea; ?></option>
            <?php } else { ?>
            <option value="textarea"><?php echo $text_textarea; ?></option>
            <?php } ?>
            </optgroup>
            <optgroup label="<?php echo $text_file; ?>">
            <?php if ($type == 'file') { ?>
            <option value="file" selected="selected"><?php echo $text_file; ?></option>
            <?php } else { ?>
            <option value="file"><?php echo $text_file; ?></option>
            <?php } ?>
            </optgroup>
            <optgroup label="<?php echo $text_date; ?>">
            <?php if ($type == 'date') { ?>
            <option value="date" selected="selected"><?php echo $text_date; ?></option>
            <?php } else { ?>
            <option value="date"><?php echo $text_date; ?></option>
            <?php } ?>
            <?php if ($type == 'time') { ?>
            <option value="time" selected="selected"><?php echo $text_time; ?></option>
            <?php } else { ?>
            <option value="time"><?php echo $text_time; ?></option>
            <?php } ?>
            <?php if ($type == 'datetime') { ?>
            <option value="datetime" selected="selected"><?php echo $text_datetime; ?></option>
            <?php } else { ?>
            <option value="datetime"><?php echo $text_datetime; ?></option>
            <?php } ?>
            </optgroup>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="col-lg-9">
          <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
        </div>
      </div>
      <table id="option-value" class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left required"><?php echo $entry_option_value; ?></td>
            <td class="text-left"><?php echo $entry_image; ?></td>
            <td class="text-right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php $option_value_row = 0; ?>
          <?php foreach ($option_values as $option_value) { ?>
          <tr id="option-value-row<?php echo $option_value_row; ?>">
            <td class="text-left"><input type="hidden" name="option_value[<?php echo $option_value_row; ?>][option_value_id]" value="<?php echo $option_value['option_value_id']; ?>" />
              <?php foreach ($languages as $language) { ?>
              <input type="text" name="option_value[<?php echo $option_value_row; ?>][option_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_value['option_value_description'][$language['language_id']]) ? $option_value['option_value_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_option_value; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_option_value[$option_value_row][$language['language_id']])) { ?>
              <span class="error"><?php echo $error_option_value[$option_value_row][$language['language_id']]; ?></span>
              <?php } ?>
              <?php } ?></td>
            <td class="text-left"><div class="image"><img src="<?php echo $option_value['thumb']; ?>" alt="" class="img-polaroid" />
                <input type="hidden" name="option_value[<?php echo $option_value_row; ?>][image]" value="<?php echo $option_value['image']; ?>" />
                <div class="image-option"><a href="#" title="<?php echo $button_edit; ?>" data-toggle="modal" data-target="#modal"><span class="icon-pencil"></span></a> <a href="#" title="<?php echo $button_clear; ?>" onclick="$(this).parent().parent().find('img').attr('src', '<?php echo $no_image; ?>'); $(this).parent().parent().find('input').attr('value', ''); return false;"><span class="icon-trash"></span></a></div>
              </div></td>
            <td class="text-right"><input type="text" name="option_value[<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $option_value['sort_order']; ?>" class="input-mini" /></td>
            <td class="text-left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
          </tr>
          <?php $option_value_row++; ?>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3"></td>
            <td class="text-left"><a onclick="addOptionValue();" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_option_value; ?></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'type\']').on('change', function() {
	if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' || this.value == 'image') {
		$('#option-value').show();
	} else {
		$('#option-value').hide();
	}
});

var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue() {
	html  = '<tr id="option-value-row' + option_value_row + '">';	
    html += '  <td class="text-left"><input type="hidden" name="option_value[' + option_value_row + '][option_value_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="option_value[' + option_value_row + '][option_value_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_option_value; ?>" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '  </td>';
    html += '  <td class="text-left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" class="img-polaroid" /><input type="hidden" name="option_value[' + option_value_row + '][image]" value="" /><div class="image-option"><a href="#" title="<?php echo $button_edit; ?>" data-toggle="modal" data-target="#modal"><span class="icon-pencil"></span></a> <a href="#" title="<?php echo $button_clear; ?>" onclick="$(this).parent().parent().find(\'img\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(this).parent().parent().find(\'input\').attr(\'value\', \'\'); return false;"><span class="icon-trash"></span></a></div></td>';
	html += '  <td class="text-right"><input type="text" name="option_value[' + option_value_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" /></td>';
	html += '  <td class="text-left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
	html += '</tr>';	
	
	$('#option-value tbody').append(html);
	
	option_value_row++;
}
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<?php echo $footer; ?>