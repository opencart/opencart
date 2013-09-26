<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<script type="text/javascript" src="//code.jquery.com/jquery-2.0.3.min.js"></script>
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.js"></script>
<link rel="stylesheet" href="view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<style type="text/css">
html, body {
	font-size: 12px;
}
#column-left .well, #column-right .panel {
	min-height: 300px;
	max-height: 300px;
	overflow-y: auto;
}
#column-left ul:first-child {
	list-style: none;
	margin: 0;
	padding-left: 0px;
}
#column-left ul ul {
	list-style: none;
	margin: 0;
	padding-left: 0px;
}
#column-left li a {
	display: block;
	padding: 5px;
	text-decoration: none;
}
#column-left li a.active {
	background: #428bca;
	color: #FFFFFF;
}
#column-left ul ul a {
	padding-left: 20px;
}
#column-left ul ul ul a {
	padding-left: 35px;
}
#column-left ul ul ul ul a {
	padding-left: 50px;
}
#column-left ul ul ul ul a {
	padding-left: 65px;
}
#selected {
	width: 200px;
	max-height: 300px;
	overflow-y: auto;	
}
#selected div {
	overflow: auto;
	padding: 5px 0px;
}
</style>
</head>
<body>
<header class="navbar navbar-default navbar-static-top">
  <div class="container">
    <button type="button" id="button-upload" data-toggle="tooltip" title="<?php echo $button_upload; ?>" class="btn btn-default navbar-btn"><i class="icon-upload"></i></button>
    <button type="button" id="button-folder" data-toggle="tooltip" title="<?php echo $button_folder; ?>" class="btn btn-default navbar-btn"><i class="icon-folder-close"></i></button>
    <div class="btn-group">
      <button type="button" id="button-selected" class="btn btn-default"><?php echo $button_selected; ?> <i class="icon-caret-down"></i></button>
    </div>
  </div>
</header>
<div class="container">
  <div class="row">
    <div id="column-left" class="col-xs-3">
      <div class="well well-sm">
        <ul>
          <li><a href="/"><i class="icon-caret-right icon-fixed-width"></i> Catalog</a></li>
        </ul>
      </div>
    </div>
    <div id="column-right" class="col-xs-9">
      <div class="panel panel-default">
        <table class="table table-hover">
          <thead>
            <tr>
              <td width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td></td>
              <td class="text-left">Name</td>
              <td class="text-left">Type</td>
              <td class="text-left">Size</td>
              <td class="text-left">Date</td>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div id="selected">
  <p class="text-center"><?php echo $text_no_results; ?></p>
</div>
<div id="upload" style="display: none;">
  <form enctype="multipart/form-data">
    <input type="file" name="image" id="image" />
    <input type="hidden" name="directory" />
  </form>
</div>
<script type="text/javascript"><!--
// Set tooltip
$('[data-toggle=\'tooltip\']').tooltip({
	container: 'body',
	placement: 'bottom'
});

