<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" id="button-import" data-toggle="tooltip" title="<?php echo $button_import; ?>" class="btn btn-success"><i class="fa fa fa-upload"></i></button>
        <button type="submit" form="form-google-base" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_import; ?> <button type="button" class="close" data-dismiss="alert">×</button></div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-google-base" class="form-horizontal">


                <div class="table-responsive">
                  <table id="category" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $entry_category; ?></td>
                        <td class="text-left"><?php echo $entry_google_category; ?></td>
                        <td></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $category_row = 0; ?>
                      <?php foreach ($google_base_categories as $google_base_category) { ?>
                      <tr id="category-row<?php echo $category_row; ?>">
                        <td class="text-left"><input type="hidden" name="google_base_category[]" value="<?php echo $google_base_category['google_base_category_id']; ?>" /><?php echo $google_base_category['name']; ?></td>
                        <td class="text-left"><input type="hidden" name="google_base_category[]" value="<?php echo $google_base_category['google_base_category_id']; ?>" /><?php echo $google_base_category['name']; ?></td>
                        <td class="text-left"><button type="button" onclick="$('#category-row<?php echo $category_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                      </tr>
                      <?php $category_row++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td><input type="hidden" name="category_id" value="" /><input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" class="form-control" /></td>
                        <td><input type="hidden" name="google_base_category_id" value="" /><input type="text" name="google_category" value="" placeholder="<?php echo $entry_google_category; ?>" class="form-control" /></td>
                        <td class="text-left"><button type="button" id="button-category-add" data-toggle="tooltip" title="<?php echo $button_category_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>


            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-data-feed"><?php echo $entry_data_feed; ?></label>
              <div class="col-sm-10">
                <textarea rows="5" id="input-data-feed" class="form-control" readonly><?php echo $data_feed; ?></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="col-sm-10">
                <select name="google_base_status" id="input-status" class="form-control">
                  <?php if ($google_base_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			},
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
	},
	'select': function(item) {
        $(this).val(item['label']);
        $('input[name=\'category_id\']').val(item['value']);
    }
});

// Google Category
$('input[name=\'google_category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=feed/google_base/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['google_base_category_id']
					}
				}));
			},
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
	},
	'select': function(item) {
        $(this).val(item['label']);
        $('input[name=\'google_base_category_id\']').val(item['value']);
    }
});

var category_row = <?php echo $category_row; ?>;

$('#button-category-add').on('çlick', function() {
    html  = '<tr id="category-row' + category_row + '">';
    html +=   '<td class="text-left"></td>';
    html +=   '<td class="text-left"></td>';
    html +=   '<td class="text-left"><button type="button" onclick="$(\'#category-row' + category_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#category tbody').append(html);
});
//--></script>
  <script type="text/javascript"><!--
$('#button-import').on('click', function() {
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
				url: 'index.php?route=feed/google_base/import&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-import').button('loading');
				},
				complete: function() {
					$('#button-import').button('reset');
				},
				success: function(json) {
					$('.alert').remove();

                    if (json['error']) {
        				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        			}

        			if (json['success']) {
        				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        			}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
</div>
<?php echo $footer; ?>
