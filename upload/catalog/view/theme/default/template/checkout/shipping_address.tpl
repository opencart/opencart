<div class="row-fluid">
  <div class="span12">
    <?php if ($addresses) { ?>
    <p>
      <label class="radio">
        <input type="radio" name="shipping_address" value="existing" checked="checked" />
        <?php echo $text_address_existing; ?></label>
    </p>
    <div id="shipping-existing">
      <select name="address_id" class="input-xxlarge">
        <?php foreach ($addresses as $address) { ?>
        <?php if ($address['address_id'] == $address_id) { ?>
        <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <p>
      <label class="radio">
        <input type="radio" name="shipping_address" value="new" />
        <?php echo $text_address_new; ?></label>
    </p>
    <?php } ?>
    <div id="shipping-new" class="form-horizontal" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
      <div class="control-group required">
        <label class="control-label" for="input-shipping-firstname"><?php echo $entry_firstname; ?></label>
        <div class="controls">
          <input type="text" name="firstname" value="" placeholder="<?php echo $entry_firstname; ?>" id="input-shipping-firstname" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-shipping-lastname"><?php echo $entry_lastname; ?></label>
        <div class="controls">
          <input type="text" name="lastname" value="" placeholder="<?php echo $entry_lastname; ?>" id="input-shipping-lastname" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-shipping-company"><?php echo $entry_company; ?></label>
        <div class="controls">
          <input type="text" name="company" value="" placeholder="<?php echo $entry_company; ?>" id="input-shipping-company" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-shipping-address-1"><?php echo $entry_address_1; ?></label>
        <div class="controls">
          <input type="text" name="address_1" value="" placeholder="<?php echo $entry_address_1; ?>" id="input-shipping-address-1" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-shipping-address-2"><?php echo $entry_address_2; ?></label>
        <div class="controls">
          <input type="text" name="address_2" value="" placeholder="<?php echo $entry_address_2; ?>" id="input-shipping-address-2" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-shipping-city"><?php echo $entry_city; ?></label>
        <div class="controls">
          <input type="text" name="city" value="" placeholder="<?php echo $entry_city; ?>" id="input-shipping-city" />
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
  </div>
  <div class="buttons">
    <div class="pull-right">
      <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-address" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'shipping_address\']').on('change', function() {
	if (this.value == 'new') {
		$('#shipping-existing').hide();
		$('#shipping-new').show();
	} else {
		$('#shipping-existing').show();
		$('#shipping-new').hide();
	}
});
//--></script> 
<script type="text/javascript"><!--
$('#input-shipping-country').on('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#shipping-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#input-shipping-postcode').parent().parent().addClass('required');
			} else {
				$('#input-shipping-postcode').parent().parent().removeClass('required');
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
			
			$('#input-shipping-zone').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#input-shipping-country').trigger('change');
//--></script>