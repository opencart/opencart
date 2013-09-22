<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<script type="text/javascript" src="//code.jquery.com/jquery-2.0.3.min.js"></script>
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
<script src="view/javascript/bootstrap/js/bootstrap.js"></script>
<link rel="stylesheet" href="view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<style type="text/css">
html, body {
	font-size: 12px;
}
#directory {
	height: 300px;
}
#directory ul {
	list-style: none;
	margin: 0;
	padding: 0;
}
#file {
	height: 300px;
}
#file div {
	float: left;
	margin-left: 10px;
}
</style>
</head>
<body>
<header class="navbar navbar-default navbar-static-top">
  <div class="container">
    <button type="button" id="button-upload" class="btn btn-primary navbar-btn"><i class="icon-upload"></i> <?php echo $button_upload; ?></button>
    <div class="btn-group">
      <button type="button" id="button-cut" title="<?php echo $button_cut; ?>" class="btn btn-default navbar-btn"><i class="icon-cut"></i></button>
      <button type="button" id="button-copy" title="<?php echo $button_copy; ?>" class="btn btn-default navbar-btn"><i class="icon-copy"></i></button>
      <button type="button" id="button-paste" title="<?php echo $button_paste; ?>" class="btn btn-default navbar-btn"><i class="icon-paste"></i></button>
    </div>
    <button type="button" id="button-folder" class="btn btn-default"><i class="icon-folder-close"></i> <?php echo $button_folder; ?></button>
    <button type="button" id="button-refresh" class="btn btn-default"><i class="icon-refresh"></i> <?php echo $button_refresh; ?></button>
  </div>
</header>
<div class="container">
  <div class="row">
    <div class="col-xs-3">
      <div id="directory" class="well">
        <ul>
          <li><a href="/"><i class="icon-caret-right"></i> Catalog</a></li>
        </ul>
      </div>
    </div>
    <div class="col-xs-9">
      <table class="table">
        <thead>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
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
<div id="upload" style="display: none;">
  <form enctype="multipart/form-data">
    <input type="file" name="image" id="image" />
    <input type="hidden" name="directory" />
  </form>
</div>
<script type="text/javascript"><!--
$('#directory').delegate('a', 'click', function(e) {
	e.preventDefault();

	var node = this;
	
	// Remove all active classes
	$('#directory li').removeClass('active');

	// Add active class to current node
	$(node).addClass('active');
	
	// If current node is closed we open it.
	if ($(node).find('> i').hasClass('icon-caret-right')) {
		$.ajax({
			url: 'index.php?route=common/filemanager/directory&token=<?php echo $token; ?>',
			type: 'post',
			data: 'directory=' + encodeURIComponent($(node).attr('href')),
			dataType: 'json',
			beforeSend: function() {
				$(node).find('> i').attr('class', 'icon-spinner icon-spin');
			},
			complete: function() {
				$(node).find('> i').attr('class', 'icon-caret-down');
			},
			success: function(json) {
				// Just in case there is already folders being listed under the selected folder we should remove them.
				$(node).parent().find('ul').remove();
				
				// If directories exist
				if (json['directory']) {
					// Add directories to the left column
					html = '<ul>';
					
					for (i = 0; i < json['directory'].length; i++) {
						html += '<li><a href="' + json['directory'][i]['path'] + '"><i class="icon-caret-right"></i> ' + json['directory'][i]['name'] + '</a></li>';
					}
					
					html += '</ul>';
					
					$(node).after(html);
					
					// Add directories to the top of the right column
					html = '';
					
					for (i = 0; i < json['directory'].length; i++) {
						html += '<tr>';
            			html += '  <td class="text-center"><input type="checkbox" name="" value="' + json['file'][i]['path'] + '" /></td>';
						html += '  <td><img src="<?php echo $no_image; ?>" alt="' + json['file'][i]['name'] + '" title="' + json['file'][i]['name'] + '" class="img-thumbnail img-responsive" /></td>';
						html += '  <td>' + json['file'][i]['name'] + '</td>';
						html += '  <td></td>';
						html += '  <td></td>';
						html += '</tr>';						
					}
						
					$('table tbody').html(html);
				}
				
				// Add files to the right column
				if (json['file']) {
					html = '';
					
					for (i = 0; i < json['file'].length; i++) {
						if (json['file'][i]['name'].length > 15) {
							name = json['file'][i]['name'].substr(0, 15) + '..';
						} else {
							name = json['file'][i]['name'];
						}
						
						html += '<tr>';
            			html += '  <td class="text-center"><input type="checkbox" name="" value="' + json['file'][i]['path'] + '" /></td>';
						html += '  <td><img src="<?php echo $no_image; ?>" alt="' + json['file'][i]['name'] + '" title="' + json['file'][i]['name'] + '" class="img-responsive" /></td>';
						html += '  <td>' + json['file'][i]['name'] + '</td>';
						html += '  <td>' + json['file'][i]['size'] + '</td>';
						html += '  <td>' + json['file'][i]['date_added'] + '</td>';
						html += '</tr>';
					}					
					
					$('table tbody').html(html);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	} else {
		$(node).find('> i').attr('class', 'icon-caret-right');
		$(node).parent().find('ul').remove();
	}
});

$('#directory a:first-child').trigger('click');

$('input[type=\'checkbox\']').on('change', function() {
	if (this.value == '') {
		//$('#selected').append('<div class="">' + this.value + '</div>');
	}
});

$('#button-upload').on('click', function() {
	
});

$('#button-folder').on('click', function() {
	
});

$('#button-cut').on('click', function() {
	
});

$('#button-copy').on('click', function() {
	
});

$('#button-paste').on('click', function() {
	
});

$('#button-rename').on('click', function() {
	$('#example').popover(options);
});

$('#button-delete').on('click', function() {
	$.ajax({
		url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
		type: 'post',
		data: 'directory=' + encodeURIComponent($(node).attr('href')),
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
