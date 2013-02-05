<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <p><?php echo $text_account_already; ?></p>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_your_details; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
        </tr>
      </table>
    </div>
    <h2><?php echo $text_your_address; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><?php echo $entry_company; ?></td>
          <td><input type="text" name="company" value="<?php echo $company; ?>" /></td>
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
          <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" />
            <?php if ($error_address_1) { ?>
            <span class="error"> <?php echo $error_address_1; ?> </span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"> <?php echo $error_city; ?> </span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"> <?php echo $error_postcode; ?> </span>
            <?php } ?></td>
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
            </select>
            <?php if ($error_country) { ?>
            <span class="error"><?php echo $error_country; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
          <td><select name="zone_id">
            </select>
            <?php if ($error_zone) { ?>
            <span class="error"><?php echo $error_zone; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <h2><?php echo $text_your_password; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="<?php echo $password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <h2><?php echo $text_newsletter; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><?php echo $entry_newsletter; ?></td>
          <td><?php if ($newsletter) { ?>
            <input type="radio" name="newsletter" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="newsletter" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <?php if ($text_agree) { ?>
    <div class="buttons">
      <div class="right"><?php echo $text_agree; ?>
        <?php if ($agree) { ?>
        <input type="checkbox" name="agree" value="1" checked="checked" />
        <?php } else { ?>
        <input type="checkbox" name="agree" value="1" />
        <?php } ?>
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
    <?php } else { ?>
    <div class="buttons">
      <div class="right">
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
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
$('select[name=\'customer_group_id\']').live('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/custom_field&customer_group_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'customer_group_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			$('.custom-field').remove();
			
			for (i = 0; i < json.length; i++) {
				custom_field = json[i];
				
				html  = '<tr id="custom-field' + custom_field['custom_field_id'] + '" class="custom-field">';
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
					html += '<td><input type="button" value="<?php echo $button_upload; ?>" id="button-custom-field' + custom_field['custom_field_id'] + '" class="button" onclick="upload(\'' + custom_field['custom_field_id'] + '\');" /><input type="hidden" name="custom_field[' + custom_field['custom_field_id'] + ']" value="" /></td>';
				}
				
				// Date
				if (custom_field['type'] == 'date') {
					html += '<td><input type="text" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" class="date" /></td>';
				}			
				
				// Datetime
				if (custom_field['type'] == 'datetime') {
					html += '<td><input type="text" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" class="datetime" /></td>';
				}		
							
				// Time
				if (custom_field['type'] == 'time') {
					html += '<td><input type="text" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" class="time" /></td>';
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

			
			if ($.browser.msie && $.browser.version == 6) {
				$('.date, .datetime, .time').bgIframe();
			}
			
			$('.date').datepicker({dateFormat: 'yy-mm-dd'});
			$('.datetime').datetimepicker({
				dateFormat: 'yy-mm-dd',
				timeFormat: 'h:m'
			});
			
			$('.time').timepicker({timeFormat: 'h:m'});		
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'customer_group_id\']').trigger('change');

function upload(product_option_id) {
	$('#file').off();
	
	$('#file').on('change', function() {
		$.ajax({
			url: 'index.php?route=sale/order/upload&token=<?php echo $token; ?>',
			type: 'post',		
			dataType: 'json',
			data: new FormData($(this).parent()[0]),
			beforeSend: function() {
				$('#button-option' + product_option_id).after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
				$('#button-option' + product_option_id).attr('disabled', true);
				$('#option' + product_option_id + ' + .error').remove();
			},	
			complete: function() {
				$('.loading').remove();
				$('#button-option' + product_option_id).attr('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					$('#option' + product_option_id).after('<span class="error">' + json['error'] + '</span>');
				}
							
				if (json['success']) {
					alert(json['success']);
					
					$('input[name=\'option[' + product_option_id + ']\']').attr('value', json['file']);
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
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
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
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		width: 640,
		height: 480
	});
});
//--></script> 
<?php echo $footer; ?>