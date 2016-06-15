<form id="laybuy-form" action="<?php echo $action; ?>" method="post" class="form form-horizontal">
  <div>
    <h3><?php echo $heading_title; ?></h3>
  </div>

  <div class="form-group">
    <label for="down-payment" class="col-sm-2"><?php echo $entry_initial; ?></label>
    <div class="col-sm-3">
      <select name="INIT" id="input-down-payment" class="form-control">
        <?php foreach ($initial_payments as $percent) { ?>
          <option value="<?php echo $percent; ?>"><?php echo $percent; ?>%</option>
        <?php } ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="months" class="col-sm-2"><?php echo $entry_months; ?></label>
    <div class="col-sm-3">
      <select name="MONTHS" id="input-months" class="form-control">
        <?php foreach ($months as $month) { ?>
		  <?php if ($month['value'] == 3) { ?>
            <option value="<?php echo $month['value']; ?>" selected="selected"><?php echo $month['label']; ?></option>
		  <?php } else { ?>
            <option value="<?php echo $month['value']; ?>"><?php echo $month['label']; ?></option>
		  <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
</form>

<div class="table-responsive">
  <h3><?php echo $text_plan_preview; ?></h3>
  <table class="table table-responsive table-condensed" id="payment-table">
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

<p style="font-size: 1.3em"><?php echo $text_delivery_msg; ?></p>

<p style="font-size: 1.3em"><?php echo $text_fee_msg; ?></p>

<div class="buttons">
  <div class="pull-right">
    <input type="submit" form="laybuy-form" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>">
  </div>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#laybuy-form').on('change', 'select', function() {
   		calculate($('#input-down-payment').val(), $('#input-months').val());
	});

	var symbol_left = "<?php echo $currency_symbol_left; ?>";
	var symbol_right = "<?php echo $currency_symbol_right; ?>";
	var order = <?php echo json_encode($order_info); ?>;
	var total = parseFloat(parseFloat(<?php echo $total; ?>) * parseFloat(order.currency_value)).toFixed(4);

	calculate($('#input-down-payment').val(), $('#input-months').val());

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

<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$('#button-confirm').button('loading');
});
//--></script>