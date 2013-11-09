<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
  <?php if ($column_left && $column_right) { ?>
    <?php $cols = 6; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $cols = 9; ?>
    <?php } else { ?>
    <?php $cols = 12; ?>
    <?php } ?><div id="content" class="col-sm-<?php echo $cols; ?>"><?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?></h1>
    <p><?php echo $text_account_already; ?></p>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
      <fieldset>
        <legend><?php echo $text_your_details; ?></legend>
        <div class="form-group required" style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
          <label class="col-sm-2 control-label"><?php echo $entry_customer_group; ?></label>
          <div class="col-sm-10">
            <?php foreach ($customer_groups as $customer_group) { ?>
            <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
            <div class="radio">
              <label>
                <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                <?php echo $customer_group['name']; ?></label>
            </div>
            <?php } else { ?>
            <div class="radio">
              <label>
                <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" />
                <?php echo $customer_group['name']; ?></label>
            </div>
            <?php } ?>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
          <div class="col-sm-10">
            <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
            <?php if ($error_firstname) { ?>
            <div class="text-danger"><?php echo $error_firstname; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
          <div class="col-sm-10">
            <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
            <?php if ($error_lastname) { ?>
            <div class="text-danger"><?php echo $error_lastname; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
          <div class="col-sm-10">
            <input type="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
            <?php if ($error_email) { ?>
            <div class="text-danger"><?php echo $error_email; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
          <div class="col-sm-10">
            <input type="tel" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
            <?php if ($error_telephone) { ?>
            <div class="text-danger"><?php echo $error_telephone; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
          <div class="col-sm-10">
            <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
          </div>
        </div>
      </fieldset>
      <fieldset>
        <legend><?php echo $text_your_address; ?></legend>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-company"><?php echo $entry_company; ?></label>
          <div class="col-sm-10">
            <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-company" class="form-control" />
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-address-1"><?php echo $entry_address_1; ?></label>
          <div class="col-sm-10">
            <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" class="form-control" />
            <?php if ($error_address_1) { ?>
            <div class="text-danger"><?php echo $error_address_1; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-address-2" for="input-address-2">
          <?php echo $entry_address_2; ?>
          </label>
          <div class="col-sm-10">
            <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2" class="form-control" />
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-city"><?php echo $entry_city; ?></label>
          <div class="col-sm-10">
            <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
            <?php if ($error_city) { ?>
            <div class="text-danger"><?php echo $error_city; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
          <div class="col-sm-10">
            <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
            <?php if ($error_postcode) { ?>
            <div class="text-danger"><?php echo $error_postcode; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
          <div class="col-sm-10">
            <select name="country_id" id="input-country" class="form-control">
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
            <div class="text-danger"><?php echo $error_country; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
          <div class="col-sm-10">
            <select name="zone_id" id="input-zone" class="form-control">
            </select>
            <?php if ($error_zone) { ?>
            <div class="text-danger"><?php echo $error_zone; ?></div>
            <?php } ?>
          </div>
        </div>
      </fieldset>
      <fieldset>
        <legend><?php echo $text_your_password; ?></legend>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
          <div class="col-sm-10">
            <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
            <?php if ($error_password) { ?>
            <div class="text-danger"><?php echo $error_password; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
          <div class="col-sm-10">
            <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
            <?php if ($error_confirm) { ?>
            <div class="text-danger"><?php echo $error_confirm; ?></div>
            <?php } ?>
          </div>
        </div>
      </fieldset>
      <fieldset>
        <legend><?php echo $text_newsletter; ?></legend>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_newsletter; ?></label>
          <div class="col-sm-10">
            <?php if ($newsletter) { ?>
            <label class="radio-inline">
              <input type="radio" name="newsletter" value="1" checked="checked" />
              <?php echo $text_yes; ?></label>
            <label class="radio-inline">
              <input type="radio" name="newsletter" value="0" />
              <?php echo $text_no; ?></label>
            <?php } else { ?>
            <label class="radio-inline">
              <input type="radio" name="newsletter" value="1" />
              <?php echo $text_yes; ?></label>
            <label class="radio-inline">
              <input type="radio" name="newsletter" value="0" checked="checked" />
              <?php echo $text_no; ?></label>
            <?php } ?>
          </div>
        </div>
      </fieldset>
      <?php if ($text_agree) { ?>
      <div class="buttons">
        <div class="pull-right"> <?php echo $text_agree; ?>
          <?php if ($agree) { ?>
          <input type="checkbox" name="agree" value="1" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="agree" value="1" />
          <?php } ?>
          &nbsp;
          <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
        </div>
      </div>
      <?php } else { ?>
      <div class="buttons">
        <div class="pull-right">
          <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
        </div>
      </div>
      <?php } ?>
    </form>
    <?php echo $content_bottom; ?></div>
</div>
<div style="display: none;">
  <?php foreach ($custom_fields as $custom_field) { ?>
  <div id="custom-field<?php echo $custom_field['custom_field_id']; ?>">
    <?php if ($custom_field['type'] != 'checkbox') { ?>
    <div class="custom-field-value"><?php echo $custom_field['value']; ?></div>
    <?php } else { ?>
    <?php foreach ($custom_field['value'] as $custom_field_value_id) { ?>
    <div class="custom-field-value"><?php echo $custom_field_value_id; ?></div>
    <?php } ?>
    <?php } ?>
    <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
    <div class="custom-field-error"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
    <?php } ?>
  </div>
  <?php } ?>
</div>
<script type="text/javascript"><!--
$('input[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/custom_field&customer_group_id=' + this.value,
		dataType: 'json',	
		success: function(json) {
			$('.custom-field').hide();
			
			for (i = 0; i < json.length; i++) {
				custom_field = json[i];
				
				if (!$('input[name^=\'custom_field[' + custom_field['custom_field_id'] + ']\'], textarea[name=\'custom_field[' + custom_field['custom_field_id'] + ']\']').length) {
					html = '';
					
					if (custom_field['type'] == 'select') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="col-sm-2 control-label" for="input-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <div class="col-sm-10">';
						html += '    <select name="custom_field[' + custom_field['custom_field_id'] + ']" id="input-custom-field' + custom_field['custom_field_id'] + '" class="form-control">';
						html += '      <option value=""><?php echo $text_select; ?></option>';
					
						for (j = 0; j < custom_field['custom_field_value'].length; j++) {
							custom_field_value = custom_field['custom_field_value'][j];
							
							html += '<option value="' + custom_field_value['custom_field_value_id'] + '"';
							
							if (custom_field_value['custom_field_value_id'] == $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-value').html()) {
								html += ' selected="selected"';
							}
			
							html += '>' + custom_field_value['name'] + '</option>';
						}
							
						html += '    </select>';
						
						if ($('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').length) {
							html += '<div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>';
						}
						
						html += '  </div>';
						html += '</div>';					
					}
					
					if (custom_field['type'] == 'radio') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="col-sm-2 control-label">' + custom_field['name'] + '</label>';
						html += '  <div class="col-sm-10">';
						html += '    <div id="input-custom-field' + custom_field['custom_field_id'] + '">';
						
						for (j = 0; j < custom_field['custom_field_value'].length; j++) {
							custom_field_value = custom_field['custom_field_value'][j];
							
							html += '<div class="radio">';
							html += '  <label><input type="radio" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field_value['custom_field_value_id'] + '"';
							
							if (custom_field_value['custom_field_value_id'] == $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-value').html()) {
								html += ' checked="checked"';
							}							
							
							html += ' /> ' + custom_field_value['name'] + '</label>';
							html += '</div>';
						}
						
						html += '    </div>';
						
						if ($('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').length) {
							html += '<div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>';
						}
						
						html += '  </div>';
						html += '</div>';				
					}
					
					if (custom_field['type'] == 'checkbox') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="col-sm-2 control-label">' + custom_field['name'] + '</label>';
						html += '  <div class="col-sm-10">';
						html += '    <div id="input-custom-field' + custom_field['custom_field_id'] + '">';
						
						for (j = 0; j < custom_field['custom_field_value'].length; j++) {
							custom_field_value = custom_field['custom_field_value'][j];
							
							html += '<div class="checkbox">';
							html += '  <label><input type="checkbox" name="custom_field[' + custom_field['custom_field_id'] + '][]" value="' + custom_field_value['custom_field_value_id'] + '"';
							
							var element = $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-value');
							
							for (k = 0; k < element.length; k++) {
								if (custom_field_value['custom_field_value_id'] == $(element[k]).text()) {
									html += ' checked="checked"';
								
									break;
								}					
							}

							html += ' /> ' + custom_field_value['name'] + '</label>';
							html += '</div>';
						}
						
						if ($('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').length) {
							html += '<div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>';
						}						
						
						html += '    </div>';					
						html += '  </div>';
						html += '</div>';				
					}
					
					//  Set the default value
					var element = $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-value');
					
					if (element.length) {
						value = element.html();
					} else {
						value = custom_field['value'];
					}
					
					if (custom_field['type'] == 'text') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="col-sm-2 control-label" for="input-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <div class="col-sm-10"><input type="text" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + value + '" placeholder="' + custom_field['name'] + '" id="input-custom-field' + custom_field['custom_field_id'] + '" class="form-control" />';
						
						if ($('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').length) {
							html += '<div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>';
						}
						
						html += '  </div>';
						html += '</div>';					
					}
					
					if (custom_field['type'] == 'textarea') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="col-sm-2 control-label" for="input-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <div class="col-sm-10"><textarea name="custom_field[' + custom_field['custom_field_id'] + ']" rows="5" placeholder="' + custom_field['name'] + '" id="input-custom-field' + custom_field['custom_field_id'] + '" class="form-control">' + value + '</textarea>';
						
						if ($('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').length) {
							html += '<div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>';
						}
												
						html += '  </div>';
						html += '</div>';
					}
					
					if (custom_field['type'] == 'file') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="col-sm-2 control-label">' + custom_field['name'] + '</label>';
						html += '  <div class="col-sm-10">';
						html += '    <button type="button" id="button-custom-field' + custom_field['custom_field_id'] + '" class="btn btn-default" onclick=""><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>';
						html += '    <input type="hidden" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + value + '" id="input-custom-field' + custom_field['custom_field_id'] + '" />';
						
						if ($('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').length) {
							html += '<div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>';
						}						
						
						html += '  </div>';
						html += '</div>';
					}
					
					if (custom_field['type'] == 'date') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="col-sm-2 control-label" for="input-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <div class="col-sm-3"><input type="date" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + value + '" id="input-custom-field' + custom_field['custom_field_id'] + '" class="form-control" />';
						
						if ($('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').length) {
							html += '<div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>';
						}
												
						html += '  </div>';
						html += '</div>';
					}
					
					if (custom_field['type'] == 'datetime') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="col-sm-2 control-label" for="input-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <div class="col-sm-10"><input type="datetime-local" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + value + '" id="input-custom-field' + custom_field['custom_field_id'] + '" class="form-control" />';
						
						if ($('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').length) {
							html += '<div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>';
						}
												
						html += '  </div>';
						html += '</div>';					
					}
					
					if (custom_field['type'] == 'time') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="col-sm-2 control-label" for="input-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <div class="col-sm-10"><input type="time" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + value + '" id="input-custom-field' + custom_field['custom_field_id'] + '" class="form-control" />';
						
						if ($('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').length) {
							html += '<div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>';
						}
												
						html += '  </div>';
						html += '</div>';					
					}
					
					$('.form-group:eq(' + custom_field['sort_order'] + ')').after(html);
				} else {
					$('#input-custom-field' + custom_field['custom_field_id']).parent().parent().show();
				}
				
				if (custom_field['required']) {
					$('#input-custom-field' + custom_field['custom_field_id']).parent().parent().addClass('required');
				} else {
					$('#input-custom-field' + custom_field['custom_field_id']).parent().parent().removeClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('input[name=\'customer_group_id\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-spinner fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spinner').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#input-postcode').parent().parent().addClass('required');
			} else {
				$('#input-postcode').parent().parent().removeClass('required');
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone']) {
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
<?php echo $footer; ?>