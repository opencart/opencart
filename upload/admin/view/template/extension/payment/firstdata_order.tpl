<h2><?php echo $text_payment_info; ?></h2>
<?php if ($void_success) { ?>
<div class="alert alert-success"><?php echo $void_success; ?></div>
<?php } ?>
<?php if ($void_error) { ?>
<div class="alert alert-warning"><?php echo $void_error; ?></div>
<?php } ?>
<table class="table table-striped table-bordered">
  <tr>
    <td><?php echo $text_order_ref; ?></td>
    <td><?php echo $firstdata_order['order_ref']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_order_total; ?></td>
    <td><?php echo $firstdata_order['total_formatted']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_total_captured; ?></td>
    <td id="firstdata_total_captured"><?php echo $firstdata_order['total_captured_formatted']; ?></td>
  </tr>
  <tr>
    <td><?php echo $text_capture_status; ?></td>
    <td id="capture_status"><?php if ($firstdata_order['capture_status'] == 1) { ?>
      <span class="capture_text"><?php echo $text_yes; ?></span>
      <?php } else { ?>
      <span class="capture_text"><?php echo $text_no; ?></span>&nbsp;&nbsp;
      <?php if ($firstdata_order['void_status'] == 0) { ?>
      <a class="button btn btn-primary" id="button_capture"><?php echo $button_capture; ?></a> <span class="btn btn-primary" id="img_loading_capture" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
      <?php } ?>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $text_void_status; ?></td>
    <td id="void_status"><?php if ($firstdata_order['void_status'] == 1) { ?>
      <span class="void_text"><?php echo $text_yes; ?></span>
      <?php } else { ?>
      <span class="void_text"><?php echo $text_no; ?></span>&nbsp;&nbsp; <a class="button btn btn-primary" id="button-void"><?php echo $button_void; ?></a> <span class="btn btn-primary" id="img_loading_void" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
      <?php } ?></td>
  </tr>
  <tr>
    <td><?php echo $text_transactions; ?>:</td>
    <td><table class="table table-striped table-bordered" id="firstdata_transactions">
        <thead>
          <tr>
            <td class="text-left"><strong><?php echo $text_column_date_added; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_type; ?></strong></td>
            <td class="text-left"><strong><?php echo $text_column_amount; ?></strong></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach($firstdata_order['transactions'] as $transaction) { ?>
          <tr>
            <td class="text-left"><?php echo $transaction['date_added']; ?></td>
            <td class="text-left"><?php echo $transaction['type']; ?></td>
            <td class="text-left"><?php echo $transaction['amount']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table></td>
  </tr>
</table>
<form method="POST" id="voidform" action="<?php echo $action_url; ?>">
  <input type="hidden" name="responseSuccessURL" value="<?php echo $void_url; ?>"/>
  <input type="hidden" name="responseFailURL" value="<?php echo $void_url; ?>"/>
  <input type="hidden" name="transactionNotificationURL" value="<?php echo $notify_url; ?>"/>
  <input type="hidden" name="txntype" value="void"/>
  <input type="hidden" name="timezone" value="GMT"/>
  <input type="hidden" name="txndatetime" value="<?php echo $request_timestamp; ?>"/>
  <input type="hidden" name="hash" value="<?php echo $hash; ?>"/>
  <input type="hidden" name="storename" value="<?php echo $merchant_id; ?>"/>
  <input type="hidden" name="chargetotal" value="<?php echo $amount; ?>"/>
  <input type="hidden" name="currency" value="<?php echo $currency; ?>"/>
  <input type="hidden" name="oid" value="<?php echo $firstdata_order['order_ref']; ?>"/>
  <input type="hidden" name="tdate" value="<?php echo $firstdata_order['tdate']; ?>"/>
  <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"/>
</form>
<form method="POST" id="captureform" action="<?php echo $action_url; ?>">
  <input type="hidden" name="responseSuccessURL" value="<?php echo $capture_url; ?>"/>
  <input type="hidden" name="responseFailURL" value="<?php echo $capture_url; ?>"/>
  <input type="hidden" name="transactionNotificationURL" value="<?php echo $notify_url; ?>"/>
  <input type="hidden" name="txntype" value="postauth"/>
  <input type="hidden" name="timezone" value="GMT"/>
  <input type="hidden" name="txndatetime" value="<?php echo $request_timestamp; ?>"/>
  <input type="hidden" name="hash" value="<?php echo $hash; ?>"/>
  <input type="hidden" name="storename" value="<?php echo $merchant_id; ?>"/>
  <input type="hidden" name="chargetotal" value="<?php echo $amount; ?>"/>
  <input type="hidden" name="currency" value="<?php echo $currency; ?>"/>
  <input type="hidden" name="oid" value="<?php echo $firstdata_order['order_ref']; ?>"/>
  <input type="hidden" name="tdate" value="<?php echo $firstdata_order['tdate']; ?>"/>
  <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"/>
</form>
<script type="text/javascript"><!--
$("#button-void").bind('click', function () {
  if (confirm('<?php echo $text_confirm_void; ?>')) {
    $('#voidform').submit();
  }
});

$("#button_capture").bind('click', function () {
  if (confirm('<?php echo $text_confirm_capture; ?>')) {
    $('#captureform').submit();
  }
});

$(document).ready(function () {
  <?php if ($void_success) { ?>
    alert('<?php echo $void_success; ?>');
  <?php } ?>

  <?php if ($void_error) { ?>
    alert('<?php echo $void_error; ?>');
  <?php } ?>

  <?php if ($capture_success) { ?>
    alert('<?php echo $capture_success; ?>');
  <?php } ?>

  <?php if ($capture_error) { ?>
    alert('<?php echo $capture_error; ?>');
  <?php } ?>
});
//--></script>