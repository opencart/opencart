<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="list-group">
              <div class="list-group-item">
                <h4 class="list-group-item-heading"><?php echo $text_store; ?></h4>
              </div>
              <div class="list-group-item">
                <select name="store_id" class="form-control">
                  <option value="0"><?php echo $text_default; ?></option>
                  <?php foreach ($stores as $store) { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="list-group">
              <div class="list-group-item">
                <h4 class="list-group-item-heading"><?php echo $text_template; ?></h4>
              </div>
              <div id="directory"></div>
            </div>
          </div>
          <div id="code" style="display: none;" class="col-lg-9 col-md-9 col-sm-12">
            <ul class="nav nav-tabs"></ul>
            <div class="tab-content"></div>
          </div>
          <div id="holding">
          
          <p class="text-center">Please select a template from the left column.</p>
          
          </div>
        </div>
      </div>
    </div>
  </div>
  <link href="view/javascript/codemirror/lib/codemirror.css" rel="stylesheet" />
  <link href="view/javascript/codemirror/theme/monokai.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/codemirror/lib/codemirror.js"></script> 
  <script type="text/javascript" src="view/javascript/codemirror/lib/xml.js"></script> 
  <script type="text/javascript" src="view/javascript/codemirror/lib/formatting.js"></script> 
  <script type="text/javascript"><!--
$('select[name="store_id"]').on('change', function(e) {
	$.ajax({
		url: 'index.php?route=design/theme/path&token=<?php echo $token; ?>&store_id=' + $('select[name="store_id"]').val(),
		dataType: 'json',
		beforeSend: function() {
			$('select[name="store_id"]').prop('disabled', true);
		},
		complete: function() {
			$('select[name="store_id"]').prop('disabled', false);
		},
		success: function(json) {
			html = '';
			
			if (json['directory']) {
				for (i = 0; i < json['directory'].length; i++) {
					html += '<a href="' + json['directory'][i]['path'] + '" class="list-group-item directory">' + json['directory'][i]['name'] + ' <i class="fa fa-arrow-right fa-fw pull-right"></i></a>';
				}
			}
			
			if (json['file']) {
				for (i = 0; i < json['file'].length; i++) {
					html += '<a href="' + json['file'][i]['path'] + '" class="list-group-item file">' + json['file'][i]['name'] + ' <i class="fa fa-arrow-right fa-fw pull-right"></i></a>';
				}
			}
									
			$('#directory').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});  

$('select[name="store_id"]').trigger('change');

$('#directory').delegate('a.directory', 'click', function(e) {  
	e.preventDefault();
	
	var node = this; 
	
	$.ajax({
		url: 'index.php?route=design/theme/path&token=<?php echo $token; ?>&store_id=' + $('select[name="store_id"]').val() + '&path=' + $(node).attr('href'),
		dataType: 'json',
		beforeSend: function() {
			$(node).find('i').removeClass('fa-arrow-right');
			$(node).find('i').addClass('fa-circle-o-notch fa-spin');
		},
		complete: function() {
			$(node).find('i').removeClass('fa-circle-o-notch fa-spin');
			$(node).find('i').addClass('fa-arrow-right');
		},
		success: function(json) {
			//console.log(json);
			
			html = '';
			
			if (json['directory']) {
				for (i = 0; i < json['directory'].length; i++) {
					html += '<a href="' + json['directory'][i]['path'] + '" class="list-group-item directory">' + json['directory'][i]['name'] + ' <i class="fa fa-arrow-right fa-fw pull-right"></i></a>';
				}
			}
			
			if (json['file']) {
				for (i = 0; i < json['file'].length; i++) {
					html += '<a href="' + json['file'][i]['path'] + '" class="list-group-item file">' + json['file'][i]['name'] + ' <i class="fa fa-arrow-right fa-fw pull-right"></i></a>';
				}
			}
			
			if (json['back']) {
				html += '<a href="' + json['back']['path'] + '" class="list-group-item directory">' + json['back']['name'] + ' <i class="fa fa-arrow-right fa-fw pull-right"></i></a>';
			}
									
			$('#directory').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#directory').delegate('a.file', 'click', function(e) {
	e.preventDefault();
	 
	var node = this; 
	
	var token = $(node)
	.attr('href')
	.slice(0, -4)
	.replace('/', '-')
	.replace('_', '-');
	
	if (!$('#tab-' + token).length) {
		$.ajax({
			url: 'index.php?route=design/theme/template&token=<?php echo $token; ?>&store_id=' + $('input[name="store_id"]').val() + '&path=' + $(node).attr('href'),
			type: 'post',
			data: $('input[name=\'code\']'),		
			dataType: 'json',
			beforeSend: function() {
				$(node).find('i').removeClass('fa-arrow-right');
				$(node).find('i').addClass('fa-circle-o-notch fa-spin');
			},
			complete: function() {
				$(node).find('i').removeClass('fa-circle-o-notch fa-spin');
				$(node).find('i').addClass('fa-arrow-right');
			},
			success: function(json) {
				//console.log(json);
				
				if (json['code']) {
					$('#code').show();
					$('#empty').hide();
							
							
														
					//$(node).attr('href')' + $('select[name="store_id"]').text() + '
					
					$('.nav-tabs').append('<li><a href="#tab-' + token + '" data-toggle="tab">' + $(node).attr('href').split('/').join(' / ') + '&nbsp;&nbsp;<i class="fa fa-minus-circle"></i></a></li>');
					
					html  = '<div class="tab-pane" id="tab-' + token + '">';	
					html += '    <textarea name="code" rows="10" id="input-' + token + '"></textarea>';
					html += '    <input type="hidden" name="store_id" value="' + $('select[name="store_id"]').val() + '" />';
					html += '    <input type="hidden" name="path" value="' + $(node).attr('href') + '" />';
					html += '  <br />';
					html += '  <div class="pull-right">';
					html += '    <button type="button" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-floppy-o"></i> <?php echo $button_save; ?></button>';
					html += '    <button type="button" class="btn btn-danger"><i class="fa fa-recycle"></i> <?php echo $button_reset; ?></button>';
					html += '  </div>';
					html += '</div>';
	
					$('.tab-content').append(html);
					
					$('.nav-tabs a[href=\'#tab-' + token + '\']').tab('show');
					
					// Initialize codemirrror
					var editor = CodeMirror.fromTextArea(document.getElementById('input-' + token), {
						mode: 'text/html',
						height: '500px',
						lineNumbers: true,
						autofocus: true,
						theme: 'monokai'
					});		
					
					editor.setValue(json['code']);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	} else {
		$('.nav-tabs a[href=\'#tab-' + token + '\']').tab('show');
	}
});

$('.nav-tabs').delegate('i.fa-minus-circle', 'click', function(e) {
	e.preventDefault();
	
	if ($(this).parent().parent().is('li.active')) {
		index = $(this).parent().parent().index()
		
		if (index == 0) {
			$(this).parent().parent().parent().find('li').eq(index + 1).find('a').tab('show');
		} else {
			$(this).parent().parent().parent().find('li').eq(index - 1).find('a').tab('show');
		}
	}
	
	$(this).parent().parent().remove();
	
	$($(this).parent().attr('href')).remove();
	
	if (!$('#code > ul > li').length) {
		$('#code').hide();
		$('#empty').show();
	}	
});

$('#button-save').on('click', function(e) { 
	var node = this; 
	
	$.ajax({
		url: 'index.php?route=design/theme/save&token=<?php echo $token; ?>&store_id=' + $('input[name="store_id"]').val() + '&path=' + $('input[name="path"]').val(),
		type: 'post',
		data: $('input[name=\'code\']'),		
		dataType: 'json',
		beforeSend: function() {
			$(node).button('loading');
		},
		complete: function() {
			$(node).button('reset');
		},
		success: function(json) {
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '</div>');
			}			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-reset').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) { 
		var node = this;
		
		$.ajax({
			url: 'index.php?route=design/theme/save&token=<?php echo $token; ?>&store_id=' + $('input[name="store_id"]').val() + '&path=' + $('input[name="path"]').val(),
			dataType: 'json',
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}
				
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '</div>');
				}			
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});

//--></script> 
</div>
<?php echo $footer; ?>