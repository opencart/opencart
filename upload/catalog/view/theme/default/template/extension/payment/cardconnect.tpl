<form id="cardconnect-form" action="<?php echo $action; ?>" method="post" class="form form-horizontal">
  <fieldset id="payment">
    <legend><?php echo $text_card_details; ?></legend>
	<?php if ($echeck) { ?>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-method"><?php echo $entry_method; ?></label>
      <div class="col-sm-10">
        <select name="method" id="input-method" class="form-control">
          <option value="card"><?php echo $text_card; ?></option>
          <option value="echeck"><?php echo $text_echeck; ?></option>
        </select>
      </div>
    </div>
	<?php } ?>
    <div class="card_container">
      <div class="form-group" <?php if (!$store_cards) { echo 'style="display:none"'; } ?>>
        <label class="col-sm-2 control-label"><?php echo $entry_card_new_or_old; ?></label>
          <div class="col-sm-10">
            <label class="radio-inline">
              <input type="radio" name="card_new" value="1" checked="checked"/>
              <?php echo $entry_card_new; ?>
            </label>
            <label class="radio-inline">
              <input type="radio" name="card_new" value="0"/>
              <?php echo $entry_card_old; ?>
            </label>
          </div>
      </div>
      <div class="card_new_container">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-card-type"><?php echo $entry_card_type; ?></label>
          <div class="col-sm-10">
            <select name="card_type" id="input-card-type" class="form-control">
              <?php foreach ($card_types as $card_type) { ?>
              <option value="<?php echo $card_type['value']; ?>"><?php echo $card_type['text']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-card-number"><?php echo $entry_card_number; ?></label>
          <div class="col-sm-10">
            <input type="text" name="card_number" value="" placeholder="<?php echo $entry_card_number; ?>" id="input-card-number" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-card-expiry"><?php echo $entry_card_expiry; ?></label>
          <div class="col-sm-3">
            <select name="card_expiry_month" id="input-card-expiry" class="form-control">
              <?php foreach ($months as $month) { ?>
              <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-3">
            <select name="card_expiry_year" class="form-control">
              <?php foreach ($years as $year) { ?>
              <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-card-cvv2"><?php echo $entry_card_cvv2; ?></label>
          <div class="col-sm-10">
            <input type="text" name="card_cvv2" value="" placeholder="<?php echo $entry_card_cvv2; ?>" id="input-card-cvv2" class="form-control" />
          </div>
        </div>
		<?php if ($store_cards) { ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-card-save" style="padding-top:0"><?php echo $entry_card_save; ?></label>
            <div class="col-sm-10">
              <input type="checkbox" name="card_save" value="1" id="input-card-save"/>
            </div>
          </div>
		<?php } ?>
      </div>
	  <div class="card_old_container" style="display:none">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-card-choice"><?php echo $entry_card_choice; ?></label>
          <div class="col-sm-8">
            <select name="card_choice" id="input-card-choice" class="form-control" <?php if (!$cards) { echo 'disabled'; } ?>>
          	  <?php if ($cards) { ?>
       		    <option value=""><?php echo $text_select_card; ?></option>
                <?php foreach ($cards as $card) { ?>
                <option value="<?php echo $card['token']; ?>"><?php echo $card['type'] . ', &nbsp; ' . $card['account'] . ', &nbsp; ' . $card['expiry']; ?></option>
                <?php } ?>
              <?php } else { ?>
                <option value=""><?php echo $text_no_cards; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-2">
            <input type="button" value="<?php echo $button_delete; ?>" id="button-delete" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger" />
          </div>
        </div>
	  </div>
	</div>
    <div class="echeck_container" style="display:none">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-account-number"><?php echo $entry_account_number; ?></label>
        <div class="col-sm-10">
          <input type="text" name="account_number" value="" placeholder="<?php echo $entry_account_number; ?>" id="input-account-number" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-routing-number"><?php echo $entry_routing_number; ?></label>
        <div class="col-sm-10">
          <input type="text" name="routing_number" value="" placeholder="<?php echo $entry_routing_number; ?>" id="input-routing-number" class="form-control" />
        </div>
      </div>
    </div>
  </fieldset>
</form>

<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>">
  </div>
</div>

<script type="text/javascript"><!--
$('select[name="method"]').on('change', function() {
	if ($(this).val() == 'card') {
		$('#payment legend').text('<?php echo $text_card_details; ?>');
		$('.card_container').show();
		$('.echeck_container').hide();
	} else {
		$('#payment legend').text('<?php echo $text_echeck_details; ?>');
		$('.card_container').hide();
		$('.echeck_container').show();
	}
});
//--></script>

<script type="text/javascript"><!--
$('input[name="card_new"]').on('change', function() {
	if ($(this).val() == '1') {
		$('.card_new_container').show();
		$('.card_old_container').hide();
	} else {
		$('.card_new_container').hide();
		$('.card_old_container').show();
	}
});
//--></script>

<script type="text/javascript"><!--
$('#button-delete').bind('click', function() {
	if (confirm('<?php echo $text_confirm_delete; ?>')) {
		$.ajax({
			url: 'index.php?route=extension/payment/cardconnect/delete',
			type: 'post',
			data: $('#input-card-choice'),
			dataType: 'json',
			beforeSend: function() {
				$('.cardconnect_message').remove();
				$('#payment').before('<div class="alert alert-info cardconnect_wait"><i class="fa fa-info-circle"></i> <?php echo $text_wait; ?></div>');
				$('#button-delete').button('loading');
			},
			complete: function() {
				$('.cardconnect_wait').remove();
				$('#button-delete').button('reset');
			},
			success: function(json) {
				if (json['error']) {
					$('#cardconnect-form').before('<div class="alert alert-danger cardconnect_message" style="display:none"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('.cardconnect_message').fadeIn();
				} else {
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

<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=extension/payment/cardconnect/send',
		type: 'post',
		data: $('#cardconnect-form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('.cardconnect_message').remove();
			$('.text-danger').remove();
			$('#payment').find('*').removeClass('has-error');
			$('#payment').before('<div class="alert alert-info cardconnect_wait"><i class="fa fa-info-circle"></i> <?php echo $text_wait; ?></div>');
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('.cardconnect_wait').remove();
			$('#button-confirm').button('reset');
		},
		success: function(json) {
			if (json['error']['warning']) {
				$('#cardconnect-form').before('<div class="alert alert-danger cardconnect_message" style="display:none"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('.cardconnect_message').fadeIn();
			}

			if (json['error']['card_number']) {
				$('#input-card-number').after('<div class="text-danger">' + json['error']['card_number'] + '</div>');

				$('#input-card-number').closest('.form-group').addClass('has-error');
			}

			if (json['error']['card_cvv2']) {
				$('#input-card-cvv2').after('<div class="text-danger">' + json['error']['card_cvv2'] + '</div>');

				$('#input-card-cvv2').closest('.form-group').addClass('has-error');
			}

			if (json['error']['card_choice']) {
				$('#input-card-choice').after('<div class="text-danger">' + json['error']['card_choice'] + '</div>');

				$('#input-card-choice').closest('.form-group').addClass('has-error');
			}

			if (json['error']['method']) {
				$('#input-method').after('<div class="text-danger">' + json['error']['method'] + '</div>');

				$('#input-method').closest('.form-group').addClass('has-error');
			}

			if (json['error']['account_number']) {
				$('#input-account-number').after('<div class="text-danger">' + json['error']['account_number'] + '</div>');

				$('#input-account-number').closest('.form-group').addClass('has-error');
			}

			if (json['error']['routing_number']) {
				$('#input-routing-number').after('<div class="text-danger">' + json['error']['routing_number'] + '</div>');

				$('#input-routing-number').closest('.form-group').addClass('has-error');
			}

			if (json['success']) {
				location = json['success'];
			}
		}
	});
});
//--></script>