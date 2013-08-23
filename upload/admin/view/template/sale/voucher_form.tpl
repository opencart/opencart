<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <?php if ($voucher_id) { ?>
        <button type="button" id="button-send" class="btn"><i class="icon-envelope"></i> <?php echo $button_send; ?></button>
        <?php } ?>
        <button type="submit" form="form-voucher" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-voucher" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <?php if ($voucher_id) { ?>
          <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_voucher_history; ?></a></li>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-code"><?php echo $entry_code; ?></label>
              <div class="col-sm-10">
                <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
                <span class="help-block"><?php echo $help_code; ?></span>
                <?php if ($error_code) { ?>
                <div class="text-danger"><?php echo $error_code; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-from-name"><?php echo $entry_from_name; ?></label>
              <div class="col-sm-10">
                <input type="text" name="from_name" value="<?php echo $from_name; ?>" placeholder="<?php echo $entry_from_name; ?>" id="input-from-name" class="form-control" />
                <?php if ($error_from_name) { ?>
                <div class="text-danger"><?php echo $error_from_name; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-from-email"><?php echo $entry_from_email; ?></label>
              <div class="col-sm-10">
                <input type="text" name="from_email" value="<?php echo $from_email; ?>" placeholder="<?php echo $entry_from_email; ?>" id="input-from-email" class="form-control" />
                <?php if ($error_from_email) { ?>
                <div class="text-danger"><?php echo $error_from_email; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-to-name"><?php echo $entry_to_name; ?></label>
              <div class="col-sm-10">
                <input type="text" name="to_name" value="<?php echo $to_name; ?>" placeholder="<?php echo $entry_to_name; ?>" id="input-to-name" class="form-control" />
                <?php if ($error_to_name) { ?>
                <div class="text-danger"><?php echo $error_to_name; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-to-email"><?php echo $entry_to_email; ?></label>
              <div class="col-sm-10">
                <input type="text" name="to_email" value="<?php echo $to_email; ?>" placeholder="<?php echo $entry_to_email; ?>" id="input-to-email" class="form-control" />
                <?php if ($error_to_email) { ?>
                <div class="text-danger"><?php echo $error_to_email; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-theme"><?php echo $entry_theme; ?></label>
              <div class="col-sm-10">
                <select name="voucher_theme_id" id="input-theme" class="form-control">
                  <?php foreach ($voucher_themes as $voucher_theme) { ?>
                  <?php if ($voucher_theme['voucher_theme_id'] == $voucher_theme_id) { ?>
                  <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>" selected="selected"><?php echo $voucher_theme['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>"><?php echo $voucher_theme['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-message"><?php echo $entry_message; ?></label>
              <div class="col-sm-10">
                <textarea name="message" rows="5" placeholder="<?php echo $entry_message; ?>" id="input-message" class="form-control"><?php echo $message; ?></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
              <div class="col-sm-10">
                <input type="text" name="amount" value="<?php echo $amount; ?>" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
                <?php if ($error_amount) { ?>
                <div class="text-danger"><?php echo $error_amount; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="col-sm-10">
                <select name="status" id="input-status" class="form-control">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <?php if ($voucher_id) { ?>
          <div class="tab-pane" id="tab-history">
            <div id="history"></div>
          </div>
          <?php } ?>
        </div>
      </form>
    </div>
  </div>
</div>
<?php if ($voucher_id) { ?>
<script type="text/javascript"><!--
$('#button-send').on('click', function() {
	$.ajax({
		url: 'index.php?route=sale/voucher/send&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'voucher_id=<?php echo $voucher_id; ?>',
		beforeSend: function() {
			$('#button-send i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-send').prop('disabled', true);
		},	
		complete: function() {
			$('#button-send i').replaceWith('<i class="icon-envelope"></i>');
			$('#button-send').prop('disabled', false);
		},
		success: function(json) {
			$('.alert').remove();
			
			if (json['error']) {
				$('.box').before('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('.box').before('<div class="alert alert-success"><i class="icon-ok-sign"></i>  ' + json['success'] + '</div>');
			}		
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
})
//--></script> 
<script type="text/javascript"><!--
$('#history .pagination a').on('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('index.php?route=sale/voucher/history&token=<?php echo $token; ?>&voucher_id=<?php echo $voucher_id; ?>');
//--></script>
<?php } ?>
<?php echo $footer; ?>