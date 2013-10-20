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
  <div class="alert alert-info"><i class="icon-info-sign"></i> <?php echo $text_refresh; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>  
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right"><a href="<?php echo $refresh; ?>" class="btn btn-info"><i class="icon-refresh"></i> <?php echo $button_refresh; ?></a> <a href="<?php echo $clear; ?>" class="btn btn-danger"><i class="icon-eraser"></i> <?php echo $button_clear; ?></a>
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
                <button type="button" id="button-modification<?php echo $modification['modification_id']; ?>" class="btn btn-success btn-xs" onclick="disableModification(<?php echo $modification['modification_id']; ?>);"><i class="icon-ok"></i> <?php echo $button_enable; ?></button>
                <?php } else { ?>
                <button type="button" id="button-modification<?php echo $modification['modification_id']; ?>" class="btn btn-danger btn-xs" onclick="enableModification(<?php echo $modification['modification_id']; ?>);"><i class="icon-remove"></i> <?php echo $button_disable; ?></button>
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
function enableModification(modification_id) {
	$.ajax({
		url: 'index.php?route=extension/modification/enable&token=<?php echo $token; ?>&modification_id=' + modification_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-modification' + modification_id + ' i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-modification' + modification_id).prop('disabled', true);				
		},
		success: function(json) {
			$('.alert').remove();
			
			$('#button-modification' + modification_id + ' i').replaceWith('<i class="icon-remove"></i>');
			$('#button-modification' + modification_id).prop('disabled', false);
						
			if (json['error']) {
				$('.panel').before('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
                $('.panel').before('<div class="alert alert-success"><i class="icon-ok-sign"></i> ' + json['success'] + '</div>');
				
				$('#button-modification' + modification_id).replaceWith('<button id="button-modification' + modification_id + '" class="btn btn-success btn-xs" onclick="disableModification(' + modification_id + ')"><i class="icon-ok"></i> <?php echo $button_enable; ?></button>');
			}
		}
	});
};

function disableModification(modification_id) {
	$.ajax({
		url: 'index.php?route=extension/modification/disable&token=<?php echo $token; ?>&modification_id=' + modification_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-modification' + modification_id + ' i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-modification' + modification_id).prop('disabled', true);		
		},
		success: function(json) {
			$('.alert').remove();
			
			$('#button-modification' + modification_id + ' i').replaceWith('<i class="icon-ok"></i>');
			$('#button-modification' + modification_id).prop('disabled', false);

			if (json['error']) {
				$('.panel').before('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
                $('.panel').before('<div class="alert alert-success"><i class="icon-ok-sign"></i> ' + json['success'] + '</div>');
				
				$('#button-modification' + modification_id).replaceWith('<button id="button-modification' + modification_id + '" class="btn btn-danger btn-xs" onclick="enableModification(' + modification_id + ')"><i class="icon-remove"></i> <?php echo $button_disable; ?></button>');
			}
		}
	});
};
//--></script> 
<?php echo $footer; ?>