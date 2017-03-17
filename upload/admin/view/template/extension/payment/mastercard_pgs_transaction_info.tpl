<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
	<?php if ($error_warning) { ?>
	  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $error_warning; ?>
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	  </div>
	<?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
      	<form class="form-horizontal" method="POST" id="transaction_form">
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_transaction_id; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $transaction_id; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_merchant; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $merchant; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_order_id; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><a href="<?php echo $url_order; ?>" target="_blank"><?php echo $order_id; ?></a></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $status; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_result; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $result; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_type; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $type; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_amount; ?></label>
	      	  <div class="col-sm-10">
	      	    <div<?php if ($is_authorized || $is_captured || $is_payment) echo ' class="input-group"'; ?>>
	      	      <?php if ($is_authorized) { ?>
	      	      	<input type="text" class="form-control" name="transaction_amount" value="<?php echo $amount; ?>" />
	      	      	<a class="form-submit input-group-addon btn btn-success" href="<?php echo $url_capture; ?>" data-toggle="tooltip" data-original-title="<?php echo $text_capture; ?>" data-confirm-text="<?php echo $text_confirm_capture; ?>"><i class="fa fa-check"></i>&nbsp;<?php echo $button_capture; ?></a>
	      	      	<a class="form-submit input-group-addon btn btn-danger" href="<?php echo $url_void; ?>" data-toggle="tooltip" data-original-title="<?php echo $text_void; ?>" data-confirm-text="<?php echo $text_confirm_void; ?>"><i class="fa fa-remove"></i>&nbsp;<?php echo $button_void; ?></a>
	      	      <?php } else if ($is_captured) { ?>
	      	      	<input type="text" class="form-control" name="transaction_amount" value="<?php echo $amount; ?>" />
	      	      	<a class="form-submit input-group-addon btn btn-danger" href="<?php echo $url_refund; ?>" data-toggle="tooltip" data-original-title="<?php echo $text_refund; ?>" data-confirm-text="<?php echo $text_confirm_refund; ?>"><i class="fa fa-reply"></i>&nbsp;<?php echo $button_refund; ?></a>
	      	      	<a class="form-submit input-group-addon btn btn-danger" href="<?php echo $url_void; ?>" data-toggle="tooltip" data-original-title="<?php echo $text_void; ?>" data-confirm-text="<?php echo $text_confirm_void; ?>"><i class="fa fa-remove"></i>&nbsp;<?php echo $button_void; ?></a>
	      	      <?php } else if ($is_payment) { ?>
	      	      	<input type="text" class="form-control" name="transaction_amount" value="<?php echo $amount; ?>" />
	      	      	<a class="form-submit input-group-addon btn btn-danger" href="<?php echo $url_refund; ?>" data-toggle="tooltip" data-original-title="<?php echo $text_refund; ?>" data-confirm-text="<?php echo $text_confirm_refund; ?>"><i class="fa fa-reply"></i>&nbsp;<?php echo $button_refund; ?></a>
	      	      <?php } else { ?>
	      	      	<div class="form-info"><?php echo $amount; ?></div>
	      	      <?php } ?>
	      	    </div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_currency; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $currency; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_risk_code; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $risk_code; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_risk_score; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $risk_score; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_api_version; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $api_version; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_browser; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $browser; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_ip; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $ip; ?></div>
	      	  </div>
	      	</div>
	      	<div class="form-group">
	      	  <label class="col-sm-2 control-label"><?php echo $entry_date_created; ?></label>
	      	  <div class="col-sm-10">
	      	    <div class="form-info"><?php echo $date_created; ?></div>
	      	  </div>
	      	</div>
      	</form>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
  .form-info {
	margin-top: 9px;
  }
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('.form-submit').click(function(e) {
		e.preventDefault();

		var amount = $('input[name="transaction_amount"]').val();

		if (confirm($(this).attr('data-confirm-text').replace('{AMOUNT}', amount))) {
			var href = $(this).attr('href');
			var input_group = $(this).closest('.input-group');

			$(input_group).find('.input-group-addon').remove();
			$(input_group).append('<span class="input-group-addon"><?php echo $text_loading; ?></span>');

			$('#transaction_form').attr('action', href).submit();
		}
	});
});
</script>
<?php echo $footer; ?> 