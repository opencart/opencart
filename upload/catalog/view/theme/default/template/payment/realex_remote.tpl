<form class="form-horizontal">
  <fieldset id="payment">
    <legend><?php echo $text_credit_card; ?></legend>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-type"><?php echo $entry_cc_type; ?></label>
      <div class="col-sm-10">
        <select name="cc_type" id="input-cc-type" class="form-control">
          <?php foreach ($cards as $card) { ?>
            <option value="<?php echo $card['code']; ?>"><?php echo $card['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-name"><?php echo $entry_cc_name; ?></label>
      <div class="col-sm-10">
        <input type="text" name="cc_name" value="" placeholder="<?php echo $entry_cc_name; ?>" id="input-cc-name" class="form-control" />
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
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-cc-issue"><span data-toggle="tooltip" title="<?php echo $help_issue; ?>"><?php echo $entry_cc_issue; ?></span></label>
      <div class="col-sm-10">
        <input type="text" name="cc_issue" value="" placeholder="<?php echo $entry_cc_issue; ?>" id="input-cc-issue" class="form-control" />
      </div>
    </div>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $.ajax({
    url: 'index.php?route=payment/realex_remote/send',
    type: 'post',
    data: $('#payment :input'),
    dataType: 'json',
    beforeSend: function() {
      $('#realex-message-error').remove();
      $('#button-confirm').attr('disabled', true);
      $('#payment').before('<div id="realex-message-wait" class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_wait; ?></div>');
    },
    success: function(json) {
      // if 3ds redirect instruction
      if (json['ACSURL']) {
        $('#3dauth').remove();

        html  = '<form action="' + json['ACSURL'] + '" method="post" id="3dauth">';
        html += '  <input type="hidden" name="MD" value="' + json['MD'] + '" />';
        html += '  <input type="hidden" name="PaReq" value="' + json['PaReq'] + '" />';
        html += '  <input type="hidden" name="TermUrl" value="' + json['TermUrl'] + '" />';
        html += '</form>';

        $('#payment').after(html);

        $('#3dauth').submit();
      }

      // if error
      if (json['error']) {
        $('#payment').before('<div id="realex-message-error" class="alert alert-warning"><i class="fa fa-info-circle"></i> '+json['error']+'</div>');
        $('#button-confirm').attr('disabled', false);
        $('#realex-message-wait').remove();
      }

      // if success
      if (json['success']) {
        location = json['success'];
      }
    }
  });
});
//--></script>