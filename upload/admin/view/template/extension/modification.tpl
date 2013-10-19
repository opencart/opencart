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
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right"><a href="<?php echo $refresh; ?>" class="btn btn-default"><i class="icon-refresh"></i> <?php echo $button_refresh; ?></a> <a href="<?php echo $clear; ?>" class="btn btn-danger"><i class="icon-eraser"></i> <?php echo $button_clear; ?></a>
        <button type="button" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-modification').submit() : false;"><i class="icon-trash"></i> <?php echo $button_delete; ?></button>
      </div>
      <h1 class="panel-title"><i class="icon-list"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-modification">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                <td class="text-left"><?php if ($sort == 'name') { ?>
                  <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'author') { ?>
                  <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'status') { ?>
                  <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'date_added') { ?>
                  <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                  <?php } ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($modifications) { ?>
              <?php foreach ($modifications as $modification) { ?>
              <tr>
                <td class="text-center"><?php if ($modification['selected']) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $modification['modification_id']; ?>" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $modification['modification_id']; ?>" />
                  <?php } ?></td>
                <td class="text-left"><?php echo $modification['name']; ?></td>
                <td class="text-left"><?php echo $modification['author']; ?></td>
                <td class="text-left"><?php if ($modification['status']) { ?>
                <button type="button" class="btn btn-success btn-xs"><i class="icon-ok"></i> <?php echo $button_enable; ?></button>
                <?php } else { ?>
                <button type="button" class="btn btn-danger btn-xs"><i class="icon-minus"></i> <?php echo $button_disable; ?></button>
                <?php } ?></td>
                <td class="text-left"><?php echo $modification['date_added']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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
$(document).delegate('#button-enable', 'click', function() {
	$.ajax({
		url: 'index.php?route=extension/modification/status&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-add i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-reward-add').prop('disabled', true);				
		},
		complete: function() {
			$('#button-reward-add i').replaceWith('<i class="icon-minus-sign"></i>');
			$('#button-reward-add').prop('disabled', false);
		},									
		success: function(json) {
			$('.alert').remove();
						
			if (json['error']) {
				$('.panel').before('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
                $('.panel').before('<div class="alert alert-success"><i class="icon-ok-sign"></i> ' + json['success'] + '</div>');
				
				$('#button-reward-add').replaceWith('<button id="button-reward-remove" class="btn btn-danger btn-xs"><i class="icon-minus-sign"></i> <?php echo $text_reward_remove; ?></button>');
			}
		}
	});
});

$(document).delegate('#button-disable', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-remove i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-reward-remove').prop('disabled', true);		
		},
		complete: function() {
			$('#button-reward-remove i').replaceWith('<i class="icon-minus-sign"></i>');
			$('#button-reward-remove').prop('disabled', false);
		},				
		success: function(json) {
			$('.alert').remove();
						
			if (json['error']) {
				$('.panel').before('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
                $('.panel').before('<div class="alert alert-success"><i class="icon-ok-sign"></i> ' + json['success'] + '</div>');
				
				$('#button-reward-remove').replaceWith('<button id="button-reward-add" class="btn btn-success btn-xs"><i class="icon-plus-sign"></i> <?php echo $text_reward_add; ?></button>');
			}
		}
	});
});
//--></script> 
<?php echo $footer; ?>