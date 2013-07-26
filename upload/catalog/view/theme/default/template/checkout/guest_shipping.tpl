<div class="row-fluid">
  <div class="span12">
    <div class="form-horizontal">
      <div class="control-group required">
        <label class="control-label" for="input-shipping-firstname"><?php echo $entry_firstname; ?></label>
        <div class="controls">
          <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-shipping-firstname" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-shipping-lastname"><?php echo $entry_lastname; ?></label>
        <div class="controls">
          <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-shipping-lastname" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-shipping-company"><?php echo $entry_company; ?></label>
        <div class="controls">
          <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-shipping-company" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-shipping-address-1"><?php echo $entry_address_1; ?></label>
        <div class="controls">
          <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-shipping-address-1" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-shipping-address-2"><?php echo $entry_address_2; ?></label>
        <div class="controls">
          <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-shipping-address-2" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-shipping-city"><?php echo $entry_city; ?></label>
        <div class="controls">
          <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-shipping-city" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-shipping-postcode"><?php echo $entry_postcode; ?></label>
        <div class="controls">
          <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-shipping-postcode" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-shipping-country"><?php echo $entry_country; ?></label>
        <div class="controls">
          <select name="country_id" id="input-shipping-country">
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
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-shipping-zone"><?php echo $entry_zone; ?></label>
        <div class="controls">
          <select name="zone_id" id="input-shipping-zone">
          </select>
        </div>
      </div>
    </div>
    <div class="buttons">
      <div class="pull-right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-guest-shipping" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#input-shipping-country').on('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#input-shipping-country').after(' <i class="icon-spinner icon-spin"></i>');
		},
		complete: function() {
			$('.icon-spinner').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#input-shipping-postcode').parent().parent().addClass('required');
			} else {
				$('#input-shipping-postcode').parent().parent().removeClass('required');
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
			
			$('#input-shipping-zone').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#input-shipping-country').trigger('change');
//--></script>