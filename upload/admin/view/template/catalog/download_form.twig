{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-download" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="{{ cancel }}" data-toggle="tooltip" title="{{ button_cancel }}" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>{{ heading_title }}</h1>
      <ul class="breadcrumb">
        {% for breadcrumb in breadcrumbs %}
        <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
        {% endfor %}
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    {% if error_warning %}
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {% endif %}
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> {{ text_form }}</h3>
      </div>
      <div class="panel-body">
        <form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-download" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label">{{ entry_name }}</label>
            <div class="col-sm-10">
              {% for language in languages %}
              <div class="input-group"> <span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span>
                <input type="text" name="download_description[{{ language.language_id }}][name]" value="{{ download_description[language.language_id] ? download_description[language.language_id].name }}" placeholder="{{ entry_name }}" class="form-control" />
              </div>
              {% if error_name[language.language_id] %}
              <div class="text-danger">{{ error_name[language.language_id] }}</div>
              {% endif %}
              {% endfor %}
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-filename"><span data-toggle="tooltip" title="{{ help_filename }}">{{ entry_filename }}</span></label>
            <div class="col-sm-10">
              <div class="input-group">
                <input type="text" name="filename" value="{{ filename }}" placeholder="{{ entry_filename }}" id="input-filename" class="form-control" />
                <span class="input-group-btn">
                <button type="button" id="button-upload" data-loading-text="{{ text_loading }}" class="btn btn-primary"><i class="fa fa-upload"></i> {{ button_upload }}</button>
                </span> </div>
              {% if error_filename %}
              <div class="text-danger">{{ error_filename }}</div>
              {% endif %}
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-mask"><span data-toggle="tooltip" title="{{ help_mask }}">{{ entry_mask }}</span></label>
            <div class="col-sm-10">
              <input type="text" name="mask" value="{{ mask }}" placeholder="{{ entry_mask }}" id="input-mask" class="form-control" />
              {% if error_mask %}
              <div class="text-danger">{{ error_mask }}</div>
              {% endif %}
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
$('#button-upload').on('click', function() {
	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=catalog/download/upload&user_token={{ user_token }}',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-upload').button('loading');
				},
				complete: function() {
					$('#button-upload').button('reset');
				},
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}

					if (json['success']) {
						alert(json['success']);

						$('input[name=\'filename\']').val(json['filename']);
						$('input[name=\'mask\']').val(json['mask']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script></div>
{{ footer }}
