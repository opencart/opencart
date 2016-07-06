{{ header }}
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}</div>
  <?php } ?>
  <div class="row">{{ column_left }}
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="{{ class }}">{{ content_top }}
      <h1>{{ heading_title }}</h1>
      <p>{{ text_account_already }}</p>
      <p>{{ text_signup }}</p>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend>{{ text_your_details }}</legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname">{{ entry_firstname }}</label>
            <div class="col-sm-10">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="{{ entry_firstname }}" id="input-firstname" class="form-control" />
              <?php if ($error_firstname) { ?>
              <div class="text-danger">{{ error_firstname }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname">{{ entry_lastname }}</label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="{{ entry_lastname }}" id="input-lastname" class="form-control" />
              <?php if ($error_lastname) { ?>
              <div class="text-danger">{{ error_lastname }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email">{{ entry_email }}</label>
            <div class="col-sm-10">
              <input type="text" name="email" value="{{ email }}" placeholder="{{ entry_email }}" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger">{{ error_email }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-telephone">{{ entry_telephone }}</label>
            <div class="col-sm-10">
              <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="{{ entry_telephone }}" id="input-telephone" class="form-control" />
              <?php if ($error_telephone) { ?>
              <div class="text-danger">{{ error_telephone }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fax">{{ entry_fax }}</label>
            <div class="col-sm-10">
              <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="{{ entry_fax }}" id="input-fax" class="form-control" />
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend>{{ text_your_address }}</legend>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-company">{{ entry_company }}</label>
            <div class="col-sm-10">
              <input type="text" name="company" value="<?php echo $company; ?>" placeholder="{{ entry_company }}" id="input-company" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-website">{{ entry_website }}</label>
            <div class="col-sm-10">
              <input type="text" name="website" value="<?php echo $website; ?>" placeholder="{{ entry_website }}" id="input-website" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address-1">{{ entry_address_1 }}</label>
            <div class="col-sm-10">
              <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="{{ entry_address_1 }}" id="input-address-1" class="form-control" />
              <?php if ($error_address_1) { ?>
              <div class="text-danger">{{ error_address_1 }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-address-2">{{ entry_address_2 }}</label>
            <div class="col-sm-10">
              <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="{{ entry_address_2 }}" id="input-address-2" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-city">{{ entry_city }}</label>
            <div class="col-sm-10">
              <input type="text" name="city" value="<?php echo $city; ?>" placeholder="{{ entry_city }}" id="input-city" class="form-control" />
              <?php if ($error_city) { ?>
              <div class="text-danger">{{ error_city }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-postcode">{{ entry_postcode }}</label>
            <div class="col-sm-10">
              <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="{{ entry_postcode }}" id="input-postcode" class="form-control" />
              <?php if ($error_postcode) { ?>
              <div class="text-danger">{{ error_postcode }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-country">{{ entry_country }}</label>
            <div class="col-sm-10">
              <select name="country_id" id="input-country" class="form-control">
                <option value="false">{{ text_select }}</option>
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <?php if ($error_country) { ?>
              <div class="text-danger">{{ error_country }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-zone">{{ entry_zone }}</label>
            <div class="col-sm-10">
              <select name="zone_id" id="input-zone" class="form-control">
              </select>
              <?php if ($error_zone) { ?>
              <div class="text-danger">{{ error_zone }}</div>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend>{{ text_payment }}</legend>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax">{{ entry_tax }}</label>
            <div class="col-sm-10">
              <input type="text" name="tax" value="<?php echo $tax; ?>" placeholder="{{ entry_tax }}" id="input-tax" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">{{ entry_payment }}</label>
            <div class="col-sm-10">
              <div class="radio">
                <label>
                  <?php if ($payment == 'cheque') { ?>
                  <input type="radio" name="payment" value="cheque" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="cheque" />
                  <?php } ?>
                  {{ text_cheque }}</label>
              </div>
              <div class="radio">
                <label>
                  <?php if ($payment == 'paypal') { ?>
                  <input type="radio" name="payment" value="paypal" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="paypal" />
                  <?php } ?>
                  {{ text_paypal }}</label>
              </div>
              <div class="radio">
                <label>
                  <?php if ($payment == 'bank') { ?>
                  <input type="radio" name="payment" value="bank" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="bank" />
                  <?php } ?>
                  {{ text_bank }}</label>
              </div>
            </div>
          </div>
          <div class="form-group payment" id="payment-cheque">
            <label class="col-sm-2 control-label" for="input-cheque">{{ entry_cheque }}</label>
            <div class="col-sm-10">
              <input type="text" name="cheque" value="<?php echo $cheque; ?>" placeholder="{{ entry_cheque }}" id="input-cheque" class="form-control" />
            </div>
          </div>
          <div class="form-group payment" id="payment-paypal">
            <label class="col-sm-2 control-label" for="input-paypal">{{ entry_paypal }}</label>
            <div class="col-sm-10">
              <input type="text" name="paypal" value="<?php echo $paypal; ?>" placeholder="{{ entry_paypal }}" id="input-paypal" class="form-control" />
            </div>
          </div>
          <div class="payment" id="payment-bank">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-bank-name">{{ entry_bank_name }}</label>
              <div class="col-sm-10">
                <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" placeholder="{{ entry_bank_name }}" id="input-bank-name" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-bank-branch-number">{{ entry_bank_branch_number }}</label>
              <div class="col-sm-10">
                <input type="text" name="bank_branch_number" value="<?php echo $bank_branch_number; ?>" placeholder="{{ entry_bank_branch_number }}" id="input-bank-branch-number" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-bank-swift-code">{{ entry_bank_swift_code }}</label>
              <div class="col-sm-10">
                <input type="text" name="bank_swift_code" value="<?php echo $bank_swift_code; ?>" placeholder="{{ entry_bank_swift_code }}" id="input-bank-swift-code" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-bank-account-name">{{ entry_bank_account_name }}</label>
              <div class="col-sm-10">
                <input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>" placeholder="{{ entry_bank_account_name }}" id="input-bank-account-name" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-bank-account-number">{{ entry_bank_account_number }}</label>
              <div class="col-sm-10">
                <input type="text" name="bank_account_number" value="<?php echo $bank_account_number; ?>" placeholder="{{ entry_bank_account_number }}" id="input-bank-account-number" class="form-control" />
              </div>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend>{{ text_your_password }}</legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password">{{ entry_password }}</label>
            <div class="col-sm-10">
              <input type="password" name="password" value="{{ password }}" placeholder="{{ entry_password }}" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger">{{ error_password }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm">{{ entry_confirm }}</label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="{{ entry_confirm }}" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger">{{ error_confirm }}</div>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <?php echo $captcha; ?>
        <?php if ($text_agree) { ?>
        <div class="buttons clearfix">
          <div class="pull-right">{{ text_agree }}
            <?php if ($agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?>
            &nbsp;
            <input type="submit" value="{{ button_continue }}" class="btn btn-primary" />
          </div>
        </div>
        <?php } else { ?>
        <div class="buttons clearfix">
          <div class="pull-right">
            <input type="submit" value="{{ button_continue }}" class="btn btn-primary" />
          </div>
        </div>
        <?php } ?>
      </form>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=affiliate/register/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value="">{{ text_select }}</option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected">{{ text_none }}</option>';
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
{{ footer }}
