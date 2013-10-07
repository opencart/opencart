<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.0.3.min.js"></script>
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
	max-height: 150px;
	overflow-y: auto;
}
#selected > div {
	overflow: auto;
	padding: 5px;
}
</style>
</head>
<body>
<header class="navbar navbar-default navbar-static-top">
  <div class="container">
    <button type="button" id="button-upload" data-toggle="tooltip" title="<?php echo $button_upload; ?>" class="btn btn-default navbar-btn"><i class="icon-upload"></i></button>
    <button type="button" id="button-folder" data-toggle="tooltip" title="<?php echo $button_folder; ?>" class="btn btn-default navbar-btn"><i class="icon-folder-close"></i></button>
    <input type="hidden" name="folder" value="" />
  </div>
</header>
<div class="container">
  <div class="row">
    <div id="column-left" class="col-xs-3">
      <div class="well well-sm">
        <ul>
          <li><a href=""><i class="icon-caret-right icon-fixed-width"></i> Catalog</a></li>
        </ul>
      </div>
    </div>
    <div id="column-right" class="col-xs-9">
      <div class="panel panel-default">
        <table class="table table-hover">
          <thead>
            <tr>
              <td></td>
              <td class="text-left"><?php echo $column_name; ?></td>
              <td class="text-left"><?php echo $column_size; ?></td>
              <td class="text-left"><?php echo $column_date; ?></td>
              <td class="text-left"></td>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div id="upload" style="display: none;">
  <form enctype="multipart/form-data">
    <input type="file" name="image" id="image" />
    <input type="hidden" name="directory" />
  </form>
</div>
<script type="text/javascript"><!--
// Set tooltip
$('header [data-toggle=\'tooltip\']').tooltip({
	container: 'header',
	placement: 'bottom'
});

var selected = new Array();

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
						if (json['directory'][i]['path']) {
							html += '<li><a href="' + json['directory'][i]['path'] + '"><i class="icon-caret-right icon-fixed-width"></i> ' + json['directory'][i]['name'] + '</a></li>';
						}
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
		// Set the current folder
		$('input[name=\'folder\']').attr('value', $(node).attr('href'));
		
		// Remove all active classes
		$('#column-left a').removeClass('active');
		
		// Add active class to current node
		$('#column-left a[href=\'' + $(node).attr('href') + '\']').addClass('active');

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
						if (json['directory'][i]['name'] == '..') {
							html += '<tr>';
							html += '  <td class="text-center"><i class="icon-level-up icon-large"></i></td>';
							html += '  <td><a href="' + json['directory'][i]['path'] + '" class="directory">' + json['directory'][i]['name'] + '</a></td>';
							html += '  <td></td>';
							html += '  <td></td>';
							html += '  <td></td>';
							html += '</tr>';	
						} else {
							html += '<tr>';
							html += '  <td class="text-center"><i class="icon-folder-close-alt icon-large"></i></td>';
							html += '  <td><a href="' + json['directory'][i]['path'] + '" class="directory">' + json['directory'][i]['name'] + '</a></td>';
							html += '  <td></td>';
							html += '  <td>' + json['directory'][i]['date'] + '</td>';
							html += '  <td><div class="btn-group">';
							html += '    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <i class="icon-caret-down"></i></button>';
							html += '    <ul class="dropdown-menu">';
							html += '      <li><a href=""><i class="icon-pencil"></i> Rename</a></li>';
							html += '      <li><a href=""><i class="icon-copy"></i> Copy</a></li>';
							html += '      <li><a href=""><i class="icon-trash"></i> Delete</a></li>';
							html += '    </ul>';
							html += '  </div></td>';
							html += '</tr>';
						}
					}
					
					$('#column-right table tbody').html(html);
					
					// Add files to the right column
					if (json['file']) {
						html = '';
						
						for (i = 0; i < json['file'].length; i++) {
							html += '<tr>';
							html += '  <td class="text-center"><i class="icon-file-alt icon-large"></i></td>';
							html += '  <td><a href="' + json['file'][i]['path'] + '">' + json['file'][i]['name'] + '</a></td>';
							html += '  <td>' + json['file'][i]['size'] + '</td>';
							html += '  <td>' + json['file'][i]['date'] + '</td>';
							html += '  <td><div class="btn-group">';
							html += '    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <i class="icon-caret-down"></i></button>';
							html += '    <ul class="dropdown-menu">';
							html += '      <li><a href=""><i class="icon-pencil"></i> Rename</a></li>';
							html += '      <li><a href=""><i class="icon-copy"></i> Copy</a></li>';
							html += '      <li><a href=""><i class="icon-trash"></i> Delete</a></li>';
							html += '    </ul>';
							html += '  </div></td>';							
							html += '</tr>';
						}					
						
						$('#column-right table tbody').append(html);
					}
				} else {
					html  = '<tr>';
					html += '  <td colspan="5" class="text-center">No results!</td>';
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
			
	// Set the current folder
	$('input[name=\'folder\']').attr('value', $(node).attr('href'));

	// Remove all active classes
	$('#column-left a').removeClass('active');
	
	// Add active class to current node
	$('#column-left a[href=\'' + $(node).attr('href') + '\']').addClass('active');
			
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
					// If link is to previous directory
					if (json['directory'][i]['name'] == '..') {
						html += '<tr>';
						html += '  <td class="text-center"><i class="icon-level-up icon-large"></i></td>';
						html += '  <td><a href="' + json['directory'][i]['path'] + '" class="directory">' + json['directory'][i]['name'] + '</a></td>';
						html += '  <td></td>';
						html += '  <td></td>';
						html += '  <td></td>';
						html += '</tr>';	
					} else {
						html += '<tr>';
						html += '  <td class="text-center"><i class="icon-folder-close-alt icon-large"></i></td>';
						html += '  <td><a href="' + json['directory'][i]['path'] + '" class="directory">' + json['directory'][i]['name'] + '</a></td>';
						html += '  <td></td>';
						html += '  <td>' + json['directory'][i]['date'] + '</td>';
						html += '  <td><div class="btn-group">';
						html += '    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <i class="icon-caret-down"></i></button>';
						html += '    <ul class="dropdown-menu">';
						html += '      <li><a href=""><i class="icon-pencil"></i> Rename</a></li>';
						html += '      <li><a href=""><i class="icon-copy"></i> Copy</a></li>';
						html += '      <li><a href=""><i class="icon-trash"></i> Delete</a></li>';
						html += '    </ul>';
						html += '  </div></td>';					
						html += '</tr>';						
					}
				}
				
				$('#column-right table tbody').html(html);
				
				// Add files to the right column
				if (json['file']) {
					html = '';
					
					for (i = 0; i < json['file'].length; i++) {
						html += '<tr>';
						html += '  <td class="text-center"><i class="icon-file-alt icon-large"></i></td>';
						html += '  <td><a href="' + json['file'][i]['path'] + '">' + json['file'][i]['name'] + '</a></td>';
						html += '  <td>' + json['file'][i]['size'] + '</td>';
						html += '  <td>' + json['file'][i]['date'] + '</td>';
						html += '  <td><div class="btn-group">';
						html += '    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <i class="icon-caret-down"></i></button>';
						html += '    <ul class="dropdown-menu">';
						html += '      <li><a href=""><i class="icon-pencil"></i> Rename</a></li>';
						html += '      <li><a href=""><i class="icon-copy"></i> Copy</a></li>';
						html += '      <li><a href=""><i class="icon-trash"></i> Delete</a></li>';
						html += '    </ul>';
						html += '  </div></td>';					
						html += '</tr>';
					}					
					
					$('#column-right table tbody').append(html);
				}
			} else {
				html  = '<tr>';
				html += '  <td colspan="5" class="text-center">No results!</td>';
				html += '</tr>';
				
				$('#column-right table tbody').html(html);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-upload').on('click', function() {
	
});

