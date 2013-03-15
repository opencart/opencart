
<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td>Your upload file:</td>
            <td><input type="button" value="<?php echo $button_upload; ?>" id="button-upload" class="button" onclick="$('input[name=\'file\']').click();" /></td>
          </tr>
        </table>
         <textarea wrap="off" style="width: 98%; height: 300px; padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF; overflow: scroll;"><?php echo $log; ?></textarea>
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
        url: 'index.php?route=extension/modification/upload&token=<?php echo $token; ?>',
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
			$('textarea').val(html);
			
			/*
			if (json['error']) {
				alert(json['error']);
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



//--></script> 
<?php echo $footer; ?>