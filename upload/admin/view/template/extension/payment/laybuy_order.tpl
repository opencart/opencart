<h2><?php echo $text_payment_info; ?></h2>
<?php if ($transaction) { ?>
<form action="" method="post" enctype="multipart/form-data" id="form-laybuy-transaction" class="form-horizontal">
<ul class="nav nav-tabs">
 <li class="active"><a href="#tab-reference" data-toggle="tab"><?php echo $tab_reference; ?></a></li>
 <li class=""><a href="#tab-customer" data-toggle="tab"><?php echo $tab_customer; ?></a></li>
 <li class=""><a href="#tab-payment-plan" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
 <?php if ($transaction['status_id'] == 1) { ?>
 <li class=""><a href="#tab-modify" data-toggle="tab"><?php echo $tab_modify; ?></a></li>
 <?php } ?>
</ul>
<div class="tab-content">
  <div class="tab-pane active" id="tab-reference">
    <div class="form-group">
      <label class="col-sm-2 control-label"><?php echo $text_paypal_profile_id; ?></label>
      <div class="col-sm-10">
        <?php echo $transaction['paypal_profile_id']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_laybuy_ref_no; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['laybuy_ref_no']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_order_id; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['order_id']; ?>
	  </div>
    </div>
  </div>
  <div class="tab-pane" id="tab-customer">
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_firstname; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['firstname']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_lastname; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['lastname']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_email; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['email']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_address; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['address']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_suburb; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['suburb']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_state; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['state']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_country; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['country']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_postcode; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['postcode']; ?>
	  </div>
	</div>
  </div>
  <div class="tab-pane" id="tab-payment-plan">
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_status; ?></label>
	  <div class="col-sm-10" id="laybuy-status">
	    <?php echo $transaction['status']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_amount; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['amount']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_downpayment_percent; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['downpayment'] . '%'; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_months; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['months']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_downpayment_amount; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['downpayment_amount']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_payment_amounts; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['payment_amounts']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_first_payment_due; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['first_payment_due']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_last_payment_due; ?></label>
	  <div class="col-sm-10">
	    <?php echo $transaction['last_payment_due']; ?>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_report; ?></label>
	  <div class="col-sm-10">
	    <table class="table table-striped">
		  <thead>
		    <tr>
			  <th><?php echo $text_instalment; ?></th>
			  <th><?php echo $text_amount; ?></th>
			  <th><?php echo $text_date; ?></th>
			  <th><?php echo $text_pp_trans_id; ?></th>
			  <th><?php echo $text_status; ?></th>
			</tr>
		  </thead>
		  <tbody>
		  <?php foreach ($transaction['report'] as $report) { ?>
		    <?php if ($report['instalment'] == '0') { ?>
		      <tr>
			    <td><?php echo $text_downpayment; ?></td>
			    <td><?php echo $report['amount']; ?></td>
			    <td><?php echo $report['date']; ?></td>
			    <td><?php echo $report['pp_trans_id']; ?></td>
			    <td><?php echo $report['status']; ?></td>
			  </tr>
		    <?php } else { ?>
		      <tr>
			    <td><?php echo $text_month . ' ' . $report['instalment']; ?></td>
			    <td><?php echo $report['amount']; ?></td>
			    <td><?php echo $report['date']; ?></td>
			    <td><?php echo $report['pp_trans_id']; ?></td>
			    <td><?php echo $report['status']; ?></td>
			  </tr>
		    <?php } ?>
		  <?php } ?>
		  </tbody>
	    </table>
	  </div>
	</div>
  </div>
  <?php if ($transaction['status_id'] == 1) { ?>
  <div class="tab-pane" id="tab-modify">
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_cancel_plan; ?></label>
	  <div class="col-sm-10">
	    <button class="btn btn-primary btn-xs" id="cancel-plan"><?php echo $button_cancel_plan; ?></button>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?php echo $text_revise_plan; ?></label>
	  <div class="col-sm-10">
	    <button class="btn btn-primary btn-xs" id="revise-plan" style="margin-bottom:10px"><?php echo $button_revise_plan; ?></button>
		<div class="amount_remaining">
		  <b><?php echo $text_remaining; ?></b> <?php echo $transaction['remaining']; ?>
		  <input type="hidden" id="amount" name="amount" value="<?php echo $total; ?>" />
		</div>
	    <select name="payment_type" id="payment-type">
	 	          <option value="1"><?php echo $text_type_laybuy; ?></option>
	 	          <option value="0"><?php echo $text_type_buynow; ?></option>
	   			</select>
		<div class="laybuy_section">
		  <br>
	      <select name="INIT" id="down-payment">
	              <?php foreach ($initial_payments as $percent) { ?>
	 		          <?php if ($percent == $transaction['downpayment']) { ?>
	 	                <option value="<?php echo $percent; ?>" selected="selected"><?php echo $percent; ?>%</option>
	     	          <?php } else { ?>
	 	                <option value="<?php echo $percent; ?>"><?php echo $percent; ?>%</option>
		      <?php } ?>
	        <?php } ?>
	   	  </select>
	  	  <select name="MONTHS" id="months">
	           <?php foreach ($months as $month) { ?>
	  		      <?php if ($month['value'] == $transaction['months']) { ?>
	               <option value="<?php echo $month['value']; ?>" selected="selected"><?php echo $month['label']; ?></option>
		      <?php } else { ?>
	               <option value="<?php echo $month['value']; ?>"><?php echo $month['label']; ?></option>
		      <?php } ?>
	           <?php } ?>
	      </select>
		  <div class="table-responsive">
		    <table class="table table-striped table-responsive table-condensed" id="payment-table" style="margin-top:5px">
		      <thead>
		        <th><?php echo $text_payment; ?></th>
		        <th><?php echo $text_due_date; ?></th>
		        <th class="text-right"><?php echo $text_amount; ?></th>
		      </thead>
		      <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
<?php } ?>
</div>
</form>
<?php } else { ?>
<?php echo $text_not_found; ?>
<?php } ?>