$('#column-left').delegate('a', 'click', function(e) {
	e.preventDefault();
	
	var node = this;
		
	// If current node is closed we open it.
	if ($(e.target).hasClass('icon-caret-right')) {
		$.ajax({
			url: 'index.php?route=common/filemanager/directory&token=<?php echo $token; ?>',
			type: 'post',
			data: 'directory=' + encodeURIComponent($(node).attr('href')),
			dataType: 'json',
			beforeSend: function() {
				$(e.target).addClass('icon-spinner icon-spin');
				$(e.target).removeClass('icon-caret-right');
			},
			complete: function() {
				$(e.target).addClass('icon-caret-down');
				$(e.target).removeClass('icon-spinner icon-spin');	
			},
			success: function(json) {
				// Just in case there is already folders being listed under the selected folder we should remove them.
				$(node).parent().find('ul').remove();

				// If directories exist
				if (json['directory']) {
					// Add directories to the left column
					html = '<ul class="icons-ul">';
					
					for (i = 0; i < json['directory'].length; i++) {
						html += '<li><a href="' + json['directory'][i]['path'] + '"><i class="icon-caret-right icon-fixed-width"></i> ' + json['directory'][i]['name'] + '</a></li>';
					}
				
					html += '</ul>';
					
					$(node).after(html);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	} else if ($(e.target).hasClass('icon-caret-down')) {
		$(e.target).removeClass('icon-caret-down');
		$(e.target).addClass('icon-caret-right');		
		$(node).parent().find('ul').remove();
	} else {
		// Remove all active classes
		$('#column-left a').removeClass('active');
	
		// Add active class to current node
		$(node).addClass('active');
		
		// If current node is closed we open it.
		$.ajax({
			url: 'index.php?route=common/filemanager/directory&token=<?php echo $token; ?>',
			type: 'post',
			data: 'directory=' + encodeURIComponent($(node).attr('href')),
			dataType: 'json',
			success: function(json) {
				if (json['directory'] || json['file']) {				
					// Add directories to the top of the right column
					html = '';
					
					for (i = 0; i < json['directory'].length; i++) {
						html += '<tr>';
						html += '  <td class="text-center"><input type="checkbox" value="' + json['directory'][i]['path'] + '" /></td>';
						html += '  <td class="text-center"><i class="icon-folder-close icon-large"></i></td>';
						html += '  <td><a href="' + json['directory'][i]['path'] + '" class="directory">' + json['directory'][i]['name'] + '</a></td>';
						html += '  <td>Directory</td>';
						html += '  <td></td>';
						html += '  <td>' + json['directory'][i]['date'] + '</td>';
						html += '</tr>';						
					}
					
					$('#column-right table tbody').html(html);
					
					// Add files to the right column
					if (json['file']) {
						html = '';
						
						for (i = 0; i < json['file'].length; i++) {
							html += '<tr>';
							html += '  <td class="text-center"><input type="checkbox" value="' + json['file'][i]['path'] + '" /></td>';
							html += '  <td class="text-center"><img src="<?php echo $no_image; ?>" alt="' + json['file'][i]['name'] + '" title="' + json['file'][i]['name'] + '" class="img-responsive" /></td>';
							html += '  <td><a href="' + json['file'][i]['path'] + '">' + json['file'][i]['name'] + '</a></td>';
							html += '  <td>Image</td>';
							html += '  <td>' + json['file'][i]['size'] + '</td>';
							html += '  <td>' + json['file'][i]['date'] + '</td>';
							html += '</tr>';
						}					
						
						$('#column-right table tbody').append(html);
					}
				} else {
					html  = '<tr>';
					html += '  <td colspan="6" class="text-center">No results!</td>';
					html += '</tr>';
					
					$('#column-right table tbody').html(html);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});

$('#column-left a:first').trigger('click');

$('#column-right').delegate('a.directory', 'click', function(e) {
	e.preventDefault();
	
	var node = this;
	
	// Remove all active classes
	$('#column-left a').removeClass('active');
	
	// If current node is closed we open it.
	$.ajax({
		url: 'index.php?route=common/filemanager/directory&token=<?php echo $token; ?>',
		type: 'post',
		data: 'directory=' + encodeURIComponent($(node).attr('href')),
		dataType: 'json',
		success: function(json) {
			if (json['directory'] || json['file']) {				
				// Add directories to the top of the right column
				html = '';
				
				for (i = 0; i < json['directory'].length; i++) {
					html += '<tr>';
					html += '  <td class="text-center"><input type="checkbox" value="' + json['directory'][i]['path'] + '" /></td>';
					html += '  <td class="text-center"><i class="icon-folder-close icon-large"></i></td>';
					html += '  <td><a href="' + json['directory'][i]['path'] + '" class="directory">' + json['directory'][i]['name'] + '</a></td>';
					html += '  <td>Directory</td>';
					html += '  <td></td>';
					html += '  <td>' + json['directory'][i]['date'] + '</td>';
					html += '</tr>';						
				}
				
				$('#column-right table tbody').html(html);
				
				// Add files to the right column
				if (json['file']) {
					html = '';
					
					for (i = 0; i < json['file'].length; i++) {
						html += '<tr>';
						html += '  <td class="text-center"><input type="checkbox" value="' + json['file'][i]['path'] + '" /></td>';
						html += '  <td class="text-center"><img src="<?php echo $no_image; ?>" alt="' + json['file'][i]['name'] + '" title="' + json['file'][i]['name'] + '" class="img-thumbnail" /></td>';
						html += '  <td><a href="' + json['file'][i]['path'] + '">' + json['file'][i]['name'] + '</a></td>';
						html += '  <td>Image</td>';
						html += '  <td>' + json['file'][i]['size'] + '</td>';
						html += '  <td>' + json['file'][i]['date'] + '</td>';
						html += '</tr>';
					}					
					
					$('#column-right table tbody').append(html);
				}
			} else {
				html  = '<tr>';
				html += '  <td colspan="6" class="text-center">No results!</td>';
				html += '</tr>';
				
				$('#column-right table tbody').html(html);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// When selecting in the right column add it to the selected list
$('#column-right').delegate('input[type=\'checkbox\']', 'change', function() {
	$('#selected input[value=\'' + this.value + '\']').parent().remove();
	
	if (this.checked) {
		html = '<div class="alert"><button type="button" class="btn btn-danger btn-sm pull-right" data-toggle="tooltip" title="<?php echo $button_remove; ?>"><i class="icon-minus-sign"></i></button>' + this.value + '<input type="hidden" name="selected[]" value="' + this.value + '" /></div>';
		
		if (!$('#selected input').length) {
			html += '<div class="text-center">';
			html += '  <div class="btn-group">';
			html += '    <button type="button" id="button-move" data-toggle="tooltip" title="<?php echo $button_move; ?>" class="btn btn-default navbar-btn"><i class="icon-move"></i></button>';
			html += '    <button type="button" id="button-copy" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default navbar-btn"><i class="icon-copy"></i></button>';
			html += '  </div>';
			html += '  <button type="button" id="button-delete" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="icon-trash"></i></button>';
			html += '</div>';
			
			$('#selected').html(html);
		} else {
			$('#selected').prepend(html);
		}
	}

	// If no selected items display the empty message
	if (!$('#selected input').length) {
		$('#selected').html('<p class="text-center"><?php echo $text_no_results; ?></p>');
	}
	
	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});		
});

// Display the popover when the selected button is clicked 
$('#button-selected').popover({
	html: true,
	trigger: 'click',
	title: '<?php echo $text_selected; ?>',
	content: $('#selected'),
	placement: 'bottom'
});

// tooltips on hover
$('#button-selected').on('shown.bs.popover', function() {
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});		
})

// Remove items and untick the left column if slected remove button is clicked
$('#selected').delegate('button', 'click', function() {
	// Remove the check if its on in the right column
	$('#column-right input[value=\'' + $(this).parent().find('input').attr('value') + '\']').prop('checked', false);
	
	// Remove item
	$(this).parent().remove();
	
	// If no selected items display the empty message
	if (!$('#selected input').length) {
		$('#selected').html('<p class="text-center"><?php echo $text_no_results; ?></p>');
	}	
});

$('#button-move').on('click', function() {
	// Remove all from copy and paste buttons
	$('#selected input[type=\'checkbox\']:checked');
	
	// Loop through each slected item. If there is an error it should appear and break the loop.
	$.ajax({
		url: 'index.php?route=common/filemanager/' + action + '&token=<?php echo $token; ?>',
		type: 'post',
		data: $('#selected input[name=\'selected\']'),
		dataType: 'json',
		success: function(json) {
			$('#selected input').remove();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}	
	});
});

$('#button-copy').on('click', function() {


});

$('#button-upload').on('click', function() {
	
});

$('#button-folder').on('click', function() {
	html  = '<div class="input-group">';
	html += '  <input type="text" name="rename" value="" class="form-control" />';
	html += '  <span class="input-group-btn"><button class="btn btn-default" type="button">Go!</button></span>';
	html += '</div>';
	
	$(this).popover({
		html: true,
		content: '#selector',
		placement: 'bottom'
	});
});

$('#button-delete').on('click', function() {
	// Loop through each slected item. If there is an error it should appear and break the loop.
	$.ajax({
		url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
		type: 'post',
		data: $('#file input[name=\'selected\']'),
		dataType: 'json',
		success: function(json) {
			if (json['error']) {
				alert(json['error']);	
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}	
	});
});

$('#button-rename').on('click', function() {
	
});
//--></script>
</body>
</html>
