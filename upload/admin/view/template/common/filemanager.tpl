<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<script type="text/javascript" src="//code.jquery.com/jquery-2.0.0.min.js"></script>
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
<link href="view/javascript/bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
<script src="view/javascript/bootstrap/js/bootstrap.js"></script>
<link rel="stylesheet" href="view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<style type="text/css">
#directory, ul {
	list-style: none;	
}
</style>
</head>
<body>
<div class="container-fluid">
<div id="menu">
  <div class="row">
    <div class="span3">
      <div class="well well-small">
        <ul id="directory">
          <li><a href="#"><i class="icon-folder-close"></i> Image <i class="icon-edit pull-right"></i> <i class="icon-remove pull-right"></i></a></li>
        </ul>
      </div>
    </div>
    <div class="span9">
      <div class="well well-small">
        <ul id="files" class="thumbnails">
        </ul>
      </div>
    </div>
  </div>
  
    <button id="button-create" class="btn"><i class="icon-folder-close"></i> <?php echo $button_folder; ?></button>
    <button id="button-delete" class="btn"><i class="icon-trash"></i> <?php echo $button_delete; ?></button>
    <button id="button-upload" class="btn"><i class="icon-upload"></i> <?php echo $button_upload; ?></button>
    <button id="button-refresh" class="btn"><i class="icon-refresh"></i> <?php echo $button_refresh; ?></button>
    <div class="btn-group">
    <button id="button-move" class="btn"><i class="icon-remove-sign"></i> <?php echo $button_move; ?></button>
    <button id="button-copy" class="btn"><i class="icon-copy"></i> <?php echo $button_copy; ?></button>
    <button id="button-rename" class="btn"><i class="icon-edit"></i> <?php echo $button_rename; ?></button>
   </div>

  
</div>
<div id="upload">
  <form enctype="multipart/form-data">
    <input type="file" name="image" id="image" />
    <input type="hidden" name="directory" />
  </form>
