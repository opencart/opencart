<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
          <?php if ($affiliate_id) { ?>
          <li><a href="#tab-transaction" data-toggle="tab"><?php echo $tab_transaction; ?></a></li>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="control-group">
              <label class="control-label" for="input-firstname"><span class="required">*</span> <?php echo $entry_firstname; ?></label>
              <div class="controls">
                <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" />
                <?php if ($error_firstname) { ?>
                <span class="error"><?php echo $error_firstname; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-lastname"><span class="required">*</span> <?php echo $entry_lastname; ?></label>
              <div class="controls">
                <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" />
                <?php if ($error_lastname) { ?>
                <span class="error"><?php echo $error_lastname; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-email"><span class="required">*</span> <?php echo $entry_email; ?></label>
              <div class="controls">
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" />
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php  } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-telephone"><span class="required">*</span> <?php echo $entry_telephone; ?></label>
              <div class="controls">
                <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" />
                <?php if ($error_telephone) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php  } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-fax"><?php echo $entry_fax; ?></label>
              <div class="controls">
                <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-company"><?php echo $entry_company; ?></label>
              <div class="controls">
                <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-company" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-address-1"><span class="required">*</span> <?php echo $entry_address_1; ?></label>
              <div class="controls">
                <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" />
                <?php if ($error_address_1) { ?>
                <span class="error"><?php echo $error_address_1; ?></span>
                <?php  } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-address-2"><?php echo $entry_address_2; ?></label>
              <div class="controls">
                <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-city"><span class="required">*</span> <?php echo $entry_city; ?></label>
              <div class="controls">
                <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" />
                <?php if ($error_city) { ?>
                <span class="error"><?php echo $error_city ?></span>
                <?php  } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-postcode"><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
              <div class="controls">
                <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" />
                <?php if ($error_postcode) { ?>
                <span class="error"><?php echo $error_postcode ?></span>
                <?php  } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-country"><span class="required">*</span> <?php echo $entry_country; ?></label>
              <div class="controls">
                <select name="country_id" id="input-country">
                  <option value="false"><?php echo $text_select; ?></option>
                  <?php foreach ($countries as $country) { ?>
                  <?php if ($country['country_id'] == $country_id) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected="selected"> <?php echo $country['name']; ?> </option>
                  <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <?php if ($error_country) { ?>
                <span class="error"><?php echo $error_country; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-zone"><span class="required">*</span> <?php echo $entry_zone; ?></label>
              <div class="controls">
                <select name="zone_id" id="input-zone">
                </select>
                <?php if ($error_zone) { ?>
                <span class="error"><?php echo $error_zone; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-code"><span class="required">*</span> <?php echo $entry_code; ?></label>
              <div class="controls">
                <input type="code" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" />

                
                <a data-toggle="tooltip" title="<?php echo $help_code; ?>"><i class="icon-info-sign"></i></a>
                
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
              <div class="controls">
                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password"  />
                <?php if ($error_password) { ?>
                <span class="error"><?php echo $error_password; ?></span>
                <?php  } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
              <div class="controls">
                <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" />
                <?php if ($error_confirm) { ?>
                <span class="error"><?php echo $error_confirm; ?></span>
                <?php  } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="controls">
                <select name="status" id="input-status">
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
          </div>
          <div class="tab-pane" id="tab-payment">
            <div class="control-group">
              <label class="control-label" for="input-commission"><?php echo $entry_commission; ?></label>
              <div class="controls">
                <input type="text" name="commission" value="<?php echo $commission; ?>" placeholder="<?php echo $entry_commission; ?>" id="input-commission" />
               
                
                <a data-toggle="tooltip" title="<?php echo $help_commission; ?>"><i class="icon-info-sign"></i></a>
                </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-tax"><?php echo $entry_tax; ?></label>
              <div class="controls">
                <input type="text" name="tax" value="<?php echo $tax; ?>" placeholder="<?php echo $entry_tax; ?>" id="input-tax" />
              </div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $entry_payment; ?></div>
              <div class="controls">
                <label class="radio">
                  <?php if ($payment == 'cheque') { ?>
                  <input type="radio" name="payment" value="cheque" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="cheque" />
                  <?php } ?>
                  <?php echo $text_cheque; ?> </label>
                <label class="radio">
                  <?php if ($payment == 'paypal') { ?>
                  <input type="radio" name="payment" value="paypal" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="paypal" />
                  <?php } ?>
                  <?php echo $text_paypal; ?> </label>
                <label class="radio">
                  <?php if ($payment == 'bank') { ?>
                  <input type="radio" name="payment" value="bank" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="bank" />
                  <?php } ?>
                  <?php echo $text_bank; ?></label>
              </div>
            </div>
            <div id="payment-cheque" class="payment">
              <div class="control-group">
                <label class="control-label" for="input-cheque"><?php echo $entry_cheque; ?></label>
                <div class="controls">
                  <input type="text" name="cheque" value="<?php echo $cheque; ?>" placeholder="<?php echo $entry_cheque; ?>" id="input-cheque" />
                </div>
              </div>
            </div>
            <div id="payment-paypal" class="payment">
              <div class="control-group">
                <label class="control-label" for="input-paypal"><?php echo $entry_paypal; ?></label>
                <div class="controls">
                  <input type="text" name="paypal" value="<?php echo $paypal; ?>" placeholder="<?php echo $entry_paypal; ?>" id="input-paypal" />
                </div>
              </div>
            </div>
            <div id="payment-bank" class="payment">
              <div class="control-group">
                <label class="control-label" for="input-bank-name"><?php echo $entry_bank_name; ?></label>
                <div class="controls">
                  <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" placeholder="<?php echo $entry_bank_name; ?>" id="input-bank-name" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-bank-branch-number"><?php echo $entry_bank_branch_number; ?></label>
                <div class="controls">
                  <input type="text" name="bank_branch_number" value="<?php echo $bank_branch_number; ?>" placeholder="<?php echo $entry_bank_branch_number; ?>" id="input-bank-branch-number" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-bank-swift-code"><?php echo $entry_bank_swift_code; ?></label>
                <div class="controls">
                  <input type="text" name="bank_swift_code" value="<?php echo $bank_swift_code; ?>" placeholder="<?php echo $entry_bank_swift_code; ?>" id="input-bank-swift-code" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-bank-account-name"><span class="required">*</span> <?php echo $entry_bank_account_name; ?></label>
                <div class="controls">
                  <input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>" placeholder="<?php echo $entry_bank_account_name; ?>" id="input-bank-account-name" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-bank-account-number"><span class="required">*</span> <?php echo $entry_bank_account_number; ?></label>
                <div class="controls">
                  <input type="text" name="bank_account_number" value="<?php echo $bank_account_number; ?>" placeholder="<?php echo $entry_bank_account_number; ?>" id="input-bank-account-number" />
                </div>
              </div>
            </div>
          </div>
          <?php if ($affiliate_id) { ?>
          <div class="tab-pane" id="tab-transaction">
            <div id="transaction"></div>
            
            <div class="control-group">
              <label class="control-label" for="input-description"><?php echo $entry_description; ?></label>
              <div class="controls">
                <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-description" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-amount"><?php echo $entry_amount; ?></label>
              <div class="controls">
                <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" id="input-amount" />
              </div>
            </div>
            <button id="button-transaction" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_transaction; ?></button>
          </div>
          <?php } ?>
        </div>
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/affiliate/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'payment_country_id\']').after(' <i class="icon-spinner icon-spin"></i>');
		},
		complete: function() {
			$('.icon-spinner').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json != '' && json['zone'] != '') {
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
$('input[name=\'payment\']').on('change', function() {
	$('.payment').hide();
	
	$('#payment-' + this.value).show();
});

$('input[name=\'payment\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('#transaction .pagination a').on('click', function() {
	$('#transaction').load(this.href);
	
	return false;
});			

$('#transaction').load('index.php?route=sale/affiliate/transaction&token=<?php echo $token; ?>&affiliate_id=<?php echo $affiliate_id; ?>');

$('#button-transaction').on('click', function() {
	$.ajax({
		url: 'index.php?route=sale/affiliate/transaction&token=<?php echo $token; ?>&affiliate_id=<?php echo $affiliate_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()),
		beforeSend: function() {
			$('.alert').remove();
			
			$('#button-transaction i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-transaction').prop('disabled', true);
		},
		complete: function() {
			$('#button-transaction i').replaceWith('<i class="icon-plus-sign"></i>');
			$('#button-transaction').prop('disabled', false);
		},
		success: function(html) {
			$('#transaction').html(html);
			
			$('#tab-transaction input[name=\'amount\']').val('');
			$('#tab-transaction input[name=\'description\']').val('');
		}
	});
});
//--></script> 
<?php echo $footer; ?>