<style>
#tab-laybuy h2 {
	margin-bottom: 15px;
}

#tab-laybuy .control-label {
	padding-top: 0;
}

#tab-laybuy .amount_remaining {
	margin-bottom: 10px;
}
</style>

<script type="text/javascript"><!--
var token = '';

// Login to the API
$.ajax({
	url: '<?php echo $store_url; ?>index.php?route=api/login',
	type: 'post',
	dataType: 'json',
	data: 'key=<?php echo $api_key; ?>',
	crossDomain: true,
	success: function(json) {
        if (json['error']) {
    		if (json['error']['key']) {
    			alert(json['error']['key']);
    		}

            if (json['error']['ip']) {
    			alert(json['error']['ip']);
    		}
        }

        if (json['token']) {
			token = json['token'];
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});
//--></script>

<script type="text/javascript"><!--
$('#cancel-plan').on('click', function(e) {
	e.preventDefault();

	$.ajax({
		url: 'index.php?route=extension/payment/laybuy/cancel&token=<?php echo $token; ?>&id=<?php echo $id; ?>&source=order',
		type: 'post',
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#cancel-plan, #revise-plan').attr('disabled', true);
			$('#cancel-plan').after('<span class="laybuy-loading fa fa-spinner" style="margin-left:2px"></span>');
		},
		complete: function() {
			$('#cancel-plan, #revise-plan').attr('disabled', false);
			$('.laybuy-loading').remove();
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}

			if (json['success']) {
				if (token) {
					// Send order history to the API
					$.ajax({
						url: '<?php echo $store_url; ?>index.php?route=api/order/history&token=' + token + '&order_id=' + json['order_id'],
						type: 'post',
						dataType: 'json',
						data: 'order_status_id=' + json['order_status_id'] + '&notify=1&override=0&append=0&comment=' + encodeURIComponent(json['comment']),
						success: function(json) {
							if (json['error']) {
								alert(json['error']);
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}

				alert(json['success']);

				location = json['reload'].replace(/&amp;/g, '&');
			}
		},
	 	error: function(xhr, ajaxOptions, thrownError) {
	 		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script>

<script type="text/javascript"><!--
$('#revise-plan').on('click', function(e) {
	e.preventDefault();

	$.ajax({
		url: 'index.php?route=extension/payment/laybuy/revise&token=<?php echo $token; ?>&id=<?php echo $id; ?>&source=order',
		type: 'post',
		data: $('#payment-type, #amount, #down-payment, #months'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#cancel-plan, #revise-plan').attr('disabled', true);
			$('#revise-plan').after('<div class="laybuy-loading fa fa-spinner" style="margin-left:2px"></div>');
		},
		complete: function() {
			$('#cancel-plan, #revise-plan').attr('disabled', false);
			$('.laybuy-loading').remove();
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}

			if (json['success']) {
				alert(json['success']);

				location = json['reload'].replace(/&amp;/g, '&');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script>

<script type="text/javascript"><!--
$('#payment-type').on('change', function() {
	var payment_type = $('#payment-type').val();

	if (payment_type == '1') {
		$('.laybuy_section').show();
	} else {
		$('.laybuy_section').hide();
	}
});
//--></script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#tab-modify').on('change', 'select', function() {
   		calculate($('#down-payment').val(), $('#months').val());
	});

	var symbol_left = "<?php echo $currency_symbol_left; ?>";
	var symbol_right = "<?php echo $currency_symbol_right; ?>";
	var order = <?php echo json_encode($order_info); ?>;
	var total = parseFloat(parseFloat(<?php echo $total; ?>) * parseFloat(order.currency_value)).toFixed(4);

	calculate($('#down-payment').val(), $('#months').val());

	function calculate(dp, months) {
		var down_payment = getPercent(dp, total);

		var remainder = total - down_payment;

		var payments = getPayments(remainder, months);
			payments[0] = {
			payment: '<?php echo $text_downpayment; ?>',
			dueDate: '<?php echo $text_today; ?>',
			amount: parseFloat(down_payment).toFixed(2)
		};

		replaceRows(payments);
	}

	function getPercent(percent, value) {
		var result = (percent / 100) * value;

		return result.toFixed(4);
	}

	function getPayments(amount, months) {
		var payment_amount = amount / months;

		var payments = {};

		for (i = 1; i <= months; i++) {
			var new_date = new Date();

			new_date.setMonth(new_date.getMonth() + i);

			payments[i] = {
				payment: '<?php echo $text_month; ?> ' + i,
				dueDate: ('0' + new_date.getDate()).slice(-2) + '/' + ('0' + (new_date.getMonth() + 1)).slice(-2) + '/' + new_date.getFullYear(),
				amount: parseFloat(payment_amount).toFixed(2)
			}
		}

		return payments;
	}

	function replaceRows(payments) {
		$('#payment-table').find('tbody').html('');

		for (payment in payments) {
			addRow(payments[payment]);
		}
	}

	function addRow(payment) {
		var row;

		row = '<tr>';
		row += '<td>' + payment.payment + '</td>';
		row += '<td>' + payment.dueDate + '</td>';
		row += '<td class="text-right">' + symbol_left + payment.amount + symbol_right + '</td>';
		row += '</tr>';

		$('#payment-table').find('tbody').append(row);
	}
});
//--></script>