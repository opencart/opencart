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
        <label class="control-label" for="button-upload"><?php echo $entry_upload; ?></label>
        <div class="controls">
          <button type="button" id="button-upload" class="btn" onclick="$('input[name=\'file\']').click();"><i class="icon-upload"></i> <?php echo $button_upload; ?></button>
          <a data-toggle="tooltip" title="<?php echo $help_upload; ?>"><i class="icon-info-sign"></i></a>
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
var progress = 0;

$('#file').on('change', function() {
	$('#button-continue').prop('disabled', true);
	
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
			//$('body').append(json);
			
			$('#progress').removeClass('error');
			$('#progress').removeClass('success');
			$('#progress .progress').removeClass('progress-danger');		
			$('#progress .progress').removeClass('progress-success');
			
			if (json['error']) {
				$('#progress').addClass('error');
				$('#progress .progress-danger').addClass('progress-danger');				
				$('#progress .help-block').html(json['error']);
			}
			
			if (json['step']) {
				step = json['step'];
				progress = step.length;
			}	
						
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
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
    });
});

$('#button-continue').on('click', function() {
	next();
});

function next() {
	if (data = step.shift()) {
		$('#progress .bar').css('width', (100 - (step.length / progress) * 100) + '%');
		$('#progress .help-block').html(data.text);
		
		$.ajax({
			url: data.url,
			type: 'post',		
			dataType: 'html',
			data: 'path=' + data.path,
			success: function(json) {
				
				
				$('body').append(json);
				
				if (json['error']) {
					$('#progress').addClass('error');
					$('#progress .progress').addClass('progress-danger');
					$('#progress .help-block').html(json['error']);
				} else {
					
				}
				
				if (!step.length) {
					$('#progress').addClass('success');
					$('#progress .progress').addClass('progress-success');
					$('#progress .help-block').html(json['success']);
				}				
				
				next();
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				//$('#progress').addClass('error');
				//$('#progress .progress').addClass('progress-danger');
				//$('#progress .help-block').html(thrownError);
				
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}
//--></script> 
<?php echo $footer; ?>