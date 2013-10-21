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
        <button type="submit" form="form-image" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-image" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
          <div class="col-sm-10">
            <?php foreach ($languages as $language) { ?>
            <div class="input-group"> <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
              <input type="text" name="image_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($image_description[$language['language_id']]) ? $image_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
            </div>
            <?php if (isset($error_name[$language['language_id']])) { ?>
            <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
            <?php } ?>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
          <div class="col-sm-10"><img src="<?php echo $thumb; ?>" alt="" id="thumb" class="img-thumbnail" /></div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-filename"><?php echo $entry_filename; ?></label>
          <div class="col-sm-10">
            <div class="input-group">
              <input type="text" name="filename" value="<?php echo $filename; ?>" placeholder="<?php echo $entry_filename; ?>" id="input-filename" class="form-control" />
              <span class="input-group-btn">
              <button type="button" id="button-upload" class="btn btn-primary"><i class="icon-upload"></i> <?php echo $button_upload; ?></button>
              </span></div>
            <span class="help-block"><?php echo $help_filename; ?></span></div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-tag"><?php echo $entry_tag; ?></label>
          <div class="col-sm-10">
            <textarea name="tag" rows="5" placeholder="<?php echo $entry_tag; ?>" id="input-tag" class="form-control"><?php echo $tag; ?></textarea>
            <span class="help-block"><?php echo $help_tag; ?></span>
            <?php if ($error_tag) { ?>
            <div class="text-danger"><?php echo $error_tag; ?></div>
            <?php } ?>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-upload').on('click', function() {
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');
	
	$('#form-upload input[name=\'file\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=catalog/image/upload&token=<?php echo $token; ?>',
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
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
					
					$('#thumb').attr('src', json['thumb']);
					$('input[name=\'filename\']').attr('value', json['filename']);
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});
//--></script> 
<?php echo $footer; ?>