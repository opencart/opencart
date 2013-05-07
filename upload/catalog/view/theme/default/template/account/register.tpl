<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <h1><?php echo $heading_title; ?></h1>
  <p><?php echo $text_account_already; ?></p>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
    <h2><?php echo $text_your_details; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input type="text" name="telephone" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" name="fax" value="" /></td>
        </tr>
      </table>
    </div>
    <h2><?php echo $text_your_address; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><?php echo $entry_company; ?></td>
          <td><input type="text" name="company" value="" /></td>
        </tr>
        <tr style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;">
          <td><?php echo $entry_customer_group; ?></td>
          <td><select name="customer_group_id">
              <?php foreach ($customer_groups as $customer_group) { ?>
              <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
              <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><input type="text" name="address_1" value="" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" value="" /></td>
        </tr>
        <tr>
          <td><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td><select name="country_id">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
          <td><select name="zone_id">
            </select></td>
        </tr>
      </table>
    </div>
    <h2><?php echo $text_your_password; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="" /></td>
        </tr>
      </table>
    </div>
    <h2><?php echo $text_newsletter; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><?php echo $entry_newsletter; ?></td>
          <td><input type="radio" name="newsletter" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" checked="checked" />
            <?php echo $text_no; ?></td>
        </tr>
      </table>
    </div>
    <?php if ($text_agree) { ?>
    <div class="buttons">
      <div class="right"><?php echo $text_agree; ?>
        <input type="checkbox" name="agree" value="1" />
        <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="btn" />
      </div>
    </div>
    <?php } else { ?>
    <div class="buttons">
      <div class="right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="btn" />
      </div>
    </div>
    <?php } ?>
  </form>
  <?php echo $content_bottom; ?></div>
<div style="display: none;">
  <form enctype="multipart/form-data">
    <input type="file" name="file" id="file" />
  </form>
</div>
<script type="text/javascript"><!--
$('#button-register').on('click', function() {
	$.ajax({
		url: 'index.php?route=account/register/save',
		type: 'post',
		data: $('form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#button-register').after(' <i class="icon-spinner icon-spin"></i>');
			$('#button-register').prop('disabled', true);
		},	
		complete: function() {
			$('#button-register').after(' <i class="icon-spinner icon-spin"></i>');
			$('#button-register').prop('disabled', false); 
		},			
		success: function(json) {
			$('.warning, .error').remove();
						
			if (json['redirect']) {
				location = json['redirect'];				
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#notification').html('<div class="alert alert-error" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['error']['firstname']) {
					$('input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				
				if (json['error']['lastname']) {
					$('input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}	
				
				if (json['error']['email']) {
					$('input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
				}
				
				if (json['error']['telephone']) {
					$('input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}	
																		
				if (json['error']['address_1']) {
					$('input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}	
				
				if (json['error']['city']) {
					$('input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
				}	
				
				if (json['error']['postcode']) {
					$('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}	
				
				if (json['error']['country']) {
					$('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
				}	
				
				if (json['error']['zone']) {
					$('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
				
				if (json['error']['password']) {
					$('input[name=\'password\']').after('<span class="error">' + json['error']['password'] + '</span>');
				}	
				
				if (json['error']['confirm']) {
					$('input[name=\'confirm\']').after('<span class="error">' + json['error']['confirm'] + '</span>');
				}																																	
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

$('select[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/custom_field&customer_group_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'customer_group_id\']').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		},
		complete: function() {
			$('.loading').remove();
		},			
		success: function(json) {
			$('.custom-field').remove();
			
			for (i = 0; i < json.length; i++) {
				custom_field = json[i];
				
				html  = '<tr class="custom-field">';
				html += '  <td>';
				
				if (custom_field['required'] == 1) {
					html += '<span id="customer-field-required' + custom_field['custom_field_id'] + '" class="required">*</span> ';
				}
				
				html += custom_field['name'] += '</td>';
				
				// Select
				if (custom_field['type'] == 'select') {
					html += '<td><select name="custom_field[' + custom_field['custom_field_id'] + ']">';
					html += '<option value=""><?php echo $text_select; ?></option>';
					
					for (j = 0; j < custom_field['custom_field_value'].length; j++) {
						html += '<option value="' + custom_field['custom_field_value'][j]['custom_field_value_id'] + '">' + custom_field['custom_field_value'][j]['name'] + '</option>';
					}

					html += '</select></td>';
				}
								
				// Radio
				if (custom_field['type'] == 'radio') {
					html += '<td>';
					
					for (j = 0; j < custom_field['custom_field_value'].length; j++) {
						html += '<input type="radio" name="custom_field[' + custom_field['custom_field_value'][j]['custom_field_value_id'] + ']" id="custom-field-value' + custom_field['custom_field_value'][j]['custom_field_value_id'] + '"><label for="custom-field-value' + custom_field['custom_field_value'][j]['custom_field_value_id'] + '">' + custom_field['custom_field_value'][j]['name'] + '</label><br />';
					}

					html += '</td>';
				}
				
				// Checkbox
				if (custom_field['type'] == 'checkbox') {
					html += '<td>';
					
					for (j = 0; j < custom_field['custom_field_value'].length; j++) {
						html += '<input type="checkbox" name="custom_field[' + custom_field['custom_field_value'][j]['custom_field_value_id'] + '][]" id="custom-field-value' + custom_field['custom_field_value'][j]['custom_field_value_id'] + '"> <label for="custom-field-value' + custom_field['custom_field_value'][j]['custom_field_value_id'] + '">' + custom_field['custom_field_value'][j]['name'] + '</label><br />';
					}

					html += '</td>';
				}

				// Text
				if (custom_field['type'] == 'text') {
					html += '<td><input type="text" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" /></td>'
				}
				
				// Textarea
				if (custom_field['type'] == 'textarea') {
					html += '<td><textarea name="custom_field[' + custom_field['custom_field_id'] + ']" cols="40" rows="5">' + custom_field['value'] + '</textarea></td>'
				}
				
				// File
				if (custom_field['type'] == 'file') {
					html += '<td><input type="button" value="<?php echo $button_upload; ?>" id="button-custom-field' + custom_field['custom_field_id'] + '" class="btn" onclick="upload(\'' + custom_field['custom_field_id'] + '\');" /><input type="hidden" name="custom_field[' + custom_field['custom_field_id'] + ']" value="" /></td>';
				}
				
				// Date
				if (custom_field['type'] == 'date') {
					html += '<td><input type="date" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" class="input-medium" /></td>';
				}			
				
				// Datetime
				if (custom_field['type'] == 'datetime') {
					html += '<td><input type="datetime-local" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" /></td>';
				}		
							
				// Time
				if (custom_field['type'] == 'time') {
					html += '<td><input type="time" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" class="input-mini" /></td>';
				}	
								
				html += '<tr>';
				
				if (custom_field['position'] == 'begining') {
					$('input[name=\'firstname\']').parent().parent().before(html);
				} else if (custom_field['position'] == 'customer_group_id' || custom_field['position'] == 'country_id' || custom_field['position'] == 'zone_id') {
					$('select[name=\'' + custom_field['position'] + '\']').parent().parent().after(html)
				} else {
					$('input[name=\'' + custom_field['position'] + '\']').parent().parent().after(html);
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'customer_group_id\']').trigger('change');

function upload(custom_field_id) {
	$('#file').off();
	
	$('#file').on('change', function() {
		$.ajax({
			url: 'index.php?route=account/register/upload',
			type: 'post',		
			dataType: 'json',
			data: new FormData($(this).parent()[0]),
			beforeSend: function() {
				$('#button-custom-field' + custom_field_id).after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
				$('#button-custom-field' + custom_field_id).prop('disabled', true);
				$('#custom-field' + custom_field_id + ' + .error').remove();
			},	
			complete: function() {
				$('.loading').remove();
				$('#button-custom-field' + custom_field_id).prop('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					$('#custom-field' + custom_field_id).after('<span class="error">' + json['error'] + '</span>');
				}
							
				if (json['success']) {
					alert(json['success']);
					
					$('input[name=\'custom_field[' + custom_field_id + ']\']').attr('value', json['file']);
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});		
	
	$('input[name=\'file\']').click();
}
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		},
		complete: function() {
			$('.loading').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		width: 640,
		height: 480
	});
});
//--></script> 
<?php echo $footer; ?>