</div>
<script type="text/javascript"><!--
$('#directory').delegate('a .icon-folder-close', 'click', function(e) {
	event.preventDefault();

	var node = this;
	
	$('#directory li').removeClass('active');
	
	$(node).parent().parent().addClass('active');
	
	if ($(this).find('> .icon-folder-close').hasClass('icon-folder-close')) {
		$.ajax({
			url: 'index.php?route=common/filemanager/directory&token=<?php echo $token; ?>',
			type: 'post',
			data: 'directory=' + encodeURIComponent($(node).attr('href')),
			dataType: 'json',
			beforeSend: function() {
				$(node).after('<i class="icon-spinner icon-spin"></i>');
			},
			complete: function() {
				$('.icon-spinner').remove();
			},
			success: function(json) {
				$(node).find('> .icon-folder-close').attr('class', 'icon-folder-open');
				$(node).parent().find('ul').remove();
				
				if (json) {
					html = '<ul>';
					
					for (i = 0; i < json.length; i++) {
						html += '<li><a href="' + json[i]['directory'] + '"><i class="icon-folder-close"></i> ' + json[i]['name'] + '</a> </li>';
					}
					
					html += '</ul>';
					
					$(node).after(html);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}	
		});
	} else {
		$(node).find('> .icon-folder-open').attr('class', 'icon-folder-close');
		$(node).parent().find('ul').remove();
	}
});



/*
$.ajax({
	url: 'index.php?route=common/filemanager/files&token=<?php echo $token; ?>',
	type: 'post',
	//data: 'directory=' + encodeURIComponent($(NODE).attr('directory')),
	dataType: 'json',
	success: function(json) {
		if (json) {
			html = '';
			
			for (i = 0; i < json.length; i++) {
				html += '<li class="span2"><div class="thumbnail"><a><img src="<?php echo $no_image; ?>" alt="" title="" class="" /></a><p>' + ((json[i]['filename'].length > 15) ? (json[i]['filename'].substr(0, 15) + '..') : json[i]['filename']) + '<br />' + json[i]['size'] + ' <i class="icon-edit"></i> <i class="icon-remove"></i></p></div></li>';
			}
			
			$('#files').html(html);
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}	
});
	

 <input type="hidden" name="image" value="' + json[i]['file'] + '" />
				$.ajax({
					url: 'index.php?route=common/filemanager/files&token=<?php echo $token; ?>',
					type: 'post',
					data: 'directory=' + encodeURIComponent($(NODE).attr('directory')),
					dataType: 'json',
					success: function(json) {
						
						
						if (json) {
							for (i = 0; i < json.length; i++) {
								html += '<a><img src="<?php echo $no_image; ?>" alt="" title="" /><br />' + ((json[i]['filename'].length > 15) ? (json[i]['filename'].substr(0, 15) + '..') : json[i]['filename']) + '<br />' + json[i]['size'] + '<input type="hidden" name="image" value="' + json[i]['file'] + '" /></a>';
							}
						}
						
						html += '</div>';
						
						

						$('#column-right').trigger('scrollstop');	
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
*/
/*
$(document).ready(function() { 
	(function(){
		var special = jQuery.event.special,
			uid1 = 'D' + (+new Date()),
			uid2 = 'D' + (+new Date() + 1);
	 
		special.scrollstart = {
			setup: function() {
				var timer,
					handler =  function(evt) {
						var _self = this,
							_args = arguments;
	 
						if (timer) {
							clearTimeout(timer);
						} else {
							evt.type = 'scrollstart';
							jQuery.event.handle.apply(_self, _args);
						}
	 
						timer = setTimeout( function(){
							timer = null;
						}, special.scrollstop.latency);
	 
					};
	 
				jQuery(this).on('scroll', handler).data(uid1, handler);
			},
			teardown: function(){
				jQuery(this).off( 'scroll', jQuery(this).data(uid1) );
			}
		};
	 
		special.scrollstop = {
			latency: 300,
			setup: function() {
				var timer,
						handler = function(evt) {
	 
						var _self = this,
							_args = arguments;
	 
						if (timer) {
							clearTimeout(timer);
						}
	 
						timer = setTimeout( function(){
	 
							timer = null;
							evt.type = 'scrollstop';
							jQuery.event.handle.apply(_self, _args);
	 
						}, special.scrollstop.latency);
	 
					};
	 
				jQuery(this).on('scroll', handler).data(uid2, handler);
	 
			},
			teardown: function() {
				jQuery(this).off('scroll', jQuery(this).data(uid2));
			}
		};
	})();
	
	$('#column-right').on('scrollstop', function() {
		$('#column-right a').each(function(index, element) {
			var height = $('#column-right').height();
			var offset = $(element).offset();
						
			if ((offset.top > 0) && (offset.top < height) && $(element).find('img').attr('src') == '<?php echo $no_image; ?>') {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent('data/' + $(element).find('input[name=\'image\']').attr('value')),
					dataType: 'html',
					success: function(html) {
						$(element).find('img').replaceWith('<img src="' + html + '" alt="" title="" />');
					}
				});
			}
		});
	});
	
	$('#column-left').tree({
		data: { 
			type: 'json',
			async: true, 
			opts: { 
				method: 'post', 
				url: 'index.php?route=common/filemanager/directory&token=<?php echo $token; ?>'
			} 
		},
		selected: 'top',
		ui: {		
			theme_name: 'classic',
			animation: 700
		},	
		types: { 
			'default': {
				clickable: true,
				creatable: false,
				renameable: false,
				deletable: false,
				draggable: false,
				max_children: -1,
				max_depth: -1,
				valid_children: 'all'
			}
		},
		callback: {
			beforedata: function(NODE, TREE_OBJ) { 
				if (NODE == false) {
					TREE_OBJ.settings.data.opts.static = [ 
						{
							data: 'image',
							attributes: { 
								'id': 'top',
								'directory': ''
							}, 
							state: 'closed'
						}
					];
					
					return { 'directory': '' } 
				} else {
					TREE_OBJ.settings.data.opts.static = false;  
					
					return { 'directory': $(NODE).attr('directory') } 
				}
			},		
			onselect: function (NODE, TREE_OBJ) {
				$.ajax({
					url: 'index.php?route=common/filemanager/files&token=<?php echo $token; ?>',
					type: 'post',
					data: 'directory=' + encodeURIComponent($(NODE).attr('directory')),
					dataType: 'json',
					success: function(json) {
						html = '<div>';
						
						if (json) {
							for (i = 0; i < json.length; i++) {
								html += '<a><img src="<?php echo $no_image; ?>" alt="" title="" /><br />' + ((json[i]['filename'].length > 15) ? (json[i]['filename'].substr(0, 15) + '..') : json[i]['filename']) + '<br />' + json[i]['size'] + '<input type="hidden" name="image" value="' + json[i]['file'] + '" /></a>';
							}
						}
						
						html += '</div>';
						
						$('#column-right').html(html);

						$('#column-right').trigger('scrollstop');	
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}
	});	

	$('#column-right a').on('click', function() {
		if ($(this).attr('class') == 'selected') {
			$(this).removeAttr('class');
		} else {
			$('#column-right a').removeAttr('class');
			
			$(this).attr('class', 'selected');
		}
	});
	
	$('#column-right a').on('dblclick', function() {
		<?php if ($fckeditor) { ?>
		window.opener.CKEDITOR.tools.callFunction(<?php echo $fckeditor; ?>, '<?php echo $directory; ?>' + $(this).find('input[name=\'image\']').attr('value'));
		
		self.close();	
		<?php } else { ?>
		parent.$('#<?php echo $field; ?>').attr('value', 'data/' + $(this).find('input[name=\'image\']').attr('value'));
		parent.$('#dialog').dialog('close');
		
		parent.$('#dialog').remove();	
		<?php } ?>
	});		
						
	$('#button-create').on('click', function() {
		var tree = $.tree.focused();
		
		if (tree.selected) {
			$('#dialog').remove();
			
			html  = '<div id="dialog">';
			html += '<?php echo $entry_folder; ?> <input type="text" name="name" value="" /> <input type="button" value="<?php echo $button_submit; ?>" />';
			html += '</div>';
			
			$('#column-right').prepend(html);
			
			$('#dialog').dialog({
				title: '<?php echo $button_folder; ?>',
				resizable: false
			});	
			
			$('#dialog input[type=\'button\']').on('click', function() {
				$.ajax({
					url: 'index.php?route=common/filemanager/create&token=<?php echo $token; ?>',
					type: 'post',
					data: 'directory=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							tree.refresh(tree.selected);
							
							alert(json.success);
						} else {
							alert(json.error);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			});
		} else {
			alert('<?php echo $error_directory; ?>');	
		}
	});
	
	$('#button-delete').on('click', function() {
		path = $('#column-right a.selected').find('input[name=\'image\']').attr('value');
							 
		if (path) {
			$.ajax({
				url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
				type: 'post',
				data: 'path=' + encodeURIComponent(path),
				dataType: 'json',
				success: function(json) {
					if (json.success) {
						var tree = $.tree.focused();
					
						tree.select_branch(tree.selected);
						
						alert(json.success);
					}
					
					if (json.error) {
						alert(json.error);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});				
		} else {
			var tree = $.tree.focused();
			
			if (tree.selected) {
				$.ajax({
					url: 'index.php?route=common/filemanager/delete&token=<?php echo $token; ?>',
					type: 'post',
					data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							tree.select_branch(tree.parent(tree.selected));
							
							tree.refresh(tree.selected);
							
							alert(json.success);
						} 
						
						if (json.error) {
							alert(json.error);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});			
			} else {
				alert('<?php echo $error_select; ?>');
			}			
		}
	});
	
	$('#button-move').on('click', function() {
		$('#dialog').remove();
		
		html  = '<div id="dialog">';
		html += '<?php echo $entry_move; ?> <select name="to"></select> <input type="button" value="<?php echo $button_submit; ?>" />';
		html += '</div>';

		$('#column-right').prepend(html);
		
		$('#dialog').dialog({
			title: '<?php echo $button_move; ?>',
			resizable: false
		});

		$('#dialog select[name=\'to\']').load('index.php?route=common/filemanager/folders&token=<?php echo $token; ?>');
		
		$('#dialog input[type=\'button\']').on('click', function() {
			path = $('#column-right a.selected').find('input[name=\'image\']').attr('value');
							 
			if (path) {																
				$.ajax({
					url: 'index.php?route=common/filemanager/move&token=<?php echo $token; ?>',
					type: 'post',
					data: 'from=' + encodeURIComponent(path) + '&to=' + encodeURIComponent($('#dialog select[name=\'to\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							var tree = $.tree.focused();
							
							tree.select_branch(tree.selected);
							
							alert(json.success);
						}
						
						if (json.error) {
							alert(json.error);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			} else {
				var tree = $.tree.focused();
				
				$.ajax({
					url: 'index.php?route=common/filemanager/move&token=<?php echo $token; ?>',
					type: 'post',
					data: 'from=' + encodeURIComponent($(tree.selected).attr('directory')) + '&to=' + encodeURIComponent($('#dialog select[name=\'to\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							tree.select_branch('#top');
								
							tree.refresh(tree.selected);
							
							alert(json.success);
						}						
						
						if (json.error) {
							alert(json.error);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});				
			}
		});
	});

	$('#button-copy').on('click', function() {
		$('#dialog').remove();
		
		html  = '<div id="dialog">';
		html += '<?php echo $entry_copy; ?> <input type="text" name="name" value="" /> <input type="button" value="<?php echo $button_submit; ?>" />';
		html += '</div>';

		$('#column-right').prepend(html);
		
		$('#dialog').dialog({
			title: '<?php echo $button_copy; ?>',
			resizable: false
		});
		
		$('#dialog select[name=\'to\']').load('index.php?route=common/filemanager/folders&token=<?php echo $token; ?>');
		
		$('#dialog input[type=\'button\']').on('click', function() {
			path = $('#column-right a.selected').find('input[name=\'image\']').attr('value');
							 
			if (path) {																
				$.ajax({
					url: 'index.php?route=common/filemanager/copy&token=<?php echo $token; ?>',
					type: 'post',
					data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							var tree = $.tree.focused();
							
							tree.select_branch(tree.selected);
							
							alert(json.success);
						}						
						
						if (json.error) {
							alert(json.error);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			} else {
				var tree = $.tree.focused();
				
				$.ajax({
					url: 'index.php?route=common/filemanager/copy&token=<?php echo $token; ?>',
					type: 'post',
					data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							tree.select_branch(tree.parent(tree.selected));
							
							tree.refresh(tree.selected);
							
							alert(json.success);
						} 						
						
						if (json.error) {
							alert(json.error);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});				
			}
		});	
	});
	
	$('#button-rename').on('click', function() {
		$('#dialog').remove();
		
		html  = '<div id="dialog">';
		html += '<?php echo $entry_rename; ?> <input type="text" name="name" value="" /> <input type="button" value="<?php echo $button_submit; ?>" />';
		html += '</div>';

		$('#column-right').prepend(html);
		
		$('#dialog').dialog({
			title: '<?php echo $button_rename; ?>',
			resizable: false
		});
		
		$('#dialog input[type=\'button\']').on('click', function() {
			path = $('#column-right a.selected').find('input[name=\'image\']').attr('value');
							 
			if (path) {		
				$.ajax({
					url: 'index.php?route=common/filemanager/rename&token=<?php echo $token; ?>',
					type: 'post',
					data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
							
							var tree = $.tree.focused();
					
							tree.select_branch(tree.selected);
							
							alert(json.success);
						} 
						
						if (json.error) {
							alert(json.error);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});			
			} else {
				var tree = $.tree.focused();
				
				$.ajax({ 
					url: 'index.php?route=common/filemanager/rename&token=<?php echo $token; ?>',
					type: 'post',
					data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),
					dataType: 'json',
					success: function(json) {
						if (json.success) {
							$('#dialog').remove();
								
							tree.select_branch(tree.parent(tree.selected));
							
							tree.refresh(tree.selected);
							
							alert(json.success);
						} 
						
						if (json.error) {
							alert(json.error);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});		
	});

	$('#image').on('change', function() {
		var tree = $.tree.focused();
				
		$('input[name=\'directory\']').attr('value', $(tree.selected).attr('directory'));		
		
		$.ajax({
			url: 'index.php?route=common/filemanager/upload&token=<?php echo $token; ?>',
			type: 'post',		
			dataType: 'json',
			data: new FormData($(this).parent()[0]),
			beforeSend: function() {
				$('#button-upload').after('<img src="view/image/loading.gif" class="loading" style="padding-top: 5px; padding-left: 5px;" />');
				$('#button-upload').prop('disabled', true);
			},	
			complete: function() {
				$('.loading').remove();
				$('#button-upload').prop('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					var tree = $.tree.focused();
					
					tree.select_branch(tree.selected);
					
					alert(json['success']);
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
	
	$('#button-refresh').on('click', function() {
		var tree = $.tree.focused();
		
		tree.refresh(tree.selected);
	});	
});
*/
//--></script>
</body>
</html>
