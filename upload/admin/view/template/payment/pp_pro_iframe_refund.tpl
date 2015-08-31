<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error != '') { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($attention != '') { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $attention; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <input type="hidden" name="amount_original" value="<?php echo $amount_original; ?>"/>
          <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>"/>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-transaction-id"><?php echo $entry_transaction_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="transaction_id" value="<?php echo $transaction_id; ?>" placeholder="<?php echo $entry_transaction_id; ?>" id="input-transaction-id" class="form-control"/>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_full_refund; ?></label>
            <div class="col-sm-10">
              <input type="hidden" name="refund_full" value="0"/>
              <input type="checkbox" name="refund_full" id="refund_full" value="1" <?php echo ($refund_available == '' ? 'checked="checked"' : ''); ?> onchange="refundAmount();"/>
            </div>
          </div>
          <div class="form-group" <?php echo ($refund_available == '' ? 'style="display:none;" ' : ''); ?>id="partial_amount_row">
            <label class="col-sm-2 control-label"><?php echo $entry_amount; ?></label>
            <div class="col-sm-10">
              <input type="text" name="amount" value="<?php echo ($refund_available != '' ? $refund_available : ''); ?>" placeholder="<?php echo $entry_amount; ?>" class="form-control"/>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_message; ?></label>
            <div class="col-sm-10">
              <textarea name="refund_message" id="paypal_refund_message" cols="40" rows="5"></textarea>
            </div>
          </div>
          <div class="pull-right"><a onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_refund; ?></a></div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
function refundAmount() {
	var valChecked = $('#refund_full').prop('checked');

	if (valChecked == true) {
		$('#partial_amount_row').hide();
	} else {
		$('#partial_amount_row').show();
	}
}
//--></script></div>
<?php echo $footer; ?>