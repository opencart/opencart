<div class="left">
<div class="row">
  <div class="span6">
  <fieldset>
  <legend><?php echo $text_your_details; ?></legend>
        <div class="control-group required">
          <label class="control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
          <div class="controls">
            <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" />
            <?php if ($error_firstname) { ?>
            <div class="error"><?php echo $error_firstname; ?></div>
            <?php } ?>
          </div>
        </div>
  
  
  </fieldset>
 </div>
  </div>
  
  
  
  <span class="text-error">*</span> <?php echo $entry_firstname; ?><br />
  <input type="text" name="firstname" value="" class="large-field" />
  <br />
  <br />
  <span class="text-error">*</span> <?php echo $entry_lastname; ?><br />
  <input type="text" name="lastname" value="" class="large-field" />
  <br />
  <br />
  <span class="text-error">*</span> <?php echo $entry_email; ?><br />
  <input type="text" name="email" value="" class="large-field" />
  <br />
  <br />
  <span class="text-error">*</span> <?php echo $entry_telephone; ?><br />
  <input type="text" name="telephone" value="" class="large-field" />
  <br />
  <br />
  <?php echo $entry_fax; ?><br />
  <input type="text" name="fax" value="" class="large-field" />
  <br />
  <br />
  <h2><?php echo $text_your_password; ?></h2>
  <span class="text-error">*</span> <?php echo $entry_password; ?><br />
  <input type="password" name="password" value="" class="large-field" />
  <br />
  <br />
  <span class="text-error">*</span> <?php echo $entry_confirm; ?> <br />
  <input type="password" name="confirm" value="" class="large-field" />
  <br />
  <br />
  <br />
</div>
<div class="right">
  <h2><?php echo $text_your_address; ?></h2>
  <?php echo $entry_company; ?><br />
  <input type="text" name="company" value="" class="large-field" />
  <br />
  <br />
  <div style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;">
  <?php echo $entry_customer_group; ?><br />
  <?php foreach ($customer_groups as $customer_group) { ?>
  <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
  <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
  <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
  <br />
  <?php } else { ?>
  <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
  <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
  <br />
  <?php } ?>
  <?php } ?>
  <br />
</div>
<div id="company-id-display"><span id="company-id-required" class="text-error">*</span> <?php echo $entry_company_id; ?><br />
  <input type="text" name="company_id" value="" class="large-field" />
  <br />
  <br />
</div>
<div id="tax-id-display"><span id="tax-id-required" class="text-error">*</span> <?php echo $entry_tax_id; ?><br />
  <input type="text" name="tax_id" value="" class="large-field" />
  <br />
  <br />
</div>
<span class="text-error">*</span> <?php echo $entry_address_1; ?><br />
<input type="text" name="address_1" value="" class="large-field" />
<br />
<br />
<?php echo $entry_address_2; ?><br />
<input type="text" name="address_2" value="" class="large-field" />
<br />
<br />
<span class="text-error">*</span> <?php echo $entry_city; ?><br />
<input type="text" name="city" value="" class="large-field" />
<br />
<br />
<span id="payment-postcode-required" class="text-error">*</span> <?php echo $entry_postcode; ?><br />
<input type="text" name="postcode" value="<?php echo $postcode; ?>" class="large-field" />
<br />
<br />
<span class="text-error">*</span> <?php echo $entry_country; ?><br />
<select name="country_id" class="large-field">
  <option value=""><?php echo $text_select; ?></option>
  <?php foreach ($countries as $country) { ?>
  <?php if ($country['country_id'] == $country_id) { ?>
  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
  <?php } else { ?>
  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
  <?php } ?>
  <?php } ?>
</select>
<br />
<br />
<span class="text-error">*</span> <?php echo $entry_zone; ?><br />
<select name="zone_id" class="large-field">
</select>
<br />
<br />
</div>

<label class="checkbox" for="newsletter">
    <input type="checkbox" name="newsletter" value="1" id="newsletter" />
    <?php echo $entry_newsletter; ?>
</label>

<?php if ($shipping_required) { ?>
<label class="checkbox" for="shipping">
    <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" />
    <?php echo $entry_shipping; ?>
</label>
<?php } ?>

<?php if ($text_agree) { ?>
<div class="buttons">
    <div class="left">
        <label class="checkbox">
            <input type="checkbox" name="agree" value="1" />
            <?php echo $text_agree; ?>
        </label>
    </div>
    <div class="right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="btn" />
    </div>
    <div class="clearfix"></div>
</div>
<?php } else { ?>
<div class="buttons">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="btn" />
</div>
<?php } ?>


<script type="text/javascript"><!--
/*
$('#payment-address input[name=\'customer_group_id\']:checked').on('change', function() {
    var customer_group = [];
    
<?php foreach ($customer_groups as $customer_group) { ?>
    customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
    customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
    customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
    customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
    customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
<?php } ?>  

    if (customer_group[this.value]) {
        if (customer_group[this.value]['company_id_display'] == '1') {
            $('#company-id-display').show();
        } else {
            $('#company-id-display').hide();
        }
        
        if (customer_group[this.value]['company_id_required'] == '1') {
            $('#company-id-required').show();
        } else {
            $('#company-id-required').hide();
        }
        
        if (customer_group[this.value]['tax_id_display'] == '1') {
            $('#tax-id-display').show();
        } else {
            $('#tax-id-display').hide();
        }
        
        if (customer_group[this.value]['tax_id_required'] == '1') {
            $('#tax-id-required').show();
        } else {
            $('#tax-id-required').hide();
        }   
    }
});

$('#payment-address input[name=\'customer_group_id\']:checked').trigger('change');
*/
//--></script> 
<script type="text/javascript"><!--
$('#payment-address select[name=\'country_id\']').on('change', function() {
    if (this.value == '') return;
    $.ajax({
        url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function() {
			$('#payment-address select[name=\'country_id\']').after(' <i class="icon-spinner icon-spin"></i>');
        },
        complete: function() {
			$('.icon-spinner').remove();
        },          
        success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#input-payment-postcode').parent().parent().addClass('required');
			} else {
				$('#input-payment-postcode').parent().parent().removeClass('required');
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
            
            $('#payment-address select[name=\'zone_id\']').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#payment-address select[name=\'country_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
    $('.colorbox').colorbox({
        maxWidth: 640,
        width: "85%",
        height: 480
    });
});
//--></script>