<form class="form-horizontal" id="payment_form">
  <fieldset id="payment">
    <legend><?php echo $text_credit_card; ?></legend>
    <?php if (!empty($accepted_cards)) { ?>
      <div class="form-group">
        <div class="col-sm-12">
          <p>
            <strong><?php echo $text_card_accepted; ?></strong>
            <ul>
              <?php if ($accepted_cards['mastercard'] == 1) { ?><li><?php echo $text_card_type_m; ?></li><?php } ?>
              <?php if ($accepted_cards['visa'] == 1) { ?><li><?php echo $text_card_type_v; ?></li><?php } ?>
              <?php if ($accepted_cards['diners'] == 1) { ?><li><?php echo $text_card_type_c; ?></li><?php } ?>
              <?php if ($accepted_cards['amex'] == 1) { ?><li><?php echo $text_card_type_a; ?></li><?php } ?>
              <?php if ($accepted_cards['maestro'] == 1) { ?><li><?php echo $text_card_type_ma; ?></li><?php } ?>
            </ul>
          </p>
        </div>
      </div>
    <?php } ?>

    <?php if ($card_storage == 1 && count($stored_cards) > 0) { ?>
    <div class="form-group">
      <div class="col-sm-12">
        <?php $i = 0; ?>

        <?php foreach ($stored_cards as $card) { ?>
          <p><input type="radio" name="cc_choice" value="<?php echo $card['token']; ?>" class="stored_card" <?php echo $i == 0 ? 'checked="checked"' : ''; ?>/> <?php echo $card['card_type'] . ' xxxx ' . $card['digits'] . ', ' . $entry_cc_expire_date . ' ' . $card['expire_month'] . '/' . $card['expire_year']; ?></p>
          <?php $i++; ?>
        <?php } ?>

        <p><input type="radio" name="cc_choice" value="new" class="stored_card" />New card</p>
      </div>
    </div>
    <?php } ?>

    <div id="card_info" style="display:none;">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-cc-name"><?php echo $entry_cc_name; ?></label>

        <div class="col-sm-10">
          <input type="text" name="cc_name" value="" placeholder="<?php echo $entry_cc_name; ?>" id="input-cc-name" class="form-control"/>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-cc-number"><?php echo $entry_cc_number; ?></label>

        <div class="col-sm-10">
          <input type="text" name="cc_number" value="" placeholder="<?php echo $entry_cc_number; ?>" id="input-cc-number" class="form-control"/>
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

      <?php if ($card_storage == 1) { ?>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-cc-cvv2">Store card details?</label>

        <div class="col-sm-10">
          <input type="hidden" name="cc_store" value="0"/> <input type="checkbox" name="cc_store" value="1" checked/>
        </div>
      </div>
      <?php } ?>
    </div>

    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?></label>
      <div class="col-sm-10">
        <input type="text" name="cc_cvv2" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input-cc-cvv2" class="form-control"/>
      </div>
    </div>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"/>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function () {
  $.ajax({
    url: 'index.php?route=payment/firstdata_remote/send',
    type: 'post',
    data: $('#payment_form').serialize(),
    dataType: 'json',
    beforeSend: function () {
      $('#firstdata_message_error').remove();
      $('#button-confirm').attr('disabled', true);
      $('#payment').before('<div id="firstdata_message_wait" class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_wait; ?></div>');
    },
    complete: function () {
      $('#button-confirm').attr('disabled', false);
      $('#firstdata_message_wait').remove();
    },
    success: function (json) {
      // if error
      if (json['error']) {
        $('#payment').before('<div id="firstdata_message_error" class="alert alert-warning"><i class="fa fa-info-circle"></i> ' + json['error'] + '</div>');
      }

      // if success
      if (json['success']) {
        location = json['success'];
      }
    }
  });
});

$(' . stored_card').bind('change', function () {
  if ($(this).val() == 'new') {
    $('#card_info').slideDown();
  } else {
    $('#card_info').slideUp();
  }
});

$(document).ready(function(){
  <?php if ($card_storage == 0) { ?>
    $('#card_info').show();
  <?php } else { ?>
    var stored_cards = <?php echo count($stored_cards); ?>;
    if (stored_cards == 0) {
      $('#card_info').show();
    }
  <?php } ?>
});
//--></script>