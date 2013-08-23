<?php echo $header; ?>
<div id="content" class="container">
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
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-option" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-option" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
          <div class="col-sm-10">
            <?php foreach ($languages as $language) { ?>
            <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
              <input type="text" name="option_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_description[$language['language_id']]) ? $option_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
            </div>
            <?php if (isset($error_name[$language['language_id']])) { ?>
            <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
            <?php } ?>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
          <div class="col-sm-10">
            <select name="type" id="input-type" class="form-control">
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
          <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
          <div class="col-sm-10">
            <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
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
                <div class="input-group"> <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                  <input type="text" name="option_value[<?php echo $option_value_row; ?>][option_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_value['option_value_description'][$language['language_id']]) ? $option_value['option_value_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_option_value; ?>" class="form-control" />
                </div>
                <?php if (isset($error_option_value[$option_value_row][$language['language_id']])) { ?>
                <div class="text-danger"><?php echo $error_option_value[$option_value_row][$language['language_id']]; ?></div>
                <?php } ?>
                <?php } ?></td>
              <td class="text-left"><img src="<?php echo $option_value['thumb']; ?>" alt="" class="img-thumbnail" />
                <input type="hidden" name="option_value[<?php echo $option_value_row; ?>][image]" value="<?php echo $option_value['image']; ?>" />
                <br />
                <br />
                <div class="btn-group">
                  <button type="button" data-toggle="modal" data-target="#modal" class="btn btn-default"><span class="icon-pencil"></span> <?php echo $button_edit; ?></button>
                  <button type="button" onclick="$(this).parent().parent().find('img').attr('src', '<?php echo $no_image; ?>'); $(this).parent().parent().find('input').attr('value', '');" class="btn btn-danger"><span class="icon-trash"></span> <?php echo $button_clear; ?></button>
                </div></td>
              <td class="text-right"><input type="text" name="option_value[<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $option_value['sort_order']; ?>" class="form-control" /></td>
              <td class="text-left"><button type="button" onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="btn btn-danger"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></button></td>
            </tr>
            <?php $option_value_row++; ?>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="text-left"><button type="button" onclick="addOptionValue();" class="btn btn-primary"><i class="icon-plus-sign"></i> <?php echo $button_add_option_value; ?></button></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
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
	html += '    <div class="input-group">';
	html += '      <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="option_value[' + option_value_row + '][option_value_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_option_value; ?>" class="form-control" />';
    html += '    </div>';
	<?php } ?>
	html += '  </td>';
    html += '  <td class="text-left"><img src="<?php echo $no_image; ?>" alt="" class="img-thumbnail" /><input type="hidden" name="option_value[' + option_value_row + '][image]" value="" /><div class="btn-group"><button type="button" data-toggle="modal" data-target="#modal" class="btn btn-default"><span class="icon-pencil"></span> <?php echo $button_edit; ?></button><button type="button" onclick="$(this).parent().parent().find(\'img\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(this).parent().parent().find(\'input\').attr(\'value\', \'\');" class="btn btn-danger"><span class="icon-trash"></span> <?php echo $button_clear; ?></button></div></td>';
	html += '  <td class="text-right"><input type="text" name="option_value[' + option_value_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="btn btn-danger"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></button></td>';
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