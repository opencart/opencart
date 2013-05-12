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
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons">
          <button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <div class="control-label"><span class="required">*</span> <?php echo $entry_name; ?></div>
          <div class="controls">
            <?php foreach ($languages as $language) { ?>
            <input type="text" name="download_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($download_description[$language['language_id']]) ? $download_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
            <?php if (isset($error_name[$language['language_id']])) { ?>
            <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
            <?php } ?>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-filename"><?php echo $entry_filename; ?></label>
          <div class="controls">
            <div class="input-append">
              <input type="text" name="filename" value="<?php echo $filename; ?>" placeholder="<?php echo $entry_filename; ?>" id="input-filename" class="span2" />
              <button type="button" id="button-upload" class="btn" onclick="$('input[name=\'file\']').click();"><i class="icon-upload"></i> <?php echo $button_upload; ?></button>
            </div>
            <a data-toggle="tooltip" title="<?php echo $help_filename; ?>"><i class="icon-info-sign"></i></a>
            <?php if ($error_filename) { ?>
            <span class="error"><?php echo $error_filename; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-mask"><?php echo $entry_mask; ?></label>
          <div class="controls">
            <input type="text" name="mask" value="<?php echo $mask; ?>" placeholder="<?php echo $entry_mask; ?>" id="input-mask" />
            <a data-toggle="tooltip" title="<?php echo $help_mask; ?>"><i class="icon-info-sign"></i></a>
            <?php if ($error_mask) { ?>
            <span class="error"><?php echo $error_mask; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-remaining"><?php echo $entry_remaining; ?></label>
          <div class="controls">
            <input type="text" name="remaining" value="<?php echo $remaining; ?>" id="input-remaining" class="input-small" />
          </div>
        </div>
        <?php if ($download_id) { ?>
        <div class="control-group">
          <label class="control-label" for="input-update"><?php echo $entry_update; ?></label>
          <div class="controls">
            <?php if ($update) { ?>
            <input type="checkbox" name="update" value="1" checked="checked" id="input-update" />
            <?php } else { ?>
            <input type="checkbox" name="update" value="1" id="input-update" />
            <?php } ?>
            <a data-toggle="tooltip" title="<?php echo $help_update; ?>"><i class="icon-info-sign"></i></a> </div>
        </div>
        <?php } ?>
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
        url: 'index.php?route=catalog/download/upload&token=<?php echo $token; ?>',
        type: 'post',		
		dataType: 'json',
		data: new FormData($(this).parent()[0]),
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
				alert(json['error']);
			}
						
			if (json['success']) {
				alert(json['success']);
				
				$('input[name=\'filename\']').attr('value', json['filename']);
				$('input[name=\'mask\']').attr('value', json['mask']);
			}
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