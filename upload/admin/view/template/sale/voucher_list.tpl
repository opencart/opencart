<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" id="button-send" data-toggle="tooltip" title="<?php echo $button_send; ?>" class="btn btn-primary"><i class="fa fa-envelope"></i></button>
        <a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-voucher').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-voucher">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'v.code') { ?>
                    <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'v.from_name') { ?>
                    <a href="<?php echo $sort_from; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_from; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_from; ?>"><?php echo $column_from; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'v.to_name') { ?>
                    <a href="<?php echo $sort_to; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_to; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_to; ?>"><?php echo $column_to; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'v.amount') { ?>
                    <a href="<?php echo $sort_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_amount; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_amount; ?>"><?php echo $column_amount; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'theme') { ?>
                    <a href="<?php echo $sort_theme; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_theme; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_theme; ?>"><?php echo $column_theme; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'v.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'v.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($vouchers) { ?>
                <?php foreach ($vouchers as $voucher) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($voucher['voucher_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $voucher['voucher_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $voucher['voucher_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $voucher['code']; ?></td>
                  <td class="text-left"><?php echo $voucher['from']; ?></td>
                  <td class="text-left"><?php echo $voucher['to']; ?></td>
                  <td class="text-right"><?php echo $voucher['amount']; ?></td>
                  <td class="text-left"><?php echo $voucher['theme']; ?></td>
                  <td class="text-left"><?php echo $voucher['status']; ?></td>
                  <td class="text-left"><?php echo $voucher['date_added']; ?></td>
                  <td class="text-right"><a href="<?php echo $voucher['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-send').on('click', function() {
	$.ajax({
		url: 'index.php?route=sale/voucher/send&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: $('input[name^=\'selected\']'),
		beforeSend: function() {
			$('#button-send i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
			$('#button-send').prop('disabled', true);
		},	
		complete: function() {
			$('#button-send i').replaceWith('<i class="fa fa-envelope"></i>');
			$('#button-send').prop('disabled', false);
		},
		success: function(json) {
			$('.alert').remove();
			
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
			}		
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
})
//--></script></div>
<?php echo $footer; ?>