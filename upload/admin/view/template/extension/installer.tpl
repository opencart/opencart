<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class=""></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <table class="form">
        <tr>
          <td><?php echo $entry_upload; ?></td>
          <td><button type="button" id="button-upload" class="btn" onclick="$('input[name=\'file\']').click();"><i class="icon-upload"></i> <?php echo $button_upload; ?></button></td>
        </tr>
        <tr>
          <td><?php echo $entry_progress; ?></td>
          <td><div id="progress" style="border: 1px solid #CCC; width: 100%;">
              <div style="width: 0%; height: 20px; margin: 2px; background: #F00;"></div>
            </div>
            <div id="output"></div></td>
        </tr>
      </table>
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
			$('#button-upload').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
			$('#button-upload').attr('disabled', true);
		},	
		complete: function() {
			$('.loading').remove();
			$('#button-upload').attr('disabled', false);
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
				$('#output').html(json['unzip']);
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
});

function unzip(file) {
	
}

function ftp(file) {
	
}

function sql(file) {
	
}

function xml(file) {
	
}
//--></script> 
<?php echo $footer; ?>