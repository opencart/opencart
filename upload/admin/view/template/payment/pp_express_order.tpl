<fieldset>
  <legend><?php echo $text_transaction; ?></legend>
  <div id="paypal-transaction"></div>
</fieldset>
<fieldset>
  <legend><?php echo $text_payment; ?></legend>
  <table class="table table-bordered">
    <tr>
      <td><?php echo $text_capture_status; ?></td>
      <td id="capture-status"><?php echo $capture_status; ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_authorised; ?></td>
      <td><?php echo $total; ?>
        <?php if ($capture_status != 'Complete') { ?>
        &nbsp;&nbsp;&nbsp;
        <button type="button" id="button-void" data-loading="<?php echo $text_loading; ?>" class="btn btn-danger"><?php echo $button_void; ?></button>
        <?php } ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_captured; ?></td>
      <td id="paypal-captured"><?php echo $captured; ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_refunded; ?></td>
      <td id="paypal-refunded"><?php echo $refunded; ?></td>
    </tr>
  </table>
</fieldset>
<form id="paypal-capture" class="form-horizontal">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab-capture" data-toggle="tab"><?php echo $tab_capture; ?></a></li>
    <li><a href="#tab-refund" data-toggle="tab"><?php echo $tab_refund; ?></a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab-capture">
      <?php if ($capture_status != 'Complete') { ?>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_capture_amount; ?></label>
        <div class="col-sm-10">
          <div class="input-group">
            <input type="text" name="amount" value="<?php echo $capture_remaining; ?>" id="input-amount" class="form-control" />
            <div class="input-group-btn">
              <button type="button" id="button-capture" data-loading="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_capture; ?></button>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-complete"><?php echo $entry_capture_complete; ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="complete" value="1" id="input-complete" class="form-control" />
        </div>
      </div>
      <?php } ?>
    </div>
    <div class="tab-pane" id="tab-refund">
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_full_refund; ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="type" id="full" value="1" />
        </div>
      </div>
      
      <?php if ($refund_available == '') { ?>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-refund-amount"><?php echo $entry_amount; ?></label>
        <div class="col-sm-10">
          <input type="text" name="amount" value="<?php echo ($refund_available != '' ? $refund_available : ''); ?>" placeholder="<?php echo $entry_amount; ?>" id="input-refund-amount" class="form-control"/>
        </div>
      </div>
      <?php } ?>
      
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-note"><?php echo $entry_note; ?></label>
        <div class="col-sm-10">
          <textarea name="note" cols="40" rows="5" id="input-note" class="form-control"></textarea>
        </div>
      </div>
      <div class="pull-right">
        <button type="button" id="button-refund" data-loading="<?php echo $text_loading; ?>" class="btn btn-danger"><?php echo $button_refund; ?></button>
      </div>
    </div>
  </div>
</form>
<br />
<script type="text/javascript"><!--
$('#paypal-transaction').load('index.php?route=payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

$('#button-capture').on('click', function() {
	$.ajax({
		url: 'index.php?route=payment/pp_express/capture&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'amount=' + $('#paypal-capture-amount').val() + '&complete=' + $('#paypal-capture-complete').prop('checked'),
		beforeSend: function() {
			$('#button-capture').button('loading');
		},
		complete: function() {
			$('#button-capture').button('reset');
		},			
		success: function(json) {
			$('.alert').remove();
			
			if (json['error']) {
				$('#tab-pp_express').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			
			if (!json['success']) {
				$('#tab-pp_express').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('#paypal-captured').text(json['captured']);
				$('#paypal-capture-amount').val(json['remaining']);

				if (json['capture_status']) {
					$('#capture-status').text(json['capture_status']);
					
					$('#paypal-capture').remove();
					
					$('#button-void').button('disable');
				}
			}				
			
			$('#paypal-transaction').load('index.php?route=payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
		}
	});
});

$('#button-void').on('click', function() {
	if (confirm('<?php echo addslashes($text_confirm_void); ?>')) {
		$.ajax({
			url: 'index.php?route=payment/pp_express/void&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			dataType: 'json',
			beforeSend: function() {
				$('#button-void').button('loading');
			},
			complete: function() {
				$('#button-void').button('reset');
			},			
			success: function(json) {
				$('.alert').remove();
				
				if (json['error']) {
					$('#tab-pp_express').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				} 
				
				if (json['capture_status']) {
					$('#capture-status').text(json['capture_status']);
					
					$('#paypal-capture').remove();
					
					$('#button-void').button('disable');
				}				
			
				$('#paypal-transaction').load('index.php?route=payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
			}
		});
	}
});

$('#button-refund').on('click', function() {
	$.ajax({
		url: 'index.php?route=payment/pp_express/refund&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'amount=' + $('#paypal-capture-amount').val() + '&complete=' + $('#paypal-capture-complete').prop('checked'),
		beforeSend: function() {
			$('#button-refund').button('loading');
		},
		complete: function() {
			$('#button-refund').button('reset');
		},			
		success: function(json) {
			$('.alert').remove();
			
			if (json['error']) {
				$('#tab-pp_express').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} 
			
			if (json['capture_status']) {
				$('#capture-status').text(json['capture_status']);
				
				$('#paypal-capture').remove();
				
				$('#button-void').button('disable');
			}				
		
			$('#paypal-transaction').load('index.php?route=payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
		}
	});
});

$('#button-refund').on('change', function() {
	var valChecked = $('#refund_full').prop('checked');
	
	if (valChecked == true) {
		$('#partial_amount_row').hide();
	} else {
		$('#partial_amount_row').show();
	}
});

$('#paypal-transaction').delegate('button', 'click', function() {
	var element = this;
	
	$.ajax({
		url: $(element).attr('href'),
		dataType: 'json',
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},		
		success: function(json) {
			$('.alert').remove();
			
			if (json['error']) {
				$('#tab-pp_express').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#paypal-transaction').load('index.php?route=payment/pp_express/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
			}
		}
	});
});
//--></script>