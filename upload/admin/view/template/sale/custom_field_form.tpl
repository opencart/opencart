<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-custom-field" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="fa fa-times"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="fa fa-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-custom-field" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
          <div class="col-sm-10">
            <?php foreach ($languages as $language) { ?>
            <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
              <input type="text" name="custom_field_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($custom_field_description[$language['language_id']]) ? $custom_field_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
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
        <div class="form-group" id="display-value">
          <label class="col-sm-2 control-label" for="input-value"><?php echo $entry_value; ?></label>
          <div class="col-sm-10">
            <input type="text" name="value" value="<?php echo $value; ?>" placeholder="<?php echo $entry_value; ?>" id="input-value" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_location; ?></label>
          <div class="col-sm-10">
             <?php foreach ($locations as $location) { ?>
             <div class="checkbox">
              <label>
                <?php if (in_array($location['value'], $custom_field_location)) { ?>
                <input type="checkbox" name="location[]" value="<?php echo $location['value']; ?>" checked="checked" />
                <?php echo $location['text']; ?>
                <?php } else { ?>
                <input type="checkbox" name="location[]" value="<?php echo $location['value']; ?>" />
                <?php echo $location['text']; ?>
                <?php } ?>
              </label>
            </div>      
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_customer_group; ?></label>
          <div class="col-sm-10">
            <?php $customer_group_row = 0; ?>
            <?php foreach ($customer_groups as $customer_group) { ?>
            <div class="checkbox">
              <label>
                <?php if (in_array($customer_group['customer_group_id'], $custom_field_customer_group)) { ?>
                <input type="checkbox" name="custom_field_customer_group[<?php echo $customer_group_row; ?>][customer_group_id]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                <?php echo $customer_group['name']; ?>
                <?php } else { ?>
                <input type="checkbox" name="custom_field_customer_group[<?php echo $customer_group_row; ?>][customer_group_id]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                <?php echo $customer_group['name']; ?>
                <?php } ?>
              </label>
            </div>
            <?php $customer_group_row++; ?>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_required; ?></label>
          <div class="col-sm-10">
            <?php $customer_group_row = 0; ?>
            <?php foreach ($customer_groups as $customer_group) { ?>
            <div class="checkbox">
              <label>
                <?php if (in_array($customer_group['customer_group_id'], $custom_field_required)) { ?>
                <input type="checkbox" name="custom_field_customer_group[<?php echo $customer_group_row; ?>][required]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                <?php echo $customer_group['name']; ?>
                <?php } else { ?>
                <input type="checkbox" name="custom_field_customer_group[<?php echo $customer_group_row; ?>][required]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                <?php echo $customer_group['name']; ?>
                <?php } ?>
              </label>
            </div>
            <?php $customer_group_row++; ?>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
          <div class="col-sm-10">
            <select name="status" id="input-status" class="form-control">
              <?php if ($status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
          <div class="col-sm-10">
            <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
          </div>
        </div>
        <table id="custom-field-value" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left required"><?php echo $entry_custom_value; ?></td>
              <td class="text-right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php $custom_field_value_row = 0; ?>
            <?php foreach ($custom_field_values as $custom_field_value) { ?>
            <tr id="custom-field-value-row<?php echo $custom_field_value_row; ?>">
              <td class="text-left"><input type="hidden" name="custom_field_value[<?php echo $custom_field_value_row; ?>][custom_field_value_id]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                <?php foreach ($languages as $language) { ?>
                <div class="input-group"> <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                  <input type="text" name="custom_field_value[<?php echo $custom_field_value_row; ?>][custom_field_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($custom_field_value['custom_field_value_description'][$language['language_id']]) ? $custom_field_value['custom_field_value_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_custom_value; ?>" class="form-control" />
                </div>
                <?php if (isset($error_custom_field_value[$custom_field_value_row][$language['language_id']])) { ?>
                <div class="text-danger"><?php echo $error_custom_field_value[$custom_field_value_row][$language['language_id']]; ?></div>
                <?php } ?>
                <?php } ?></td>
              <td class="text-right"><input type="text" name="custom_field_value[<?php echo $custom_field_value_row; ?>][sort_order]" value="<?php echo $custom_field_value['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
              <td class="text-left"><button onclick="$('#custom-field-value-row<?php echo $custom_field_value_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></td>
            </tr>
            <?php $custom_field_value_row++; ?>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="text-left"><button type="button" onclick="addCustomFieldValue();" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_custom_field_value_add; ?></button></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'type\']').on('change', function() {
	if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox') {
		$('#custom-field-value').show();
		$('#display-value').hide();
	} else {
		$('#custom-field-value').hide();
		$('#display-value').show();
	}
	
	$('input[name=\'value\']').attr('type', 'text');
	
	if (this.value == 'date') {
		 $('input[name=\'value\']').attr('type', 'date');
	} else if (this.value == 'time') {
		$('input[name=\'value\']').attr('type', 'time');
	} else if (this.value == 'datetime') {
		$('input[name=\'value\']').attr('type', 'datetime-local');
	}
});

$('select[name=\'type\']').trigger('change');

$('input[name^=\'location\']').on('change', function() {
	
	if (this.value == 'account') {
		//$('#custom-field-value').show();
		//$('#display-value').hide();
	}
	
	
	
});

var custom_field_value_row = <?php echo $custom_field_value_row; ?>;

function addCustomFieldValue() {
	html  = '<tr id="custom-field-value-row' + custom_field_value_row + '">';	
    html += '  <td class="text-left"><input type="hidden" name="custom_field_value[' + custom_field_value_row + '][custom_field_value_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '    <div class="input-group">';
	html += '      <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="custom_field_value[' + custom_field_value_row + '][custom_field_value_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_custom_value; ?>" class="form-control" />';
    html += '    </div>';
	<?php } ?>
	html += '  </td>';
	html += '  <td class="text-right"><input type="text" name="custom_field_value[' + custom_field_value_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#custom-field-value-row' + custom_field_value_row + '\').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></td>';
	html += '</tr>';	
	
	$('#custom-field-value tbody').append(html);
	
	custom_field_value_row++;
}
//--></script> 
<?php echo $footer; ?>