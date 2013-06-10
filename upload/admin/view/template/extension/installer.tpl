<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-puzzle-piece icon-large"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content form-horizontal">
      <div class="control-group">
        <label class="control-label" for="button-upload"><?php echo $entry_upload; ?> <span class="help-block"><?php echo $help_upload; ?></span></label>
        <div class="controls">
          <button type="button" id="button-upload" class="btn btn-primary" onclick="$('input[name=\'file\']').val(''); $('input[name=\'file\']').click();"><i class="icon-upload"></i> <?php echo $button_upload; ?></button>
          <?php if ($error_warning) { ?>
          <button type="button" id="button-clear" class="btn btn-danger"><i class="icon-eraser"></i> <?php echo $button_clear; ?></button>
          <?php } else { ?>
          <button type="button" id="button-clear" disabled="disabled" class="btn btn-danger"><i class="icon-eraser"></i> <?php echo $button_clear; ?></button>
          <?php } ?>
        </div>
      </div>
      <div id="progress" class="control-group">
        <div class="control-label"><?php echo $entry_progress; ?></div>
        <div class="controls">
          <div class="progress">
            <div class="bar" style="width: 0%;"></div>
          </div>
          <span class="help-block"></span></div>
      </div>
      <div class="control-group">
        <div class="control-label"><?php echo $entry_overwrite; ?></div>
        <div class="controls">
          <textarea rows="10" readonly="readonly" id="overwrite" class="input-xxlarge"></textarea>
          <br />
          <br />
          <button type="button" id="button-continue" class="btn" disabled="disabled"><i class="icon-ok"></i> <?php echo $button_continue; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<div style="display: none;">
  <form enctype="multipart/form-data">
    <input type="file" name="file" id="file" />
  </form>
</div>
<script type="text/javascript"><!--
var step = new Array();
var total = 0;

$('#file').on('change', function() {
	// Reset everything
	$('.alert').remove();
	$('#progress .bar').css('width', '0%');
	$('#progress').removeClass('error success');
	$('#progress .progress').removeClass('progress-danger, progress-success');		
	$('#progress .help-block').html('');
	
	$.ajax({
        url: 'index.php?route=extension/installer/upload&token=<?php echo $token; ?>',
        type: 'post',		
		dataType: 'json',
		data: new FormData($(this).parent()[0]),
        cache: false,
        contentType: false,
        processData: false,	
		beforeSend: function() {
			$('#button-upload i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-upload').prop('disabled', true);
		},	
		complete: function() {
			$('#button-upload i').replaceWith('<i class="icon-upload"></i>');
			$('#button-upload').prop('disabled', false);
		},		
		success: function(json) {
			if (json['error']) {
				$('#progress').addClass('error');
				$('#progress .progress-danger').addClass('progress-danger');				
				
				$('#progress .help-block').html(json['error']);
			}
			
			if (json['step']) {
				step = json['step'];
				total = step.length;
				
				if (json['overwrite'].length) {
					html = '';
					
					for (i = 0; i < json['overwrite'].length; i++) {
						html += json['overwrite'][i] + "\n";
					}
					
					$('#overwrite').html(html);
					
					$('#button-continue').prop('disabled', false);
				} else {
					next();
				}				
			}	
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
    });
});

$('#button-continue').on('click', function() {
	next();
	
	$('#button-continue').prop('disabled', true);
});

function next() {
	data = step.shift();
	
	if (data) {
		$('#progress .bar').css('width', (100 - (step.length / total) * 100) + '%');
		$('#progress .help-block').html(data.text);
		
		$.ajax({
			url: data.url,
			type: 'post',		
			dataType: 'json',
			data: 'path=' + data.path,
			success: function(json) {
				if (json['error']) {
					$('#progress').addClass('error');
					$('#progress .progress').addClass('progress-danger');
					
					$('#progress .help-block').html(json['error']);
					
					$('#button-clear').prop('disabled', false);
				} 
				
				if (json['success']) {
					$('#progress').addClass('success');
					$('#progress .progress').addClass('progress-success');
					
					$('#progress .help-block').html(json['success']);
				}
									
				if (!json['error'] && !json['success']) {
					next();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

$('#button-clear').bind('click', function() {
	$.ajax({
		url: 'index.php?route=extension/installer/clear&token=<?php echo $token; ?>',	
		dataType: 'json',
		beforeSend: function() {
			$('#button-clear i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-clear').prop('disabled', true);
		},	
		complete: function() {
			$('#button-clear i').replaceWith('<i class="icon-eraser"></i>');
		},		
		success: function(json) {
			$('.alert').remove();
				
			if (json['error']) {
				$('.box').before('<div class="alert alert-error">' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} 
		
			if (json['success']) {
				$('.box').before('<div class="alert alert-success">' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('#button-clear').prop('disabled', true);
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script> 
<?php echo $footer; ?>