$('#button-folder').on('click', function() {
	$(this).popover({
		html: true,
		content: function () {
			html  = '<div class="input-group">';
			html += '  <input type="text" name="rename" value="" class="form-control" />';
			html += '  <span class="input-group-btn"><button type="button" id="button-rename" class="btn btn-default">Go!</button></span>';
			html += '</div>';
			
			return html;			
		},
		placement: 'bottom'
	});
});

$('#button-rename').on('click', function() {
	
});



// Remove items and untick the left column if slected remove button is clicked
$('header').delegate('#selected button', 'click', function() {
	var index = selected.indexOf($(this).parent().find('input').attr('value'));
	
	// Remove from the array
	if (index != -1) {
		selected.splice(index, 1);
	}
	
	// Remove the check if its on in the right column
	$('#column-right input[value=\'' + $(this).parent().find('input').attr('value') + '\']').prop('checked', false);
	
	// Remove item
	$(this).parent().remove();
	
	// If no selected items display the empty message
	if (!selected.length) {
		$('#selected').html('<p class="text-center"><?php echo $text_no_results; ?></p>');
	}
});

// Move selected items	
$('header').delegate('#button-move', 'click', function() {
	// Remove all from copy and paste buttons
	var node = $('#selected div:first input');
	
	// Loop through each slected item. If there is an error it should appear and break the loop.
	if (node) {
		$.ajax({
			url: 'index.php?route=common/filemanager/move&token=<?php echo $token; ?>',
			type: 'post',
			data: 'from=' + encodeURIComponent(node.attr('value')) + '&to=' + encodeURIComponent($('input[name=\'folder\']').attr('value')),
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				} else {
					$(node).parent().find('button').trigger('click');
					
					$('#button-move').trigger('click');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}	
		});
	}
});

// Copy selected items	
$('header').delegate('#button-copy', 'click', function() {
	// Remove all from copy and paste buttons
	var node = $('#selected div:first input');
	
	// Loop through each slected item. If there is an error it should appear and break the loop.
	$.ajax({
		url: 'index.php?route=common/filemanager/copy&token=<?php echo $token; ?>',
		type: 'post',
		data: 'from=' + encodeURIComponent(node.attr('value')) + '&to=' + encodeURIComponent($('input[name=\'folder\']').attr('value')),
		dataType: 'json',
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			} else {    				
				$(node).parent().find('button').trigger('click');
					
				$('#button-copy').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}	
	});
});

$('header').delegate('#button-delete', 'click', function() {
	// Remove all from copy and paste buttons
	var node = $('#selected div:first input');
	
	// Loop through each slected item. If there is an error it should appear and break the loop.
	$.ajax({
		url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
		type: 'post',
		data: 'path=' + encodeURIComponent(node.attr('value')),
		dataType: 'json',
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			} else {
				$(node).parent().find('button').trigger('click');
				
				$('#button-delete').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}	
	});
});
//--></script>
</body>
</html>