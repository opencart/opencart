 <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
<form class="form-horizontal">
  <fieldset id="payment">
	<legend><?php echo $text_credit_card; ?></legend>
	<?php if (!empty($existing_cards)) { ?>
		<div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $entry_card; ?></label>
		  <div class="col-sm-10">
			<label class="radio-inline">
			  <input type="radio" name="CreateToken" value="0" checked="checked"/>
			  <?php echo $entry_card_existing; ?>
			</label>
			<label class="radio-inline">
			  <input type="radio" name="CreateToken" value=""/>
			  <?php echo $entry_card_new; ?>
			</label>
		  </div>
		</div>
		<div id="card-existing">
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="Token"><?php echo $entry_cc_choice; ?></label>
			<div class="col-sm-8">
			  <select name="Token" class="form-control">
				<?php foreach ($existing_cards as $existing_card) { ?>
					<option value="<?php echo $existing_card['token']; ?>"><?php echo $text_card_type . ' ' . $existing_card['type']; ?>, <?php echo $text_card_digits . ' ' . $existing_card['digits']; ?>, <?php echo $text_card_expiry . ' ' . $existing_card['expiry']; ?></option>
				<?php } ?>
			  </select>
			</div>
			<div class="col-sm-2">
			  <input type="button" value="<?php echo $button_delete_card; ?>" id="button-delete" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger" />
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="cc_cvv2" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input-cc-cvv2" class="form-control" />
			</div>
		  </div>
		</div>
		<div  style="display: none" id="card-new">
	  <?php } else { ?>
		  <div id="card-new">
		<?php } ?>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-cc-owner"><?php echo $entry_cc_owner; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="cc_owner" value="" placeholder="<?php echo $entry_cc_owner; ?>" id="input-cc-owner" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-cc-type"><?php echo $entry_cc_type; ?></label>
		  <div class="col-sm-10">
			<select name="cc_type" id="input-cc-type" class="form-control">
			  <?php foreach ($cards as $card) { ?>
				  <option value="<?php echo $card['value']; ?>"><?php echo $card['text']; ?></option>
			  <?php } ?>
			</select>
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-cc-number"><?php echo $entry_cc_number; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="cc_number" value="" placeholder="<?php echo $entry_cc_number; ?>" id="input-cc-number" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-cc-expire-date"><?php echo $entry_cc_expire_date; ?></label>
		  <div class="col-sm-3">
			<select name="cc_expire_date_month" id="input-cc-expire-date" class="form-control">
			  <?php foreach ($months as $month) { ?>
				  <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
			  <?php } ?>
			</select>
		  </div>
		  <div class="col-sm-3">
			<select name="cc_expire_date_year" class="form-control">
			  <?php foreach ($year_expire as $year) { ?>
				  <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
			  <?php } ?>
			</select>
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="cc_cvv2" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input-cc-cvv2" class="form-control" />
		  </div>
		</div>
		<?php if ($sagepay_direct_card) { ?>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-cc-save"><?php echo $entry_card_save; ?></label>
			  <div class="col-sm-2">
				<input type="checkbox" name="CreateToken" value="1" id="input-cc-save"/>
			  </div>
			</div>
		<?php } ?>
	  </div>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
	<input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
<?php if (!empty($existing_cards)) { ?>
	      $('#card-new input').prop('disabled', true);
	      $('#card-new input').prop('disabled', true);
	      $('#card-new select').prop('disabled', true);
<?php } ?>
    });
//</script>
<script type="text/javascript">
	$('input[name=\'CreateToken\']').on('change', function() {
      if (this.value === '0') {
        $('#card-existing').show();
        $('#card-new').hide();
        $('#card-new input').prop('disabled', true);
        $('#card-new select').prop('disabled', true);
        $('#card-existing select').prop('disabled', false);
        $('#card-existing input').prop('disabled', false);
      } else {
        $('#card-existing').hide();
        $('#card-new').show();
        $('#card-new input').prop('disabled', false);
        $('#card-new select').prop('disabled', false);
        $('#card-existing select').prop('disabled', true);
        $('#card-existing input').prop('disabled', true);
      }
    });
//</script>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
      $.ajax({
        url: 'index.php?route=extension/payment/sagepay_direct/send',
        type: 'post',
        data: $('#card-new :input[type=\'text\']:enabled, #card-new select:enabled, #card-new :input[type=\'checkbox\']:checked:enabled, #payment select:enabled, #card-existing :input:enabled'),
        dataType: 'json',
        cache: false,
			beforeSend: function() {
          $('#button-confirm').button('loading');
        },
			complete: function() {
          $('#button-confirm').button('reset');
        },
			success: function(json) {
          if (json['ACSURL']) {
            $('#3dauth').remove();

            html = '<form action="' + json['ACSURL'] + '" method="post" id="3dauth">';
            html += '  <input type="hidden" name="MD" value="' + json['MD'] + '" />';
            html += '  <input type="hidden" name="PaReq" value="' + json['PaReq'] + '" />';
            html += '  <input type="hidden" name="TermUrl" value="' + json['TermUrl'] + '" />';
            html += '</form>';

            $('#payment').after(html);

            $('#3dauth').submit();
          }

          if (json['error']) {
            alert(json['error']);
          }

          if (json['redirect']) {
            location = json['redirect'];
          }
        }
      });
    });
//--></script>
<script type="text/javascript"><!--
    $('#button-delete').bind('click', function () {
      if (confirm('<?php echo $text_confirm_delete; ?>')) {
        $.ajax({
          url: 'index.php?route=extension/payment/sagepay_direct/delete',
          type: 'post',
          data: $('#card-existing :input[name=\'Token\']'),
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
              $.ajax({
                url: 'index.php?route=checkout/confirm',
                dataType: 'html',
                success: function (html) {
                  $('#collapse-checkout-confirm .panel-body').html(html);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
              });
            }

          }
        });
      }
    });
//--></script>