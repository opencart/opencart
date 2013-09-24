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
#directory, #file {
	min-height: 300px;
	max-height: 300px;
	overflow-y: auto;
}
#directory ul:first-child {
	list-style: none;
	margin: 0;
	padding-left: 0px;
}
#directory ul ul {
	list-style: none;
	margin: 0;	
	padding-left: 0px;
}
#directory li a {
	display: block;
	padding: 5px;
	text-decoration: none;
}
#directory li a.active {
	background: #428bca;
	color: #FFFFFF;
}
#directory ul ul a {
	padding-left: 20px;
}
#directory ul ul ul a {
	padding-left: 40px;
}
#directory ul ul ul ul a {
	padding-left: 60px;
}
#directory ul ul ul ul a {
	padding-left: 80px;
}

</style>
</head>
<body>
<header class="navbar navbar-default navbar-static-top">
  <div class="container">
    <button type="button" id="button-upload" data-toggle="tooltip" title="<?php echo $button_upload; ?>" class="btn btn-default navbar-btn"><i class="icon-upload"></i></button>
    <button type="button" id="button-folder" data-toggle="tooltip" title="<?php echo $button_folder; ?>" class="btn btn-default navbar-btn"><i class="icon-folder-close"></i></button>
    
    <div class="btn-group" data-toggle="buttons">
      <button type="button" id="button-cut" data-toggle="tooltip" title="<?php echo $button_cut; ?>" class="btn btn-default navbar-btn"><i class="icon-cut"></i></button>
      
      <button type="button" id="button-copy" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default navbar-btn"><i class="icon-copy"></i></button>
     
      <button type="button" id="button-paste" data-toggle="tooltip" title="<?php echo $button_paste; ?>" class="btn btn-default navbar-btn"><i class="icon-paste"></i></button>
    </div>
    
    <button type="button" id="button-delete" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="icon-trash"></i></button>
    <button type="button" id="button-refresh" class="btn btn-default"><i class="icon-refresh"></i> <?php echo $button_refresh; ?></button>
  </div>
</header>
<div class="container">
  <div class="row">
    <div class="col-xs-3">
      <div id="directory" class="well well-sm">
        <ul>
          <li><a href="/"><i class="icon-caret-right icon-fixed-width"></i> Catalog</a></li>
        </ul>
      </div>
    </div>
    <div class="col-xs-9">
      <div id="file" class="panel panel-default">
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
<div id="selected" style="display: none;">


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

$('#directory').delegate('a', 'click', function(e) {
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
		$('#directory a').removeClass('active');
	
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
						html += '  <td class="text-center"><input type="checkbox" name="selected" value="' + json['directory'][i]['path'] + '" /></td>';
						html += '  <td class="text-center"><i class="icon-folder-close icon-large"></i></td>';
						html += '  <td><a href="' + json['directory'][i]['path'] + '">' + json['directory'][i]['name'] + '</a></td>';
						html += '  <td>Directory</td>';
						html += '  <td></td>';
						html += '  <td>' + json['directory'][i]['date'] + '</td>';
						html += '</tr>';						
					}
					
					$('table tbody').html(html);
					
					// Add files to the right column
					if (json['file']) {
						html = '';
						
						for (i = 0; i < json['file'].length; i++) {
							html += '<tr>';
							html += '  <td class="text-center"><input type="checkbox" name="selected" value="' + json['file'][i]['path'] + '" /></td>';
							html += '  <td class="text-center text-danger"><i class="icon-picture icon-large"></i></td>';
							html += '  <td><a href="' + json['file'][i]['path'] + '">' + json['file'][i]['name'] + '</a></td>';
							html += '  <td>Image</td>';
							html += '  <td>' + json['file'][i]['size'] + '</td>';
							html += '  <td>' + json['file'][i]['date'] + '</td>';
							html += '</tr>';
						}					
						
						$('table tbody').append(html);
					}
				} else {
					html  = '<tr>';
					html += '  <td colspan="6" class="text-center">No results!</td>';
					html += '</tr>';
					
					$('table tbody').html(html);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});

$('#directory a:first').trigger('click');




$('#button-upload').on('click', function() {
	
});

$('#button-folder').on('click', function() {
	html  = '<div class="input-group">';
	html += '  <input type="text" name="rename" value="" class="form-control" />';
	html += '  <span class="input-group-btn"><button class="btn btn-default" type="button">Go!</button></span>';
	html += '</div>';
	
	$(this).popover({
		html: true,
		content: html,
		placement: 'bottom'
	});
});

$('#button-cut').on('click', function() {
	$('#selected input').remove();
	
	element = $('#file input[name=\'selected\']:checked');
	
	if (element.length) {
		
		
		
		$(this).parent().find('button').removeClass('active');
		
		var html = '';
		
		$('#file input[name=\'selected\']:checked').each(function(index, element) {
			html += '<input type="hidden" name="selected[]" value="' + element.value + '" />';
		});
	}
	
	var html = '';
	
	$('#selected').append();
});

$('#button-copy').on('click', function() {
	element = $('#file input[name=\'selected\']:checked');
	
	if (element.length) {
		$(this).parent().find('button').removeClass('active');
		
		$('#file input[name=\'selected\']:checked').each(function(index, element) {
			$('#selected').append('<input type="hidden" name="selected[]" value="' + element.value + '" />');
		});	
	} else {
		$(this).parent().find('button').removeClass('active');
	}
});

$('#button-paste').on('click', function() {
	// Remove all from copy and paste buttons
	
	//if () {
		
	//}
	
	$.ajax({
		url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
		type: 'post',
		data: $('#selected input[name=\'selected\']'),
		dataType: 'json',
		success: function(json) {

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}	
	});	
});

$('#button-rename').on('click', function() {
	
});

$('#button-delete').on('click', function() {
	$.ajax({
		url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
		type: 'post',
		data: $('#file input[name=\'selected\']'),
		dataType: 'json',
		success: function(json) {
			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}	
	});		
});
//--></script>
</body>
</html>
