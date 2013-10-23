<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title"><?php echo $heading_title; ?></h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-xs-5"><a href="<?php echo $parent; ?>" id="button-parent" class="btn btn-default"><i class="icon-level-up"></i></a>
          <button type="button" id="button-upload" class="btn btn-primary"><i class="icon-upload"></i></button>
          <button type="button" id="button-folder" class="btn btn-default"><i class="icon-folder-close"></i></button>
          <button type="button" id="button-delete" class="btn btn-danger"><i class="icon-trash"></i></button>
        </div>
        <div class="col-sm-7">
          <div class="input-group">
            <input type="text" name="search" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_search; ?>" class="form-control">
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
          <div class="thumbnail">
            <div style="width: 100px; height: 100px; background: #CCC;"> <a href="<?php echo $image['href']; ?>" class="directory"><i class="icon-folder-close" style="font-size: 100px; color: #FFF;"></i><?php echo $image['name']; ?></a></div>
            <div class="caption">
              <p>
                <input type="checkbox" name="delete[]" value="<?php echo $image['path']; ?>" />
                <?php echo $image['name']; ?></p>
            </div>
          </div>
          <?php } ?>
          <?php if ($image['type'] == 'image') { ?>
          <div class="thumbnail"><a href="<?php echo $image['path']; ?>"><img src="<?php echo $image['image']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" /></a>
            <div class="caption">
              <p>
                <input type="checkbox" name="delete[]" value="<?php echo $image['path']; ?>" />
                <?php echo $image['name']; ?></p>
            </div>
          </div>
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
$('#modal-image a.thumbnail').on('click', function(e) {
	e.preventDefault();
	
	alert($(this).attr('href'));
	
	//$('#thumb').attr('src', $(this).find('img').attr('src'));
	
	//$('#input-image').attr('value', $(this).attr('href'));
});

$('#modal-image a.directory').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('#modal-image .pagination a').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('#modal-image #button-parent').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('#modal-image #button-search').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load('index.php?route=common/filemanager&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>&filter_name=' +  encodeURIComponent($('#modal-image input[name=\'search\']').val()));
});

$('#modal-image #button-upload').on('click', function() {
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');
	
	$('#form-upload input[name=\'file\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=common/filemanager/upload&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
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
					
					$('#modal-image').load('index.php?route=common/filemanager&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>');
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});

$('#modal-image #button-folder').popover({
	html: true,
	placement: 'bottom',
	title: '<?php echo $entry_folder; ?>',
	content: function() {
		html  = '<div class="input-group">';
		html += '  <input type="text" name="folder" value="" placeholder="<?php echo $entry_folder; ?>" class="form-control">';
		html += '  <span class="input-group-btn"><button type="button" id="button-create" class="btn btn-primary"><i class="icon-search"></i></button></span>';
		html += '</div>';
		
		return html;
	},
	container: '#modal-image'
});
	
$('#modal-image').delegate('#button-create', 'click', function() {
	alert('hi');
	$.ajax({
		url: 'index.php?route=common/filemanager/folder&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>',
		type: 'post',		
		dataType: 'json',
		data: 'folder=' + encodeURIComponent($('#modal-image input[name=\'folder\']').val()),
		beforeSend: function() {
			$('#modal-image #button-create i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#modal-image #button-create').prop('disabled', true);
		},
		complete: function() {
			$('#modal-image #button-create i').replaceWith('<i class="icon-upload"></i>');
			$('#modal-image #button-create').prop('disabled', false);
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}
			
			if (json['success']) {
				alert(json['success']);
				
				//$('#modal-image').load('index.php?route=common/filemanager&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>');
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});	

$('#modal-image #button-delete').on('click', function(e) {
	e.preventDefault();
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		$.ajax({
			url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
			type: 'post',		
			dataType: 'json',
			data: $('#modal-image input[name^=\'delete\']:checked'),
			beforeSend: function() {
				$('#modal-image #button-delete i').replaceWith('<i class="icon-spinner icon-spin"></i>');
				$('#modal-image #button-delete').prop('disabled', true);
			},	
			complete: function() {
				$('#modal-image #button-delete i').replaceWith('<i class="icon-trash"></i>');
				$('#modal-image #button-delete').prop('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
					
					$('#modal-image').load('index.php?route=common/filemanager&token=<?php echo $token; ?>&directory=<?php echo $directory; ?>');
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>