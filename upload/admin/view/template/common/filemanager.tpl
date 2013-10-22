<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title"><?php echo $heading_title; ?></h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-xs-5">
          <button type="button" id="button-parent" class="btn btn-default"><i class="icon-level-up"></i></button>
          <button type="button" id="button-upload" class="btn btn-primary"><i class="icon-upload"></i></button>
          <button type="button" id="button-folder" class="btn btn-default"><i class="icon-folder-close"></i></button>
          <button type="button" id="button-delete" class="btn btn-danger"><i class="icon-trash"></i></button>
        </div>
        <div class="col-sm-7">
          <div class="input-group">
            <input type="text" name="search" value="" placeholder="<?php echo $entry_search; ?>" class="form-control">
            <span class="input-group-btn">
            <button type="button" id="button-search" class="btn btn-primary"><i class="icon-search"></i></button>
            </span></div>
        </div>
      </div>
      <hr />
      
      <?php foreach (array_chunk($images, 4) as $image) { ?>
      <div class="row">
        <?php foreach ($image as $image) { ?>
        
        <div class="col-sm-3">
        
          <?php if ($image['type'] == 'directory') { ?>
          <a href="<?php echo $image['path']; ?>" class="directory"><?php echo $image['name']; ?></a>
          <?php } ?>
          
          <?php if ($image['type'] == 'image') { ?>
          <a href="<?php echo $image['path']; ?>" class="thumbnail"><img src="<?php echo $image['image']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" /></a>
          <?php } ?>
                    
        </div>
          
        <?php } ?>
      </div>
      <br />
      <?php } ?>
    </div>
    <div class="modal-footer"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
$('#modal-image a.directory').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load('index.php?route=common/filemanager&token=<?php echo $token; ?>&directory=' + encodeURIComponent($(this).attr('href')));
});

$('#modal-image .pagination a').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('#modal-image a.thumbnail').on('click', function(e) {
	e.preventDefault();
	
	//$('#thumb').attr('src', $(this).find('img').attr('src'));
	
	//$('#input-image').attr('value', $(this).attr('href'));
});

$('#modal-image #button-upload').on('click', function() {
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');
	
	$('#form-upload input[name=\'file\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=common/filemanager/upload&token=<?php echo $token; ?>&directory=',
			type: 'post',		
			dataType: 'json',
			data: new FormData($(this).parent()[0]),
			cache: false,
			contentType: false,
			processData: false,		
			beforeSend: function() {
				$('#modal-image #button-upload i').replaceWith('<i class="icon-spinner icon-spin"></i>');
				$('#modal-image #button-upload').prop('disabled', true);
			},	
			complete: function() {
				$('#modal-image #button-upload i').replaceWith('<i class="icon-upload"></i>');
				$('#modal-image #button-upload').prop('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});

$('#modal-image #button-folder').on('click', function() {
	html  = '<div class="input-group">';
	html += '  <input type="text" name="older" value="" placeholder="<?php echo $entry_folder; ?>" class="form-control">';
	html += '  <span class="input-group-btn"><button type="button" id="button-create" class="btn btn-primary"><i class="icon-search"></i></button></span>';
	html += '</div>';
	
	$(this).popover({
		html: true,
		placement: 'bottom',
		title: '<?php echo $entry_folder; ?> <button type="button" class="close" data-dismiss="#button-folder">&times;</button>',
		content: html,
		container: '#modal-image'
	});
	
	$(this).popover('show');
	
	$('#modal-image #button-create').on('click', function() {
		$.ajax({
			url: 'index.php?route=common/filemanager/folder&token=<?php echo $token; ?>&directory=',
			type: 'post',		
			dataType: 'json',
			data: new FormData($(this).parent()[0]),
			cache: false,
			contentType: false,
			processData: false,		
			beforeSend: function() {
				$('#modal-image #button-upload i').replaceWith('<i class="icon-spinner icon-spin"></i>');
				$('#modal-image #button-upload').prop('disabled', true);
			},	
			complete: function() {
				$('#modal-image #button-upload i').replaceWith('<i class="icon-upload"></i>');
				$('#modal-image #button-upload').prop('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});	
});

$('#modal-image #button-delete').on('click', function() {

});

$('#modal-image #button-search').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load('index.php?route=common/filemanager&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($('input-search').attr('value')));
});
//--></script> 
