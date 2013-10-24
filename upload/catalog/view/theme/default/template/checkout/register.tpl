<div class="row">
  <div class="col-sm-6">
    <fieldset>
      <legend><?php echo $text_your_details; ?></legend>
      <div class="form-group" style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
        <label class="control-label"><?php echo $entry_customer_group; ?></label>
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
      <div class="form-group required">
        <label class="control-label" for="input-payment-firstname"><?php echo $entry_firstname; ?></label>
        <input type="text" name="firstname" value="" placeholder="<?php echo $entry_firstname; ?>" id="input-payment-firstname" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-lastname"><?php echo $entry_lastname; ?></label>
        <input type="text" name="lastname" value="" placeholder="<?php echo $entry_lastname; ?>" id="input-payment-lastname" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-email"><?php echo $entry_email; ?></label>
        <input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" id="input-payment-email" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-telephone"><?php echo $entry_telephone; ?></label>
        <input type="text" name="telephone" value="" placeholder="<?php echo $entry_telephone; ?>" id="input-payment-telephone" class="form-control" />
      </div>
      <div class="form-group">
        <label class="control-label" for="input-payment-fax"><?php echo $entry_fax; ?></label>
        <input type="text" name="fax" value="" placeholder="<?php echo $entry_fax; ?>" id="input-payment-fax" class="form-control" />
      </div>
    </fieldset>
    <fieldset>
      <legend><?php echo $text_your_password; ?></legend>
      <div class="form-group required">
        <label class="control-label" for="input-payment-password"><?php echo $entry_password; ?></label>
        <input type="password" name="password" value="" placeholder="<?php echo $entry_password; ?>" id="input-payment-password" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-confirm"><?php echo $entry_confirm; ?></label>
        <input type="password" name="confirm" value="" placeholder="<?php echo $entry_confirm; ?>" id="input-payment-confirm" class="form-control" />
      </div>
    </fieldset>
  </div>
  <div class="col-sm-6">
    <fieldset>
      <legend><?php echo $text_your_address; ?></legend>
      <div class="form-group">
        <label class="control-label" for="input-payment-company"><?php echo $entry_company; ?></label>
        <input type="text" name="company" value="" placeholder="<?php echo $entry_company; ?>" id="input-payment-company" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-address-1"><?php echo $entry_address_1; ?></label>
        <input type="text" name="address_1" value="" placeholder="<?php echo $entry_address_1; ?>" id="input-payment-address-1" class="form-control" />
      </div>
      <div class="form-group">
        <label class="control-label" for="input-payment-address-2"><?php echo $entry_address_2; ?></label>
        <input type="text" name="address_2" value="" placeholder="<?php echo $entry_address_2; ?>" id="input-payment-address-2" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-city"><?php echo $entry_city; ?></label>
        <input type="text" name="city" value="" placeholder="<?php echo $entry_city; ?>" id="input-payment-city" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-postcode"><?php echo $entry_postcode; ?></label>
        <input type="text" name="postcode" value="" placeholder="<?php echo $entry_postcode; ?>" id="input-payment-postcode" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-country"><?php echo $entry_country; ?></label>
        <select name="country_id" id="input-payment-country" class="form-control">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($countries as $country) { ?>
          <?php if ($country['country_id'] == $country_id) { ?>
          <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
          <?php } else { ?>
          <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-zone"><?php echo $entry_zone;; ?></label>
        <select name="zone_id" id="input-payment-zone" class="form-control">
        </select>
      </div>
    </fieldset>
  </div>
</div>
<div class="checkbox">
  <label for="newsletter">
    <input type="checkbox" name="newsletter" value="1" id="newsletter" />
    <?php echo $entry_newsletter; ?></label>
</div>
<?php if ($shipping_required) { ?>
<div class="checkbox">
  <label>
    <input type="checkbox" name="shipping_address" value="1" checked="checked" />
    <?php echo $entry_shipping; ?></label>
</div>
<?php } ?>
<?php if ($text_agree) { ?>
<div class="buttons clearfix">
  <div class="pull-right"><?php echo $text_agree; ?> &nbsp;
    <input type="checkbox" name="agree" value="1" />
    <input type="button" value="<?php echo $button_continue; ?>" id="button-register" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } else { ?>
<div class="buttons clearfix">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-register" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } ?>
<script type="text/javascript"><!--
$('#collapse-payment-address input[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=checkout/register/custom_field&customer_group_id=' + this.value,
		dataType: 'json',	
		success: function(json) {
			$('#collapse-payment-address .custom-field').hide();
			
			for (i = 0; i < json.length; i++) {
				custom_field = json[i];
				
				if (!$('#collapse-payment-address input[name^=\'custom_field[' + custom_field['custom_field_id'] + ']\'], #collapse-payment-address textarea[name=\'custom_field[' + custom_field['custom_field_id'] + ']\']').length) {
					html = '';
					
					if (custom_field['type'] == 'select') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="control-label" for="input-payment-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <select name="custom_field[' + custom_field['custom_field_id'] + ']" id="input-payment-custom-field' + custom_field['custom_field_id'] + '" class="form-control">';
						html += '    <option value=""><?php echo $text_select; ?></option>';
					
						for (j = 0; j < custom_field['custom_field_value'].length; j++) {
							html += '<option value="' + custom_field['custom_field_value'][j]['custom_field_value_id'] + '">' + custom_field['custom_field_value'][j]['name'] + '</option>';
						}
							
						html += '  </select>';

						html += '</div>';					
					}
					
					if (custom_field['type'] == 'radio') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="control-label">' + custom_field['name'] + '</label>';
						html += '  <div id="input-payment-custom-field' + custom_field['custom_field_id'] + '">';
						
						for (j = 0; j < custom_field['custom_field_value'].length; j++) {
							custom_field_value = custom_field['custom_field_value'][j];
							
							html += '<div class="radio">';
							html += '  <label><input type="radio" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field_value['custom_field_value_id'] + '" /> ' + custom_field_value['name'] + '</label>';
							html += '</div>';
						}
						
						html += '  </div>';
						html += '</div>';				
					}
					
					if (custom_field['type'] == 'checkbox') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="control-label">' + custom_field['name'] + '</label>';
						html += '  <div id="input-payment-custom-field' + custom_field['custom_field_id'] + '">';
						
						for (j = 0; j < custom_field['custom_field_value'].length; j++) {
							custom_field_value = custom_field['custom_field_value'][j];
							
							html += '<div class="checkbox">';
							html += '  <label><input type="checkbox" name="custom_field[' + custom_field['custom_field_id'] + '][]" value="' + custom_field_value['custom_field_value_id'] + '" /> ' + custom_field_value['name'] + '</label>';
							html += '</div>';
						}
						
						html += '  </div>';					
						html += '</div>';				
					}
					
					if (custom_field['type'] == 'text') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="control-label" for="input-payment-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <input type="text" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" placeholder="' + custom_field['name'] + '" id="input-payment-custom-field' + custom_field['custom_field_id'] + '" class="form-control" />';
						html += '</div>';					
					}
					
					if (custom_field['type'] == 'textarea') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="control-label" for="input-payment-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <textarea name="custom_field[' + custom_field['custom_field_id'] + ']" rows="5" placeholder="' + custom_field['name'] + '" id="input-payment-custom-field' + custom_field['custom_field_id'] + '" class="form-control">' + custom_field['value'] + '</textarea>';
						html += '</div>';
					}
					
					if (custom_field['type'] == 'file') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="control-label">' + custom_field['name'] + '</label>';
						html += '  <button type="button" id="button-payment-custom-field' + custom_field['custom_field_id'] + '" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>';
						html += '  <input type="hidden" name="custom_field[' + custom_field['custom_field_id'] + ']" value="" id="input-payment-custom-field' + custom_field['custom_field_id'] + '" />';
						html += '</div>';
					}
					
					if (custom_field['type'] == 'date') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="control-label" for="input-payment-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <input type="date" name="custom_field[' + custom_field['custom_field_id'] + ']" value="" id="input-payment-custom-field' + custom_field['custom_field_id'] + '" class="form-control" />';
						html += '</div>';
					}
					
					if (custom_field['type'] == 'datetime') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="control-label" for="input-payment-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <input type="datetime-local" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" id="input-payment-custom-field' + custom_field['custom_field_id'] + '" class="form-control" />';
						html += '</div>';					
					}
					
					if (custom_field['type'] == 'time') {
						html += '<div class="form-group custom-field">';
						html += '  <label class="control-label" for="input-payment-custom-field' + custom_field['custom_field_id'] + '">' + custom_field['name'] + '</label>';
						html += '  <input type="time" name="custom_field[' + custom_field['custom_field_id'] + ']" value="' + custom_field['value'] + '" id="input-payment-custom-field' + custom_field['custom_field_id'] + '" class="form-control" />';
						html += '</div>';					
					}
					
					$('#collapse-payment-address .form-group:eq(' + custom_field['sort_order'] + ')').after(html);
				} else {
					$('#input-payment-custom-field' + custom_field['custom_field_id']).parent().show();
				}
				
				if (custom_field['required']) {
					$('#input-payment-custom-field' + custom_field['custom_field_id']).parent().addClass('required');
				} else {
					$('#input-payment-custom-field' + custom_field['custom_field_id']).parent().removeClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#collapse-payment-address input[name=\'customer_group_id\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('#input-payment-country').on('change', function() {
	$.ajax({
        url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function() {
			$('#input-payment-country').after(' <i class="fa fa-spinner fa-spin"></i>');
        },
        complete: function() {
			$('.fa-spinner').remove();
        },          
        success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#input-payment-postcode').parent().parent().addClass('required');
			} else {
				$('#input-payment-postcode').parent().parent().removeClass('required');
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
            
            $('#input-payment-zone').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-payment-country').trigger('change');
//--></script>