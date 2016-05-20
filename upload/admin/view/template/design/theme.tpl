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
        </div>
      </div>
    </div>
  </div>
  <link href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css" rel="stylesheet" />
  <link href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css" rel="stylesheet" />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script> 
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script> 
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js"></script> 
  <script type="text/javascript"><!--
$('select[name="store_id"]').on('change', function(e) {
	$.ajax({
		url: 'index.php?route=design/theme/template&token=<?php echo $token; ?>&store_id=' + $('select[name="store_id"]').val(),
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
					html += '<a href="' + json['directory'][i]['href'] + '" class="list-group-item">' + json['directory'][i]['name'] + ' <i class="fa fa-arrow-right fa-fw pull-right"></i></a>';
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

$('#directory').delegate('a', 'click', function(e) {  
	e.preventDefault();
	
	var node = this; 
	
	$.ajax({
		url: $(node).attr('href'),
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
			$('#code').show();
			
			html = '';
			
			if (json['directory']) {	
				for (i = 0; i < json['directory'].length; i++) {
					html += '<a href="' + json['directory'][i]['href'] + '" class="list-group-item">' + json['directory'][i]['name'] + ' <i class="fa fa-arrow-right fa-fw pull-right"></i></a>';
				}
			}
			
			$('#directory').html(html);
 			
			if (json['code']) {
				var token = Math.random().toString().substr(2, 10);
				
				$('.nav-tabs').append('<li><a href="#tab-code' + token + '" data-toggle="tab">' + $(node).text() + '&nbsp;&nbsp;<button type="button" class="close" data-dismiss="alert">&times;</button></a></li>');
				
				html  = '<div class="tab-pane" id="tab-code' + token + '">';
				html += '  <fieldset>';
				html += '    <legend>' + $('select[name="store_id"]').text() + ' / ' + $(node).text() + '</legend>';
				html += '    <textarea name="code" rows="10" id="input-code' + token + '"></textarea>';
				html += '  </fieldset>';
				html += '  <br />';
				html += '  <div class="pull-right">';
				html += '    <button type="button" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-floppy-o"></i> <?php echo $button_save; ?></button>';
				html += '    <button type="button" class="btn btn-danger"><i class="fa fa-recycle"></i> <?php echo $button_reset; ?></button>';
				html += '  </div>';
				html += '</div>';

				$('.tab-content').append(html);
				
				$('.nav-tabs a[href=\'#tab-code' + token + '\']').tab('show');
				
				// Initialize codemirrror
				var editor = CodeMirror.fromTextArea(document.getElementById('input-code' + token), {
					mode: 'text/html',
					height: '500px',
					lineNumbers: true,
					autofocus: true,
					theme: 'monokai'
				});		
				
				editor.setValue(json['code']);
			}
			
			if ($('#code > ul > li').length) {
				$('#code').show();
			} else {
				$('#code').hide();
			}			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('.nav-tabs a .close').on('click', function() {
	
	//#tab-code' + token(
	
	
	//$('.nav-tabs a .close').remove();
	
	//$('.nav-tabs a .close').remove();
});

$('#button-save').on('click', function(e) { 
	var node = this; 
	
	$.ajax({
		url: $(this).attr('href'),
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
			url: $(this).attr('href'),
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