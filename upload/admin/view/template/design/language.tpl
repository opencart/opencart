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
                <h4 class="list-group-item-heading"><?php echo $text_language; ?></h4>
              </div>
              <div class="list-group-item">
                <select name="language_id" class="form-control">
                  <?php foreach ($languages as $language) { ?>
                  <?php if ($language['language_id'] == $language_id) { ?>
                  <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="list-group">
              <div class="list-group-item">
                <h4 class="list-group-item-heading"><?php echo $text_translation; ?></h4>
              </div>
              <div id="path"></div>
            </div>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12">
    
              <div id="translation"></div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('select[name="store_id"]').on('change', function(e) {
	$.ajax({
		url: 'index.php?route=design/language/path&token=<?php echo $token; ?>&store_id=' + $('select[name="store_id"]').val() + '&language_id=' + $('select[name="language_id"]').val(),
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

			$('#path').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name="store_id"]').trigger('change');

$('#path').on('click', 'a.directory', function(e) {
	e.preventDefault();

	var node = this;

	$.ajax({
		url: 'index.php?route=design/language/path&token=<?php echo $token; ?>&store_id=' + $('select[name="store_id"]').val() + '&language_id=' + $('select[name="language_id"]').val() + '&path=' + $(node).attr('href'),
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
				html += '<a href="' + json['back']['path'] + '" class="list-group-item directory">' + json['back']['name'] + ' <i class="fa fa-arrow-left fa-fw pull-right"></i></a>';
			}

			$('#path').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#path').on('click', 'a.file',function(e) {
	e.preventDefault();

	var node = this;
	
	// Check if the file has an extension
	$.ajax({
		url: 'index.php?route=design/language/translation&token=<?php echo $token; ?>&store_id=' + $('select[name="store_id"]').val() + '&language_id=' + $('select[name="language_id"]').val() + '&path=' + $(node).attr('href'),
		dataType: 'html',
		beforeSend: function() {
			$(node).find('i').removeClass('fa-arrow-right');
			$(node).find('i').addClass('fa-circle-o-notch fa-spin');
		},
		complete: function() {
			$(node).find('i').removeClass('fa-circle-o-notch fa-spin');
			$(node).find('i').addClass('fa-arrow-right');
		},
		success: function(html) {
			$('#translation').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#translation').on('click', '#button-save', function(e) {
	var node = this;
				
	$.ajax({
		url: 'index.php?route=design/language/save&token=<?php echo $token; ?>&store_id=' + $('select[name="store_id"]').val() + '&language_id=' + $('select[name="language_id"]').val() + '&path=' + $('.tab-content .active input[name="path"]').val(),
		type: 'post',
		data: $('#translation input'),
		dataType: 'json',
		beforeSend: function() {
			$(node).button('loading');
		},
		complete: function() {
			$(node).button('reset');
		},
		success: function(json) {
			$('.alert').remove();
			
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script> 
</div>
<?php echo $footer; ?> 