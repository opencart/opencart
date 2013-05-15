<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-puzzle-piece icon-large"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content form-horizontal">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="button-upload"><?php echo $entry_upload; ?></label>
          <div class="controls">
            <button type="button" id="button-upload" class="btn" onclick="$('input[name=\'file\']').click();"><i class="icon-upload"></i> <?php echo $button_upload; ?></button>

            
            <a data-toggle="tooltip" title="<?php echo $help_upload; ?>"><i class="icon-info-sign"></i></a>
            
            </div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $entry_progress; ?></div>
          <div class="controls">
            <div class="progress">
              <div class="bar" style="width: 60%;"></div>
            </div>
            <div id="output">The Beow file will be over written:
            
            <table>
            <tr><td></td></tr>
            </table>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div style="display: none;">
  <form enctype="multipart/form-data">
    <input type="file" name="file" id="file" />
  </form>
</div>
<script type="text/javascript"><!--
$('#file').on('change', function() {
    $.ajax({
        url: 'index.php?route=extension/installer/upload&token=<?php echo $token; ?>',
        type: 'post',		
		dataType: 'html',
		data: new FormData($(this).parent()[0]),
		beforeSend: function() {
			$('#button-upload i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-upload').prop('disabled', true);
		},	
		complete: function() {
			$('#button-upload i').replaceWith('<i class="icon-upload"></i>');
			$('#button-upload').prop('disabled', false);
		},		
		success: function(html) {
			$('#output').html(html);
			/*
			if (json['error']) {
				$('#output').html(json['error']);
			}
			
			if (json['unzip']) {
				$('#output').html(json['unzip']);
			}
			
			if (json['ftp']) {
				$('#output').html(json['unzip']);
			}			
						
			

						
			if (json['success']) {
				alert(json['success']);
			}
			*/
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		},
        cache: false,
        contentType: false,
        processData: false
    });
});

function unzip(file) {
    $.ajax({
        url: 'index.php?route=extension/installer/upload&token=<?php echo $token; ?>',
        type: 'post',		
		dataType: 'html',
		data: ,
		beforeSend: function() {
			$('#button-upload i').replaceWith('<i class="icon-spinner"></i>');
			$('#button-upload').prop('disabled', true);
		},	
		complete: function() {
			$('#button-upload i').replaceWith('<i class="icon-upload"></i>');
			$('#button-upload').prop('disabled', false);
		},		
		success: function(html) {
			$('#output').html(html);
			
			if (json['error']) {
				$('#output').html(json['error']);
			}
			
			if (json['unzip']) {
				$('#output').html(json['unzip']);
			}
			
			if (json['ftp']) {
				$('#output').html(json['ftp']);
			}			
						
			/*

						
			if (json['success']) {
				alert(json['success']);
			}
			*/
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		},
        cache: false,
        contentType: false,
        processData: false
    });
	
}

function ftp(file) {
	
}

function sql(file) {
	
}

function xml(file) {
	
}
//--></script> 
<?php echo $footer; ?>