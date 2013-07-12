<?php echo $header; ?>

<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    
    <li>
        <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
        </a>
    </li>
    <?php } ?>
</ul> <!-- Breadcrumb -->

<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?></div>
<?php } ?>

<div class="row">

    <?php echo $column_left; ?>

    <div id="content" class="span9">

        <?php echo $content_top; ?>

        <h1><?php echo $heading_title; ?></h1>
        <p><?php echo $text_account_already; ?></p>
        <p><?php echo $text_signup; ?></p>

        <h3><?php echo $text_your_details; ?></h3>

        <form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

        <!-- Your Personal Details -->

        <div class="content">

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_firstname; ?></label>
                <div class="controls">
                    <input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                    <?php if ($error_firstname) { ?>
                        <div class="error"><?php echo $error_firstname; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_lastname; ?></label>
                <div class="controls">
                    <input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                    <?php if ($error_lastname) { ?>
                        <div class="error"><?php echo $error_lastname; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_email; ?></label>
                <div class="controls">
                    <input type="text" name="email" value="<?php echo $email; ?>" />
                    <?php if ($error_email) { ?>
                        <div class="error"><?php echo $error_email; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_telephone; ?></label>
                <div class="controls">
                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" />
                    <?php if ($error_telephone) { ?>
                        <div class="error"><?php echo $error_telephone; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><?php echo $entry_fax; ?></label>
                <div class="controls">
                    <input type="text" name="fax" value="<?php echo $fax; ?>" />
                </div>
            </div>
                
        </div>

        <!-- Your Address Details -->

        <h3><?php echo $text_your_address; ?></h3>
        
        <div class="content">

            <div class="control-group">
                <label class="control-label"><?php echo $entry_company; ?></label>
                <div class="controls">
                    <input type="text" name="company" value="<?php echo $company; ?>" />
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><?php echo $entry_website; ?></label>
                <div class="controls">
                    <input type="text" name="website" value="<?php echo $website; ?>" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_address_1; ?></label>
                <div class="controls">
                    <input type="text" name="address_1" value="<?php echo $address_1; ?>" />
                    <?php if ($error_address_1) { ?>
                        <div class="error"><?php echo $error_address_1; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><?php echo $entry_address_2; ?></label>
                <div class="controls">
                    <input type="text" name="address_2" value="<?php echo $address_2; ?>" />
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_city; ?></label>
                <div class="controls">
                    <input type="text" name="city" value="<?php echo $city; ?>" />
                    <?php if ($error_city) { ?>
                        <div class="error"><?php echo $error_city; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span id="postcode-required" class="text-error">*</span> <?php echo $entry_postcode; ?></label>
                <div class="controls">
                    <input type="text" name="postcode" value="<?php echo $postcode; ?>" />
                    <?php if ($error_postcode) { ?>
                        <div class="error"><?php echo $error_postcode; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_country; ?></label>
                <div class="controls">
                    <select name="country_id">
                        <option value="false"><?php echo $text_select; ?></option>
                        <?php foreach ($countries as $country) { ?>
                        <?php if ($country['country_id'] == $country_id) { ?>
                        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                    <?php if ($error_country) { ?>
                        <div class="error"><?php echo $error_country; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_zone; ?></label>
                <div class="controls">
                    <select name="zone_id"></select>
                    <?php if ($error_zone) { ?>
                        <div class="error"><?php echo $error_zone; ?></div>
                    <?php } ?>
                </div>
            </div>

        </div>

        <!-- Payment Information -->

        <h3><?php echo $text_payment; ?></h3>

        <div class="content">

        <table class="form">

            <tbody>
                <tr>
                    <td><?php echo $entry_tax; ?></td>
                    <td><input type="text" name="tax" value="<?php echo $tax; ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_payment; ?></td>
                    <td><?php if ($payment == 'cheque') { ?>
                    <input type="radio" name="payment" value="cheque" id="cheque" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="payment" value="cheque" id="cheque" />
                    <?php } ?>
                    <label for="cheque"><?php echo $text_cheque; ?></label>
                    <?php if ($payment == 'paypal') { ?>
                    <input type="radio" name="payment" value="paypal" id="paypal" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="payment" value="paypal" id="paypal" />
                    <?php } ?>
                    <label for="paypal"><?php echo $text_paypal; ?></label>
                    <?php if ($payment == 'bank') { ?>
                    <input type="radio" name="payment" value="bank" id="bank" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="payment" value="bank" id="bank" />
                    <?php } ?>
                    <label for="bank"><?php echo $text_bank; ?></label></td>
                </tr>
            </tbody>

            <tbody id="payment-cheque" class="payment">
                <tr>
                    <td><?php echo $entry_cheque; ?></td>
                    <td><input type="text" name="cheque" value="<?php echo $cheque; ?>" /></td>
                </tr>
            </tbody>

            <tbody class="payment" id="payment-paypal">
                <tr>
                    <td><?php echo $entry_paypal; ?></td>
                    <td>
                        <input type="text" name="paypal" value="<?php echo $paypal; ?>" />
                    </td>
                </tr>
            </tbody>

            <tbody id="payment-bank" class="payment">
                <tr>
                    <td><?php echo $entry_bank_name; ?></td>
                    <td>
                        <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_bank_branch_number; ?></td>
                    <td>
                        <input type="text" name="bank_branch_number" value="<?php echo $bank_branch_number; ?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $entry_bank_swift_code; ?>
                    </td>
                    <td>
                        <input type="text" name="bank_swift_code" value="<?php echo $bank_swift_code; ?>" />
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_bank_account_name; ?></td>
                    <td>
                        <input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_bank_account_number; ?></td>
                    <td>
                        <input type="text" name="bank_account_number" value="<?php echo $bank_account_number; ?>" />
                    </td>
                </tr>
            </tbody>
        </table>
        </div> <!-- /content -->

        <!-- Your Password -->

        <h3><?php echo $text_your_password; ?></h3>

        <div class="content">

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_password; ?></label>
                <div class="controls">
                    <input type="password" name="password" value="<?php echo $password; ?>" />
                    <?php if ($error_password) { ?>
                        <div class="error"><?php echo $error_password; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_confirm; ?></label>
                <div class="controls">
                    <input type="password" name="confirm" value="<?php echo $confirm; ?>" />
                    <?php if ($error_confirm) { ?>
                        <div class="error"><?php echo $error_confirm; ?></div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if ($text_agree) { ?>
        <div class="buttons clearfix">
            <?php echo $text_agree; ?>
            <?php if ($agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?>
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary pull-right" />
        </div>
        <?php } else { ?>
        <div class="buttons clearfix">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary pull-right" />
        </div>
        <?php } ?>

        </form>

<?php echo $content_bottom; ?>

</div>

<?php echo $column_right; ?>

</div>

<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=affiliate/register/country&country_id=' + this.value,
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


<script type="text/javascript"><!--
$('input[name=\'payment\']').bind('change', function() {
	$('.payment').hide();
	
	$('#payment-' + this.value).show();
});

$('input[name=\'payment\']:checked').trigger('change');
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
<?php echo $footer; ?>