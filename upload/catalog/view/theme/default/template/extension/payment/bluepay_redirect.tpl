<form class="form-horizontal" action="https://secure.bluepay.com/interfaces/bp10emu" method=POST>
    <fieldset id="payment">
        <legend><?php echo $text_credit_card; ?></legend>
		<?php if (!empty($existing_cards)) { ?>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_card; ?></label>
				<div class="col-sm-10">
					<label class="radio-inline">
						<input type="radio" name="new-existing" value="existing" checked="checked"/>
						<?php echo $entry_card_existing; ?>
					</label>
					<label class="radio-inline">
						<input type="radio" name="new-existing" value="new" />
						<?php echo $entry_card_new; ?>
					</label>
				</div>
			</div>
			<div id="card-existing">
				<div class="form-group required">
					<label class="col-sm-2 control-label" for="Token"><?php echo $entry_cc_choice; ?></label>
					<div class="col-sm-10">
						<select name="RRNO" class="form-control">
							<?php foreach ($existing_cards as $existing_card) { ?>
								<option value="<?php echo $existing_card['token']; ?>"><?php echo $text_card_type . ' ' . $existing_card['type']; ?>, <?php echo $text_card_digits . ' ' . $existing_card['digits']; ?>, <?php echo $text_card_expiry . ' ' . $existing_card['expiry']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?></label>
					<div class="col-sm-10">
						<input type="text" name="CVCCVV2" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input-cc-cvv2" class="form-control" />
					</div>
				</div>
			</div>
			<div  style="display: none" id="card-new">
			<?php } else { ?>
				<div id="card-new">
				<?php } ?>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-cc-number"><?php echo $entry_cc_number; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="CC_NUM" value="" placeholder="<?php echo $entry_cc_number; ?>" id="input-cc-number" class="form-control" />
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-cc-expire-date"><?php echo $entry_cc_expire_date; ?></label>
                    <div class="col-sm-3">
                        <select name="CC_EXPIRES_MONTH" id="input-cc-expire-date" class="form-control">
							<?php foreach ($months as $month) { ?>
								<option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
							<?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="CC_EXPIRES_YEAR" class="form-control">
							<?php foreach ($year_expire as $year) { ?>
								<option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
							<?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="CVCCVV2" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input-cc-cvv2" class="form-control" />
                    </div>
                </div>
				<?php if ($bluepay_redirect_card) { ?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-cc-save"><?php echo $entry_card_save; ?></label>
						<div class="col-sm-2">
							<input id="input-cc-save" type="checkbox" name="CreateToken" value="1" />
						</div>
					</div>
				<?php } ?>
            </div>
        </div>
        <div class="buttons">
            <div class="pull-right">
                <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
            </div>
        </div>
    </fieldset>
</form>
<script type="text/javascript"><!--
    $(document).ready(function() {
<?php if (!empty($existing_cards)) { ?>
			$('#card-new input').prop('disabled', true);
			$('#card-new input').prop('disabled', true);
			$('#card-new select').prop('disabled', true);
<?php } ?>
	});
//--></script>
<script type="text/javascript"><!--
	$('input[name=\'new-existing\']').on('change', function() {
		if (this.value === 'existing') {
			$('#card-existing').show();
			$('#card-new').hide();
			$('#card-new input').prop('disabled', true);
			$('#card-new select').prop('disabled', true);
			$('#card-existing select').prop('disabled', false);
			$('#input-cc-cvv2').prop('disabled', false);
		} else {
			$('#card-existing').hide();
			$('#card-new').show();
			$('#card-new input').prop('disabled', false);
			$('#card-new select').prop('disabled', false);
			$('#card-existing select').prop('disabled', true);
			$('#card-existing input').prop('disabled', true);
		}
	});
//--></script>
<script type="text/javascript">
	$('#button-confirm').bind('click', function() {
		$.ajax({
			url: 'index.php?route=extension/payment/bluepay_redirect/send',
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


				if (json['error']) {
					alert(json['error']);
				}

				if (json['redirect']) {
					location = json['redirect'];
				}
			}
		});
	});
//</script>
