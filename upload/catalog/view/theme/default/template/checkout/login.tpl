<div class="left">
  <h2><?php echo $text_new_customer; ?></h2>
  <p><?php echo $text_checkout; ?></p>
  <label for="register">
    <?php if ($account == 'register') { ?>
    <input type="radio" name="account" value="register" id="register" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="account" value="register" id="register" />
    <?php } ?>
    <b><?php echo $text_register; ?></b></label>
  <br />
  <?php if ($guest_checkout) { ?>
  <label for="guest">
    <?php if ($account == 'guest') { ?>
    <input type="radio" name="account" value="guest" id="guest" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="account" value="guest" id="guest" />
    <?php } ?>
    <b><?php echo $text_guest; ?></b></label>
  <br />
  <?php } ?>
  <br />
  <p><?php echo $text_register_account; ?></p>
  <input type="button" value="<?php echo $button_continue; ?>" id="button-account" class="button" />
  <br />
  <br />
</div>
<div id="login" class="right">
  <h2><?php echo $text_returning_customer; ?></h2>
  <p><?php echo $text_i_am_returning_customer; ?></p>
  <b><?php echo $entry_email; ?></b><br />
  <input type="text" name="email" value="" />
  <br />
  <br />
  <b><?php echo $entry_password; ?></b><br />
  <input type="password" name="password" value="" />
  <br />
  <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
  <br />
  <input type="button" value="<?php echo $button_login; ?>" id="button-login" class="button" /><br />
  <br />
</div>
<script type="text/javascript"><!--
$('#checkout .checkout-content input[name=\'account\']').off().on('change', function() {
	if ($(this).attr('value') == 'register') {
		$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_account; ?>');
	} else {
		$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
	}
});

// Checkout
$('#button-account').off().on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/' + $('input[name=\'account\']:checked').attr('value'),
		dataType: 'html',
		beforeSend: function() {
			$('#button-account').attr('disabled', true);
			$('#button-account').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		},		
		complete: function() {
			$('#button-account').attr('disabled', false);
			$('.loading').remove();
		},			
		success: function(html) {
			$('.warning, .error').remove();
			
			$('#payment-address .checkout-content').html(html);
				
			$('#checkout .checkout-content').slideUp('slow');
				
			$('#payment-address .checkout-content').slideDown('slow');
				
			$('.checkout-heading a').remove();
				
			$('#checkout .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Login
$('#button-login').off().on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/login/save',
		type: 'post',
		data: $('#checkout #login :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-login').attr('disabled', true);
			$('#button-login').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		},	
		complete: function() {
			$('#button-login').attr('disabled', false);
			$('.loading').remove();
		},				
		success: function(json) {
			$('.warning, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#checkout .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
//--></script>