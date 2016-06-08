<h2><?php echo $text_payment_info; ?></h2>
<table class="table table-striped table-bordered">
  <tr>
    <td><?php echo $entry_capture_status; ?>: </td>
    <td id="capture-status"><?php if ($complete) { ?>
      <?php echo $text_complete; ?>
      <?php } else { ?>
      <?php echo $text_incomplete; ?>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $entry_capture; ?></td>
    <td id="complete-entry"><?php if ($complete) { ?>
      -
      <?php } else { ?>
      <?php echo $entry_complete_capture; ?>
      <input type="checkbox" name="capture-complete" value="1" />
      <br />
      <input type="text" name="capture-amount" value="0.00" />
      <a class="btn btn-primary" id="button-capture" onclick="capture()"><?php echo $button_capture; ?></a>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $entry_void; ?></td>
    <td id="reauthorise-entry"><?php if ($complete) { ?>
      -
      <?php } else { ?>
      <a class="btn btn-primary" id="button-void" onclick="doVoid()"><?php echo $button_void; ?></a>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $entry_transactions; ?></td>
    <td><table id="transaction-table" class="table table-striped table-bordered">
        <thead>
          <tr>
            <td class="text-left"><?php echo $column_transaction_id; ?></td>
            <td class="text-left"><?php echo $column_transaction_type; ?></td>
            <td class="text-left"><?php echo $column_amount; ?></td>
            <td class="text-left"><?php echo $column_time; ?></td>
            <td class="text-left"><?php echo $column_actions; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $transaction) { ?>
          <tr>
            <td class="text-left"><?php echo $transaction['transaction_reference']; ?></td>
            <td class="text-left"><?php echo $transaction['transaction_type']; ?></td>
            <td class="text-left"><?php echo number_format($transaction['amount'], 2); ?></td>
            <td class="text-left"><?php echo $transaction['time']; ?></td>
            <td class="text-left"><?php foreach ($transaction['actions'] as $action) { ?>
              [<a href="<?php echo $action['href']; ?>"><?php echo $action['title']; ?></a>]
              <?php } ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table></td>
  </tr>
</table>
<script type="text/javascript"><!--
function markAsComplete() {
    $('#complete-entry, #reauthorise-entry, #reauthorise-entry').html('-');
    $('#capture-status').html('<?php echo $text_complete; ?>');
}

function doVoid() {
    if (confirm('<?php echo $text_confirm_void; ?>')) {
        $.ajax({
            type:'POST',
            dataType: 'json',
            data: {'order_id':<?php echo (int)$order_id; ?>},
            url: 'index.php?route=extension/payment/pp_payflow_iframe/void&token=<?php echo $token; ?>',
            beforeSend: function() {
                $('#button-void').after('<span class="btn btn-primary loading"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>');
                $('#button-void').hide();
            },
            success: function(data) {
                if (!data.error) {
                    $('#capture-status').text('<?php echo $text_complete; ?>');

                    var html = '';
                    html += '<tr>';
                    html += ' <td class="left">' + data.success.transaction_reference + '</td>';
                    html += ' <td class="left">' + data.success.transaction_type + '</td>';
                    html += ' <td class="left">' + data.success.amount + '</td>';
                    html += ' <td class="left">' + data.success.time + '</td>';
                    html += ' <td class="left"></td>';
                    html += '</tr>';
                    $('#transaction-table tbody').append(html);

                    markAsComplete();
                }

                if (data.error) {
                    alert(data.error);
                    $('#button-void').show();
                }

                $('.loading').remove();
            }
        });
    }
}

function capture() {
    var amount = $('input[name="capture-amount"]').val();
    var complete = 0;

    if ($('input[name="capture-complete"]').is(':checked')) {
        complete = 1;
    }

    $.ajax({
        type:'POST',
        dataType: 'json',
        data: {'order_id':<?php echo $order_id; ?>, 'amount':amount, 'complete':complete },
        url: 'index.php?route=extension/payment/pp_payflow_iframe/capture&token=<?php echo $token; ?>',

        beforeSend: function() {
            $('#button-capture').after('<span class="btn btn-primary loading"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>');
            $('#button-capture').hide();
        },

        success: function(data) {
            if (!data.error) {
                var html = '';
                html += '<tr>';
                html += ' <td class="left">' + data.success.transaction_reference + '</td>';
                html += ' <td class="left">' + data.success.transaction_type + '</td>';
                html += ' <td class="left">' + data.success.amount + '</td>';
                html += ' <td class="left">' + data.success.time + '</td>';
                html += ' <td class="left">';

                $.each(data.success.actions, function(index, value) {
                    html += ' [<a href="' + value.href + '">' + value.title + '</a>] ';
                });

                html += '</td>';
                html += '</tr>';
                $('#transaction-table tbody').append(html);

                if (complete == 1) {
                    markAsComplete();
                }
            }

            if (data.error) {
                alert(data.error);
            }

            $('#button-capture').show();
            $('.loading').remove();
        }
    });
}
//--></script>