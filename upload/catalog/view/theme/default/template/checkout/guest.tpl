<div class="row-fluid">
  <div class="span6">
    <fieldset>
      <legend><?php echo $text_your_details; ?></legend>
      <div class="control-group required">
        <label class="control-label" for="input-payment-firstname"><?php echo $entry_firstname; ?></label>
        <div class="controls">
          <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-payment-firstname" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-payment-lastname"><?php echo $entry_lastname; ?></label>
        <div class="controls">
          <input type="text" name="firstname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-payment-lastname" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-payment-email"><?php echo $entry_email; ?></label>
        <div class="controls">
          <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-payment-email" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-payment-telephone"><?php echo $entry_telephone; ?></label>
        <div class="controls">
          <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-payment-telephone" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-payment-fax"><?php echo $entry_fax; ?></label>
        <div class="controls">
          <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-payment-fax" />
        </div>
      </div>
    </fieldset>
  </div>
  <div class="span6">
    <fieldset>
      <legend><?php echo $text_your_address; ?></legend>
      <div class="control-group">
        <label class="control-label" for="input-payment-company"><?php echo $entry_company; ?></label>
        <div class="controls">
          <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-payment-company" />
        </div>
      </div>
      <div class="control-group" style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
        <div class="control-label"><?php echo $entry_customer_group; ?></div>
        <div class="controls">
          <?php foreach ($customer_groups as $customer_group) { ?>
          <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
          <label class="radio">
            <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
            <?php echo $customer_group['name']; ?></label>
          <?php } else { ?>
          <label class="radio">
            <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" />
            <?php echo $customer_group['name']; ?></label>
          <?php } ?>
          <?php } ?>
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-payment-address-1"><?php echo $entry_address_1; ?></label>
        <div class="controls">
          <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-payment-address_1" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-payment-address-2"><?php echo $entry_address_2; ?></label>
        <div class="controls">
          <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-payment-address-2" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-payment-city"><?php echo $entry_city; ?></label>
        <div class="controls">
          <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-payment-city" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-payment-postcode"><?php echo $entry_postcode; ?></label>
        <div class="controls">
          <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-payment-postcode" />
        </div>
      </div>
      <div class="control-group required">
        <label class="control-label" for="input-payment-country"><?php echo $entry_country; ?></label>
        <div class="controls">
          <select name="country_id" id="input-payment-country">
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
        <label class="control-label" for="input-payment-zone;"><?php echo $entry_zone;; ?></label>
        <div class="controls">
          <select name="zone_id" id="input-payment-zone">
          </select>
        </div>
      </div>
    </fieldset>
  </div>
</div>
<?php if ($shipping_required) { ?>
<div class="row-fluid">
  <div class="span12">
    <label class="checkbox">
      <?php if ($shipping_address) { ?>
      <input type="checkbox" name="shipping_address" value="1" checked="checked" />
      <?php } else { ?>
      <input type="checkbox" name="shipping_address" value="1" />
      <?php } ?>
      <?php echo $entry_shipping; ?></label>
  </div>
</div>
<?php } ?>
<div class="buttons clearfix">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-guest" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#payment-address input[name=\'customer_group_id\']:checked').change(function() {
    var customer_group = [];
    
});

$('#payment-address input[name=\'customer_group_id\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('#input-payment-country').on('change', function() {
    $.ajax({
        url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function() {
            $('#input-payment-country').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
        },
        complete: function() {
            $('.wait').remove();
        },          
        success: function(json) {
            if (json['postcode_required'] == '1') {
                $('#payment-postcode-required').show();
            } else {
                $('#payment-postcode-required').hide();
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
            
            $('#input-payment-zone').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-payment-country').trigger('change');
//--></script>