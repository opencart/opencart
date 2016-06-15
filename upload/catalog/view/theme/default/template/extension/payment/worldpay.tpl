<?php if (!empty($existing_cards)) { ?>
	<legend><?php echo $text_credit_card; ?></legend>
	<div class="form-horizontal">
	  <div id="choose-card" class="form-group">
		<label class="col-sm-2 control-label"><?php echo $entry_card; ?></label>
		<div class="col-sm-10">
		  <label class="radio-inline">
			<input type="radio" name="existing-card" value="1" checked="checked"/>
			<?php echo $entry_card_existing; ?>
		  </label>
		  <label class="radio-inline">
			<input type="radio" name="existing-card" value="0"/>
			<?php echo $entry_card_new; ?>
		  </label>
		</div>
	  </div>
	</div>
	<form id="payment-existing-form" action="<?php echo $form_submit; ?>" method="post" class="form-horizontal">
	  <fieldset>
		<div id="card-existing">
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="token"><?php echo $entry_cc_choice; ?></label>
			<div class="col-sm-10">
			  <select name="token" data-worldpay="token" class="form-control">
				<?php foreach ($existing_cards as $existing_card) { ?>
					<option value="<?php echo $existing_card['token']; ?>"><?php echo $text_card_type . ' ' . $existing_card['type']; ?>, <?php echo $text_card_digits . ' ' . $existing_card['digits']; ?>, <?php echo $text_card_expiry . ' ' . $existing_card['expiry']; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="buttons clearfix">
			<div class="pull-right">
			  <input type="button" value="<?php echo $button_delete_card; ?>" id="button-delete" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="input-cc-cvc"><?php echo $entry_cc_cvc; ?></label>
			<div class="col-sm-10">
			  <input type="text" data-worldpay="cvc" value="" size="4" placeholder="<?php echo $entry_cc_cvc; ?>" id="input-cc-cvc" class="form-control" />
			</div>
		  </div>
		</div>
	  </fieldset>
	  <div class="buttons">
		<div class="pull-right">
		  <input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
		</div>
	  </div>
	</form>
	<form style="display: none" id="payment-new-form" action="<?php echo $form_submit; ?>" method="post" class="form-horizontal">
  <?php } else { ?>
	  <form id="payment-new-form" action="<?php echo $form_submit; ?>" method="post" class="form-horizontal">
	<?php } ?>
	<fieldset>
	  <div class="form-group">
		<div class="col-sm-11" id='paymentDetailsHere' style="margin-left: 3%"></div>
	  </div>
	  <?php if ($worldpay_card) { ?>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-cc-save"><?php echo $entry_card_save; ?></label>
			<div class="col-sm-2">
			  <input type="checkbox" name="save-card" value=true id="input-cc-save"/>
			</div>
		  </div>
	  <?php } ?>
	  </div>
	</fieldset>
	<div class="buttons">
	  <div class="pull-right">
		<input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
	  </div>
	</div>
  </form>
  <div id="payment-errors">
  </div>
  <script type="text/javascript"><!--

      //Load Worldpay.js and run script functions
      $.getScript("<?php echo $worldpay_script; ?>", function (data, textStatus, jqxhr) {
        Worldpay.setClientKey("<?php echo $worldpay_client_key; ?>");

        // disable new card form if existing cards
<?php if (!empty($existing_cards)) { ?>
	        $('#payment-new-form :input').prop('disabled', true);
<?php } ?>

        // Set if token is reusable, remove first value when Worldpay update
        Worldpay.reusable = true;
<?php if (isset($recurring_products)) { ?>
	        Worldpay.reusable = true;
<?php } else { ?>
	        $('input[name=\'save-card\']').on('change', function () {
	          if ($(this).is(':checked')) {
	            Worldpay.reusable = true;
	          } else {
	            Worldpay.reusable = false;
	          }
	        });
<?php } ?>

        Worldpay.templateSaveButton = false;
        Worldpay.useTemplate('payment-new-form', 'paymentDetailsHere', 'inline', function (obj) {
          var _el = document.createElement('input');
          _el.value = obj.token;
          _el.type = 'hidden';
          _el.name = 'token';
          document.getElementById('payment-new-form').appendChild(_el);
		  document.getElementById('payment-new-form').submit();
        });

        //Submit form
        $('input[type=\'submit\']').on('click', function () {
          var existing = $('input[name=\'existing-card\']:checked').val();
          if (existing === '1') {
            var form = document.getElementById('payment-existing-form');
            Worldpay.useForm(form, function (status, response) {
              if (response.error || status != 200) {
                Worldpay.handleError(form, document.getElementById('payment-errors'), response.error);
              } else {
                form.submit();
              }
            }, true);
          } else {
            Worldpay.submitTemplateForm();
          }
        });
      });

      //Delete a card
      $('#button-delete').on('click', function () {
        var token = $('select[name=\'token\'] option:selected');

        if (confirm('<?php echo $text_confirm_delete; ?>\n' + token.text())) {
          $.ajax({
            url: 'index.php?route=extension/payment/worldpay/deleteCard',
            type: 'post',
            data: {token: token.val()},
            dataType: 'json',
            beforeSend: function () {
              $('#button-delete').button('loading');
            },
            complete: function () {
              $('#button-delete').button('reset');
            },
            success: function (json) {
              if (json['error']) {
                alert(json['error']);
              }

              if (json['success']) {
                alert(json['success']);
                if (json['existing_cards']) {
                  token.remove();
                } else {
                  $('input[name=\'existing-card\'][value=0]').click();
                  $('#choose-card').remove();
                  $('#payment-existing-form').remove();
                }
              }
            }
          });
        }
      });

      // enable or disable forms based on exiting or new card option
      $('input[name=\'existing-card\']').on('change', function () {
        if (this.value === '1') {
          $('#payment-existing-form').show();
          $('#payment-new-form').hide();
          $('#payment-new-form :input').prop('disabled', true);
          $('#payment-existing-form :input').prop('disabled', false);
        } else {
          $('#payment-existing-form').hide();
          $('#payment-new-form').show();
          $('#payment-new-form :input').prop('disabled', false);
          $('#payment-existing-form :input').prop('disabled', true);
        }
      });
      //--></script>