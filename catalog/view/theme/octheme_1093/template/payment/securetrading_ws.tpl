<form class="form-horizontal">
  <fieldset id="payment">
    <legend><?php echo $text_card_details; ?></legend>
    
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
      <div class="col-sm-10">
        <select name="type" id="input-type" class="form-control">
          <?php foreach ($cards as $key => $title) { ?>
            <option value="<?php echo $key ?>"><?php echo $title; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-number"><?php echo $entry_number; ?></label>
      <div class="col-sm-10">
        <input type="text" name="number" value="" placeholder="<?php echo $entry_number; ?>" id="input-number" class="form-control" />
      </div>
    </div>
    
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-expire-date"><?php echo $entry_expire_date; ?></label>
      <div class="col-sm-3">
        <select name="expire_month" id="expire-date" class="form-control">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-3">
        <select name="expire_year" class="form-control">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cvv2"><?php echo $entry_cvv2; ?></label>
      <div class="col-sm-10">
        <input type="text" name="cvv2" value="" placeholder="<?php echo $entry_cvv2; ?>" id="input-cvv2" class="form-control" />
      </div>
    </div>

  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input id="button-confirm" type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript">
$('#button-confirm').bind('click', function() {    
    $.ajax({  
        url: 'index.php?route=payment/securetrading_ws/process',
        type: 'post',
        data: $('#payment :input'),
        dataType: 'json',
    
    beforeSend: function() {
        $('#button-confirm').attr('disabled', true);
        $('form.form-horizontal .alert').remove();
        $('#payment').before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_wait; ?></div>');
    },
    
    complete: function() {
        $('#button-confirm').attr('disabled', false);
    },
    success: function(json) {
        $('form.form-horizontal .alert').remove();
        
        if (json['status']) {
            if (json['redirect']) {
                location = json['redirect'];
            } else {
                $('#payment').before('<form id="threed-form" action="' + json['acs_url'] + '" method="POST"><input type="hidden" name="PaReq" value="' + json['pareq'] + '" /><input type="hidden" name="MD" value="' + json['md'] + '" /><input type="hidden" name="TermUrl" value="' + json['term_url'] + '" /></form>');
                $('#threed-form').submit();
            }
        } else {
            $('#payment').before('<div class="alert alert-danger"><i class="fa fa-info-circle"></i> ' + json['message'] + '</div>');
        }
    }
  });
});
</script>